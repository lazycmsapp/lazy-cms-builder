<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Acme\CmsDashboard\Services\WordPressImporter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WordPressImportController extends Controller
{
    private function checkAccess(): void
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }
    }

    private function iniToBytes(string $val): int
    {
        $val  = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $num  = (int) $val;
        return match ($last) {
            'g' => $num * 1024 * 1024 * 1024,
            'm' => $num * 1024 * 1024,
            'k' => $num * 1024,
            default => $num,
        };
    }

    private function maxUploadBytes(): int
    {
        $upload = $this->iniToBytes(ini_get('upload_max_filesize') ?: '8M');
        $post   = $this->iniToBytes(ini_get('post_max_size')       ?: '8M');
        return min($upload, $post);
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1024 * 1024 * 1024) return round($bytes / 1024 / 1024 / 1024, 1) . ' GB';
        if ($bytes >= 1024 * 1024)        return round($bytes / 1024 / 1024, 0) . ' MB';
        return round($bytes / 1024, 0) . ' KB';
    }

    public function index()
    {
        $this->checkAccess();

        $maxUploadBytes = $this->maxUploadBytes();
        $maxUploadHuman = $this->formatBytes($maxUploadBytes);

        return view('cms-dashboard::admin.tools.wp-import', compact('maxUploadBytes', 'maxUploadHuman'));
    }

    public function import(Request $request)
    {
        $this->checkAccess();

        $maxKb = (int) ($this->maxUploadBytes() / 1024);

        $request->validate([
            'wxr_file' => [
                'required', 'file', 'max:' . $maxKb,
                function ($attribute, $value, $fail) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    if (!in_array($ext, ['xml', 'wxr'])) {
                        $fail('Only WordPress export files (.xml or .wxr) are allowed.');
                    }
                },
            ],
        ], [
            'wxr_file.required' => 'Please choose a WordPress export (.xml) file.',
            'wxr_file.max'      => 'The file exceeds the server upload limit of ' . $this->formatBytes($this->maxUploadBytes()) . '.',
        ]);

        $xml = file_get_contents($request->file('wxr_file')->getRealPath());

        if (!$xml || stripos($xml, '<rss') === false || stripos($xml, 'wordpress.org/export') === false) {
            return back()->with('error', 'That does not look like a WordPress export (WXR) file. In WordPress go to Tools → Export → "All content" and upload the downloaded .xml file.');
        }

        @set_time_limit(0);

        $summary = (new WordPressImporter())->importFromXml($xml, [
            'user_id'      => auth()->id(),
            'lang'         => app()->getLocale(),
            'import_pages' => $request->boolean('import_pages', true),
        ]);

        if (function_exists('lazy_log_activity')) {
            lazy_log_activity('imported', 'Imported WordPress content (posts: ' . $summary['posts'] . ', pages: ' . $summary['pages'] . ')');
        }
        if (function_exists('clear_page_cache')) {
            clear_page_cache();
        }

        return back()->with('wp_import_summary', $summary)->with('success', 'WordPress import finished.');
    }

    public function importMedia(Request $request)
    {
        $this->checkAccess();

        $maxKb = (int) ($this->maxUploadBytes() / 1024);

        $request->validate([
            'wp_media_file' => [
                'required', 'file', 'max:' . $maxKb,
                function ($attribute, $value, $fail) {
                    if (strtolower($value->getClientOriginalExtension()) !== 'zip') {
                        $fail('Only .zip files are accepted for media import.');
                    }
                },
            ],
        ], [
            'wp_media_file.max' => 'The file exceeds the server upload limit of ' . $this->formatBytes($this->maxUploadBytes()) . '.',
        ]);

        try {
            $uploadDir = storage_path('app/public/uploads');
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);

            $zip = new \ZipArchive();
            if ($zip->open($request->file('wp_media_file')->getRealPath()) !== true) {
                throw new \Exception('Could not open the zip file.');
            }

            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf', 'mp4', 'mp3', 'mov', 'avi', 'ico', 'bmp', 'tif', 'tiff', 'woff', 'woff2'];
            $count   = 0;
            $skipped = 0;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (substr($name, -1) === '/' || basename($name)[0] === '.') continue;

                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) { $skipped++; continue; }

                $basename = basename($name);
                $dest     = $uploadDir . '/' . $basename;

                if (file_exists($dest)) {
                    $basename = pathinfo($basename, PATHINFO_FILENAME) . '_wp' . $i . '.' . $ext;
                    $dest     = $uploadDir . '/' . $basename;
                }

                $data = $zip->getFromIndex($i);
                if ($data !== false) {
                    file_put_contents($dest, $data);
                    $count++;
                }
            }

            $zip->close();

            $msg = "Media import complete: {$count} files imported to the uploads folder.";
            if ($skipped > 0) $msg .= " {$skipped} non-media files skipped.";

            if (function_exists('lazy_log_activity')) lazy_log_activity('imported', $msg);

            return back()->with('media_success', $msg);

        } catch (\Exception $e) {
            return back()->with('media_error', 'Media import failed: ' . $e->getMessage());
        }
    }
}
