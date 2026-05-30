{{-- Dynamic source picker for custom element fields. Variable: $dynKey (settings key holding the source) --}}
<div class="flex items-center gap-2 px-3 py-2 bg-[#0091ea]/8 border border-[#0091ea]/25 rounded-lg">
    <i class="fa fa-database text-[#0091ea] text-[11px] shrink-0"></i>
    <select v-model="editingElement.settings.{{ $dynKey }}"
            class="flex-1 bg-transparent text-[12px] font-bold text-[#0091ea] focus:outline-none cursor-pointer">
        <option value="post_title">Post Title</option>
        <option value="post_url">Post URL</option>
        <option value="post_excerpt">Post Excerpt</option>
        <option value="post_date">Post Date</option>
        <option value="post_author">Author Name</option>
        <option value="featured_image">Featured Image</option>
        <option value="site_name">Site Name</option>
    </select>
    <button @click="editingElement.settings.{{ $dynKey }} = ''"
            class="w-5 h-5 flex items-center justify-center text-[#0091ea]/50 hover:text-red-500 transition-colors rounded shrink-0">
        <i class="fa fa-times text-[10px]"></i>
    </button>
</div>
