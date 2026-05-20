<div v-if="el.type === 'image'"
     class="element-image-wrapper w-full relative group/img-preview"
     :class="[el.settings.cssClass || '', 'image-container-' + el.id, el.settings.hoverType ? 'hover-' + el.settings.hoverType : '']"
     :id="el.settings.cssId || undefined"
     :style="[
         {
            display: 'flex',
            width: '100%',
            justifyContent: (getResponsiveVal(el.settings, 'textAlign', device) || 'center') === 'left' ? 'flex-start' : ((getResponsiveVal(el.settings, 'textAlign', device) || 'center') === 'right' ? 'flex-end' : 'center'),
            marginTop: getUnitVal(getResponsiveVal(el.settings, 'marginTop', device) ?? 0, getResponsiveVal(el.settings, 'marginTopUnit', device) || 'px'),
            marginRight: getUnitVal(getResponsiveVal(el.settings, 'marginRight', device) ?? 0, getResponsiveVal(el.settings, 'marginRightUnit', device) || 'px'),
            marginBottom: getUnitVal(getResponsiveVal(el.settings, 'marginBottom', device) ?? 0, getResponsiveVal(el.settings, 'marginBottomUnit', device) || 'px'),
            marginLeft: getUnitVal(getResponsiveVal(el.settings, 'marginLeft', device) ?? 0, getResponsiveVal(el.settings, 'marginLeftUnit', device) || 'px')
         },
         getCanvasVisibilityStyle(el.settings)
     ]">
    
    <div class="image-inner relative overflow-hidden"
         :style="{
            width: el.settings.width ? getUnitVal(el.settings.width, el.settings.widthUnit || 'px') : 'auto',
            maxWidth: el.settings.maxWidth ? getUnitVal(el.settings.maxWidth, el.settings.maxWidthUnit || 'px') : '100%',
            borderRadius: getUnitVal(el.settings.borderRadius ?? 0, el.settings.borderRadiusUnit || 'px'),
            borderTopWidth: getUnitVal(el.settings.borderSizeTop ?? 0, 'px'),
            borderRightWidth: getUnitVal(el.settings.borderSizeRight ?? 0, 'px'),
            borderBottomWidth: getUnitVal(el.settings.borderSizeBottom ?? 0, 'px'),
            borderLeftWidth: getUnitVal(el.settings.borderSizeLeft ?? 0, 'px'),
            borderStyle: 'solid',
            borderColor: el.settings.borderColor || 'transparent'
         }">
        
         <img v-if="el.settings.url" 
             :src="el.settings.url" 
             :alt="el.settings.alt || ''" 
             class="block w-full h-auto pointer-events-none">
             
        <div v-else 
             class="bg-slate-100 border-2 border-dashed border-slate-300 rounded flex flex-col items-center justify-center p-8 text-slate-400">
            <i class="fa fa-image text-4xl mb-2 opacity-50"></i>
            <span class="text-xs font-bold uppercase tracking-widest">No Image Selected</span>
        </div>
    </div>
</div>
