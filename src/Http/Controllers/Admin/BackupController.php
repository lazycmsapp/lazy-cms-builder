<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermission('manage_settings') && !auth()->user()->hasPermission('access_backup_restore')) {
            abort(403);
        }

        $backups = [];
        $backupDir = storage_path('app/backups');
        
        if (file_exists($backupDir)) {
            $files = array_diff(scandir($backupDir), array('.', '..'));
            
            foreach ($files as $file) {
                $filePath = $backupDir . '/' . $file;
                if (is_file($filePath)) {
                    $backups[] = [
                        'name' => $file,
                        'size' => round(filesize($filePath) / 1024 / 1024, 2) . ' MB',
                        'date' => Carbon::createFromTimestamp(filemtime($filePath))->format('Y-m-d H:i:s'),
                        'path' => $filePath
                    ];
                }
            }
        }

        // Sort by date descending
        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return view('cms-dashboard::admin.tools.backup', compact('backups'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('manage_settings') && !auth()->user()->hasPermission('access_backups') && !auth()->user()->hasPermission('access_tools')) {
            abort(403);
        }

        try {
            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            $backupDir = storage_path('app/backups');

            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $path = $backupDir . '/' . $filename;

            // Simple Database Export Logic
            $tables = DB::select('SHOW TABLES');
            $dbName = config('database.connections.mysql.database');
            $sql = "-- Lazy CMS Backup\n-- Database: {$dbName}\n-- Date: " . now() . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = current((array)$table);
                
                // Structure
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable->{'Create Table'} . ";\n\n";

                // Data
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $row = (array)$row;
                    $columns = array_keys($row);
                    $values = array_map(function($value) {
                        if (is_null($value)) return 'NULL';
                        return "'" . addslashes($value) . "'";
                    }, array_values($row));
                    
                    $sql .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
            $sql .= "SET FOREIGN_KEY_CHECKS=1;";

            file_put_contents($path, $sql);

            lazy_log_activity('created', "Created a database backup: {$filename}");
            return redirect()->back()->with('success', 'Backup created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function restore($filename)
    {
        if (!auth()->user()->hasPermission('manage_settings') && !auth()->user()->hasPermission('access_backups') && !auth()->user()->hasPermission('access_tools')) {
            abort(403);
        }

        try {
            $path = storage_path('app/backups/' . $filename);
            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'Backup file not found.');
            }

            $sql = file_get_contents($path);
            
            // Execute the SQL
            DB::unprepared($sql);

            lazy_log_activity('restored', "Restored database from snapshot: {$filename}");
            return redirect()->back()->with('success', 'Database restored successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Restoration failed: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        if (!auth()->user()->hasPermission('manage_settings') && !auth()->user()->hasPermission('access_backups') && !auth()->user()->hasPermission('access_tools')) {
            abort(403);
        }

        $path = storage_path('app/backups/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function destroy($filename)
    {
        if (!auth()->user()->hasPermission('manage_settings') && !auth()->user()->hasPermission('access_backups') && !auth()->user()->hasPermission('access_tools')) {
            abort(403);
        }

        $path = storage_path('app/backups/' . $filename);
        if (file_exists($path)) {
            unlink($path);
            return redirect()->back()->with('success', 'Backup deleted successfully.');
        }

        return redirect()->back()->with('error', 'Backup not found.');
    }
}
