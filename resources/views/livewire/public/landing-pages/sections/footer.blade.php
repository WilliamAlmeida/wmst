{{-- Footer Section: Rodapé com links --}}
<footer class="w-full pt-16 pb-6 px-4 relative overflow-hidden bg-gray-900 text-white" id="footer">
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-8">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-4 text-2xl font-semibold">
                    <span>Chat</span>
                    <img src="{{ asset('https://api.chat.ibox.delivery/public/logotipos/android-chrome-192x192.png') }}" alt="Chat iBox Logo" class="h-10">
                </div>
                <div class="text-gray-400 text-base mb-6 max-w-md">Transforme seu atendimento com Inteligência Artificial. Escale suas vendas e suporte sem aumentar a equipe.</div>
                <div class="flex gap-6">
                    <a href="https://instagram.com/iboxdelivery/" class="group flex items-center gap-2 text-gray-400 hover:text-white transition-all duration-300">
                        <div class="w-10 h-10 flex items-center justify-center bg-gray-800 border border-gray-700 rounded-lg group-hover:border-blue-500 group-hover:bg-blue-600 transition-all duration-300">
                            <span class="sr-only">Instagram</span>
                            <x-icon name="phosphor.instagram-logo" class="w-5 h-5" />
                        </div>
                    </a>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-12 md:gap-16">
                <div>
                    <div class="font-bold text-white text-lg mb-4">Produto</div>
                    <ul class="text-gray-400 space-y-3">
                        <li><a href="#inicio" class="hover:text-blue-400 transition-colors duration-300 hover:translate-x-1 inline-block">Início</a></li>
                        <li><a href="#como-usar" class="hover:text-blue-400 transition-colors duration-300 hover:translate-x-1 inline-block">Como funciona</a></li>
                        <li><a href="#faq" class="hover:text-blue-400 transition-colors duration-300 hover:translate-x-1 inline-block">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <div class="font-bold text-white text-lg mb-4">Legal</div>
                    <ul class="text-gray-400 space-y-3">
                        <li><a href="{{ route('privacy-policy') }}" class="hover:text-blue-400 transition-colors duration-300 hover:translate-x-1 inline-block">Privacidade</a></li>
                        <li><a href="{{ route('terms-use') }}" class="hover:text-blue-400 transition-colors duration-300 hover:translate-x-1 inline-block">Termos</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 pt-6 text-center text-gray-500 text-sm">
            <p class="mb-2">© 2025 Chat iBox. Todos os direitos reservados.</p>
        </div>
    </div>
</footer>