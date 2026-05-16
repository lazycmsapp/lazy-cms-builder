<div v-if="showElementModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm animate-fade-in" @click.self="showElementModal = false">
    <div class="bg-white w-[95vw] max-w-[1200px] h-[90vh] flex flex-col shadow-2xl rounded overflow-hidden">
        
        <!-- Header (Exactly like column-select) -->
        <div class="bg-[#222] text-white h-14 flex items-center justify-between px-6 shrink-0">
            <h3 class="text-sm font-bold uppercase tracking-wider">Select Element</h3>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" v-model="searchElementQuery" :placeholder="elementModalTab === 'nested' ? 'Search Columns' : 'Search Elements'" class="bg-[#333] border-none text-xs text-white px-10 py-2 rounded focus:ring-1 focus:ring-[#0091ea] w-64 outline-none">
                    <i class="fa fa-search absolute left-3 top-2.5 text-slate-500 text-xs"></i>
                </div>
                <button @click="showElementModal = false" class="text-slate-500 hover:text-white transition-colors"><i class="fa fa-times text-lg"></i></button>
            </div>
        </div>

        <!-- Tabs (Exactly like column-select style) -->
        <div class="bg-[#0091ea] h-10 flex items-center px-4 shrink-0">
            <button v-if="elementModalAllowedTabs.includes('elements')"
                    @click="elementModalTab = 'elements'" 
                    class="px-5 h-full text-[11px] font-bold uppercase transition-all"
                    :class="elementModalTab === 'elements' ? 'text-white bg-white/10' : 'text-white/70 hover:bg-white/5'">
                Elements
            </button>
            <button v-if="!elementModalRestricted && elementModalAllowedTabs.includes('nested')"
                    @click="elementModalTab = 'nested'" 
                    class="px-5 h-full text-[11px] font-bold uppercase transition-all"
                    :class="elementModalTab === 'nested' ? 'text-white bg-white/10' : 'text-white/70 hover:bg-white/5'">
                Nested Columns
            </button>
        </div>

        <!-- Grid Body (Exactly like column-select style) -->
        <div class="flex-1 overflow-y-auto p-10 bg-[#fff] custom-scrollbar">
            
            <!-- Elements Tab -->
            <div v-if="elementModalTab === 'elements'" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-x-6 gap-y-10">
                <div v-for="el in filteredAvailableElements" :key="el.type" 
                     @click="addElement(el.type)"
                     class="group flex flex-col items-center gap-3 cursor-pointer">
                    <div class="w-full aspect-square bg-white border border-dashed border-slate-200 p-2 flex flex-col items-center justify-center gap-2 rounded group-hover:border-[#0091ea] group-hover:shadow-md transition-all transform group-hover:-translate-y-1">
                        <i :class="el.icon" class="text-2xl text-slate-400 group-hover:text-[#0091ea] transition-colors"></i>
                    </div>
                    <span class="text-[10px] font-bold uppercase text-slate-500 group-hover:text-[#0091ea] transition-colors">@{{ el.name || el.type }}</span>
                </div>
            </div>

            <!-- Nested Columns Tab (Copy of column-select grid) -->
            <div v-if="elementModalTab === 'nested'" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-x-6 gap-y-10">
                <div v-for="layout in filteredNestedColumnLayouts" :key="layout.id" 
                     @click="selectNestedLayout(layout.config)"
                     class="group flex flex-col items-center gap-3 cursor-pointer">
                    <div class="w-full aspect-[16/10] bg-white border border-transparent group-hover:border-slate-200 group-hover:border-dashed p-1 transition-all rounded">
                        <div class="w-full h-full flex gap-1 justify-center">
                            <div v-for="part in layout.config.split('-')" 
                                 class="h-full bg-[#9da8b1] rounded-sm transition-colors group-hover:bg-[#86929c]"
                                 :style="{ width: `calc(${(part.split('/')[0] / part.split('/')[1]) * 100}% - 4px)` }"></div>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-slate-500 group-hover:text-black transition-colors">@{{ layout.label }}</span>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="h-12 border-t border-slate-100 flex items-center px-6 bg-slate-50/50 shrink-0">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Lazy CMS Rebuild • Element Selector</span>
        </div>

    </div>
</div>
