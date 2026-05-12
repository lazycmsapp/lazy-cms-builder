<div v-if="showColumnModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm animate-fade-in" @click.self="showColumnModal = false">
    <div class="bg-white w-[95vw] max-w-[1200px] h-[90vh] flex flex-col shadow-2xl rounded overflow-hidden">
        <!-- Header -->
        <div class="bg-[#222] text-white h-14 flex items-center justify-between px-6 shrink-0">
            <h3 class="text-sm font-bold uppercase tracking-wider">@{{ columnModalType === 'new' ? 'Select Column' : 'Select Column Layout' }}</h3>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <input type="text" v-model="searchColumnQuery" placeholder="Search Columns" class="bg-[#333] border-none text-xs text-white px-10 py-2 rounded focus:ring-1 focus:ring-[#0091ea] w-64 outline-none">
                    <i class="fa fa-search absolute left-3 top-2.5 text-slate-500 text-xs"></i>
                </div>
                <button @click="showColumnModal = false" class="text-slate-500 hover:text-white transition-colors"><i class="fa fa-times text-lg"></i></button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-[#0091ea] h-10 flex items-center px-4 shrink-0">
            <button class="px-4 h-full text-[11px] font-bold uppercase text-white bg-white/10">Builder Columns</button>
            <button class="px-4 h-full text-[11px] font-bold uppercase text-white/70 hover:bg-white/5 transition-all">Library Columns</button>
        </div>

        <!-- Grid Body -->
        <div class="flex-1 overflow-y-auto p-10 bg-[#fff]">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-x-6 gap-y-10">
                <div v-for="layout in filteredColumnLayouts" :key="layout.id" 
                     class="group flex flex-col items-center gap-3 cursor-pointer"
                     @click="selectLayout(layout)">
                    
                    <!-- Visual Rep  -->
                    <div class="w-full aspect-[16/10] bg-white border border-transparent group-hover:border-slate-200 group-hover:border-dashed p-1 transition-all rounded">
                        <div class="w-full h-full flex gap-1 justify-center">
                            <div v-for="part in layout.config.split('-')" 
                                 class="h-full bg-[#9da8b1] rounded-sm transition-colors group-hover:bg-[#86929c]"
                                 :style="{ width: `calc(${(part.split('/')[0] / part.split('/')[1]) * 100}% - 4px)` }"></div>
                        </div>
                    </div>

                    <!-- Label -->
                    <span class="text-[11px] font-bold text-slate-500 group-hover:text-black transition-colors">@{{ layout.label }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
