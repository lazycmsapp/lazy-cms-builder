@php
    $base = (isset($isNestedRow) && $isNestedRow) ? 'layout[editingContext.ci].columns[editingContext.coli].elements[editingContext.eli]' : 'layout[editingContext.ci]';
    $label = (isset($isNestedRow) && $isNestedRow) ? 'Nested Columns' : 'Container';
@endphp
<div class="h-full flex flex-col bg-white">
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100" v-if="!{{ isset($isNestedRow) && $isNestedRow ? 'true' : 'false' }}">
        <h3 class="text-[13px] font-bold text-[#444]">{{ $label }}</h3>
        <div class="flex gap-2">
            <button class="text-slate-400 hover:text-slate-600"><i class="fa fa-ellipsis-h text-[10px]"></i></button>
            <button @click="activeTab='navigator'; editingContext.type = null" class="text-slate-400 hover:text-red-500"><i class="fa fa-times text-[10px]"></i></button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex bg-[#0091ea]">
        <button @click="activePanelTab = 'general'" :class="activePanelTab === 'general' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'" class="flex-1 py-2 text-[12px] font-bold transition-colors">
            <i class="fa fa-sliders-h"></i>
        </button>
        <button v-if="{{ isset($isNestedRow) && $isNestedRow ? 'false' : 'true' }}" @click="activePanelTab = 'design'" :class="activePanelTab === 'design' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'" class="flex-1 py-2 text-[10px] font-bold transition-colors">
            Design
        </button>
        <button v-if="{{ isset($isNestedRow) && $isNestedRow ? 'false' : 'true' }}" @click="activePanelTab = 'background'" :class="activePanelTab === 'background' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'" class="flex-1 py-2 text-[10px] font-bold transition-colors">
            Background
        </button>
        <button v-if="{{ isset($isNestedRow) && $isNestedRow ? 'false' : 'true' }}" @click="activePanelTab = 'link'" :class="activePanelTab === 'link' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'" class="flex-1 py-2 text-[12px] transition-colors">
            <i class="fa fa-link"></i>
        </button>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto p-4" v-if="{{ $base }}">
        
        <!-- General Tab -->
        <div v-show="activePanelTab === 'general'" class="space-y-6">
            
            <!-- Specific Nested Row Settings -->
            <div v-if="{{ isset($isNestedRow) && $isNestedRow ? 'true' : 'false' }}">
                <div class="text-[10px] text-slate-500 mb-6 p-2.5 bg-slate-50 border border-slate-100 rounded leading-relaxed">
                    When using "default" values for the flex options here, that means they will inherit from the parent container.
                </div>
                
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Row Minimum Height</label>
                        <div class="flex gap-2 items-center">
                            <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                            <i class="fa fa-desktop text-[10px] text-slate-300"></i>
                        </div>
                    </div>
                    <input type="text" v-model="{{ $base }}.settings.minHeight" placeholder="e.g. 50px" class="w-full border border-slate-200 rounded px-3 py-2 text-[12px] focus:outline-none focus:border-[#0091ea]">
                </div>

            </div>

            <!-- Interior Content Width -->
            <div v-if="{{ isset($isNestedRow) && $isNestedRow ? 'false' : 'true' }}">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Interior Content Width</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <div class="flex bg-slate-100 rounded overflow-hidden">
                    <button @click="{{ $base }}.settings.contentWidth = '100%'" 
                            :class="{{ $base }}.settings.contentWidth === '100%' ? 'bg-slate-800 text-white shadow-inner' : 'text-slate-500 hover:bg-slate-200'"
                            class="flex-1 py-1.5 text-[10px] font-medium transition-colors">100% Width</button>
                    <button @click="{{ $base }}.settings.contentWidth = 'site'" 
                            :class="{{ $base }}.settings.contentWidth === 'site' ? 'bg-slate-800 text-white shadow-inner' : 'text-slate-500 hover:bg-slate-200'"
                            class="flex-1 py-1.5 text-[10px] font-medium transition-colors">Site Width</button>
                </div>
            </div>

        <!-- Height -->
        <div v-if="!{{ isset($isNestedRow) && $isNestedRow ? 'true' : 'false' }}">
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Height</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <select v-model="{{ $base }}.settings.height" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                <option value="auto">Auto</option>
                <option value="full">Full Height</option>
                <option value="custom">Custom</option>
            </select>
            <div v-if="{{ $base }}.settings.height === 'custom'" class="mt-2">
                <input type="text" v-model="{{ $base }}.settings.customHeight" placeholder="e.g. 400px, 50vh" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
            </div>
        </div>

        <!-- Column Alignment -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Column Alignment</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button @click="{{ $base }}.settings.alignItems = 'flex-start'"
                        :class="{{ $base }}.settings.alignItems === 'flex-start' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="4" width="3" height="10" rx="0.5"/>
                        <rect x="10.5" y="4" width="3" height="14" rx="0.5"/>
                        <rect x="16" y="4" width="3" height="8" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Top</div>
                </button>
                <button @click="{{ $base }}.settings.alignItems = 'center'"
                        :class="{{ $base }}.settings.alignItems === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="7" width="3" height="10" rx="0.5"/>
                        <rect x="10.5" y="5" width="3" height="14" rx="0.5"/>
                        <rect x="16" y="8" width="3" height="8" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Center</div>
                </button>
                <button @click="{{ $base }}.settings.alignItems = 'flex-end'"
                        :class="{{ $base }}.settings.alignItems === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="10" width="3" height="10" rx="0.5"/>
                        <rect x="10.5" y="6" width="3" height="14" rx="0.5"/>
                        <rect x="16" y="12" width="3" height="8" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Bottom</div>
                </button>
                <button @click="{{ $base }}.settings.alignItems = 'stretch'"
                        :class="{{ $base }}.settings.alignItems === 'stretch' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3l3 3h-2v12h2l-3 3-3-3h2V6H9l3-3z" fill="currentColor"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Stretch</div>
                </button>
            </div>
        </div>

        <!-- Row Alignment (Align Content) -->
        <div v-if="{{ $base }}.settings.height !== 'auto' || {{ isset($isNestedRow) && $isNestedRow ? 'true' : 'false' }}">
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Row Alignment</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <!-- 1. Stretch (Default) -->
                <button @click="{{ $base }}.settings.rowAlignContent = 'stretch'"
                        :class="{{ $base }}.settings.rowAlignContent === 'stretch' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="4" y="3" width="16" height="3" rx="0.5"/>
                        <rect x="4" y="8" width="16" height="3" rx="0.5"/>
                        <rect x="4" y="13" width="10" height="3" rx="0.5"/>
                        <rect x="4" y="18" width="16" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Stretch</div>
                </button>

                <!-- 2. Align Top (flex-start) -->
                <button @click="{{ $base }}.settings.rowAlignContent = 'flex-start'"
                        :class="{{ $base }}.settings.rowAlignContent === 'flex-start' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="4" width="14" height="2" rx="0.5"/>
                        <rect x="7" y="8" width="10" height="4" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Top</div>
                </button>

                <!-- 3. Align Center (center) -->
                <button @click="{{ $base }}.settings.rowAlignContent = 'center'"
                        :class="{{ $base }}.settings.rowAlignContent === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="11" width="14" height="2" rx="0.5"/>
                        <rect x="7" y="7" width="10" height="3" rx="0.5"/>
                        <rect x="7" y="14" width="10" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Center</div>
                </button>

                <!-- 4. Align Bottom (flex-end) -->
                <button @click="{{ $base }}.settings.rowAlignContent = 'flex-end'"
                        :class="{{ $base }}.settings.rowAlignContent === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="7" y="12" width="10" height="4" rx="0.5"/>
                        <rect x="5" y="18" width="14" height="2" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Bottom</div>
                </button>

                <!-- 5. Space Between (space-between) -->
                <button @click="{{ $base }}.settings.rowAlignContent = 'space-between'"
                        :class="{{ $base }}.settings.rowAlignContent === 'space-between' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="3" width="14" height="2" rx="0.5"/>
                        <rect x="7" y="6" width="10" height="3" rx="0.5"/>
                        <rect x="7" y="15" width="10" height="3" rx="0.5"/>
                        <rect x="5" y="19" width="14" height="2" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Between</div>
                </button>

                <!-- 6. Space Around (space-around) -->
                <button @click="{{ $base }}.settings.rowAlignContent = 'space-around'"
                        :class="{{ $base }}.settings.rowAlignContent === 'space-around' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="6" width="14" height="2" rx="0.5"/>
                        <rect x="7" y="9" width="10" height="2" rx="0.5"/>
                        <rect x="7" y="13" width="10" height="2" rx="0.5"/>
                        <rect x="5" y="16" width="14" height="2" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Around</div>
                </button>

                <!-- 7. Space Evenly (space-evenly) -->
                <button @click="{{ $base }}.settings.rowAlignContent = 'space-evenly'"
                        :class="{{ $base }}.settings.rowAlignContent === 'space-evenly' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="4" width="14" height="2" rx="0.5"/>
                        <rect x="5" y="11" width="14" height="2" rx="0.5"/>
                        <rect x="5" y="18" width="14" height="2" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Evenly</div>
                </button>
            </div>
        </div>

        <!-- Column Justification -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Column Justification</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button @click="{{ $base }}.settings.justifyContent = 'flex-start'" 
                        :class="{{ $base }}.settings.justifyContent === 'flex-start' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="4" y="5" width="8" height="3" rx="0.5"/>
                        <rect x="4" y="10.5" width="14" height="3" rx="0.5"/>
                        <rect x="4" y="16" width="11" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Justify Left</div>
                </button>
                <button @click="{{ $base }}.settings.justifyContent = 'center'" 
                        :class="{{ $base }}.settings.justifyContent === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="8" y="5" width="8" height="3" rx="0.5"/>
                        <rect x="5" y="10.5" width="14" height="3" rx="0.5"/>
                        <rect x="6.5" y="16" width="11" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Justify Center</div>
                </button>
                <button @click="{{ $base }}.settings.justifyContent = 'flex-end'" 
                        :class="{{ $base }}.settings.justifyContent === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="12" y="5" width="8" height="3" rx="0.5"/>
                        <rect x="6" y="10.5" width="14" height="3" rx="0.5"/>
                        <rect x="9" y="16" width="11" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Justify Right</div>
                </button>
                <button @click="{{ $base }}.settings.justifyContent = 'space-between'" 
                        :class="{{ $base }}.settings.justifyContent === 'space-between' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 16v-3h12v3l4-4-4-4v3H6V8l-4 4 4 4z" fill="currentColor"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Between</div>
                </button>
                <button @click="{{ $base }}.settings.justifyContent = 'space-around'" 
                        :class="{{ $base }}.settings.justifyContent === 'space-around' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="4" y="6" width="16" height="3" rx="0.5"/>
                        <rect x="4" y="11" width="16" height="3" rx="0.5"/>
                        <rect x="4" y="16" width="16" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Around</div>
                </button>
                <button @click="{{ $base }}.settings.justifyContent = 'space-evenly'" 
                        :class="{{ $base }}.settings.justifyContent === 'space-evenly' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="4" y="9.5" width="16" height="5" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Evenly</div>
                </button>
            </div>
        </div>

        <!-- Content Wrap -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Content Wrap</label>
                <div class="flex gap-2 text-slate-300">
                    <i class="fa fa-question-circle text-[10px]"></i>
                    <i class="fa fa-desktop text-[10px]"></i>
                </div>
            </div>
            <div class="flex bg-slate-100 rounded overflow-hidden">
                <button @click="{{ $base }}.settings.flexWrap = 'default'" 
                        :class="!{{ $base }}.settings.flexWrap || {{ $base }}.settings.flexWrap === 'default' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                        class="flex-1 py-1.5 text-[10px] font-medium transition-colors">Default</button>
                <button @click="{{ $base }}.settings.flexWrap = 'wrap'" 
                        :class="{{ $base }}.settings.flexWrap === 'wrap' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                        class="flex-1 py-1.5 text-[10px] font-medium transition-colors">Wrap</button>
                <button @click="{{ $base }}.settings.flexWrap = 'nowrap'" 
                        :class="{{ $base }}.settings.flexWrap === 'nowrap' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                        class="flex-1 py-1.5 text-[10px] font-medium transition-colors">No Wrap</button>
            </div>
        </div>

        <!-- Overflow -->
        <div v-if="{{ isset($isNestedRow) && $isNestedRow ? 'true' : 'false' }}">
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Overflow</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <select v-model="{{ $base }}.settings.overflow" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                <option value="default">Default</option>
                <option value="hidden">Hidden</option>
                <option value="visible">Visible</option>
                <option value="auto">Auto</option>
                <option value="scroll">Scroll</option>
            </select>
        </div>

        <!-- Column Spacing -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Column Spacing</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <input type="number" min="0" v-model="{{ $base }}.settings.columnGap" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
        </div>

        <!-- Container HTML Tag -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Container HTML Tag</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <select v-model="{{ $base }}.settings.htmlTag" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                <option value="div">Default</option>
                <option value="header">Header</option>
                <option value="footer">Footer</option>
                <option value="main">Main</option>
                <option value="article">Article</option>
                <option value="section">Section</option>
            </select>
        </div>

        <!-- Name Of Menu Anchor -->
        <div v-if="!{{ isset($isNestedRow) && $isNestedRow ? 'true' : 'false' }}">
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Name Of Menu Anchor</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <input type="text" v-model="{{ $base }}.settings.menuAnchor" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
        </div>

        <!-- Container Visibility -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Device Visibility</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <div class="flex gap-0.5 rounded overflow-hidden" @click.capture="if (!{{ $base }}.settings.visibility) { {{ $base }}.settings.visibility = { mobile: true, tablet: true, desktop: true }; }">
                <button @click="{{ $base }}.settings.visibility.mobile = !{{ $base }}.settings.visibility.mobile" 
                        :class="{{ $base }}.settings.visibility && {{ $base }}.settings.visibility.mobile !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-200 text-slate-400'"
                        class="flex-1 py-1.5 transition-colors flex items-center justify-center">
                    <i class="fa fa-mobile-alt text-[11px]"></i>
                </button>
                <button @click="{{ $base }}.settings.visibility.tablet = !{{ $base }}.settings.visibility.tablet" 
                        :class="{{ $base }}.settings.visibility && {{ $base }}.settings.visibility.tablet !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-200 text-slate-400'"
                        class="flex-1 py-1.5 transition-colors flex items-center justify-center">
                    <i class="fa fa-tablet-alt text-[11px]"></i>
                </button>
                <button @click="{{ $base }}.settings.visibility.desktop = !{{ $base }}.settings.visibility.desktop" 
                        :class="{{ $base }}.settings.visibility && {{ $base }}.settings.visibility.desktop !== false ? 'bg-[#0091ea] text-white' : 'bg-slate-200 text-slate-400'"
                        class="flex-1 py-1.5 transition-colors flex items-center justify-center">
                    <i class="fa fa-desktop text-[11px]"></i>
                </button>
            </div>
        </div>

        <!-- Container Publishing Status -->
        <div v-if="!{{ isset($isNestedRow) && $isNestedRow ? 'true' : 'false' }}">
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Container Publishing Status</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <select v-model="{{ $base }}.settings.status" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
        </div>

        <!-- CSS Class -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">CSS Class</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <input type="text" v-model="{{ $base }}.settings.cssClass" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
        </div>
    </div>

    <!-- Design Tab -->
    <div v-if="{{ isset($isNestedRow) && $isNestedRow ? 'false' : 'true' }}" v-show="activePanelTab === 'design'" class="space-y-6">
            <!-- Visual Spacing Tool -->
            <!-- Margin Section -->
            <div class="pt-4 border-t border-slate-50">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-[13px] font-bold text-[#333]">Margin</label>
                    <div class="flex gap-2 items-center">
                        <i class="fa fa-question-circle text-[11px] text-slate-300"></i>
                        <i class="fa fa-desktop text-[11px] text-slate-300"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="flex flex-col gap-1">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Top</label>
                        <div class="flex border border-slate-200 rounded-md overflow-hidden focus-within:ring-1 focus-within:ring-[#0091ea]/20 focus-within:border-[#0091ea]">
                            <input type="number" v-model.number="{{ $base }}.settings.marginTop" class="w-full h-9 px-3 text-[12px] border-none focus:ring-0" placeholder="0">
                            <select v-model="{{ $base }}.settings.marginTopUnit" class="bg-slate-50 border-l border-slate-200 text-[10px] px-1 focus:ring-0 border-none outline-none cursor-pointer">
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
                            <input type="number" v-model.number="{{ $base }}.settings.marginBottom" class="w-full h-9 px-3 text-[12px] border-none focus:ring-0" placeholder="0">
                            <select v-model="{{ $base }}.settings.marginBottomUnit" class="bg-slate-50 border-l border-slate-200 text-[10px] px-1 focus:ring-0 border-none outline-none cursor-pointer">
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
                    <div class="flex gap-2 items-center">
                        <i class="fa fa-question-circle text-[11px] text-slate-300"></i>
                        <i class="fa fa-desktop text-[11px] text-slate-300"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="flex flex-col gap-1" v-for="side in ['Top', 'Right', 'Bottom', 'Left']">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center">@{{side}}</label>
                        <div class="flex border border-slate-200 rounded-md overflow-hidden focus-within:ring-1 focus-within:ring-[#0091ea]/20 focus-within:border-[#0091ea]">
                            <input type="number" min="0" v-model.number="{{ $base }}.settings['padding' + side]" class="w-full h-8 px-1 text-[11px] text-center border-none focus:ring-0" placeholder="0">
                            <select v-model="{{ $base }}.settings['padding' + side + 'Unit']" class="bg-slate-50 border-l border-slate-200 text-[9px] px-1 focus:ring-0 border-none outline-none cursor-pointer text-center">
                                <option value="px">px</option>
                                <option value="rem">rem</option>
                                <option value="%">%</option>
                                <option value="em">em</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Container Link Color -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">{{ $label }} Link Color</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-cog text-[10px]"></i>
                        <i class="fa fa-undo text-[10px]"></i>
                        <i class="fa fa-circle text-[10px] text-white border border-slate-300 rounded-full"></i>
                        <i class="fa fa-database text-[10px]"></i>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <div class="checkerboard rounded overflow-hidden w-6 h-6 flex-shrink-0 border border-slate-200">
                        <div @click="openColorPicker($event, {{ $base }}.settings, 'linkColor', 'linkColorOpacity')" 
                             :style="{ backgroundColor: hexToRgba({{ $base }}.settings.linkColor, {{ $base }}.settings.linkColorOpacity) }"
                             class="w-full h-full cursor-pointer"></div>
                    </div>
                    <div class="relative flex-1">
                        <input type="text" v-model="{{ $base }}.settings.linkColor" class="w-full border border-slate-200 rounded px-2 py-1.5 pl-2 pr-8 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]" placeholder="#000000">
                        <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Container Border Size -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">{{ $label }} Border Size</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-cog text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-1">
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top</label>
                        <input type="number" min="0" v-model="{{ $base }}.settings.borderSizeTop" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Right</label>
                        <input type="number" min="0" v-model="{{ $base }}.settings.borderSizeRight" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bottom</label>
                        <input type="number" min="0" v-model="{{ $base }}.settings.borderSizeBottom" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Left</label>
                        <input type="number" min="0" v-model="{{ $base }}.settings.borderSizeLeft" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </div>
            </div>

            <!-- Container Border Color (Conditional) -->
            <div v-if="{{ $base }}.settings.borderSizeTop > 0 || {{ $base }}.settings.borderSizeRight > 0 || {{ $base }}.settings.borderSizeBottom > 0 || {{ $base }}.settings.borderSizeLeft > 0">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">{{ $label }} Border Color</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-database text-[10px]"></i>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <div class="checkerboard rounded overflow-hidden w-8 h-8 flex-shrink-0 border border-slate-200">
                        <div @click="openColorPicker($event, {{ $base }}.settings, 'borderColor', 'borderColorOpacity')" 
                             :style="{ backgroundColor: hexToRgba({{ $base }}.settings.borderColor, {{ $base }}.settings.borderColorOpacity) }"
                             class="w-full h-full cursor-pointer"></div>
                    </div>
                    <div class="relative flex-1">
                        <input type="text" v-model="{{ $base }}.settings.borderColor" class="w-full border border-slate-200 rounded px-2 py-1.5 pl-2 pr-8 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Border Radius -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Border Radius</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <div class="grid grid-cols-2 gap-1">
                    <div v-for="(label, key) in {'TopLeft': 'T/L', 'TopRight': 'T/R', 'BottomRight': 'B/R', 'BottomLeft': 'B/L'}">
                        <label class="block text-[7px] font-bold text-slate-400 mb-1 uppercase tracking-wider text-center">@{{ label }}</label>
                        <div class="flex border border-slate-200 rounded overflow-hidden focus-within:ring-1 focus-within:ring-[#0091ea]/20 focus-within:border-[#0091ea]">
                            <input type="number" min="0" v-model.number="{{ $base }}.settings['borderRadius' + key]" class="w-full h-8 px-1 text-[11px] text-center border-none focus:ring-0" placeholder="0">
                            <select v-model="{{ $base }}.settings['borderRadius' + key + 'Unit']" class="bg-slate-50 border-l border-slate-200 text-[9px] px-1 focus:ring-0 border-none outline-none cursor-pointer text-center">
                                <option value="px">px</option>
                                <option value="rem">rem</option>
                                <option value="%">%</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Box Shadow -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Box Shadow</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <div class="flex w-[100px] bg-slate-100 rounded overflow-hidden">
                    <button @click="{{ $base }}.settings.boxShadow = true" 
                            :class="{{ $base }}.settings.boxShadow ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                            class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Yes</button>
                    <button @click="{{ $base }}.settings.boxShadow = false" 
                            :class="!{{ $base }}.settings.boxShadow ? 'bg-slate-200 text-slate-500' : 'text-slate-500 hover:bg-slate-200'"
                            class="flex-1 py-1.5 text-[10px] font-bold transition-colors">No</button>
                </div>
            </div>

            <template v-if="{{ $base }}.settings.boxShadow">
                <!-- Box Shadow Position -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Box Shadow Position</label>
                        <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Vertical</label>
                            <input type="number" min="0" v-model="{{ $base }}.settings.boxShadowPositionVertical" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        </div>
                        <div>
                            <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Horizontal</label>
                            <input type="number" min="0" v-model="{{ $base }}.settings.boxShadowPositionHorizontal" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        </div>
                    </div>
                </div>

                <!-- Box Shadow Blur Radius -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Box Shadow Blur Radius</label>
                        <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="number" min="0" v-model="{{ $base }}.settings.boxShadowBlurRadius" class="w-16 border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <input type="range" v-model="{{ $base }}.settings.boxShadowBlurRadius" min="0" max="100" class="flex-1 accent-[#0091ea]">
                    </div>
                </div>

                <!-- Box Shadow Spread Radius -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Box Shadow Spread Radius</label>
                        <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="number" min="0" v-model="{{ $base }}.settings.boxShadowSpreadRadius" class="w-16 border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <input type="range" v-model="{{ $base }}.settings.boxShadowSpreadRadius" min="0" max="100" class="flex-1 accent-[#0091ea]">
                    </div>
                </div>

                <!-- Box Shadow Color -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Box Shadow Color</label>
                        <div class="flex gap-2 text-slate-300">
                            <i class="fa fa-question-circle text-[10px]"></i>
                            <i class="fa fa-database text-[10px]"></i>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center">
                        <div class="checkerboard rounded overflow-hidden w-8 h-8 flex-shrink-0 border border-slate-200">
                            <div @click="openColorPicker($event, {{ $base }}.settings, 'boxShadowColor', 'boxShadowColorOpacity')" 
                                 :style="{ backgroundColor: hexToRgba({{ $base }}.settings.boxShadowColor, {{ $base }}.settings.boxShadowColorOpacity) }"
                                 class="w-full h-full cursor-pointer"></div>
                        </div>
                        <div class="relative flex-1">
                            <input type="text" v-model="{{ $base }}.settings.boxShadowColor" class="w-full border border-slate-200 rounded px-2 py-1.5 pl-2 pr-8 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Box Shadow Style -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Box Shadow Style</label>
                        <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                    </div>
                    <div class="flex w-[120px] bg-slate-100 rounded overflow-hidden">
                        <button @click="{{ $base }}.settings.boxShadowStyle = 'outer'" 
                                :class="{{ $base }}.settings.boxShadowStyle === 'outer' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                                class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Outer</button>
                        <button @click="{{ $base }}.settings.boxShadowStyle = 'inner'" 
                                :class="{{ $base }}.settings.boxShadowStyle === 'inner' ? 'bg-slate-200 text-slate-500' : 'text-slate-500 hover:bg-slate-200'"
                                class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Inner</button>
                    </div>
                </div>
            </template>

            <!-- Z Index -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Z Index</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <input type="number" min="0" v-model="{{ $base }}.settings.zIndex" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
            </div>

            <!-- Overflow -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Overflow</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <select v-model="{{ $base }}.settings.overflow" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    <option value="default">Default</option>
                    <option value="hidden">Hidden</option>
                    <option value="auto">Auto</option>
                    <option value="scroll">Scroll</option>
                    <option value="visible">Visible</option>
                </select>
            </div>

        </div>

        <!-- Background Tab -->
        <div v-if="{{ isset($isNestedRow) && $isNestedRow ? 'false' : 'true' }}" v-show="activePanelTab === 'background'" class="space-y-6">
            
            <!-- Background Options -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Background Options</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-chevron-down text-[10px]"></i>
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                
                <!-- Sub Tabs for Background Type -->
                <div class="flex border border-slate-200 rounded overflow-hidden bg-slate-50 mb-4">
                    <button @click="{{ $base }}.settings.bgType = 'color'" title="Background Color" :class="{{ $base }}.settings.bgType === 'color' ? 'text-[#0091ea] bg-white border-b-2 border-[#0091ea]' : 'text-slate-400 hover:text-[#0091ea]'" class="flex-1 py-2 text-[12px]"><i class="fa fa-fill-drip"></i></button>
                    <button @click="{{ $base }}.settings.bgType = 'gradient'" title="Background Gradient" :class="{{ $base }}.settings.bgType === 'gradient' ? 'text-[#0091ea] bg-white border-b-2 border-[#0091ea]' : 'text-slate-400 hover:text-[#0091ea]'" class="flex-1 py-2 text-[12px]"><i class="fa fa-adjust"></i></button>
                    <button @click="{{ $base }}.settings.bgType = 'image'" title="Background Image" :class="{{ $base }}.settings.bgType === 'image' ? 'text-[#0091ea] bg-white border-b-2 border-[#0091ea]' : 'text-slate-400 hover:text-[#0091ea]'" class="flex-1 py-2 text-[12px]"><i class="fa fa-image"></i></button>
                </div>

                <!-- 1. Color Tab Content -->
                <div v-show="{{ $base }}.settings.bgType === 'color'" class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">{{ $label }} Background Color</label>
                            <div class="flex gap-2 text-slate-300">
                                <i class="fa fa-question-circle text-[10px]"></i>
                                <i class="fa fa-cog text-[10px]"></i>
                                <i class="fa fa-undo text-[10px]"></i>
                                <i class="fa fa-desktop text-[10px]"></i>
                                <i class="fa fa-database text-[10px]"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            <input type="text" v-model="{{ $base }}.settings.bgColor" class="wp-input h-7 flex-1 text-[10px] text-center font-mono focus:outline-none focus:border-[#2271b1]">
                            <button @click="{{ $base }}.settings.bgColor = '#ffffff'; {{ $base }}.settings.bgColorOpacity = 1" class="wp-btn-secondary h-7 px-2 text-[10px]">Default</button>
                        </div>
                        <div class="flex gap-2 items-center">
                            <div class="checkerboard rounded overflow-hidden w-8 h-8 flex-shrink-0 border border-slate-200">
                                <div @click="openColorPicker($event, {{ $base }}.settings, 'bgColor', 'bgColorOpacity')" 
                                     :style="{ backgroundColor: hexToRgba({{ $base }}.settings.bgColor, {{ $base }}.settings.bgColorOpacity) }"
                                     class="w-full h-full cursor-pointer"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Gradient Tab Content -->
                <div v-show="{{ $base }}.settings.bgType === 'gradient'" class="space-y-4 border border-slate-100 rounded-md p-2">
                    <!-- Start Color -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Start Color</label>
                            <div class="flex gap-2 text-slate-300">
                                <i class="fa fa-question-circle text-[10px]"></i>
                                <i class="fa fa-cog text-[10px]"></i>
                                <i class="fa fa-undo text-[10px]"></i>
                                <i class="fa fa-database text-[10px]"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            <input type="text" v-model="{{ $base }}.settings.bgGradientStartColor" class="wp-input h-7 flex-1 text-[10px] text-center font-mono focus:outline-none focus:border-[#2271b1]">
                        </div>
                        <div class="flex gap-2 items-center">
                            <div class="checkerboard rounded overflow-hidden w-8 h-8 flex-shrink-0 border border-slate-200">
                                <div @click="openColorPicker($event, {{ $base }}.settings, 'bgGradientStartColor', 'bgGradientStartOpacity')" 
                                     :style="{ backgroundColor: hexToRgba({{ $base }}.settings.bgGradientStartColor, {{ $base }}.settings.bgGradientStartOpacity) }"
                                     class="w-full h-full cursor-pointer"></div>
                            </div>
                        </div>
                    </div>

                    <!-- End Color -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient End Color</label>
                            <div class="flex gap-2 text-slate-300">
                                <i class="fa fa-question-circle text-[10px]"></i>
                                <i class="fa fa-cog text-[10px]"></i>
                                <i class="fa fa-undo text-[10px]"></i>
                                <i class="fa fa-database text-[10px]"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mb-2">
                            <input type="text" v-model="{{ $base }}.settings.bgGradientEndColor" class="wp-input h-7 flex-1 text-[10px] text-center font-mono focus:outline-none focus:border-[#2271b1]">
                        </div>
                        <div class="flex gap-2 items-center">
                            <div class="checkerboard rounded overflow-hidden w-8 h-8 flex-shrink-0 border border-slate-200">
                                <div @click="openColorPicker($event, {{ $base }}.settings, 'bgGradientEndColor', 'bgGradientEndOpacity')" 
                                     :style="{ backgroundColor: hexToRgba({{ $base }}.settings.bgGradientEndColor, {{ $base }}.settings.bgGradientEndOpacity) }"
                                     class="w-full h-full cursor-pointer"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Start Position -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Start Position</label>
                            <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" v-model="{{ $base }}.settings.bgGradientStartPosition" class="w-16 border border-slate-200 rounded px-2 py-1 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <input type="range" min="0" max="100" v-model="{{ $base }}.settings.bgGradientStartPosition" class="flex-1 h-1 bg-[#0091ea] rounded appearance-none cursor-pointer">
                        </div>
                    </div>

                    <!-- End Position -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient End Position</label>
                            <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" v-model="{{ $base }}.settings.bgGradientEndPosition" class="w-16 border border-slate-200 rounded px-2 py-1 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <input type="range" min="0" max="100" v-model="{{ $base }}.settings.bgGradientEndPosition" class="flex-1 h-1 bg-[#0091ea] rounded appearance-none cursor-pointer">
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Type</label>
                            <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                        </div>
                        <div class="flex bg-slate-100 rounded overflow-hidden">
                            <button @click="{{ $base }}.settings.bgGradientType = 'linear'" 
                                    :class="{{ $base }}.settings.bgGradientType === 'linear' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                                    class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Linear</button>
                            <button @click="{{ $base }}.settings.bgGradientType = 'radial'" 
                                    :class="{{ $base }}.settings.bgGradientType === 'radial' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                                    class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Radial</button>
                        </div>
                    </div>

                    <!-- Angle -->
                    <div v-show="{{ $base }}.settings.bgGradientType === 'linear'">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Angle</label>
                            <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" v-model="{{ $base }}.settings.bgGradientAngle" class="w-16 border border-slate-200 rounded px-2 py-1 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <input type="range" min="0" max="360" v-model="{{ $base }}.settings.bgGradientAngle" class="flex-1 h-1 bg-[#0091ea] rounded appearance-none cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- 3. Image Tab Content -->
                <div v-show="{{ $base }}.settings.bgType === 'image'" class="space-y-4 border border-slate-100 rounded-md p-2">
                    <div v-if="{{ $base }}.settings.bgImage" class="relative group">
                        <img :src="{{ $base }}.settings.bgImage" class="w-full h-[120px] object-cover rounded border border-slate-200">
                        <div class="flex justify-center gap-2 mt-2">
                            <button @click="{{ $base }}.settings.bgImage = ''" class="px-3 py-1.5 text-[11px] font-bold border border-slate-200 rounded text-[#444] hover:bg-slate-50 transition-colors">Remove</button>
                            <button @click="openMediaModal('bgImage')" class="px-3 py-1.5 text-[11px] font-bold bg-[#0091ea] text-white rounded hover:bg-[#007cc0] transition-colors">Edit</button>
                        </div>
                    </div>
                    <div v-else>
                        <button @click="openMediaModal('bgImage')" class="w-full h-[80px] border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors rounded flex flex-col items-center justify-center gap-1">
                            <i class="fa fa-plus text-[#0091ea] text-lg"></i>
                        </button>
                    </div>

                    <template v-if="{{ $base }}.settings.bgImage">
                        <!-- Skip Lazy Loading -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Skip Lazy Loading</label>
                                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                            </div>
                            <div class="flex bg-slate-100 rounded overflow-hidden w-[100px]">
                                <button @click="{{ $base }}.settings.bgImageSkipLazy = true" 
                                        :class="{{ $base }}.settings.bgImageSkipLazy ? 'bg-slate-800 text-white' : 'text-slate-500 hover:bg-slate-200'"
                                        class="flex-1 py-1 text-[10px] font-medium transition-colors">Yes</button>
                                <button @click="{{ $base }}.settings.bgImageSkipLazy = false" 
                                        :class="!{{ $base }}.settings.bgImageSkipLazy ? 'bg-slate-800 text-white' : 'text-slate-500 hover:bg-slate-200'"
                                        class="flex-1 py-1 text-[10px] font-medium transition-colors">No</button>
                            </div>
                        </div>

                        <!-- Background Position -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Position</label>
                                <div class="flex gap-2 text-slate-300">
                                    <i class="fa fa-question-circle text-[10px]"></i>
                                    <i class="fa fa-desktop text-[10px]"></i>
                                </div>
                            </div>
                            <select v-model="{{ $base }}.settings.bgImagePosition" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
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
                                <div class="flex gap-2 text-slate-300">
                                    <i class="fa fa-question-circle text-[10px]"></i>
                                    <i class="fa fa-desktop text-[10px]"></i>
                                </div>
                            </div>
                            <select v-model="{{ $base }}.settings.bgImageRepeat" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
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
                                <div class="flex gap-2 text-slate-300">
                                    <i class="fa fa-question-circle text-[10px]"></i>
                                    <i class="fa fa-desktop text-[10px]"></i>
                                </div>
                            </div>
                            <select v-model="{{ $base }}.settings.bgImageSize" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="auto">Default</option>
                                <option value="cover">Cover</option>
                                <option value="contain">Contain</option>
                            </select>
                        </div>

                        <!-- Fading Animation -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Fading Animation</label>
                                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                            </div>
                            <div class="flex bg-slate-100 rounded overflow-hidden w-[100px]">
                                <button @click="{{ $base }}.settings.bgImageFading = true" 
                                        :class="{{ $base }}.settings.bgImageFading ? 'bg-slate-800 text-white' : 'text-slate-500 hover:bg-slate-200'"
                                        class="flex-1 py-1 text-[10px] font-medium transition-colors">Yes</button>
                                <button @click="{{ $base }}.settings.bgImageFading = false" 
                                        :class="!{{ $base }}.settings.bgImageFading ? 'bg-slate-800 text-white' : 'text-slate-500 hover:bg-slate-200'"
                                        class="flex-1 py-1 text-[10px] font-medium transition-colors">No</button>
                            </div>
                        </div>

                        <!-- Background Parallax -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Parallax</label>
                                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                            </div>
                            <select v-model="{{ $base }}.settings.bgImageParallax" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="none">No Parallax (no effects)</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>

                        <!-- Background Blend Mode -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Blend Mode</label>
                                <div class="flex gap-2 text-slate-300">
                                    <i class="fa fa-question-circle text-[10px]"></i>
                                    <i class="fa fa-desktop text-[10px]"></i>
                                </div>
                            </div>
                            <select v-model="{{ $base }}.settings.bgImageBlendMode" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
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

        <!-- Link Tab -->
        <div v-if="{{ isset($isNestedRow) && $isNestedRow ? 'false' : 'true' }}" v-show="activePanelTab === 'link'" class="space-y-6">
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Link URL</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <input type="text" v-model="{{ $base }}.settings.linkUrl" placeholder="https://..." class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
            </div>
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Link Target</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <select v-model="{{ $base }}.settings.linkTarget" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    <option value="_self">Same Window</option>
                    <option value="_blank">New Window</option>
                </select>
            </div>
        </div>

    </div>
</div>
