<x-cms-dashboard::layouts.admin title="Manage {{ $taxonomy->name }} Terms" active-menu="acpt">
    <x-cms-dashboard::admin.delete-modal />
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-[23px] font-normal text-[#1d2327] inline-block mr-3">{{ $taxonomy->name }} Terms</h1>
        
        <form action="" method="GET" class="flex gap-2">
            @if(request('cpt')) <input type="hidden" name="cpt" value="{{ request('cpt') }}"> @endif
            <input type="text" name="s" value="{{ request('s') }}" class="wp-input h-8 px-2 text-[13px]" placeholder="Search terms...">
            <button type="submit" class="wp-btn-secondary h-8 px-3">Search Terms</button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-white border-l-4 border-[#00a32a] shadow-sm p-3 mb-4 text-[13px]">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="bg-white border-l-4 border-[#d63638] shadow-sm p-3 mb-4 text-[13px]">
            @if(session('error')) <p>{{ session('error') }}</p> @endif
            @if($errors->any())
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- Add New Term Column -->
        <div class="w-full md:w-1/3">
            <h2 class="text-[17px] font-normal mb-4">Add New {{ $taxonomy->singular_name ?? $taxonomy->name }}</h2>
            
            <form action="{{ route('admin.acpt.terms.store', $taxonomy->slug) }}" method="POST">
                @csrf
                @php $activeLanguages = \Acme\CmsDashboard\Models\Language::where('status', true)->get(); @endphp
                @if($activeLanguages->count() > 1)
                    <div class="mb-4">
                        <label class="block text-[14px] text-[#2c3338] mb-1 font-semibold">Language</label>
                        <select name="lang_code" class="wp-input w-full h-8 py-0">
                            @foreach($activeLanguages as $lang)
                                <option value="{{ $lang->code }}" {{ $lang->code == app()->getLocale() ? 'selected' : '' }}>
                                    {{ $lang->flag }} {{ $lang->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden" name="lang_code" value="{{ app()->getLocale() }}">
                @endif
                <input type="hidden" name="cpt_slug" value="{{ request('cpt') ?: ($taxonomy->post_types[0] ?? '') }}">
                <div class="mb-4">
                    <label class="block text-[14px] text-[#2c3338] mb-1 font-semibold">Name</label>
                    <input type="text" name="name" class="wp-input w-full" required>
                    <p class="text-[12px] text-[#646970] mt-1 italic">The name is how it appears on your site.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-[14px] text-[#2c3338] mb-1 font-semibold">Slug</label>
                    <input type="text" name="slug" class="wp-input w-full">
                    <p class="text-[12px] text-[#646970] mt-1 italic">The "slug" is the URL-friendly version of the name.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-[14px] text-[#2c3338] mb-1 font-semibold">Parent {{ $taxonomy->singular_name ?? $taxonomy->name }}</label>
                    <select name="parent_id" class="wp-input w-full h-8 py-0">
                        <option value="">None</option>
                        @foreach($fullTree ?? [] as $t)
                            <option value="{{ $t->id }}">{{ str_repeat('— ', $t->level ?? 0) }}{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-[14px] text-[#2c3338] mb-1 font-semibold">Description</label>
                    <textarea name="description" rows="5" class="wp-input w-full"></textarea>
                    <p class="text-[12px] text-[#646970] mt-1 italic">The description is not prominent by default; however, some themes may show it.</p>
                </div>

                <button type="submit" class="wp-btn-secondary h-8 px-4 font-semibold">Add New {{ $taxonomy->singular_name ?? $taxonomy->name }}</button>
            </form>
        </div>

        <!-- Terms List Column -->
        <div class="flex-grow">
            <form action="{{ route('admin.acpt.terms.bulk', $taxonomy->slug) }}" method="POST">
                @csrf
                <div class="flex justify-between items-center mb-2">
                    <div class="flex gap-2 items-center">
                        <select name="action" class="wp-input h-8 py-0 text-[13px]">
                            <option value="-1">Bulk actions</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="button" onclick="handleBulkTermAction()" class="wp-btn-secondary h-8 leading-[1] text-[13px]">Apply</button>
                    </div>
                    <x-cms-dashboard::admin.pagination :paginator="$terms" />
                </div>

                <div class="bg-white border border-[#c3c4c7] shadow-sm">
                    <table class="wp-list-table w-full text-left text-[13px]">
                        <thead>
                            <tr class="border-b border-[#c3c4c7] bg-[#f6f7f7]">
                                <th class="p-3 font-bold w-10"><input type="checkbox" id="select-all"></th>
                                <th class="p-3 font-bold">Name</th>
                                <th class="p-3 font-bold">Description</th>
                                <th class="p-3 font-bold">Slug</th>
                                <th class="p-3 font-bold w-20 text-center">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($terms as $term)
                            <tr class="border-b border-[#f0f0f1] hover:bg-[#f6f7f7] group">
                                <td class="p-3"><input type="checkbox" name="ids[]" value="{{ $term->id }}" class="item-checkbox"></td>
                                <td class="p-3">
                                    <a href="{{ route('admin.acpt.terms.edit', [$taxonomy->slug, $term->id, 'cpt' => request('cpt') ?: ($taxonomy->post_types[0] ?? '')]) }}" class="text-[#2271b1] font-bold block">{{ str_repeat('— ', $term->level ?? 0) }}{{ $term->name }}</a>
                                    <div class="mt-1 invisible group-hover:visible space-x-2 text-[12px]">
                                        <a href="{{ route('admin.acpt.terms.edit', [$taxonomy->slug, $term->id, 'cpt' => request('cpt') ?: ($taxonomy->post_types[0] ?? '')]) }}" class="text-[#2271b1]">Edit</a> | 
                                        <button type="button" class="text-[#b32d2e] hover:underline" onclick="confirmDeleteTerm({{ $term->id }}, '{{ addslashes($term->name) }}')">Delete</button>
                                    </div>
                                </td>
                                <td class="p-3 text-[#646970]">{{ $term->description ?: '—' }}</td>
                                <td class="p-3 text-[#646970]">{{ $term->slug }}</td>
                                <td class="p-3 text-center">
                                    <a href="{{ route('admin.posts.index', ['type' => request('cpt') ?: ($taxonomy->post_types[0] ?? 'post'), 'term_id' => $term->id]) }}" class="text-[#2271b1] font-semibold">{{ $term->posts_count ?? 0 }}</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-3 text-center italic text-[#646970]">No terms found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end">
                    <x-cms-dashboard::admin.pagination :paginator="$terms" />
                </div>
            </form>
        </div>
    </div>

    <form id="single-delete-form" method="POST" class="hidden">
        @csrf @method('DELETE')
    </form>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
        });

        window.confirmDeleteTerm = async function(id, name) {
            const confirmed = await window.lazyConfirm({
                title: 'Delete Term',
                message: `Are you sure you want to delete the term "${name}"? This action cannot be undone.`,
                confirmText: 'Delete Term',
                isDanger: true
            });

            if (confirmed) {
                const form = document.getElementById('single-delete-form');
                form.action = `{{ url('/admin/acpt/tax-terms/' . $taxonomy->slug) }}/${id}`;
                form.submit();
            }
        };

        window.handleBulkTermAction = async function() {
            const action = document.querySelector('select[name="action"]').value;
            const selected = document.querySelectorAll('.item-checkbox:checked');

            if (action === '-1') return;
            if (selected.length === 0) {
                window.showToast('Please select at least one term.', 'warning');
                return;
            }

            if (action === 'delete') {
                const confirmed = await window.lazyConfirm({
                    title: 'Bulk Delete Terms',
                    message: `Are you sure you want to permanently delete ${selected.length} terms? This action cannot be undone.`,
                    confirmText: 'Delete Selected',
                    isDanger: true
                });

                if (confirmed) {
                    document.querySelector('form[action="{{ route('admin.acpt.terms.bulk', $taxonomy->slug) }}"]').submit();
                }
            } else {
                document.querySelector('form[action="{{ route('admin.acpt.terms.bulk', $taxonomy->slug) }}"]').submit();
            }
        };
    </script>

</x-cms-dashboard::layouts.admin>
