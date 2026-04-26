<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';

const props = withDefaults(
    defineProps<{
        as?: string;
        delay?: number;
        once?: boolean;
    }>(),
    {
        as: 'div',
        delay: 0,
        once: true,
    },
);

const el = ref<HTMLElement | null>(null);
let observer: IntersectionObserver | null = null;

onMounted(() => {
    if (!el.value || typeof IntersectionObserver === 'undefined') {
        el.value?.classList.add('is-visible');
        return;
    }

    observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                window.setTimeout(() => {
                    entry.target.classList.add('is-visible');
                }, props.delay);
                if (props.once && observer) {
                    observer.unobserve(entry.target);
                }
            } else if (!props.once) {
                entry.target.classList.remove('is-visible');
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    observer.observe(el.value);
});

onBeforeUnmount(() => {
    observer?.disconnect();
});
</script>

<template>
    <component :is="as" ref="el" class="reveal">
        <slot />
    </component>
</template>
