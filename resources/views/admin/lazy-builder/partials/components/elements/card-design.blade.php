{{-- 1. Layout --}}
<div>
    <label class="text-[12px] font-bold text-[#333] block mb-2">Layout</label>
    <div class="grid grid-cols-4 gap-1.5">
        <button v-for="opt in [{v:'grid',i:'fa-th-large',l:'Grid'},{v:'list',i:'fa-list',l:'List'},{v:'masonry',i:'fa-columns',l:'Masonry'},{v:'carousel',i:'fa-film',l:'Carousel'}]"
                :key="opt.v"
                @click="editingElement.settings.layout = opt.v"
                :class="editingElement.settings.layout === opt.v ? 'bg-[#0091ea] text-white border-[#0091ea]' : 'bg-white text-slate-500 border-slate-200 hover:border-[#0091ea]/50'"
                class="flex flex-col items-center justify-center gap-1 py-2.5 border rounded transition-all text-center">
            <i :class="'fa ' + opt.i" class="text-sm"></i>
            <span class="text-[10px] font-bold">@{{ opt.l }}</span>
        </button>
    </div>
</div>

{{-- 2. Card Alignment (align-items within each grid row) --}}
<div>
    <label class="text-[12px] font-bold text-[#333] block mb-2">Card Alignment</label>
    <div class="grid grid-cols-2 gap-2">
        <button @click="editingElement.settings.card_alignment = 'flex-start'"
                :class="(editingElement.settings.card_alignment === 'flex-start' || editingElement.settings.card_alignment === 'start' || editingElement.settings.card_alignment === 'left' || !editingElement.settings.card_alignment) ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <rect x="5" y="4" width="3" height="10" rx="0.5"/>
                <rect x="10.5" y="4" width="3" height="14" rx="0.5"/>
                <rect x="16" y="4" width="3" height="8" rx="0.5"/>
            </svg>
            <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Top</div>
        </button>
        <button @click="editingElement.settings.card_alignment = 'center'"
                :class="editingElement.settings.card_alignment === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <rect x="5" y="7" width="3" height="10" rx="0.5"/>
                <rect x="10.5" y="5" width="3" height="14" rx="0.5"/>
                <rect x="16" y="8" width="3" height="8" rx="0.5"/>
            </svg>
            <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Center</div>
        </button>
        <button @click="editingElement.settings.card_alignment = 'flex-end'"
                :class="(editingElement.settings.card_alignment === 'flex-end' || editingElement.settings.card_alignment === 'end' || editingElement.settings.card_alignment === 'right') ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <rect x="5" y="10" width="3" height="10" rx="0.5"/>
                <rect x="10.5" y="6" width="3" height="14" rx="0.5"/>
                <rect x="16" y="12" width="3" height="8" rx="0.5"/>
            </svg>
            <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Bottom</div>
        </button>
        <button @click="editingElement.settings.card_alignment = 'stretch'"
                :class="(editingElement.settings.card_alignment === 'stretch' || editingElement.settings.card_alignment === '') ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 3l3 3h-2v12h2l-3 3-3-3h2V6H9l3-3z"/>
            </svg>
            <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Stretch</div>
        </button>
    </div>
</div>

{{-- 3. Number of Columns (responsive) --}}
<div>
    <label class="text-[12px] font-bold text-[#333] block mb-2">Number of Columns</label>
    <div class="grid grid-cols-3 gap-2">
        <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1"><i class="fa fa-desktop mr-1"></i>Desktop</label>
            <input type="number" v-model.number="editingElement.settings.columns"
                   min="1" max="6"
                   class="w-full border border-slate-200 rounded px-3 py-2 text-[13px] text-slate-600 focus:outline-none focus:border-[#0091ea]">
        </div>
        <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1"><i class="fa fa-tablet-alt mr-1"></i>Tablet</label>
            <input type="number" v-model.number="editingElement.settings.columns_tablet"
                   min="1" max="4"
                   class="w-full border border-slate-200 rounded px-3 py-2 text-[13px] text-slate-600 focus:outline-none focus:border-[#0091ea]">
        </div>
        <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1"><i class="fa fa-mobile-alt mr-1"></i>Mobile</label>
            <input type="number" v-model.number="editingElement.settings.columns_mobile"
                   min="1" max="2"
                   class="w-full border border-slate-200 rounded px-3 py-2 text-[13px] text-slate-600 focus:outline-none focus:border-[#0091ea]">
        </div>
    </div>
</div>

