<!-- Dimension Settings -->
<div class="space-y-6">
    <div class="bg-slate-50/50 p-4 rounded-lg border border-slate-100">
        <h4 class="text-[11px] font-black uppercase tracking-widest text-[#0091ea] mb-4">Dimension</h4>
        
        <div class="space-y-4">
            <!-- Width -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wide">WIDTH</label>
                    <div class="flex bg-white border border-slate-200 rounded p-0.5">
                        <button @click="editingElement.settings.widthUnit = 'px'" :class="editingElement.settings.widthUnit === 'px' ? 'bg-slate-100' : ''" class="px-2 py-0.5 text-[9px] font-bold rounded">PX</button>
                        <button @click="editingElement.settings.widthUnit = '%'" :class="editingElement.settings.widthUnit === '%' ? 'bg-slate-100' : ''" class="px-2 py-0.5 text-[9px] font-bold rounded">%</button>
                    </div>
                </div>
                <input type="number" v-model="editingElement.settings.width" class="w-full border border-slate-200 rounded px-3 py-2 text-[13px] focus:outline-none focus:border-[#0091ea]">
            </div>

            <!-- Max Width -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wide">MAX WIDTH</label>
                    <div class="flex bg-white border border-slate-200 rounded p-0.5">
                        <button @click="editingElement.settings.maxWidthUnit = 'px'" :class="editingElement.settings.maxWidthUnit === 'px' ? 'bg-slate-100' : ''" class="px-2 py-0.5 text-[9px] font-bold rounded">PX</button>
                        <button @click="editingElement.settings.maxWidthUnit = '%'" :class="editingElement.settings.maxWidthUnit === '%' ? 'bg-slate-100' : ''" class="px-2 py-0.5 text-[9px] font-bold rounded">%</button>
                    </div>
                </div>
                <input type="number" v-model="editingElement.settings.maxWidth" class="w-full border border-slate-200 rounded px-3 py-2 text-[13px] focus:outline-none focus:border-[#0091ea]">
            </div>

        </div>
    </div>

    <!-- Spacing & Border -->
    <div class="bg-slate-50/50 p-4 rounded-lg border border-slate-100">
        <h4 class="text-[11px] font-black uppercase tracking-widest text-[#0091ea] mb-4">Spacing & Style</h4>
        
        <div class="space-y-4">
            <!-- Margins -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wide">MARGIN</label>
                    <i class="fa fa-desktop text-[10px] text-slate-300"></i>
                </div>
                <div class="grid grid-cols-4 gap-2">
                    <div>
                        <label class="text-[8px] text-slate-400 font-bold uppercase mb-1 block">Top</label>
                        <input type="text" v-model="editingElement.settings.marginTop" class="w-full border border-slate-200 rounded py-2 text-center text-[12px]">
                    </div>
                    <div>
                        <label class="text-[8px] text-slate-400 font-bold uppercase mb-1 block">Right</label>
                        <input type="text" v-model="editingElement.settings.marginRight" class="w-full border border-slate-200 rounded py-2 text-center text-[12px]">
                    </div>
                    <div>
                        <label class="text-[8px] text-slate-400 font-bold uppercase mb-1 block">Bottom</label>
                        <input type="text" v-model="editingElement.settings.marginBottom" class="w-full border border-slate-200 rounded py-2 text-center text-[12px]">
                    </div>
                    <div>
                        <label class="text-[8px] text-slate-400 font-bold uppercase mb-1 block">Left</label>
                        <input type="text" v-model="editingElement.settings.marginLeft" class="w-full border border-slate-200 rounded py-2 text-center text-[12px]">
                    </div>
                </div>
            </div>

            <!-- Border Radius -->
            <div>
                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wide block mb-2">BORDER RADIUS</label>
                <input type="number" v-model="editingElement.settings.borderRadius" class="w-full border border-slate-200 rounded px-3 py-2 text-[13px]">
            </div>

            <!-- Border Settings -->
            <div>
                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wide block mb-2">BORDER COLOR</label>
                <div class="flex gap-2 items-center">
                    <div class="checkerboard rounded-full overflow-hidden w-9 h-9 flex-shrink-0 border border-slate-200 shadow-sm cursor-pointer"
                         @click="openColorPicker($event, editingElement.settings, 'borderColor')">
                        <div :style="{ backgroundColor: editingElement.settings.borderColor }" class="w-full h-full"></div>
                    </div>
                    <div class="relative flex-1">
                        <input type="text" v-model="editingElement.settings.borderColor"
                               placeholder="#000000"
                               class="w-full border border-slate-200 rounded px-3 py-2 text-[13px]">
                    </div>
                </div>
            </div>

            <!-- Border Size -->
            <div>
                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wide block mb-2">BORDER SIZE</label>
                <div class="grid grid-cols-4 gap-2">
                    <div>
                        <label class="text-[8px] font-bold text-slate-400 uppercase mb-1 block">Top</label>
                        <input type="number" v-model.number="editingElement.settings.borderSizeTop" class="w-full border border-slate-200 rounded py-2 text-center text-[12px]">
                    </div>
                    <div>
                        <label class="text-[8px] font-bold text-slate-400 uppercase mb-1 block">Right</label>
                        <input type="number" v-model.number="editingElement.settings.borderSizeRight" class="w-full border border-slate-200 rounded py-2 text-center text-[12px]">
                    </div>
                    <div>
                        <label class="text-[8px] font-bold text-slate-400 uppercase mb-1 block">Bottom</label>
                        <input type="number" v-model.number="editingElement.settings.borderSizeBottom" class="w-full border border-slate-200 rounded py-2 text-center text-[12px]">
                    </div>
                    <div>
                        <label class="text-[8px] font-bold text-slate-400 uppercase mb-1 block">Left</label>
                        <input type="number" v-model.number="editingElement.settings.borderSizeLeft" class="w-full border border-slate-200 rounded py-2 text-center text-[12px]">
                    </div>
                </div>
            </div>

            <!-- Hover Effect -->
            <div>
                <label class="text-[11px] font-bold text-slate-600 uppercase tracking-wide block mb-2">HOVER EFFECT</label>
                <select v-model="editingElement.settings.hoverType"
                        class="w-full border border-slate-200 rounded px-3 py-2 text-[13px] focus:outline-none focus:border-[#0091ea]">
                    <option value="none">None</option>
                    <option value="zoom-in">Zoom In</option>
                    <option value="zoom-out">Zoom Out</option>
                    <option value="lift">Lift</option>
                    <option value="shadow">Shadow</option>
                    <option value="opacity">Opacity</option>
                </select>
            </div>
        </div>
    </div>
</div>
