{{-- Builder Library Modal --}}
<transition name="modal-fade">
<div v-if="showLibraryModal"
     class="fixed inset-0 bg-black/50 z-[9998] flex items-center justify-center p-4"
     @click.self="showLibraryModal = false">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[88vh] flex flex-col overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[#0091ea]/10 rounded-lg flex items-center justify-center">
                    <i class="fa fa-archive text-[#0091ea] text-sm"></i>
                </div>
                <h2 class="text-base font-bold text-slate-700">Builder Library</h2>
            </div>
            <button @click="showLibraryModal = false"
                    class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded-lg transition-colors text-slate-400 hover:text-slate-600">
                <i class="fa fa-times"></i>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="flex border-b border-slate-100 px-6 flex-shrink-0 bg-white">
            <button v-for="tab in libraryTabs" :key="tab.key"
                    @click="libraryActiveTab = tab.key"
                    class="px-4 py-3 text-xs font-semibold border-b-2 transition-colors flex items-center gap-2 mr-1"
                    :class="libraryActiveTab === tab.key
                        ? 'border-[#0091ea] text-[#0091ea]'
                        : 'border-transparent text-slate-400 hover:text-slate-600'">
                <i :class="tab.icon"></i>
                @{{ tab.label }}
                <span v-if="libraryItems[tab.key]?.length"
                      class="px-1.5 py-0.5 rounded-full text-[10px] font-bold"
                      :class="libraryActiveTab === tab.key ? 'bg-[#0091ea]/10 text-[#0091ea]' : 'bg-slate-100 text-slate-400'">
                    @{{ libraryItems[tab.key].length }}
                </span>
            </button>
        </div>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto p-6">

            {{-- Save current item to library --}}
            <div v-if="libraryCanSave"
                 class="mb-6 p-4 bg-[#0091ea]/5 rounded-xl border border-[#0091ea]/20">
                <p class="text-xs font-semibold text-slate-600 mb-3 flex items-center gap-2">
                    <i class="fa fa-download text-[#0091ea]"></i>
                    Save current <span class="text-[#0091ea]">@{{ libraryActiveTabLabel }}</span> to library
                </p>
                <div class="flex gap-3">
                    <input v-model="libraryNewName"
                           type="text"
                           placeholder="Give it a name..."
                           class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#0091ea] focus:ring-2 focus:ring-[#0091ea]/10"
                           @keydown.enter="saveToLibrary">
                    <button @click="saveToLibrary"
                            :disabled="!libraryNewName.trim() || isSavingToLibrary"
                            class="px-4 py-2 bg-[#0091ea] text-white text-sm font-semibold rounded-lg hover:bg-[#0082d3] disabled:opacity-40 disabled:cursor-not-allowed transition-colors flex items-center gap-2 whitespace-nowrap">
                        <i :class="isSavingToLibrary ? 'fa fa-spinner fa-spin' : 'fa fa-save'"></i>
                        Save to Library
                    </button>
                </div>
            </div>

            {{-- Empty state --}}
            <div v-if="libraryCurrentItems.length === 0"
                 class="text-center py-16 text-slate-300">
                <i class="fa fa-box-open text-5xl mb-4 block"></i>
                <p class="text-sm font-semibold text-slate-400">No saved @{{ libraryActiveTabLabel }} yet</p>
                <p class="text-xs text-slate-300 mt-1">Save items from the canvas toolbar to reuse them here</p>
            </div>

            {{-- Library grid --}}
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <div v-for="item in libraryCurrentItems" :key="item.id"
                     class="border border-slate-200 rounded-xl overflow-hidden hover:border-[#0091ea] hover:shadow-md transition-all group cursor-default">
                    {{-- Icon Preview --}}
                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 p-8 flex items-center justify-center border-b border-slate-100">
                        <i :class="libraryTabIcon + ' text-4xl text-slate-200 group-hover:text-[#0091ea]/40 transition-colors'"></i>
                    </div>
                    {{-- Info + Actions --}}
                    <div class="p-3">
                        <p class="text-xs font-bold text-slate-700 mb-1 truncate" :title="item.name">@{{ item.name }}</p>
                        <p class="text-[10px] text-slate-400 mb-3">@{{ item.created_at }}</p>
                        <div class="flex gap-2">
                            <button @click="insertFromLibrary(item)"
                                    class="flex-1 text-[11px] py-1.5 bg-[#0091ea] text-white rounded-lg hover:bg-[#0082d3] transition-colors font-semibold flex items-center justify-center gap-1">
                                <i class="fa fa-plus text-[10px]"></i> Add @{{ {containers:'Container',columns:'Column',nested_columns:'Nested Column',elements:'Element'}[libraryActiveTab] }}
                            </button>
                            <button @click="deleteFromLibrary(item.id)"
                                    class="w-8 py-1.5 bg-red-50 text-red-400 rounded-lg hover:bg-red-100 hover:text-red-500 transition-colors flex items-center justify-center"
                                    title="Delete from library">
                                <i class="fa fa-trash-alt text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</transition>