{{-- 4. Column Spacing --}}
<div>
    <div class="flex justify-between items-center mb-2">
        <label class="text-[12px] font-bold text-[#333]">Column Spacing</label>
        <span class="text-[12px] text-slate-400 font-bold">@{{ editingElement.settings.column_spacing ?? 24 }}px</span>
    </div>
    <div class="flex gap-3 items-center">
        <input type="range" v-model.number="editingElement.settings.column_spacing"
               min="0" max="80" class="flex-1 accent-[#0091ea]">
        <input type="number" v-model.number="editingElement.settings.column_spacing"
               min="0" max="80"
               class="w-16 border border-slate-200 rounded px-2 py-2 text-[13px] text-center focus:outline-none focus:border-[#0091ea]">
    </div>
</div>

{{-- 5. Row Spacing --}}
<div>
    <div class="flex justify-between items-center mb-2">
        <label class="text-[12px] font-bold text-[#333]">Row Spacing</label>
        <span class="text-[12px] text-slate-400 font-bold">@{{ editingElement.settings.row_spacing ?? 24 }}px</span>
    </div>
    <div class="flex gap-3 items-center">
        <input type="range" v-model.number="editingElement.settings.row_spacing"
               min="0" max="80" class="flex-1 accent-[#0091ea]">
        <input type="number" v-model.number="editingElement.settings.row_spacing"
               min="0" max="80"
               class="w-16 border border-slate-200 rounded px-2 py-2 text-[13px] text-center focus:outline-none focus:border-[#0091ea]">
    </div>
</div>

{{-- 6. Margin --}}
<div>
    <label class="text-[12px] font-bold text-[#333] block mb-3">Margin</label>
    <div class="grid grid-cols-2 gap-3">
        @foreach([['marginTop','Top'],['marginRight','Right'],['marginBottom','Bottom'],['marginLeft','Left']] as [$key,$lbl])
        <div>
            <label class="text-[10px] font-bold text-slate-400 uppercase block mb-1">{{ $lbl }}</label>
            <div class="flex gap-1">
                <input type="number" v-model.number="editingElement.settings.{{ $key }}"
                       class="flex-1 min-w-0 border border-slate-200 rounded-l px-2 py-2 text-[13px] text-slate-600 focus:outline-none focus:border-[#0091ea]">
                <select v-model="editingElement.settings.{{ $key }}Unit"
                        class="border border-l-0 border-slate-200 rounded-r px-1 py-2 text-[12px] text-slate-500 bg-slate-50 focus:outline-none focus:border-[#0091ea]">
                    <option value="px">px</option>
                    <option value="%">%</option>
                    <option value="em">em</option>
                    <option value="rem">rem</option>
                </select>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Element Visibility --}}
<div class="pt-4 border-t border-slate-50">
    <label class="text-[12px] font-bold text-[#333] block mb-2">Element Visibility</label>
    <div class="grid grid-cols-3 gap-1">
        <button @click="editingElement.settings.visibility.mobile = !editingElement.settings.visibility.mobile"
                :class="editingElement.settings.visibility.mobile ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400'"
                class="py-3 rounded transition-all flex items-center justify-center">
            <i class="fa fa-mobile-alt text-sm"></i>
        </button>
        <button @click="editingElement.settings.visibility.tablet = !editingElement.settings.visibility.tablet"
                :class="editingElement.settings.visibility.tablet ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400'"
                class="py-3 rounded transition-all flex items-center justify-center">
            <i class="fa fa-tablet-alt text-sm"></i>
        </button>
        <button @click="editingElement.settings.visibility.desktop = !editingElement.settings.visibility.desktop"
                :class="editingElement.settings.visibility.desktop ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400'"
                class="py-3 rounded transition-all flex items-center justify-center">
            <i class="fa fa-desktop text-sm"></i>
        </button>
    </div>
</div>

{{-- CSS Class & ID --}}
<div class="grid grid-cols-1 gap-4 pt-4 border-t border-slate-50">
    <div>
        <label class="text-[12px] font-bold text-[#333] block mb-2">CSS Class</label>
        <input type="text" v-model="editingElement.settings.cssClass"
               class="w-full border border-slate-200 rounded px-3 py-2.5 text-[13px] text-slate-600 focus:outline-none focus:border-[#0091ea]">
    </div>
    <div>
        <label class="text-[12px] font-bold text-[#333] block mb-2">CSS ID</label>
        <input type="text" v-model="editingElement.settings.cssId"
               class="w-full border border-slate-200 rounded px-3 py-2.5 text-[13px] text-slate-600 focus:outline-none focus:border-[#0091ea]">
    </div>
</div>
