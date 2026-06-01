<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Acme\CmsDashboard\Services\WordPressImporter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WordPressImportController extends Controller
{
    private function authorize(): void
    {
        if (!auth()->user()->hasPermission('manage_settings')) {
            abort(403);
        }
    }

    public function index()
    {
        $this->authorize();
        return view('cms-dashboard::admin.tools.wp-import');
    }

    public function import(Request $request)
    {
        $this->authorize();

        $request->validate([
            'wxr_file' => 'required|file|max:102400', // up to 100 MB
        ], [
            'wxr_file.required' => 'Please choose a WordPress export (.xml) file.',
        ]);

        $xml = file_get_contents($request->file('wxr_file')->getRealPath());
        if (!$xml || stripos($xml, '<rss') === false || stripos($xml, 'wordpress.org/export') === false) {
            return back()->with('error', 'That does not look like a WordPress export (WXR) file. In WordPress go to Tools → Export → "All content" and upload the downloaded .xml file.');
        }

        @set_time_limit(0);

        $importer = new WordPressImporter();
        $summary = $importer->importFromXml($xml, [
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

        return back()->with('wp_import_summary', $summary)
                     ->with('success', 'WordPress import finished.');
    }
}
