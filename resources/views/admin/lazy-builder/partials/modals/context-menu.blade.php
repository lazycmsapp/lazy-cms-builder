<template v-if="ctxMenu.show">
    <!-- Backdrop to close on outside click -->
    <div class="fixed inset-0 z-[99998]" @click="closeCtxMenu()" @contextmenu.prevent="closeCtxMenu()"></div>

    <!-- Menu -->
    <div class="fixed z-[99999] bg-[#222] text-white shadow-2xl rounded overflow-hidden min-w-[190px] select-none"
         :style="{ top: ctxMenu.y + 'px', left: ctxMenu.x + 'px' }"
         @click.stop>

        <!-- Title -->
        <div class="px-4 py-2.5 bg-[#111] text-[10px] font-black uppercase tracking-[0.2em] border-b border-white/10">
            @{{ ctxMenuTitle }}
        </div>

        <!-- Actions -->
        <div class="py-1">
            <button @click="ctxEdit()"
                    class="w-full px-4 py-2 text-left text-[13px] text-slate-200 hover:bg-white/10 transition-colors">
                Edit
            </button>
            <button v-if="ctxMenu.type !== 'nested-row'"
                    @click="ctxSave()"
                    class="w-full px-4 py-2 text-left text-[13px] text-slate-200 hover:bg-white/10 transition-colors">
                Save
            </button>
            <button @click="ctxClone()"
                    class="w-full px-4 py-2 text-left text-[13px] text-slate-200 hover:bg-white/10 transition-colors">
                Clone
            </button>

            <div class="border-t border-white/10 my-1"></div>

            <button @click="ctxRemove()"
                    class="w-full px-4 py-2 text-left text-[13px] text-red-400 hover:bg-red-500/20 transition-colors">
                Remove
            </button>

            <div class="border-t border-white/10 my-1"></div>

            <button @click="ctxCopy()"
                    class="w-full px-4 py-2 text-left text-[13px] text-slate-200 hover:bg-white/10 transition-colors">
                Copy
            </button>
            <button @click="ctxClipboard?.type === ctxMenu.type && ctxPaste('start')"
                    :class="ctxClipboard?.type === ctxMenu.type ? 'text-slate-200 hover:bg-white/10 cursor-pointer' : 'text-slate-500 cursor-not-allowed'"
                    class="w-full px-4 py-2 text-left text-[13px] transition-colors">
                Paste At Start
            </button>
            <button @click="ctxClipboard?.type === ctxMenu.type && ctxPaste('end')"
                    :class="ctxClipboard?.type === ctxMenu.type ? 'text-slate-200 hover:bg-white/10 cursor-pointer' : 'text-slate-500 cursor-not-allowed'"
                    class="w-full px-4 py-2 text-left text-[13px] transition-colors">
                Paste At End
            </button>
        </div>
    </div>
</template>
