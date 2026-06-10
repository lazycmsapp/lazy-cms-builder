{{-- Reusable Element Visibility control (per-device show/hide). Binds editingElement.settings.visibility --}}
<div>
    <div class="flex justify-between items-center mb-3">
        <label class="text-[12px] font-bold text-[#333]">Element Visibility</label>
    </div>
    <div class="grid grid-cols-3 gap-2">
        <button @click="editingElement.settings.visibility.mobile = !editingElement.settings.visibility.mobile"
                :class="editingElement.settings.visibility.mobile ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400'"
                class="py-3 rounded transition-all flex items-center justify-center" title="Mobile">
            <i class="fa fa-mobile-alt text-sm"></i>
        </button>
        <button @click="editingElement.settings.visibility.tablet = !editingElement.settings.visibility.tablet"
                :class="editingElement.settings.visibility.tablet ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400'"
                class="py-3 rounded transition-all flex items-center justify-center" title="Tablet">
            <i class="fa fa-tablet-alt text-sm"></i>
        </button>
        <button @click="editingElement.settings.visibility.desktop = !editingElement.settings.visibility.desktop"
                :class="editingElement.settings.visibility.desktop ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400'"
                class="py-3 rounded transition-all flex items-center justify-center" title="Desktop">
            <i class="fa fa-desktop text-sm"></i>
        </button>
    </div>
</div>
