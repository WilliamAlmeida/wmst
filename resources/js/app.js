import './bootstrap';
// import './wm-animations';

// import Alpine from 'alpinejs';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import intersect from '@alpinejs/intersect';

// window.Alpine = Alpine;
Alpine.plugin(intersect);
// Alpine.start();

document.addEventListener('livewire:init', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Ajuste para o menu fixo
                    behavior: 'smooth'
                });
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const el = document.querySelector('.hero-typed-text');
    if (!el) return;

    const phrases = [
        'Automações Inteligentes que Revolucionam seu Negócio',
        'Soluções Digitais que Revolucionam seu Negócio',
        'Sistemas Personalizados que Revolucionam seu Negócio'
    ];
    let currentPhrase = 0;
    let charIndex = 0;
    let isDeleting = false;
    let isPaused = false;
    const typeSpeed = 50;
    const deleteSpeed = 50;
    const pauseDuration = 3000;
    let typedText = '';

    function formattedText(text) {
        const word = 'Revolucionam';
        const index = text.indexOf(word);
        if (index !== -1 && index + word.length <= text.length) {
            return (
                text.substring(0, index) +
                '<span class="gradient-text">' +
                word +
                '</span>' +
                text.substring(index + word.length)
            );
        }
        return text;
    }

    function typeNextChar() {
        const phrase = phrases[currentPhrase];

        if (isDeleting) {
            typedText = phrase.substring(0, charIndex - 1);
            charIndex--;
        } else {
            typedText = phrase.substring(0, charIndex + 1);
            charIndex++;
        }

        el.innerHTML = formattedText(typedText);

        if (!isDeleting && charIndex === phrase.length) {
            isPaused = true;
            setTimeout(() => {
                isPaused = false;
                isDeleting = true;
                typeNextChar();
            }, pauseDuration);
            return;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            currentPhrase = (currentPhrase + 1) % phrases.length;
        }

        const speed = isDeleting ? deleteSpeed : typeSpeed;
        if (!isPaused) {
            setTimeout(typeNextChar, speed);
        }
    }

    typeNextChar();
});

Livewire.start();