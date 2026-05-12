<div v-if="el.type === 'heading'" class="element-heading" :class="getVisibilityClasses(el.settings)">
    <h2 :style="{ 
            textAlign: el.settings.textAlign,
            fontSize: getUnitVal(el.settings.fontSize, el.settings.fontSizeUnit),
            letterSpacing: getUnitVal(el.settings.letterSpacing, el.settings.letterSpacingUnit)
        }" class="m-0 p-0 text-slate-800 font-bold leading-tight">
        @{{ el.settings.title || 'New Heading' }}
    </h2>
</div>
