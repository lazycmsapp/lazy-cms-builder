<x-cms-dashboard::layouts.admin>
    <x-slot name="title">WordPress Import — Lazy CMS</x-slot>

    <div class="px-2 max-w-[760px]">
        <h1 class="text-[23px] font-normal text-[#1d2327] mb-1">Import from WordPress</h1>
        <p class="text-[13px] text-[#646970] mb-5">Bring posts, pages, categories and tags over from a WordPress site using its export file.</p>

        @if(session('success'))
            <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-3 mb-4 text-[13px] text-[#1d2327]">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-[#fcf0f1] border-l-4 border-[#d63638] p-3 mb-4 text-[13px] text-[#1d2327]">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-[#fcf0f1] border-l-4 border-[#d63638] p-3 mb-4 text-[13px] text-[#1d2327]">{{ $errors->first() }}</div>
        @endif

        {{-- Import summary --}}
        @if(session('wp_import_summary'))
            @php $s = session('wp_import_summary'); @endphp
            <div class="bg-white border border-[#dfdfdf] rounded mb-5 overflow-hidden">
                <div class="px-4 py-2 bg-[#f6f7f7] border-b border-[#dfdfdf] text-[12px] font-bold text-[#1d2327] uppercase">Import Result</div>
                <div class="p-4 grid grid-cols-3 gap-3 text-center">
                    @foreach(['posts'=>'Posts','pages'=>'Pages','cpt'=>'Custom','categories'=>'Categories','tags'=>'Tags','skipped'=>'Skipped'] as $k => $label)
                        <div class="border border-[#f0f0f1] rounded py-3">
                            <div class="text-[22px] font-semibold text-[#1d2327]">{{ $s[$k] ?? 0 }}</div>
                            <div class="text-[11px] text-[#646970] uppercase">{{ $label }}</div>
                        </div>
                    @endforeach
                </div>
                @if(!empty($s['errors']))
                    <div class="px-4 pb-4">
                        <div class="text-[12px] font-bold text-[#b32d2e] mb-1">{{ count($s['errors']) }} item(s) had problems:</div>
                        <ul class="text-[12px] text-[#646970] list-disc pl-5 max-h-40 overflow-y-auto">
                            @foreach(array_slice($s['errors'], 0, 50) as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        {{-- Upload form --}}
        <form action="{{ route('admin.wp-import.import') }}" method="POST" enctype="multipart/form-data"
              class="bg-white border border-[#dfdfdf] rounded p-5">
            @csrf

            <label class="block text-[13px] font-semibold text-[#1d2327] mb-1">WordPress export file (.xml / WXR)</label>
            <input type="file" name="wxr_file" accept=".xml,text/xml,application/xml" required
                   class="block w-full text-[13px] text-[#1d2327] border border-[#8c8f94] rounded p-2 mb-1 bg-white">
            <p class="text-[12px] text-[#646970] mb-4">Max 100 MB. Larger sites: split the export by content type in WordPress.</p>

            <label class="flex items-center gap-2 text-[13px] text-[#1d2327] mb-5 cursor-pointer">
                <input type="checkbox" name="import_pages" value="1" checked class="rounded border-[#8c8f94]">
                Also import Pages (uncheck to import Posts only)
            </label>

            <button type="submit" class="wp-btn-primary">Run Import</button>
        </form>

        {{-- How-to --}}
        <div class="mt-6 bg-[#f6f7f7] border border-[#dfdfdf] rounded p-4 text-[13px] text-[#50575e]">
            <div class="font-semibold text-[#1d2327] mb-2">How to get the export file</div>
            <ol class="list-decimal pl-5 space-y-1">
                <li>In your WordPress admin, go to <strong>Tools → Export</strong>.</li>
                <li>Choose <strong>All content</strong> (or just Posts / Pages), then <strong>Download Export File</strong>.</li>
                <li>Upload that <code>.xml</code> file here and click <strong>Run Import</strong>.</li>
            </ol>
            <div class="mt-3 text-[12px] text-[#646970]">
                Notes: content is imported into the rich editor (it opens as a Text Block in the builder).
                Scheduled (future) posts keep their schedule. Featured images are linked to their original URLs.
                Re-running is safe — items already imported (same type &amp; slug) are skipped.
            </div>
        </div>
    </div>
</x-cms-dashboard::layouts.admin>
