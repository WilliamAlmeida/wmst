import { onBeforeUnmount, ref } from 'vue';

export type TextToSpeechStatus = 'idle' | 'playing' | 'paused';

/**
 * Leitura em voz alta no próprio navegador (Web Speech API — speechSynthesis).
 * Gratuito e sem chamadas externas; a voz depende das instaladas no navegador/SO.
 *
 * O texto é fatiado em sentenças curtas antes de falar: utterances muito longas
 * são interrompidas silenciosamente pelo Chrome (bug conhecido da API).
 */
export function useTextToSpeech(lang = 'pt-BR') {
    const supported = ref(typeof window !== 'undefined' && 'speechSynthesis' in window);
    const status = ref<TextToSpeechStatus>('idle');

    // Invalida callbacks de utterances antigas após um stop()/nova leitura.
    let generation = 0;

    const chunkText = (text: string): string[] => {
        const sentences = text
            .replace(/\s+/g, ' ')
            .split(/(?<=[.!?…:])\s+/)
            .map((part) => part.trim())
            .filter((part) => part !== '');

        const chunks: string[] = [];
        let current = '';

        for (const sentence of sentences) {
            if (current !== '' && current.length + sentence.length > 220) {
                chunks.push(current);
                current = sentence;

                continue;
            }

            current = current === '' ? sentence : `${current} ${sentence}`;
        }

        if (current !== '') {
            chunks.push(current);
        }

        return chunks;
    };

    const pickVoice = (): SpeechSynthesisVoice | null => {
        const voices = window.speechSynthesis.getVoices();
        const prefix = lang.slice(0, 2).toLowerCase();

        return (
            voices.find((voice) => voice.lang.replace('_', '-').toLowerCase() === lang.toLowerCase()) ??
            voices.find((voice) => voice.lang.toLowerCase().startsWith(prefix)) ??
            null
        );
    };

    const stop = (): void => {
        if (!supported.value) {
            return;
        }

        generation += 1;
        window.speechSynthesis.cancel();
        status.value = 'idle';
    };

    const speak = (text: string): void => {
        if (!supported.value || text.trim() === '') {
            return;
        }

        stop();

        const myGeneration = ++generation;
        const chunks = chunkText(text);
        const voice = pickVoice();

        chunks.forEach((chunk, index) => {
            const utterance = new SpeechSynthesisUtterance(chunk);
            utterance.lang = lang;

            if (voice) {
                utterance.voice = voice;
            }

            if (index === chunks.length - 1) {
                utterance.onend = () => {
                    if (generation === myGeneration) {
                        status.value = 'idle';
                    }
                };
            }

            utterance.onerror = () => {
                if (generation === myGeneration) {
                    status.value = 'idle';
                }
            };

            window.speechSynthesis.speak(utterance);
        });

        status.value = 'playing';
    };

    const toggle = (text: string): void => {
        if (!supported.value) {
            return;
        }

        if (status.value === 'playing') {
            window.speechSynthesis.pause();
            status.value = 'paused';

            return;
        }

        if (status.value === 'paused') {
            window.speechSynthesis.resume();
            status.value = 'playing';

            return;
        }

        speak(text);
    };

    onBeforeUnmount(stop);

    return { supported, status, toggle, stop };
}
