<x-cms-dashboard::layouts.admin>
    <x-slot name="title">REST API Settings - Lazy CMS</x-slot>

    <div class="px-2">
        <h1 class="text-[23px] font-normal text-[#1d2327] mb-4">Settings</h1>
        
        @include('cms-dashboard::admin.settings.nav')

        @if (session('success'))
            <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-3 mb-6 text-[13px] text-[#1d2327]">
                {{ session('success') }}
            </div>
        @endif

        <div class="max-w-[800px]">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                
                <h3 class="text-[18px] font-medium text-[#1d2327] mb-4">REST API Configuration</h3>
                
                <table class="w-full border-separate border-spacing-y-6">
                    <tr>
                        <th scope="row" class="w-[200px] text-left align-top pt-2">
                            <label class="text-[14px] font-semibold text-[#1d2327]">Enable REST API</label>
                        </th>
                        <td>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="enable_rest_api" value="1" {{ ($settings['enable_rest_api'] ?? '1') == '1' ? 'checked' : '' }} class="w-4 h-4 mr-2">
                                <span class="text-[14px] text-[#1d2327]">Allow external access to CMS data via JSON API</span>
                            </label>
                        </td>
                    </tr>
                </table>

                <div class="mt-8 p-6 bg-blue-50 border border-blue-100 rounded-lg">
                    <h4 class="text-blue-800 font-bold mb-3 flex items-center gap-2">
                        <span class="material-icons text-sm">info</span>
                        How to use REST API
                    </h4>
                    <p class="text-sm text-blue-700 mb-4">Once enabled, you can access your content from any React, Vue, or Mobile app using these endpoints:</p>
                    
                    <ul class="space-y-3">
                        <li class="bg-white p-3 rounded border border-blue-100 flex items-center justify-between">
                            <code class="text-xs text-gray-700">{{ url('/api/v1/posts') }}</code>
                            <span class="text-[10px] bg-gray-100 px-2 py-1 rounded">GET</span>
                        </li>
                        <li class="bg-white p-3 rounded border border-blue-100 flex items-center justify-between">
                            <code class="text-xs text-gray-700">{{ url('/api/v1/settings') }}</code>
                            <span class="text-[10px] bg-gray-100 px-2 py-1 rounded">GET</span>
                        </li>
                    </ul>

                    <div class="mt-6">
                        <h5 class="text-blue-800 font-semibold text-xs uppercase mb-2">Example React/Vue Fetch:</h5>
                        <div class="bg-gray-900 rounded p-4 text-gray-300 font-mono text-[11px]">
                            <pre>fetch('{{ url('/api/v1/posts') }}')
  .then(res => res.json())
  .then(data => console.log(data));</pre>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 mt-8">
                    <button type="submit" class="wp-btn-primary px-4 h-8 font-semibold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-cms-dashboard::layouts.admin>
