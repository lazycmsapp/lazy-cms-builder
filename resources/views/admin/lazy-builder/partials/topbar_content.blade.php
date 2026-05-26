    <!-- Left Section -->
    <div class="flex items-center gap-1 h-full">
        <div class="topbar-icon">
            <svg class="w-6 h-6 text-[#0091ea]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div class="h-6 w-px bg-white/10 mx-2"></div>
        <div class="topbar-icon" title="Layout" @click="activeTab='navigator'">
            <i class="fa fa-th-large text-sm"></i>
        </div>
        <div @click="undo()" :title="canUndo ? 'Undo (Ctrl+Z)' : 'Nothing to undo'"
             :class="canUndo ? 'cursor-pointer' : 'opacity-30 cursor-not-allowed pointer-events-none'"
             class="topbar-icon">
            <i class="fa fa-undo text-sm"></i>
        </div>
        <div @click="redo()" :title="canRedo ? 'Redo (Ctrl+Y)' : 'Nothing to redo'"
             :class="canRedo ? 'cursor-pointer' : 'opacity-30 cursor-not-allowed pointer-events-none'"
             class="topbar-icon">
            <i class="fa fa-redo text-sm"></i>
        </div>
        <div class="topbar-icon" title="Responsive" @click="device = device === 'desktop' ? 'tablet' : (device === 'tablet' ? 'mobile' : 'desktop')">
            <i class="fa" :class="device==='desktop' ? 'fa-desktop' : (device==='tablet' ? 'fa-tablet-alt' : 'fa-mobile-alt')"></i>
        </div>
        <div class="topbar-icon" title="Add Element" @click="activeTab='elements'">
            <i class="fa fa-plus text-sm"></i>
        </div>
        <div class="topbar-icon" title="Clear All" @click="layout = []">
            <i class="fa fa-trash text-sm text-red-400"></i>
        </div>
    </div>

    <!-- Center Section (Page Title) -->
    <div class="hidden md:block">
        <span class="text-[11px] font-bold text-white/40 uppercase tracking-[0.3em]">Editing: {{ $builderTitle ?? $post->title }}</span>
    </div>

    <!-- Right Section -->
    <div class="flex items-center gap-4 h-full">
        <div class="topbar-icon" title="Settings" @click="activeTab='settings'">
            <i class="fa fa-cog text-sm"></i>
        </div>
        <div class="topbar-icon" title="Help">
            <i class="fa fa-question-circle text-sm"></i>
        </div>
        <div class="topbar-icon" @click="isPreview = !isPreview" title="Preview">
            <i class="fa" :class="isPreview ? 'fa-eye-slash' : 'fa-eye'"></i>
        </div>
        
        <div class="h-6 w-px bg-white/10 mx-1"></div>
        
        <button @click="isDirty && saveLayout()" :disabled="isSaving || !isDirty" class="btn-save"
                :style="{ backgroundColor: isDirty ? '#4CAF50' : '#4A5259' }"
                :class="!isDirty ? 'opacity-50 cursor-not-allowed' : ''">
            <span v-if="isSaving"><i class="fa fa-spinner fa-spin mr-2"></i> Saving</span>
            <span v-else>Save</span>
        </button>
        
        <a href="{{ $builderBackUrl ?? route('admin.posts.index') }}" class="topbar-icon hover:bg-red-500/20 text-white/60">
            <i class="fa fa-times"></i>
        </a>
    </div>
