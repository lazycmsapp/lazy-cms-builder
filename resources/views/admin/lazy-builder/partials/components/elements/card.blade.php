<div v-if="el.type === 'card'"
     class="w-full py-2"
     :style="getCanvasVisibilityStyle(el.settings)">

    {{-- Header bar --}}
    <div class="flex items-center gap-1.5 mb-2 px-1">
        <div class="w-5 h-5 bg-[#0091ea] rounded flex items-center justify-center">
            <i class="fa fa-th-large text-white text-[9px]"></i>
        </div>
        <span class="text-[11px] font-black text-[#1d2327] uppercase tracking-wider">Card</span>
        <span v-if="el.settings.post_card_id && postCardsMap[el.settings.post_card_id]"
              class="text-[9px] bg-[#0091ea] text-white px-2 py-0.5 rounded-full font-bold">
            @{{ postCardsMap[el.settings.post_card_id] }}
        </span>
        <span v-else class="text-[9px] bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full font-bold">No card selected</span>
    </div>

    {{-- Post grid --}}
    <div class="grid gap-2"
         :style="{ gridTemplateColumns: 'repeat(' + Math.min(el.settings.columns || 3, 4) + ', 1fr)' }">

        {{-- When we have real posts --}}
        <template v-if="recentPosts.length">
            <div v-for="(post, pi) in recentPosts.slice(0, Math.min(el.settings.posts_count || 6, el.settings.columns || 3, 4))"
                 :key="pi"
                 class="bg-white border border-slate-200/80 rounded-lg overflow-hidden shadow-sm">

                {{-- Card selected: render each element in the post card layout --}}
                <template v-if="el.settings.post_card_id && getCardElementsFlat(el.settings.post_card_id).length">
                    <template v-for="(cardElItem, ei) in getCardElementsFlat(el.settings.post_card_id)" :key="ei">

                        <template v-if="cardElItem.type === 'image'">
                            <img v-if="post.image" :src="post.image" class="w-full object-cover" style="display:block;aspect-ratio:16/9;">
                            <div v-else class="w-full bg-gradient-to-br from-slate-100 to-slate-200" style="aspect-ratio:16/9;"></div>
                        </template>

                        <div v-else-if="cardElItem.type === 'title'" class="px-2.5 pt-2 pb-0.5">
                            <div class="font-bold text-slate-800 leading-tight line-clamp-2"
                                 :style="{ fontSize: Math.min(cardElItem.settings?.fontSize || 16, 18) + 'px', color: cardElItem.settings?.color || '#1d2327' }">
                                @{{ post.title }}
                            </div>
                        </div>

                        <div v-else-if="cardElItem.type === 'post_content'" class="px-2.5 py-0.5">
                            <div class="text-[10px] text-slate-500 leading-snug line-clamp-2"
                                 :style="{ color: cardElItem.settings?.color || '#6b7280' }">
                                @{{ post.excerpt || 'Post excerpt will appear here…' }}
                            </div>
                        </div>

                        <div v-else-if="cardElItem.type === 'button'" class="px-2.5 pb-2.5 pt-1.5">
                            <span class="inline-block text-[10px] font-bold px-3 py-1 rounded"
                                  :style="{ background: cardElItem.settings?.buttonBgColor || '#0091ea', color: cardElItem.settings?.buttonTextColor || '#fff' }">
                                @{{ cardElItem.settings?.text || 'Click Here' }}
                            </span>
                        </div>

                        <div v-else-if="['text_block','special_text'].includes(cardElItem.type)" class="px-2.5 py-0.5">
                            <div class="text-[10px] text-slate-500 leading-snug line-clamp-1">
                                @{{ (cardElItem.settings?.content || '').replace(/<[^>]+>/g, '') }}
                            </div>
                        </div>

                        <div v-else-if="cardElItem.type === 'spacer'"
                             :style="{ height: Math.min(cardElItem.settings?.height || 20, 40) + 'px' }"></div>

                    </template>
                </template>

                {{-- No card selected: image + title fallback --}}
                <template v-else>
                    <img v-if="post.image" :src="post.image" class="w-full object-cover" style="display:block;aspect-ratio:16/9;">
                    <div v-else class="w-full bg-gradient-to-br from-slate-100 to-slate-200" style="aspect-ratio:16/9;"></div>
                    <div class="p-2">
                        <div class="text-[11px] font-bold text-slate-700 leading-tight line-clamp-2">@{{ post.title }}</div>
                    </div>
                </template>

            </div>
        </template>

        {{-- No real posts at all: skeleton cards --}}
        <template v-else>
            <div v-for="n in Math.min(el.settings.posts_count || 6, el.settings.columns || 3, 4)"
                 :key="n"
                 class="bg-white border border-slate-200/80 rounded-lg overflow-hidden shadow-sm">
                <div class="bg-gradient-to-br from-slate-100 to-slate-200" style="aspect-ratio:16/9;"></div>
                <div class="p-2 space-y-1.5">
                    <div class="h-2.5 bg-slate-200 rounded w-full"></div>
                    <div class="h-2 bg-slate-100 rounded w-3/4"></div>
                </div>
            </div>
        </template>

    </div>

</div>
