<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { X } from 'lucide-vue-next';
import ScanFrame from './ScanFrame.vue';

withDefaults(
    defineProps<{
        src: string;
        alt: string;
        imgClass?: string;
        width?: number | string;
        height?: number | string;
        loading?: 'lazy' | 'eager';
    }>(),
    {
        loading: 'lazy',
    },
);

const open = ref(false);
const mounted = ref(false);

onMounted(() => {
    mounted.value = true;
});

const onKey = (event: KeyboardEvent): void => {
    if (event.key === 'Escape') open.value = false;
};

watch(open, (isOpen) => {
    if (isOpen) {
        document.addEventListener('keydown', onKey);
        document.body.style.overflow = 'hidden';
    } else {
        document.removeEventListener('keydown', onKey);
        document.body.style.overflow = '';
    }
});

onBeforeUnmount(() => {
    document.removeEventListener('keydown', onKey);
    document.body.style.overflow = '';
});
</script>

<template>
    <button
        type="button"
        class="group/zoom block w-full cursor-zoom-in"
        :aria-label="`Ampliar imagem: ${alt}`"
        @click="open = true"
    >
        <img
            :src="src"
            :alt="alt"
            :class="imgClass"
            :width="width"
            :height="height"
            :loading="loading"
            decoding="async"
            class="transition duration-300 group-hover/zoom:scale-[1.02]"
        />
    </button>

    <Teleport to="body" :disabled="!mounted">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="open"
                class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/85 p-6 backdrop-blur-sm md:p-12"
                role="dialog"
                aria-modal="true"
                :aria-label="alt"
                @click="open = false"
            >
                <button
                    type="button"
                    class="absolute right-4 top-4 z-10 rounded-full border border-white/20 bg-white/10 p-2 text-white transition hover:bg-white/20"
                    aria-label="Fechar"
                    @click.stop="open = false"
                >
                    <X class="h-5 w-5" />
                </button>

                <ScanFrame size="lg" @click.stop>
                    <img
                        :src="src"
                        :alt="alt"
                        class="max-h-[85vh] w-auto max-w-[88vw] rounded-xl object-contain shadow-2xl"
                    />
                </ScanFrame>
            </div>
        </Transition>
    </Teleport>
</template>
