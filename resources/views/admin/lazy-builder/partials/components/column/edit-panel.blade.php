@php 
    $baseCol = "layout[editingContext.ci].columns[editingContext.coli]";
    $nestedCol = "layout[editingContext.ci].columns[editingContext.coli].elements[editingContext.eli].columns[editingContext.ncoli]";
    $c = "((editingContext.type === 'nested-column') ? $nestedCol : $baseCol).settings";
@endphp

<div class="h-full flex flex-col bg-white">
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
        <h3 class="text-[13px] font-bold text-[#444]">@{{ editingContext.type === 'nested-column' ? 'Nested Column' : 'Column' }}</h3>
        <div class="flex gap-2">
            <button class="text-slate-400 hover:text-slate-600"><i class="fa fa-ellipsis-h text-[10px]"></i></button>
            <button @click="activeTab='navigator'; editingContext.type = null" class="text-slate-400 hover:text-red-500"><i class="fa fa-times text-[10px]"></i></button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex bg-[#0091ea]">
        <button @click="activeColPanelTab = 'general'"
                :class="activeColPanelTab === 'general' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'"
                class="flex-1 py-2 text-[11px] font-bold transition-all flex items-center justify-center gap-1">
            <i class="fa fa-sliders-h"></i>
            <span v-if="activeColPanelTab === 'general'">General</span>
        </button>
        <button @click="activeColPanelTab = 'design'"
                :class="activeColPanelTab === 'design' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'"
                class="flex-1 py-2 text-[11px] font-bold transition-all flex items-center justify-center gap-1">
            <i class="fa fa-paint-brush"></i>
            <span v-if="activeColPanelTab === 'design'">Design</span>
        </button>
        <button @click="activeColPanelTab = 'background'"
                :class="activeColPanelTab === 'background' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'"
                class="flex-1 py-2 text-[11px] font-bold transition-all flex items-center justify-center gap-1">
            <i class="fa fa-image"></i>
            <span v-if="activeColPanelTab === 'background'">BG</span>
        </button>
        <button @click="activeColPanelTab = 'extra'"
                :class="activeColPanelTab === 'extra' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'"
                class="flex-1 py-2 text-[11px] font-bold transition-all flex items-center justify-center gap-1">
            <i class="fa fa-cog"></i>
            <span v-if="activeColPanelTab === 'extra'">Extra</span>
        </button>
    </div>

    <!-- Panel body -->
    <div class="flex-1 overflow-y-auto p-4"
         v-if="editingColumn">

        <!-- ══ GENERAL TAB ══ -->
        <div v-show="activeColPanelTab === 'general'" class="space-y-6">

            <!-- Alignment (align-self) -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Alignment</label>
                    <div class="flex gap-1 items-center">
                        <button @click="resetResponsiveVal({{ $c }}, 'alignment', device, 'default')" title="Reset" class="text-slate-300 hover:text-red-500 transition-colors"><i class="fa fa-undo text-[10px]"></i></button>
                        <div class="relative inline-block">
                            <button @click="activeResponsiveMenu = activeResponsiveMenu === 'colAlignment' ? null : 'colAlignment'" class="px-1.5 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] transition-all flex items-center gap-1">
                                <i class="fa" :class="device === 'desktop' ? 'fa-desktop' : (device === 'tablet' ? 'fa-tablet-alt' : 'fa-mobile-alt')"></i>
                                <i class="fa fa-caret-down text-[8px] text-slate-400"></i>
                            </button>
                            <div v-show="activeResponsiveMenu === 'colAlignment'" class="absolute right-0 mt-1 bg-white border border-slate-200 rounded shadow-lg z-50 flex gap-0.5 p-1 min-w-max">
                                <button @click="device = 'desktop'; activeResponsiveMenu = null" :class="device === 'desktop' ? 'bg-[#0091ea] text-white' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all"><i class="fa fa-desktop text-[11px]"></i></button>
                                <button @click="device = 'tablet'; activeResponsiveMenu = null" :class="device === 'tablet' ? 'bg-[#0091ea] text-white' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all"><i class="fa fa-tablet-alt text-[11px]"></i></button>
                                <button @click="device = 'mobile'; activeResponsiveMenu = null" :class="device === 'mobile' ? 'bg-[#0091ea] text-white' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all"><i class="fa fa-mobile-alt text-[11px]"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-5 gap-1">
                    <button @click="setResponsiveVal({{ $c }}, 'alignment', device, 'default')"
                            :class="(!getResponsiveVal({{ $c }}, 'alignment', device) || getResponsiveVal({{ $c }}, 'alignment', device) === 'default') ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-undo-alt text-[12px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Default</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'alignment', device, 'flex-start')"
                            :class="getResponsiveVal({{ $c }}, 'alignment', device) === 'flex-start' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="4" width="3" height="10" rx="0.5"/><rect x="10.5" y="4" width="3" height="14" rx="0.5"/><rect x="16" y="4" width="3" height="8" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Top</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'alignment', device, 'center')"
                            :class="getResponsiveVal({{ $c }}, 'alignment', device) === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="7" width="3" height="10" rx="0.5"/><rect x="10.5" y="5" width="3" height="14" rx="0.5"/><rect x="16" y="8" width="3" height="8" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Center</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'alignment', device, 'flex-end')"
                            :class="getResponsiveVal({{ $c }}, 'alignment', device) === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="10" width="3" height="10" rx="0.5"/><rect x="10.5" y="6" width="3" height="14" rx="0.5"/><rect x="16" y="12" width="3" height="8" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Bottom</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'alignment', device, 'stretch')"
                            :class="getResponsiveVal({{ $c }}, 'alignment', device) === 'stretch' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3l3 3h-2v12h2l-3 3-3-3h2V6H9l3-3z"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Stretch</div>
                    </button>
                </div>
            </div>

            <!-- Content Layout -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Content Layout</label>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button @click="{{ $c }}.contentLayout = 'column'"
                            :class="{{ $c }}.contentLayout === 'column' || !{{ $c }}.contentLayout ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors text-[11px] font-semibold">Column</button>
                    <button @click="{{ $c }}.contentLayout = 'row'"
                            :class="{{ $c }}.contentLayout === 'row' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors text-[11px] font-semibold">Row</button>
                    <button @click="{{ $c }}.contentLayout = 'block'"
                            :class="{{ $c }}.contentLayout === 'block' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors text-[11px] font-semibold">Block</button>
                </div>
            </div>

            <!-- Content Alignment: Column mode -->
            <div v-if="{{ $c }}.contentLayout === 'column' || !{{ $c }}.contentLayout">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Content Alignment</label>
                    <div class="flex gap-1 items-center">
                        <button @click="resetResponsiveVal({{ $c }}, 'contentAlignV', device, 'flex-start')" title="Reset" class="text-slate-300 hover:text-red-500 transition-colors"><i class="fa fa-undo text-[10px]"></i></button>
                        <div class="relative inline-block">
                            <button @click="activeResponsiveMenu = activeResponsiveMenu === 'colContentAlignV' ? null : 'colContentAlignV'" class="px-1.5 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] transition-all flex items-center gap-1">
                                <i class="fa" :class="device === 'desktop' ? 'fa-desktop' : (device === 'tablet' ? 'fa-tablet-alt' : 'fa-mobile-alt')"></i>
                                <i class="fa fa-caret-down text-[8px] text-slate-400"></i>
                            </button>
                            <div v-show="activeResponsiveMenu === 'colContentAlignV'" class="absolute right-0 mt-1 bg-white border border-slate-200 rounded shadow-lg z-50 flex gap-0.5 p-1 min-w-max">
                                <button @click="device = 'desktop'; activeResponsiveMenu = null" :class="device === 'desktop' ? 'bg-[#0091ea] text-white' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all"><i class="fa fa-desktop text-[11px]"></i></button>
                                <button @click="device = 'tablet'; activeResponsiveMenu = null" :class="device === 'tablet' ? 'bg-[#0091ea] text-white' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all"><i class="fa fa-tablet-alt text-[11px]"></i></button>
                                <button @click="device = 'mobile'; activeResponsiveMenu = null" :class="device === 'mobile' ? 'bg-[#0091ea] text-white' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all"><i class="fa fa-mobile-alt text-[11px]"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignV', device, 'flex-start')"
                            :class="(!getResponsiveVal({{ $c }}, 'contentAlignV', device) || getResponsiveVal({{ $c }}, 'contentAlignV', device) === 'flex-start') ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="4" width="14" height="2" rx="0.5"/><rect x="7" y="8" width="10" height="4" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Top</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignV', device, 'center')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignV', device) === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="11" width="14" height="2" rx="0.5"/><rect x="7" y="7" width="10" height="3" rx="0.5"/><rect x="7" y="14" width="10" height="3" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Middle</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignV', device, 'flex-end')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignV', device) === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="7" y="12" width="10" height="4" rx="0.5"/><rect x="5" y="18" width="14" height="2" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Bottom</div>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignV', device, 'space-between')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignV', device) === 'space-between' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="3" width="14" height="2" rx="0.5"/><rect x="7" y="6" width="10" height="3" rx="0.5"/><rect x="7" y="15" width="10" height="3" rx="0.5"/><rect x="5" y="19" width="14" height="2" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Between</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignV', device, 'space-around')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignV', device) === 'space-around' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="6" width="14" height="2" rx="0.5"/><rect x="7" y="9" width="10" height="2" rx="0.5"/><rect x="7" y="13" width="10" height="2" rx="0.5"/><rect x="5" y="16" width="14" height="2" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Around</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignV', device, 'space-evenly')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignV', device) === 'space-evenly' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="4" width="14" height="2" rx="0.5"/><rect x="5" y="11" width="14" height="2" rx="0.5"/><rect x="5" y="18" width="14" height="2" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Evenly</div>
                    </button>
                </div>
            </div>

            <!-- Content Alignment: Row mode -->
            <div v-if="{{ $c }}.contentLayout === 'row'">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Content Alignment</label>
                    <div class="flex gap-1 items-center">
                        <button @click="resetResponsiveVal({{ $c }}, 'contentAlignH', device, 'flex-start'); resetResponsiveVal({{ $c }}, 'contentAlignV', device, 'flex-start')" title="Reset" class="text-slate-300 hover:text-red-500 transition-colors"><i class="fa fa-undo text-[10px]"></i></button>
                        <div class="relative inline-block">
                            <button @click="activeResponsiveMenu = activeResponsiveMenu === 'colContentAlignRow' ? null : 'colContentAlignRow'" class="px-1.5 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] transition-all flex items-center gap-1">
                                <i class="fa" :class="device === 'desktop' ? 'fa-desktop' : (device === 'tablet' ? 'fa-tablet-alt' : 'fa-mobile-alt')"></i>
                                <i class="fa fa-caret-down text-[8px] text-slate-400"></i>
                            </button>
                            <div v-show="activeResponsiveMenu === 'colContentAlignRow'" class="absolute right-0 mt-1 bg-white border border-slate-200 rounded shadow-lg z-50 flex gap-0.5 p-1 min-w-max">
                                <button @click="device = 'desktop'; activeResponsiveMenu = null" :class="device === 'desktop' ? 'bg-[#0091ea] text-white' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all"><i class="fa fa-desktop text-[11px]"></i></button>
                                <button @click="device = 'tablet'; activeResponsiveMenu = null" :class="device === 'tablet' ? 'bg-[#0091ea] text-white' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all"><i class="fa fa-tablet-alt text-[11px]"></i></button>
                                <button @click="device = 'mobile'; activeResponsiveMenu = null" :class="device === 'mobile' ? 'bg-[#0091ea] text-white' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all"><i class="fa fa-mobile-alt text-[11px]"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Horizontal (justify-content) --}}
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignH', device, 'flex-start')"
                            :class="(!getResponsiveVal({{ $c }}, 'contentAlignH', device) || getResponsiveVal({{ $c }}, 'contentAlignH', device) === 'flex-start') ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="5" width="2" height="14" rx="0.5"/><rect x="8" y="7" width="4" height="10" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Start</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignH', device, 'center')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignH', device) === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="11" y="5" width="2" height="14" rx="0.5"/><rect x="7" y="7" width="3" height="10" rx="0.5"/><rect x="14" y="7" width="3" height="10" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Center</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignH', device, 'flex-end')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignH', device) === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="12" y="7" width="4" height="10" rx="0.5"/><rect x="18" y="5" width="2" height="14" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">End</div>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignH', device, 'space-between')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignH', device) === 'space-between' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="5" width="2" height="14" rx="0.5"/><rect x="6" y="7" width="3" height="10" rx="0.5"/><rect x="15" y="7" width="3" height="10" rx="0.5"/><rect x="19" y="5" width="2" height="14" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Between</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignH', device, 'space-around')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignH', device) === 'space-around' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="5" width="2" height="14" rx="0.5"/><rect x="9" y="7" width="2" height="10" rx="0.5"/><rect x="13" y="7" width="2" height="10" rx="0.5"/><rect x="16" y="5" width="2" height="14" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Around</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignH', device, 'space-evenly')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignH', device) === 'space-evenly' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="5" width="2" height="14" rx="0.5"/><rect x="11" y="5" width="2" height="14" rx="0.5"/><rect x="18" y="5" width="2" height="14" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Evenly</div>
                    </button>
                </div>
                {{-- Vertical align-items --}}
                <div class="grid grid-cols-2 gap-2">
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignV', device, 'flex-start')"
                            :class="(!getResponsiveVal({{ $c }}, 'contentAlignV', device) || getResponsiveVal({{ $c }}, 'contentAlignV', device) === 'flex-start') ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="4" width="3" height="10" rx="0.5"/><rect x="10.5" y="4" width="3" height="14" rx="0.5"/><rect x="16" y="4" width="3" height="8" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Top</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignV', device, 'center')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignV', device) === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="7" width="3" height="10" rx="0.5"/><rect x="10.5" y="5" width="3" height="14" rx="0.5"/><rect x="16" y="8" width="3" height="8" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Middle</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignV', device, 'flex-end')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignV', device) === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="5" y="10" width="3" height="10" rx="0.5"/><rect x="10.5" y="6" width="3" height="14" rx="0.5"/><rect x="16" y="12" width="3" height="8" rx="0.5"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Bottom</div>
                    </button>
                    <button @click="setResponsiveVal({{ $c }}, 'contentAlignV', device, 'stretch')"
                            :class="getResponsiveVal({{ $c }}, 'contentAlignV', device) === 'stretch' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3l3 3h-2v12h2l-3 3-3-3h2V6H9l3-3z" fill="currentColor"/></svg>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Stretch</div>
                    </button>
                </div>
            </div>

            <!-- Gap -->
            <div v-if="{{ $c }}.contentLayout === 'column' || {{ $c }}.contentLayout === 'row'">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Gap</label>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Width</label>
                        <input type="number" min="0" v-model.number="{{ $c }}.gapWidth" placeholder="px"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Height</label>
                        <input type="number" min="0" v-model.number="{{ $c }}.gapHeight" placeholder="px"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </div>
            </div>

            <!-- Column HTML Tag -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">@{{ editingContext.type === 'nested-column' ? 'Nested Column' : 'Column' }} HTML Tag</label>
                </div>
                <select v-model="{{ $c }}.htmlTag"
                        class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    <option value="div">Default (div)</option>
                    <option value="article">article</option>
                    <option value="section">section</option>
                    <option value="aside">aside</option>
                    <option value="header">header</option>
                    <option value="footer">footer</option>
                </select>
            </div>

            <!-- Link URL -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Link URL</label>
                </div>
                <div class="relative">
                    <input type="text" v-model="{{ $c }}.linkUrl" placeholder="https://"
                           class="w-full border border-slate-200 rounded px-2 py-1.5 pr-7 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    <i class="fa fa-link absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                </div>
            </div>

            <!-- Column Visibility -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Device Visibility</label>
                </div>
                <div class="grid grid-cols-3 gap-2"
                     @click.capture="if (!{{ $c }}.visibility) { {{ $c }}.visibility = { mobile: true, tablet: true, desktop: true }; }">
                    <button @click="{{ $c }}.visibility.mobile = !{{ $c }}.visibility.mobile"
                            :class="{{ $c }}.visibility && {{ $c }}.visibility.mobile !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-mobile-alt text-[12px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Mobile</div>
                    </button>
                    <button @click="{{ $c }}.visibility.tablet = !{{ $c }}.visibility.tablet"
                            :class="{{ $c }}.visibility && {{ $c }}.visibility.tablet !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-tablet-alt text-[12px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Tablet</div>
                    </button>
                    <button @click="{{ $c }}.visibility.desktop = !{{ $c }}.visibility.desktop"
                            :class="{{ $c }}.visibility && {{ $c }}.visibility.desktop !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                            class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                        <i class="fa fa-desktop text-[12px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Desktop</div>
                    </button>
                </div>
            </div>

            <!-- CSS Class -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">CSS Class</label>
                </div>
                <input type="text" v-model="{{ $c }}.cssClass"
                       class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
            </div>

            <!-- CSS ID -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">CSS ID</label>
                </div>
                <input type="text" v-model="{{ $c }}.cssId"
                       class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
            </div>

        </div><!-- /general -->

        <!-- ══ DESIGN TAB (Overhauled) ══ -->
        <div v-show="activeColPanelTab === 'design'" class="space-y-6">
            
            <!-- Width Selection -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <label class="text-[11px] font-bold text-[#444] flex items-center gap-2">
                        Width
                    </label>
                    <div class="flex gap-1 items-center">
                        <div class="relative inline-block">
                            <button @click="activeResponsiveMenu = activeResponsiveMenu === 'colWidth' ? null : 'colWidth'" class="px-1.5 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] transition-all flex items-center gap-1" title="Responsive Mode">
                                <i class="fa" :class="device === 'desktop' ? 'fa-desktop' : (device === 'tablet' ? 'fa-tablet-alt' : 'fa-mobile-alt')"></i>
                                <i class="fa fa-caret-down text-[8px] text-slate-400"></i>
                            </button>
                            <div v-show="activeResponsiveMenu === 'colWidth'" class="absolute right-0 mt-1 bg-white border border-slate-200 rounded shadow-lg z-50 flex gap-0.5 p-1 min-w-max">
                                <button @click="device = 'desktop'; activeResponsiveMenu = null" :class="device === 'desktop' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Large (Desktop)">
                                    <i class="fa fa-desktop text-[11px]"></i>
                                </button>
                                <button @click="device = 'tablet'; activeResponsiveMenu = null" :class="device === 'tablet' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Medium (Tablet)">
                                    <i class="fa fa-tablet-alt text-[11px]"></i>
                                </button>
                                <button @click="device = 'mobile'; activeResponsiveMenu = null" :class="device === 'mobile' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Small (Mobile)">
                                    <i class="fa fa-mobile-alt text-[11px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-5 gap-1 mb-3">
                    <button v-for="w in ['16.66%', '20%', '25%', '33.33%', '40%', '50%', '60%', '66.66%', '75%', '80%', '83.33%', '100%', 'auto']"
                            @click="updateBasis(w)"
                            :class="editingColumn.basis === w ? 'bg-[#0091ea] text-white' : 'bg-slate-50 text-slate-400 border-slate-100'"
                            class="py-1.5 border rounded text-[9px] font-bold transition-all hover:border-[#0091ea]">
                        @{{ formatBasisToFraction(w) }}
                    </button>
                </div>
                <button class="text-[11px] text-[#0091ea] font-bold flex items-center gap-1.5 hover:underline">
                    <i class="fa fa-pen text-[9px]"></i> Use Custom Width
                </button>
            </div>

            <!-- Layout Logic -->
            <div class="space-y-4 pt-4 border-t border-slate-50">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Maximum Height</label>
                    <input type="text" v-model="{{ $c }}.maxHeight" placeholder="e.g. 500px or 50vh" class="w-full border border-slate-200 rounded px-3 py-2 text-[12px] focus:outline-none focus:border-[#0091ea]">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Flex Grow</label>
                        <input type="number" min="0" v-model.number="{{ $c }}.flexGrow" placeholder="0" class="w-full border border-slate-200 rounded px-3 py-2 text-[12px] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Flex Shrink</label>
                        <input type="number" min="0" v-model.number="{{ $c }}.flexShrink" placeholder="0" class="w-full border border-slate-200 rounded px-3 py-2 text-[12px] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </div>
            </div>

            <!-- Column Spacing -->
            <div class="pt-4 border-t border-slate-50">
                <label class="text-[11px] font-bold text-[#444] block mb-3">@{{ editingContext.type === 'nested-column' ? 'Nested Column' : 'Column' }} Spacing</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Left</label>
                        <input type="number" min="0" v-model.number="{{ $c }}.columnSpacingLeft" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Right</label>
                        <input type="number" min="0" v-model.number="{{ $c }}.columnSpacingRight" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px]">
                    </div>
                </div>
            <!-- Margin Section -->
            <div class="pt-4 border-t border-slate-50">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-[13px] font-bold text-[#333]">Margin</label>
                    <div class="flex gap-1 items-center">
                        <button @click="resetResponsiveVal({{ $c }}, 'marginTop', device, ''); resetResponsiveVal({{ $c }}, 'marginBottom', device, '')" title="Reset Value" class="text-slate-300 hover:text-red-500 transition-colors">
                            <i class="fa fa-undo text-[10px]"></i>
                        </button>
                        <div class="relative inline-block">
                            <button @click="activeResponsiveMenu = activeResponsiveMenu === 'colMargin' ? null : 'colMargin'" class="px-1.5 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] transition-all flex items-center gap-1" title="Responsive Mode">
                                <i class="fa" :class="device === 'desktop' ? 'fa-desktop' : (device === 'tablet' ? 'fa-tablet-alt' : 'fa-mobile-alt')"></i>
                                <i class="fa fa-caret-down text-[8px] text-slate-400"></i>
                            </button>
                            <div v-show="activeResponsiveMenu === 'colMargin'" class="absolute right-0 mt-1 bg-white border border-slate-200 rounded shadow-lg z-50 flex gap-0.5 p-1 min-w-max">
                                <button @click="device = 'desktop'; activeResponsiveMenu = null" :class="device === 'desktop' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Large (Desktop)">
                                    <i class="fa fa-desktop text-[11px]"></i>
                                </button>
                                <button @click="device = 'tablet'; activeResponsiveMenu = null" :class="device === 'tablet' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Medium (Tablet)">
                                    <i class="fa fa-tablet-alt text-[11px]"></i>
                                </button>
                                <button @click="device = 'mobile'; activeResponsiveMenu = null" :class="device === 'mobile' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Small (Mobile)">
                                    <i class="fa fa-mobile-alt text-[11px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="flex flex-col gap-1">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Top</label>
                        <div class="flex border border-slate-200 rounded-md overflow-hidden focus-within:ring-1 focus-within:ring-[#0091ea]/20 focus-within:border-[#0091ea]">
                            <input type="number" v-model.number="{{ $c }}[device === 'desktop' ? 'marginTop' : 'marginTop_' + device]" class="w-full h-9 px-3 text-[12px] border-none focus:ring-0" :placeholder="getResponsiveVal({{ $c }}, 'marginTop', device) || '0'">
                            <select v-model="{{ $c }}[device === 'desktop' ? 'marginTopUnit' : 'marginTopUnit_' + device]" class="bg-slate-50 border-l border-slate-200 text-[10px] px-1 focus:ring-0 border-none outline-none cursor-pointer">
                                <option value="px">px</option>
                                <option value="rem">rem</option>
                                <option value="%">%</option>
                                <option value="em">em</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Bottom</label>
                        <div class="flex border border-slate-200 rounded-md overflow-hidden focus-within:ring-1 focus-within:ring-[#0091ea]/20 focus-within:border-[#0091ea]">
                            <input type="number" v-model.number="{{ $c }}[device === 'desktop' ? 'marginBottom' : 'marginBottom_' + device]" class="w-full h-9 px-3 text-[12px] border-none focus:ring-0" :placeholder="getResponsiveVal({{ $c }}, 'marginBottom', device) || '0'">
                            <select v-model="{{ $c }}[device === 'desktop' ? 'marginBottomUnit' : 'marginBottomUnit_' + device]" class="bg-slate-50 border-l border-slate-200 text-[10px] px-1 focus:ring-0 border-none outline-none cursor-pointer">
                                <option value="px">px</option>
                                <option value="rem">rem</option>
                                <option value="%">%</option>
                                <option value="em">em</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Padding Section -->
            <div class="pt-4 border-t border-slate-50">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-[13px] font-bold text-[#333]">Padding</label>
                    <div class="flex gap-1 items-center">
                        <button @click="['Top','Right','Bottom','Left'].forEach(s => { resetResponsiveVal({{ $c }}, 'padding' + s, device, ''); resetResponsiveVal({{ $c }}, 'padding' + s + 'Unit', device, 'px'); })" title="Reset Value" class="text-slate-300 hover:text-red-500 transition-colors">
                            <i class="fa fa-undo text-[10px]"></i>
                        </button>
                        <div class="relative inline-block">
                            <button @click="activeResponsiveMenu = activeResponsiveMenu === 'colPadding' ? null : 'colPadding'" class="px-1.5 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] transition-all flex items-center gap-1" title="Responsive Mode">
                                <i class="fa" :class="device === 'desktop' ? 'fa-desktop' : (device === 'tablet' ? 'fa-tablet-alt' : 'fa-mobile-alt')"></i>
                                <i class="fa fa-caret-down text-[8px] text-slate-400"></i>
                            </button>
                            <div v-show="activeResponsiveMenu === 'colPadding'" class="absolute right-0 mt-1 bg-white border border-slate-200 rounded shadow-lg z-50 flex gap-0.5 p-1 min-w-max">
                                <button @click="device = 'desktop'; activeResponsiveMenu = null" :class="device === 'desktop' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Large (Desktop)">
                                    <i class="fa fa-desktop text-[11px]"></i>
                                </button>
                                <button @click="device = 'tablet'; activeResponsiveMenu = null" :class="device === 'tablet' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Medium (Tablet)">
                                    <i class="fa fa-tablet-alt text-[11px]"></i>
                                </button>
                                <button @click="device = 'mobile'; activeResponsiveMenu = null" :class="device === 'mobile' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Small (Mobile)">
                                    <i class="fa fa-mobile-alt text-[11px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="flex flex-col gap-1" v-for="side in ['Top', 'Right', 'Bottom', 'Left']">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center">@{{side}}</label>
                        <div class="flex border border-slate-200 rounded-md overflow-hidden focus-within:ring-1 focus-within:ring-[#0091ea]/20 focus-within:border-[#0091ea]">
                            <input type="number" min="0" v-model.number="{{ $c }}[device === 'desktop' ? 'padding' + side : 'padding' + side + '_' + device]" :placeholder="getResponsiveVal({{ $c }}, 'padding' + side, device) || '0'" class="w-full h-8 px-1 text-[11px] text-center border-none focus:ring-0">
                            <select v-model="{{ $c }}[device === 'desktop' ? 'padding' + side + 'Unit' : 'padding' + side + 'Unit_' + device]" class="bg-slate-50 border-l border-slate-200 text-[9px] px-1 focus:ring-0 border-none outline-none cursor-pointer text-center">
                                <option value="px">px</option>
                                <option value="rem">rem</option>
                                <option value="%">%</option>
                                <option value="em">em</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
         </div>

            <!-- Hover Type -->
            <div class="pt-4 border-t border-slate-50">
                <label class="text-[11px] font-bold text-[#444] block mb-3">Hover Type</label>
                <select v-model="{{ $c }}.hoverType" class="w-full border border-slate-200 rounded px-3 py-2 text-[12px] focus:outline-none focus:border-[#0091ea]">
                    <option value="none">None</option>
                    <option value="zoom">Zoom In</option>
                    <option value="lift">Lift Up</option>
                    <option value="glow">Inner Glow</option>
                    <option value="fade">Fade Out</option>
                </select>
            </div>

            <!-- Border Size -->
            <div class="pt-4 border-t border-slate-50">
                <label class="text-[11px] font-bold text-[#444] block mb-3">@{{ editingContext.type === 'nested-column' ? 'Nested Column' : 'Column' }} Border Size</label>
                <div class="grid grid-cols-2 gap-2">
                    <div v-for="pos in ['Top', 'Right', 'Bottom', 'Left']">
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider text-center">@{{ pos }}</label>
                        <input type="number" min="0" v-model.number="{{ $c }}['borderSize' + pos]" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-center">
                    </div>
                </div>
            </div>

            <!-- Border Color -->
            <div v-if="{{ $c }}.borderSizeTop > 0 || {{ $c }}.borderSizeRight > 0 || {{ $c }}.borderSizeBottom > 0 || {{ $c }}.borderSizeLeft > 0" class="pt-4 border-t border-slate-50">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">@{{ editingContext.type === 'nested-column' ? 'Nested Column' : 'Column' }} Border Color</label>
                </div>
                <div class="flex gap-2 items-center">
                    <div class="checkerboard rounded overflow-hidden w-6 h-6 flex-shrink-0 border border-slate-200">
                        <div @click="openColorPicker($event, {{ $c }}, 'borderColor', 'borderColorOpacity')" 
                             :style="{ backgroundColor: hexToRgba({{ $c }}.borderColor, {{ $c }}.borderColorOpacity) }"
                             class="w-full h-full cursor-pointer"></div>
                    </div>
                    <div class="relative flex-1">
                        <input type="text" v-model="{{ $c }}.borderColor" class="w-full border border-slate-200 rounded px-2 py-1.5 pl-2 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </div>
            </div>

            <!-- Border Radius -->
            <div class="pt-4 border-t border-slate-50">
                <label class="text-[11px] font-bold text-[#444] block mb-3">Border Radius</label>
                <div class="grid grid-cols-2 gap-1">
                    <div v-for="(label, key) in {'TopLeft': 'T/L', 'TopRight': 'T/R', 'BottomRight': 'B/R', 'BottomLeft': 'B/L'}">
                        <label class="block text-[7px] font-bold text-slate-400 mb-1 uppercase tracking-wider text-center">@{{ label }}</label>
                        <div class="flex border border-slate-200 rounded overflow-hidden focus-within:ring-1 focus-within:ring-[#0091ea]/20 focus-within:border-[#0091ea]">
                            <input type="number" min="0" v-model.number="{{ $c }}['borderRadius' + key]" class="w-full h-8 px-1 text-[11px] text-center border-none focus:ring-0" placeholder="0">
                            <select v-model="{{ $c }}['borderRadius' + key + 'Unit']" class="bg-slate-50 border-l border-slate-200 text-[9px] px-1 focus:ring-0 border-none outline-none cursor-pointer text-center">
                                <option value="px">px</option>
                                <option value="rem">rem</option>
                                <option value="%">%</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Box Shadow Section -->
            <div class="pt-4 border-t border-slate-50 space-y-4">
                <div class="flex justify-between items-center">
                    <label class="text-[11px] font-bold text-[#444]">Box Shadow</label>
                    <div class="flex bg-slate-100 rounded p-0.5">
                        <button @click="{{ $c }}.boxShadow = true" :class="{{ $c }}.boxShadow ? 'bg-[#0091ea] text-white shadow-sm' : 'text-slate-400'" class="px-4 py-1 text-[10px] font-bold rounded transition-all">Yes</button>
                        <button @click="{{ $c }}.boxShadow = false" :class="!{{ $c }}.boxShadow ? 'bg-white text-slate-600 shadow-sm' : 'text-slate-400'" class="px-4 py-1 text-[10px] font-bold rounded transition-all">No</button>
                    </div>
                </div>

                <template v-if="{{ $c }}.boxShadow">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Vertical</label>
                            <input type="number" min="0" v-model.number="{{ $c }}.boxShadowPositionVertical" class="w-full border border-slate-200 rounded px-3 py-1.5 text-[11px]">
                        </div>
                        <div>
                            <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Horizontal</label>
                            <input type="number" min="0" v-model.number="{{ $c }}.boxShadowPositionHorizontal" class="w-full border border-slate-200 rounded px-3 py-1.5 text-[11px]">
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Blur Radius</label>
                            <span class="text-[10px] font-bold text-[#0091ea]">@{{ ({!! $c !!}.boxShadowBlurRadius || 0) }}px</span>
                        </div>
                        <input type="range" v-model.number="{{ $c }}.boxShadowBlurRadius" min="0" max="100" class="w-full accent-[#0091ea]">
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase">Spread Radius</label>
                            <span class="text-[10px] font-bold text-[#0091ea]">@{{ ({!! $c !!}.boxShadowSpreadRadius || 0) }}px</span>
                        </div>
                        <input type="range" v-model.number="{{ $c }}.boxShadowSpreadRadius" min="0" max="100" class="w-full accent-[#0091ea]">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-2">Box Shadow Color</label>
                        <div class="flex gap-2 items-center">
                            <div class="checkerboard rounded overflow-hidden w-8 h-8 flex-shrink-0 border border-slate-200">
                                <div @click="openColorPicker($event, {{ $c }}, 'boxShadowColor', 'boxShadowColorOpacity')" 
                                     :style="{ backgroundColor: hexToRgba({{ $c }}.boxShadowColor, {{ $c }}.boxShadowColorOpacity) }"
                                     class="w-full h-full cursor-pointer"></div>
                            </div>
                            <input type="text" v-model="{{ $c }}.boxShadowColor" class="flex-1 border border-slate-200 rounded px-3 py-1.5 text-[11px]">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-2">Box Shadow Style</label>
                        <div class="flex bg-slate-100 rounded p-0.5">
                            <button @click="{{ $c }}.boxShadowStyle = 'outer'" :class="{{ $c }}.boxShadowStyle === 'outer' || !{{ $c }}.boxShadowStyle ? 'bg-[#0091ea] text-white shadow-sm' : 'text-slate-400'" class="flex-1 py-1 text-[10px] font-bold rounded transition-all">Outer</button>
                            <button @click="{{ $c }}.boxShadowStyle = 'inner'" :class="{{ $c }}.boxShadowStyle === 'inner' ? 'bg-[#0091ea] text-white shadow-sm' : 'text-slate-400'" class="flex-1 py-1 text-[10px] font-bold rounded transition-all">Inner</button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Z-Index -->
            <div class="pt-4 border-t border-slate-50 pb-10">
                <label class="text-[11px] font-bold text-[#444] block mb-2">Z Index</label>
                <input type="number" min="0" v-model.number="{{ $c }}.zIndex" class="w-full border border-slate-200 rounded px-3 py-2 text-[12px] focus:outline-none focus:border-[#0091ea]">
            </div>

        </div><!-- /design -->

        <!-- ══ BACKGROUND TAB ══ -->
        <div v-show="activeColPanelTab === 'background'" class="space-y-6">
            
            <!-- Background Options -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Background Options</label>
                </div>
                
                <!-- Sub Tabs for Background Type -->
                <div class="flex border border-slate-200 rounded overflow-hidden bg-slate-50 mb-4">
                    <button @click="{{ $c }}.bgType = 'color'" title="Background Color" :class="{{ $c }}.bgType === 'color' ? 'text-[#0091ea] bg-white border-b-2 border-[#0091ea]' : 'text-slate-400 hover:text-[#0091ea]'" class="flex-1 py-2 text-[12px]"><i class="fa fa-fill-drip"></i></button>
                    <button @click="{{ $c }}.bgType = 'gradient'" title="Background Gradient" :class="{{ $c }}.bgType === 'gradient' ? 'text-[#0091ea] bg-white border-b-2 border-[#0091ea]' : 'text-slate-400 hover:text-[#0091ea]'" class="flex-1 py-2 text-[12px]"><i class="fa fa-adjust"></i></button>
                    <button @click="{{ $c }}.bgType = 'image'" title="Background Image" :class="{{ $c }}.bgType === 'image' ? 'text-[#0091ea] bg-white border-b-2 border-[#0091ea]' : 'text-slate-400 hover:text-[#0091ea]'" class="flex-1 py-2 text-[12px]"><i class="fa fa-image"></i></button>
                </div>

                <!-- 1. Color Tab Content -->
                <div v-show="{{ $c }}.bgType === 'color'" class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">@{{ editingContext.type === 'nested-column' ? 'Nested Column' : 'Column' }} Background Color</label>
                            <div class="flex gap-1 items-center">
                                <button @click="resetResponsiveVal({{ $c }}, 'bgColor', device, '#ffffff'); resetResponsiveVal({{ $c }}, 'bgColorOpacity', device, 1)" title="Reset Value" class="text-slate-300 hover:text-red-500 transition-colors">
                                    <i class="fa fa-undo text-[10px]"></i>
                                </button>
                                <div class="relative inline-block">
                                    <button @click="activeResponsiveMenu = activeResponsiveMenu === 'colBgColor' ? null : 'colBgColor'" class="px-1.5 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] transition-all flex items-center gap-1" title="Responsive Mode">
                                        <i class="fa" :class="device === 'desktop' ? 'fa-desktop' : (device === 'tablet' ? 'fa-tablet-alt' : 'fa-mobile-alt')"></i>
                                        <i class="fa fa-caret-down text-[8px] text-slate-400"></i>
                                    </button>
                                    <div v-show="activeResponsiveMenu === 'colBgColor'" class="absolute right-0 mt-1 bg-white border border-slate-200 rounded shadow-lg z-50 flex gap-0.5 p-1 min-w-max">
                                        <button @click="device = 'desktop'; activeResponsiveMenu = null" :class="device === 'desktop' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Large (Desktop)">
                                            <i class="fa fa-desktop text-[11px]"></i>
                                        </button>
                                        <button @click="device = 'tablet'; activeResponsiveMenu = null" :class="device === 'tablet' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Medium (Tablet)">
                                            <i class="fa fa-tablet-alt text-[11px]"></i>
                                        </button>
                                        <button @click="device = 'mobile'; activeResponsiveMenu = null" :class="device === 'mobile' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Small (Mobile)">
                                            <i class="fa fa-mobile-alt text-[11px]"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            <input type="text" v-model="{{ $c }}[device === 'desktop' ? 'bgColor' : 'bgColor_' + device]" class="wp-input h-7 flex-1 text-[10px] text-center font-mono focus:outline-none focus:border-[#2271b1]">
                            <button @click="setResponsiveVal({{ $c }}, 'bgColor', device, '#ffffff'); setResponsiveVal({{ $c }}, 'bgColorOpacity', device, 1)" class="wp-btn-secondary h-7 px-2 text-[10px]">Default</button>
                        </div>
                        <div class="flex gap-2 items-center">
                            <div class="checkerboard rounded overflow-hidden w-8 h-8 flex-shrink-0 border border-slate-200">
                                <div @click="openColorPicker($event, {{ $c }}, device === 'desktop' ? 'bgColor' : 'bgColor_' + device, device === 'desktop' ? 'bgColorOpacity' : 'bgColorOpacity_' + device, getResponsiveVal({{ $c }}, 'bgColor', device))"
                                     :style="{ backgroundColor: hexToRgba(getResponsiveVal({{ $c }}, 'bgColor', device), getResponsiveVal({{ $c }}, 'bgColorOpacity', device) !== undefined ? getResponsiveVal({{ $c }}, 'bgColorOpacity', device) : 1) }"
                                     class="w-full h-full cursor-pointer"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Gradient Tab Content -->
                <div v-show="{{ $c }}.bgType === 'gradient'" class="space-y-4 border border-slate-100 rounded-md p-2">
                    <!-- Start Color -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Start Color</label>
                            <div class="flex gap-2 items-center">
                                <button @click="clearColorField({{ $c }}, 'bgGradientStartColor', 'bgGradientStartOpacity')" 
                                        class="text-slate-300 hover:text-red-500 transition-colors" title="Reset">
                                    <i class="fa fa-undo text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            <input type="text" v-model="{{ $c }}.bgGradientStartColor" class="wp-input h-7 flex-1 text-[10px] text-center font-mono focus:outline-none focus:border-[#2271b1]">
                        </div>
                        <div class="flex gap-2 items-center">
                            <div class="checkerboard rounded overflow-hidden w-8 h-8 flex-shrink-0 border border-slate-200">
                                <div @click="openColorPicker($event, {{ $c }}, 'bgGradientStartColor', 'bgGradientStartOpacity')" 
                                     :style="{ backgroundColor: hexToRgba({{ $c }}.bgGradientStartColor, {{ $c }}.bgGradientStartOpacity) }"
                                     class="w-full h-full cursor-pointer"></div>
                            </div>
                        </div>
                    </div>

                    <!-- End Color -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient End Color</label>
                            <div class="flex gap-2 items-center">
                                <button @click="clearColorField({{ $c }}, 'bgGradientEndColor', 'bgGradientEndOpacity')" 
                                        class="text-slate-300 hover:text-red-500 transition-colors" title="Reset">
                                    <i class="fa fa-undo text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            <input type="text" v-model="{{ $c }}.bgGradientEndColor" class="wp-input h-7 flex-1 text-[10px] text-center font-mono focus:outline-none focus:border-[#2271b1]">
                        </div>
                        <div class="flex gap-2 items-center">
                            <div class="checkerboard rounded overflow-hidden w-8 h-8 flex-shrink-0 border border-slate-200">
                                <div @click="openColorPicker($event, {{ $c }}, 'bgGradientEndColor', 'bgGradientEndOpacity')" 
                                     :style="{ backgroundColor: hexToRgba({{ $c }}.bgGradientEndColor, {{ $c }}.bgGradientEndOpacity) }"
                                     class="w-full h-full cursor-pointer"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Start Position -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Start Position</label>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" v-model="{{ $c }}.bgGradientStartPosition" class="w-16 border border-slate-200 rounded px-2 py-1 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <input type="range" min="0" max="100" v-model="{{ $c }}.bgGradientStartPosition" class="flex-1 h-1 bg-[#0091ea] rounded appearance-none cursor-pointer">
                        </div>
                    </div>

                    <!-- End Position -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient End Position</label>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" v-model="{{ $c }}.bgGradientEndPosition" class="w-16 border border-slate-200 rounded px-2 py-1 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <input type="range" min="0" max="100" v-model="{{ $c }}.bgGradientEndPosition" class="flex-1 h-1 bg-[#0091ea] rounded appearance-none cursor-pointer">
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Type</label>
                        </div>
                        <div class="flex bg-slate-100 rounded overflow-hidden">
                            <button @click="{{ $c }}.bgGradientType = 'linear'" 
                                    :class="{{ $c }}.bgGradientType === 'linear' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                                    class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Linear</button>
                            <button @click="{{ $c }}.bgGradientType = 'radial'" 
                                    :class="{{ $c }}.bgGradientType === 'radial' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                                    class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Radial</button>
                        </div>
                    </div>

                    <!-- Angle -->
                    <div v-show="{{ $c }}.bgGradientType === 'linear'">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Angle</label>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" v-model="{{ $c }}.bgGradientAngle" class="w-16 border border-slate-200 rounded px-2 py-1 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <input type="range" min="0" max="360" v-model="{{ $c }}.bgGradientAngle" class="flex-1 h-1 bg-[#0091ea] rounded appearance-none cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- 3. Image Tab Content -->
                <div v-show="{{ $c }}.bgType === 'image'" class="space-y-4 border border-slate-100 rounded-md p-2">
                    
                    <!-- Image Selection -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Background Image</label>
                            <div class="flex gap-1 items-center">
                                <div class="relative inline-block">
                                    <button @click="activeResponsiveMenu = activeResponsiveMenu === 'colBgImage' ? null : 'colBgImage'" class="px-1.5 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-600 text-[10px] transition-all flex items-center gap-1" title="Responsive Mode">
                                        <i class="fa" :class="device === 'desktop' ? 'fa-desktop' : (device === 'tablet' ? 'fa-tablet-alt' : 'fa-mobile-alt')"></i>
                                        <i class="fa fa-caret-down text-[8px] text-slate-400"></i>
                                    </button>
                                    <div v-show="activeResponsiveMenu === 'colBgImage'" class="absolute right-0 mt-1 bg-white border border-slate-200 rounded shadow-lg z-50 flex gap-0.5 p-1 min-w-max">
                                        <button @click="device = 'desktop'; activeResponsiveMenu = null" :class="device === 'desktop' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Large (Desktop)">
                                            <i class="fa fa-desktop text-[11px]"></i>
                                        </button>
                                        <button @click="device = 'tablet'; activeResponsiveMenu = null" :class="device === 'tablet' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Medium (Tablet)">
                                            <i class="fa fa-tablet-alt text-[11px]"></i>
                                        </button>
                                        <button @click="device = 'mobile'; activeResponsiveMenu = null" :class="device === 'mobile' ? 'bg-[#0091ea] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'" class="w-6 h-6 rounded text-[10px] flex items-center justify-center transition-all" title="Small (Mobile)">
                                            <i class="fa fa-mobile-alt text-[11px]"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="{{ $c }}.bgImage" class="relative group">
                            <img :src="{{ $c }}.bgImage" class="w-full h-[120px] object-cover rounded border border-slate-200">
                            <div class="flex justify-center gap-2 mt-2">
                                <button @click="{{ $c }}.bgImage = ''" class="px-3 py-1.5 text-[11px] font-bold border border-slate-200 rounded text-[#444] hover:bg-slate-50 transition-colors">Remove</button>
                                <button @click="openMediaModal('bgImage')" class="px-3 py-1.5 text-[11px] font-bold bg-[#0091ea] text-white rounded hover:bg-[#007cc0] transition-colors">Edit</button>
                            </div>
                        </div>
                        <div v-else>
                            <button @click="openMediaModal('bgImage')" class="w-full h-[80px] border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors rounded flex flex-col items-center justify-center gap-1">
                                <i class="fa fa-plus text-[#0091ea] text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <template v-if="{{ $c }}.bgImage">
                        <!-- Skip Lazy Loading -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Skip Lazy Loading</label>
                            </div>
                            <div class="flex bg-slate-100 rounded overflow-hidden w-[100px]">
                                <button @click="{{ $c }}.bgImageSkipLazy = true" 
                                        :class="{{ $c }}.bgImageSkipLazy ? 'bg-slate-800 text-white' : 'text-slate-500 hover:bg-slate-200'"
                                        class="flex-1 py-1 text-[10px] font-medium transition-colors">Yes</button>
                                <button @click="{{ $c }}.bgImageSkipLazy = false" 
                                        :class="!{{ $c }}.bgImageSkipLazy ? 'bg-slate-800 text-white' : 'text-slate-500 hover:bg-slate-200'"
                                        class="flex-1 py-1 text-[10px] font-medium transition-colors">No</button>
                            </div>
                        </div>

                        <!-- Background Position -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Position</label>
                            </div>
                            <select v-model="{{ $c }}.bgImagePosition" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="top left">Top Left</option>
                                <option value="top center">Top Center</option>
                                <option value="top right">Top Right</option>
                                <option value="center left">Center Left</option>
                                <option value="center center">Center Center</option>
                                <option value="center right">Center Right</option>
                                <option value="bottom left">Bottom Left</option>
                                <option value="bottom center">Bottom Center</option>
                                <option value="bottom right">Bottom Right</option>
                            </select>
                        </div>

                        <!-- Background Repeat -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Repeat</label>
                            </div>
                            <select v-model="{{ $c }}.bgImageRepeat" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="no-repeat">No Repeat</option>
                                <option value="repeat">Repeat</option>
                                <option value="repeat-x">Repeat X</option>
                                <option value="repeat-y">Repeat Y</option>
                            </select>
                        </div>

                        <!-- Background Size -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Size</label>
                            </div>
                            <select v-model="{{ $c }}.bgImageSize" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="auto">Default</option>
                                <option value="cover">Cover</option>
                                <option value="contain">Contain</option>
                            </select>
                        </div>

                        <!-- Background Parallax -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Parallax</label>
                            </div>
                            <select v-model="{{ $c }}.bgImageParallax" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="none">No Parallax</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>

                        <!-- Background Blend Mode -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Blend Mode</label>
                            </div>
                            <select v-model="{{ $c }}.bgImageBlendMode" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="normal">Disabled</option>
                                <option value="multiply">Multiply</option>
                                <option value="screen">Screen</option>
                                <option value="overlay">Overlay</option>
                                <option value="darken">Darken</option>
                                <option value="lighten">Lighten</option>
                            </select>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- ══ EXTRA TAB ══ -->
        <div v-show="activeColPanelTab === 'extra'" class="space-y-5">

            <!-- Sticky Section -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Sticky Column</label>
                </div>
                <div class="flex bg-slate-100 rounded overflow-hidden mb-3">
                    <button @click="{{ $c }}.sticky = true"
                            :class="{{ $c }}.sticky ? 'bg-slate-800 text-white shadow-inner' : 'text-slate-500 hover:bg-slate-200'"
                            class="flex-1 py-1.5 text-[11px] font-medium transition-colors">Enable</button>
                    <button @click="{{ $c }}.sticky = false"
                            :class="!{{ $c }}.sticky ? 'bg-slate-800 text-white shadow-inner' : 'text-slate-500 hover:bg-slate-200'"
                            class="flex-1 py-1.5 text-[11px] font-medium transition-colors">Disable</button>
                </div>

                <template v-if="{{ $c }}.sticky">
                    <!-- Per-device sticky -->
                    <div class="mb-3">
                        <label class="text-[10px] text-slate-500 block mb-1.5">Enable Sticky On</label>
                        <div class="flex gap-1">
                            <button @click="{{ $c }}.stickyDesktop = {{ $c }}.stickyDesktop === false ? true : false"
                                    :class="{{ $c }}.stickyDesktop !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-200 text-slate-400'"
                                    class="flex-1 py-1 rounded text-[10px] font-medium transition-colors flex items-center justify-center gap-1">
                                <i class="fa fa-desktop text-[9px]"></i> Desktop
                            </button>
                            <button @click="{{ $c }}.stickyTablet = {{ $c }}.stickyTablet === false ? true : false"
                                    :class="{{ $c }}.stickyTablet !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-200 text-slate-400'"
                                    class="flex-1 py-1 rounded text-[10px] font-medium transition-colors flex items-center justify-center gap-1">
                                <i class="fa fa-tablet-alt text-[9px]"></i> Tablet
                            </button>
                            <button @click="{{ $c }}.stickyMobile = {{ $c }}.stickyMobile === false ? true : false"
                                    :class="{{ $c }}.stickyMobile !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-200 text-slate-400'"
                                    class="flex-1 py-1 rounded text-[10px] font-medium transition-colors flex items-center justify-center gap-1">
                                <i class="fa fa-mobile-alt text-[9px]"></i> Mobile
                            </button>
                        </div>
                    </div>

                    <!-- Sticky Offset -->
                    <div class="mb-3">
                        <label class="text-[10px] text-slate-500 block mb-1.5">Top Offset (px)</label>
                        <input type="number" v-model.number="{{ $c }}.stickyOffset" placeholder="0"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>

                    <!-- Sticky Z-Index -->
                    <div>
                        <label class="text-[10px] text-slate-500 block mb-1.5">Sticky Z-Index</label>
                        <input type="number" v-model.number="{{ $c }}.stickyZIndex" placeholder="99"
                               class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </template>
            </div>

            <!-- Z-Index & Overflow -->
            <div class="border-t border-slate-100 pt-4 space-y-4">
                <div>
                    <label class="text-[11px] font-bold text-[#444] block mb-2">Z-Index</label>
                    <input type="number" v-model.number="{{ $c }}.zIndex" placeholder="auto"
                           class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                </div>
                <div>
                    <label class="text-[11px] font-bold text-[#444] block mb-2">Overflow</label>
                    <select v-model="{{ $c }}.overflow"
                            class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <option value="default">Default</option>
                        <option value="hidden">Hidden</option>
                        <option value="visible">Visible</option>
                        <option value="auto">Auto</option>
                        <option value="scroll">Scroll</option>
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>
