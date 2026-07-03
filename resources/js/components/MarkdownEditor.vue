<script setup lang="ts">
import { computed, nextTick, ref } from 'vue';
import { Bold, Code, Eye, Heading2, Italic, Link2, List, ListOrdered, Pencil, Quote } from 'lucide-vue-next';
import { renderMarkdown } from '@/lib/markdown';

const props = withDefaults(
    defineProps<{
        modelValue: string;
        placeholder?: string;
        minHeight?: string;
    }>(),
    { placeholder: 'Escreva em Markdown…', minHeight: '20rem' },
);

const emit = defineEmits<{ 'update:modelValue': [value: string] }>();

const mode = ref<'write' | 'preview'>('write');
const textarea = ref<HTMLTextAreaElement | null>(null);

const value = computed({
    get: () => props.modelValue,
    set: (next: string) => emit('update:modelValue', next),
});

const rendered = computed(() => renderMarkdown(props.modelValue || ''));

const setValue = (next: string, cursor: number): void => {
    value.value = next;
    void nextTick(() => {
        const el = textarea.value;

        if (el) {
            el.focus();
            el.setSelectionRange(cursor, cursor);
        }
    });
};

// Envolve a seleção (negrito, itálico, código, link).
const wrap = (before: string, after: string, placeholder: string): void => {
    const el = textarea.value;

    if (!el) {
        return;
    }

    const start = el.selectionStart;
    const end = el.selectionEnd;
    const text = props.modelValue;
    const selected = text.slice(start, end) || placeholder;
    const inserted = before + selected + after;

    setValue(text.slice(0, start) + inserted + text.slice(end), start + before.length + selected.length);
};

// Prefixa a linha atual (títulos, listas, citação).
const linePrefix = (prefix: string): void => {
    const el = textarea.value;

    if (!el) {
        return;
    }

    const start = el.selectionStart;
    const text = props.modelValue;
    const lineStart = text.lastIndexOf('\n', start - 1) + 1;

    setValue(text.slice(0, lineStart) + prefix + text.slice(lineStart), start + prefix.length);
};

const tools = [
    { icon: Bold, title: 'Negrito', run: () => wrap('**', '**', 'negrito') },
    { icon: Italic, title: 'Itálico', run: () => wrap('*', '*', 'itálico') },
    { icon: Heading2, title: 'Título', run: () => linePrefix('## ') },
    { icon: List, title: 'Lista', run: () => linePrefix('- ') },
    { icon: ListOrdered, title: 'Lista numerada', run: () => linePrefix('1. ') },
    { icon: Quote, title: 'Citação', run: () => linePrefix('> ') },
    { icon: Code, title: 'Código', run: () => wrap('`', '`', 'código') },
    { icon: Link2, title: 'Link', run: () => wrap('[', '](https://)', 'texto') },
];
</script>

<template>
    <div class="rounded-md border">
        <div class="flex flex-wrap items-center gap-1 border-b bg-muted/40 p-1.5">
            <button
                v-for="tool in tools"
                :key="tool.title"
                type="button"
                :title="tool.title"
                class="inline-flex h-8 w-8 items-center justify-center rounded text-zinc-600 hover:bg-white hover:text-zinc-900 disabled:opacity-40"
                :disabled="mode === 'preview'"
                @click="tool.run"
            >
                <component :is="tool.icon" class="h-4 w-4" />
            </button>

            <div class="ml-auto flex items-center gap-1">
                <button
                    type="button"
                    class="inline-flex items-center gap-1.5 rounded px-2.5 py-1.5 text-xs font-medium"
                    :class="mode === 'write' ? 'bg-white text-zinc-900 shadow-sm' : 'text-zinc-500 hover:text-zinc-800'"
                    @click="mode = 'write'"
                >
                    <Pencil class="h-3.5 w-3.5" />
                    Escrever
                </button>
                <button
                    type="button"
                    class="inline-flex items-center gap-1.5 rounded px-2.5 py-1.5 text-xs font-medium"
                    :class="mode === 'preview' ? 'bg-white text-zinc-900 shadow-sm' : 'text-zinc-500 hover:text-zinc-800'"
                    @click="mode = 'preview'"
                >
                    <Eye class="h-3.5 w-3.5" />
                    Visualizar
                </button>
            </div>
        </div>

        <textarea
            v-show="mode === 'write'"
            ref="textarea"
            v-model="value"
            :placeholder="placeholder"
            class="block w-full resize-y rounded-b-md px-3 py-2 font-mono text-sm outline-none"
            :style="{ minHeight }"
        />

        <div
            v-show="mode === 'preview'"
            class="prose prose-sm prose-zinc max-w-none overflow-y-auto px-4 py-3"
            :style="{ minHeight }"
        >
            <div v-if="props.modelValue.trim()" v-html="rendered" />
            <p v-else class="text-sm text-muted-foreground">Nada para visualizar ainda.</p>
        </div>
    </div>
</template>
