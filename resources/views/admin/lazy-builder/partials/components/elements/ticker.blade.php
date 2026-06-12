<div v-if="el.type === 'ticker'"
     class="element-ticker w-full"
     :class="[el.settings.cssClass || '']"
     :id="el.settings.cssId || undefined"
     :style="[
         {
             marginTop:    getUnitVal(el.settings.marginTop    ?? 0, el.settings.marginTopUnit    || 'px'),
             marginBottom: getUnitVal(el.settings.marginBottom ?? 0, el.settings.marginBottomUnit || 'px'),
         },
         getCanvasVisibilityStyle(el.settings)
     ]">

    <div :style="{
             width:        '100%',
             boxSizing:    'border-box',
             display:      'flex',
             alignItems:   'center',
             overflow:     'hidden',
             background:   el.settings.bgColor        || '#1e3a8a',
             color:        el.settings.textColor       || '#ffffff',
             fontSize:     el.settings.fontSize        || '14px',
             fontWeight:   el.settings.fontWeight      || '500',
             height:       (el.settings.height ?? 44) + 'px',
             borderRadius: (el.settings.borderRadius ?? 0) + 'px',
         }">

        {{-- Label badge --}}
        <div v-if="el.settings.label"
             :style="{
                 background:   el.settings.labelBgColor   || '#ef4444',
                 color:        el.settings.labelTextColor  || '#ffffff',
                 padding:      '0 14px',
                 height:       '100%',
                 display:      'flex',
                 alignItems:   'center',
                 fontWeight:   '700',
                 whiteSpace:   'nowrap',
                 flexShrink:   '0',
                 fontSize:     el.settings.fontSize || '14px',
                 textTransform:'uppercase',
                 letterSpacing:'.03em',
             }">
            @{{ el.settings.label }}
        </div>

        {{-- Items preview (static, no animation) --}}
        <div style="flex:1;overflow:hidden;padding:0 16px;display:flex;align-items:center;gap:16px;">
            <template v-if="el.settings.items && el.settings.items.filter(i => i.text).length">
                <template v-for="(item, idx) in el.settings.items.filter(i => i.text).slice(0, 4)" :key="idx">
                    <span style="white-space:nowrap;">@{{ item.text }}</span>
                    <span v-if="idx < el.settings.items.filter(i => i.text).slice(0,4).length - 1"
                          style="opacity:.5;">@{{ el.settings.separator !== '' ? (el.settings.separator || '•') : '' }}</span>
                </template>
                <span v-if="el.settings.items.filter(i => i.text).length > 4"
                      style="opacity:.5;white-space:nowrap;">…</span>
            </template>
            <span v-else style="opacity:.4;font-style:italic;">Add ticker items…</span>
        </div>
    </div>
</div>
