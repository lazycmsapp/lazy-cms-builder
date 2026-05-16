<div v-if="el.type === 'button'"
     class="element-button-wrapper"
     :class="[el.settings.cssClass || '', 'button-container-' + el.id]"
     :id="el.settings.cssId || undefined"
     :style="[
         { 
            display: 'flex',
            width: '100%',
            justifyContent: el.settings.textAlign === 'left' ? 'flex-start' : (el.settings.textAlign === 'right' ? 'flex-end' : 'center'),
            marginTop: getUnitVal(el.settings.marginTop ?? 10, 'px'),
            marginBottom: getUnitVal(el.settings.marginBottom ?? 10, 'px')
         },
         getCanvasVisibilityStyle(el.settings)
     ]">
    
    <a href="javascript:void(0)"
       :id="'btn-preview-' + el.id"
       @mouseenter="el.isHovered = true"
       @mouseleave="el.isHovered = false"
       :style="{
           display: el.settings.buttonSpan ? 'block' : 'inline-block',
           width: el.settings.buttonSpan ? '100%' : 'auto',
           paddingTop: getUnitVal(el.settings.paddingTop, 'px'),
           paddingBottom: getUnitVal(el.settings.paddingBottom, 'px'),
           paddingLeft: getUnitVal(el.settings.paddingLeft, 'px'),
           paddingRight: getUnitVal(el.settings.paddingRight, 'px'),
           marginTop: getUnitVal(el.settings.marginTopInner ?? 0, 'px'),
           marginBottom: getUnitVal(el.settings.marginBottomInner ?? 0, 'px'),
           marginLeft: getUnitVal(el.settings.marginLeft ?? 0, 'px'),
           marginRight: getUnitVal(el.settings.marginRight ?? 0, 'px'),
           backgroundColor: (el.settings.buttonStyle === 'custom' && el.settings.bgGradientStartColor && el.settings.bgGradientEndColor) ? 'transparent' : (el.isHovered ? (el.settings.hoverBgColor || '#007cc0') : (el.settings.bgColor || '#0091ea')),
           backgroundImage: (el.settings.buttonStyle === 'custom' && el.settings.bgGradientStartColor && el.settings.bgGradientEndColor) 
                ? (el.isHovered 
                    ? (el.settings.bgGradientType === 'radial' 
                        ? `radial-gradient(circle at center, ${el.settings.bgGradientHoverStartColor || el.settings.bgGradientStartColor} ${el.settings.bgGradientStartPosition ?? 0}%, ${el.settings.bgGradientHoverEndColor || el.settings.bgGradientEndColor} ${el.settings.bgGradientEndPosition ?? 100}%)`
                        : `linear-gradient(${el.settings.bgGradientAngle ?? 180}deg, ${el.settings.bgGradientHoverStartColor || el.settings.bgGradientStartColor} ${el.settings.bgGradientStartPosition ?? 0}%, ${el.settings.bgGradientHoverEndColor || el.settings.bgGradientHoverEndColor} ${el.settings.bgGradientEndPosition ?? 100}%)`)
                    : (el.settings.bgGradientType === 'radial' 
                        ? `radial-gradient(circle at center, ${el.settings.bgGradientStartColor} ${el.settings.bgGradientStartPosition ?? 0}%, ${el.settings.bgGradientEndColor} ${el.settings.bgGradientEndPosition ?? 100}%)`
                        : `linear-gradient(${el.settings.bgGradientAngle ?? 180}deg, ${el.settings.bgGradientStartColor} ${el.settings.bgGradientStartPosition ?? 0}%, ${el.settings.bgGradientEndColor} ${el.settings.bgGradientEndPosition ?? 100}%)`)
                )
                : 'none',
           color: el.isHovered ? (el.settings.hoverColor || '#ffffff') : (el.settings.color || '#ffffff'),
           borderRadius: getUnitVal(el.settings.borderRadius ?? 5, 'px'),
           borderTopWidth: getUnitVal(el.settings.borderSizeTop ?? 0, 'px'),
           borderRightWidth: getUnitVal(el.settings.borderSizeRight ?? 0, 'px'),
           borderBottomWidth: getUnitVal(el.settings.borderSizeBottom ?? 0, 'px'),
           borderLeftWidth: getUnitVal(el.settings.borderSizeLeft ?? 0, 'px'),
           borderStyle: 'solid',
           borderColor: el.settings.borderColor || '#000000',
           fontFamily: el.settings.fontFamily || 'inherit',
           fontSize: getUnitVal(el.settings.fontSize, 'px'),
           fontWeight: el.settings.fontWeight || '600',
           lineHeight: el.settings.lineHeight || 'normal',
           letterSpacing: getUnitVal(el.settings.letterSpacing ?? 0, 'px'),
           textTransform: el.settings.textTransform || 'none',
           textDecoration: 'none',
           transition: 'all 0.3s ease',
           textAlign: 'center'
       }">
        <i v-if="el.settings.icon && el.settings.iconPosition !== 'right'" :class="[el.settings.icon, 'mr-2']"></i>
        @{{ el.settings.text || 'Click Here' }}
        <i v-if="el.settings.icon && el.settings.iconPosition === 'right'" :class="[el.settings.icon, 'ml-2']"></i>
    </a>
</div>
