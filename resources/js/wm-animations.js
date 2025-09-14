// Componente Typewriter Simples
function typewriter(text, initialSpeed = 100) {
    return {
        fullText: text,
        displayedText: '',
        currentIndex: 0,
        speed: initialSpeed,
        intervalId: null,
        isPaused: false,
        
        startTypewriter() {
            this.clearInterval();
            this.intervalId = setInterval(() => {
                if (!this.isPaused && this.currentIndex < this.fullText.length) {
                    this.displayedText += this.fullText[this.currentIndex];
                    this.currentIndex++;
                } else if (this.currentIndex >= this.fullText.length) {
                    this.clearInterval();
                }
            }, this.speed);
        },
        
        restart() {
            this.displayedText = '';
            this.currentIndex = 0;
            this.isPaused = false;
            this.startTypewriter();
        },
        
        togglePause() {
            this.isPaused = !this.isPaused;
        },
        
        updateSpeed() {
            if (this.intervalId) {
                this.clearInterval();
                this.startTypewriter();
            }
        },
        
        clearInterval() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        }
    }
}

// Componente Multi Typewriter
function multiTypewriter(phrases, speed = 100, pauseBetween = 1000) {
    return {
        phrases: phrases,
        currentPhraseIndex: 0,
        displayedText: '',
        currentIndex: 0,
        speed: speed,
        pauseBetween: pauseBetween,
        intervalId: null,
        
        start() {
            this.typeCurrentPhrase();
        },
        
        typeCurrentPhrase() {
            this.clearInterval();
            const currentPhrase = this.phrases[this.currentPhraseIndex];
            
            this.intervalId = setInterval(() => {
                if (this.currentIndex < currentPhrase.length) {
                    this.displayedText += currentPhrase[this.currentIndex];
                    this.currentIndex++;
                } else {
                    this.clearInterval();
                    setTimeout(() => {
                        this.nextPhrase();
                    }, this.pauseBetween);
                }
            }, this.speed);
        },
        
        nextPhrase() {
            this.currentPhraseIndex = (this.currentPhraseIndex + 1) % this.phrases.length;
            this.displayedText = '';
            this.currentIndex = 0;
            this.typeCurrentPhrase();
        },
        
        next() {
            this.clearInterval();
            this.nextPhrase();
        },
        
        restart() {
            this.currentPhraseIndex = 0;
            this.displayedText = '';
            this.currentIndex = 0;
            this.typeCurrentPhrase();
        },
        
        clearInterval() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        }
    }
}

// Componente Delete Typewriter
function deleteTypewriter(words, typeSpeed = 100, deleteSpeed = 50, pauseTime = 2000) {
    return {
        words: words,
        currentWordIndex: 0,
        displayedText: '',
        isDeleting: false,
        typeSpeed: typeSpeed,
        deleteSpeed: deleteSpeed,
        pauseTime: pauseTime,
        timeoutId: null,
        
        start() {
            this.type();
        },
        
        type() {
            const currentWord = this.words[this.currentWordIndex];
            const speed = this.isDeleting ? this.deleteSpeed : this.typeSpeed;
            
            if (this.isDeleting) {
                this.displayedText = currentWord.substring(0, this.displayedText.length - 1);
            } else {
                this.displayedText = currentWord.substring(0, this.displayedText.length + 1);
            }
            
            let nextSpeed = speed;
            
            if (!this.isDeleting && this.displayedText === currentWord) {
                nextSpeed = this.pauseTime;
                this.isDeleting = true;
            } else if (this.isDeleting && this.displayedText === '') {
                this.isDeleting = false;
                this.currentWordIndex = (this.currentWordIndex + 1) % this.words.length;
                nextSpeed = this.typeSpeed;
            }
            
            this.timeoutId = setTimeout(() => this.type(), nextSpeed);
        },
        
        restart() {
            if (this.timeoutId) {
                clearTimeout(this.timeoutId);
            }
            this.currentWordIndex = 0;
            this.displayedText = '';
            this.isDeleting = false;
            this.start();
        }
    }
}

// Make typewriter functions available globally for Alpine
window.typewriter = typewriter;
window.multiTypewriter = multiTypewriter;
window.deleteTypewriter = deleteTypewriter;