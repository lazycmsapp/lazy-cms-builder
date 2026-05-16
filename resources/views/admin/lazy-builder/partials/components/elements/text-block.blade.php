<div v-if="el.type === 'text_block' || el.type === 'special_text'"
     class="element-text-block-wrapper"
     :class="[el.settings.cssClass || '', 'text-block-container-' + el.id]"
     :id="el.settings.cssId || undefined"
     :style="[
         { 
            maxWidth: '100%',
            textAlign: el.settings.textAlign || 'center',
            paddingTop: (el.settings.paddingTop ?? 10) + 'px',
            paddingRight: (el.settings.paddingRight ?? 0) + 'px',
            paddingBottom: (el.settings.paddingBottom ?? 10) + 'px',
            paddingLeft: (el.settings.paddingLeft ?? 0) + 'px',
            marginTop: (el.settings.marginTop ?? 0) + 'px',
            marginRight: (el.settings.marginRight ?? 0) + 'px',
            marginBottom: (el.settings.marginBottom ?? 0) + 'px',
            marginLeft: (el.settings.marginLeft ?? 0) + 'px',
            // Typography on wrapper
            color: (el.isHovered && el.settings.hoverColor) ? el.settings.hoverColor : (el.settings.color || '#333333'),
            fontFamily: el.settings.fontFamily || 'inherit',
            fontSize: getUnitVal(el.settings.fontSize || 16, el.settings.fontSizeUnit || 'px'),
            fontWeight: el.settings.fontWeight || '400',
            lineHeight: el.settings.lineHeight || '1.5',
            letterSpacing: (el.settings.letterSpacing || 0) + 'px',
            textTransform: el.settings.textTransform || 'none'
         },
         getCanvasVisibilityStyle(el.settings)
     ]">
    <div class="text-block-content" 
       v-html="el.settings.content || 'your content is here...'"
       @mouseenter="el.isHovered = true"
       @mouseleave="el.isHovered = false"
       :style="{
           textAlign: 'inherit',
           margin: '0',
           width: '100%',
           transition: 'color 0.3s ease',
           color: 'inherit'
       }">
    </div>
    <div v-if="false"></div> <!-- Just to have a sibling for the style if needed -->
    <component is="style">
        .text-block-container-@{{ el.id }} .text-block-content { 
            text-align: inherit !important; 
            color: inherit !important; 
            font-size: inherit !important; 
            font-family: inherit !important; 
            font-weight: inherit !important; 
            line-height: inherit !important; 
            letter-spacing: inherit !important; 
            text-transform: inherit !important; 
            margin: 0 !important; 
        }
        .text-block-container-@{{ el.id }} ul { list-style-type: disc !important; margin-left: 20px !important; margin-bottom: 15px !important; }
        .text-block-container-@{{ el.id }} ol { list-style-type: decimal !important; margin-left: 20px !important; margin-bottom: 15px !important; }
        .text-block-container-@{{ el.id }} li { margin-bottom: 5px !important; }
    </component>
</div>
