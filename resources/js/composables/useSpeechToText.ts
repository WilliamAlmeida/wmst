import { ref } from 'vue';

interface SpeechRecognitionLike {
    lang: string;
    continuous: boolean;
    interimResults: boolean;
    onresult: ((event: SpeechRecognitionResultEventLike) => void) | null;
    onend: (() => void) | null;
    onerror: ((event: { error?: string }) => void) | null;
    start: () => void;
    stop: () => void;
}

interface SpeechRecognitionResultEventLike {
    resultIndex: number;
    results: ArrayLike<{
        isFinal: boolean;
        0: { transcript: string };
    }>;
}

type SpeechRecognitionCtor = new () => SpeechRecognitionLike;

/**
 * Transcrição de voz no próprio navegador (Web Speech API).
 * onFinal recebe cada trecho finalizado da fala.
 */
export function useSpeechToText(onFinal: (text: string) => void, lang = 'pt-BR') {
    const win = window as unknown as {
        SpeechRecognition?: SpeechRecognitionCtor;
        webkitSpeechRecognition?: SpeechRecognitionCtor;
    };
    const Ctor = win.SpeechRecognition ?? win.webkitSpeechRecognition;

    const supported = ref(Boolean(Ctor));
    const listening = ref(false);
    const error = ref<string | null>(null);

    let recognition: SpeechRecognitionLike | null = null;

    const stop = (): void => {
        recognition?.stop();
    };

    const start = (): void => {
        if (!Ctor) {
            supported.value = false;

            return;
        }

        error.value = null;
        recognition = new Ctor();
        recognition.lang = lang;
        recognition.continuous = true;
        recognition.interimResults = false;

        recognition.onresult = (event) => {
            let text = '';

            for (let i = event.resultIndex; i < event.results.length; i++) {
                const result = event.results[i];

                if (result.isFinal) {
                    text += result[0].transcript;
                }
            }

            if (text.trim() !== '') {
                onFinal(text.trim());
            }
        };

        recognition.onerror = (event) => {
            error.value = event?.error ?? 'speech-error';
            listening.value = false;
        };

        recognition.onend = () => {
            listening.value = false;
        };

        recognition.start();
        listening.value = true;
    };

    const toggle = (): void => {
        if (listening.value) {
            stop();
        } else {
            start();
        }
    };

    return { supported, listening, error, start, stop, toggle };
}
