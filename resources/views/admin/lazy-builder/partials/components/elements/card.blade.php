<div v-if="el.type === 'card'"
     class="w-full py-2"
     :style="getCanvasVisibilityStyle(el.settings)">

    {{-- Header bar --}}
    <div class="flex items-center justify-between mb-2 px-1">
        <div class="flex items-center gap-1.5">
            <div class="w-5 h-5 bg-[#0091ea] rounded flex items-center justify-center">
                <i class="fa fa-th-large text-white text-[9px]"></i>
            </div>
            <span class="text-[11px] font-black text-[#1d2327] uppercase tracking-wider">Card</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span v-if="el.settings.post_card_id && postCardsMap[el.settings.post_card_id]"
                  class="text-[9px] bg-[#0091ea] text-white px-2 py-0.5 rounded-full font-bold">
                @{{ postCardsMap[el.settings.post_card_id] }}
            </span>
            <span v-if="el.settings.post_type && el.settings.post_type !== 'post'"
                  class="text-[9px] bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full font-bold capitalize">
                @{{ el.settings.post_type }}
            </span>
            <span class="text-[9px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-bold capitalize">
                @{{ el.settings.layout || 'grid' }}
            </span>
        </div>
    </div>

    {{-- Real post preview grid --}}
    <div class="grid gap-2"
         :style="{ gridTemplateColumns: 'repeat(' + Math.min(el.settings.columns || 3, 4) + ', 1fr)' }">

        <template v-if="recentPosts.length">
            <div v-for="(post, i) in recentPosts.slice(0, Math.min(el.settings.posts_count || 6, el.settings.columns || 3, 4))"
                 :key="i"
                 class="bg-white border border-slate-200/80 rounded-lg overflow-hidden shadow-sm">
                {{-- Image --}}
                <div v-if="post.image"
                     class="w-full bg-slate-100 overflow-hidden"
                     style="aspect-ratio:16/9;">
                    <img :src="post.image" class="w-full h-full object-cover" style="display:block;">
                </div>
                <div v-else class="w-full bg-gradient-to-br from-slate-100 to-slate-200" style="aspect-ratio:16/9;"></div>
                {{-- Text --}}
                <div class="p-2">
                    <div class="text-[11px] font-bold text-slate-700 leading-tight mb-1 line-clamp-2">@{{ post.title }}</div>
                    <div v-if="post.excerpt" class="text-[10px] text-slate-400 leading-snug line-clamp-2">@{{ post.excerpt }}</div>
                </div>
            </div>
        </template>

        <template v-else>
            <div v-for="n in Math.min(el.settings.posts_count || 6, el.settings.columns || 3, 4)"
                 :key="n"
                 class="bg-white border border-slate-200/80 rounded-lg overflow-hidden shadow-sm">
                <div class="h-16 bg-gradient-to-br from-slate-100 to-slate-200"></div>
                <div class="p-2 space-y-1">
                    <div class="h-2.5 bg-slate-200 rounded w-full"></div>
                    <div class="h-2 bg-slate-100 rounded w-3/4"></div>
                </div>
            </div>
        </template>

    </div>

    {{-- Info row --}}
    <div class="mt-2 pt-2 border-t border-slate-100 flex items-center flex-wrap gap-3 px-1 text-[9px] text-slate-400 font-bold">
        <span class="flex items-center gap-1">
            <i class="fa fa-columns text-[#0091ea]/50"></i> @{{ el.settings.columns || 3 }} cols
        </span>
        <span class="flex items-center gap-1">
            <i class="fa fa-list text-[#0091ea]/50"></i> @{{ el.settings.posts_count || 6 }} posts
        </span>
        <span class="flex items-center gap-1 capitalize">
            <i class="fa fa-database text-[#0091ea]/50"></i> @{{ el.settings.content_source || 'latest' }}
        </span>
        <span v-if="el.settings.pagination_type && el.settings.pagination_type !== 'none'" class="flex items-center gap-1">
            <i class="fa fa-ellipsis-h text-[#0091ea]/50"></i> @{{ el.settings.pagination_type }}
        </span>
    </div>

</div>
