{{-- Hero Section: Headline principal, nome do sistema: Chat iBox --}}
@include('livewire.public.landing-pages.sections.navbar')
<div class="w-full pt-24 pb-20 px-4 md:px-0 relative overflow-hidden">
    {{-- Background Effects --}}
    <div class="absolute inset-0 bg-gradient-to-b from-red-950/20 via-transparent to-transparent"></div>
    <div class="absolute top-20 left-1/4 w-96 h-96 bg-red-600/20 rounded-full blur-[120px]"></div>
    <div class="absolute top-40 right-1/4 w-80 h-80 bg-yellow-600/10 rounded-full blur-[100px]"></div>
    
    <div class="max-w-5xl mx-auto text-center relative z-10">
        <div class="text-yellow-500 text-sm font-bold mb-4 tracking-wider animate-pulse">• LEVE O PODER DA INTELIGÊNCIA ARTIFICIAL PARA DENTRO DO SEU NEGÓCIO! •</div>
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-8 leading-tight drop-shadow-[0_0_30px_rgba(220,38,38,0.3)]">
            Imagine ter um colaborador que <span class="bg-gradient-to-r from-red-600 via-red-500 to-yellow-500 bg-clip-text text-transparent">nunca dorme</span>, <span class="bg-gradient-to-r from-red-600 via-red-500 to-yellow-500 bg-clip-text text-transparent">nunca erra</span> e custa quase nada.<br>
            Conheça seu novo <span class="bg-gradient-to-r from-red-600 to-yellow-500 bg-clip-text text-transparent drop-shadow-[0_0_20px_rgba(220,38,38,0.8)]">Agente de IA</span>.
        </h1>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="group bg-gradient-to-br from-zinc-900 to-black rounded-2xl border border-red-600/30 py-8 px-4 flex flex-col items-center hover:border-red-600 transition-all duration-300 hover:shadow-[0_0_40px_rgba(220,38,38,0.3)] hover:scale-105">
                <div class="mb-3 w-16 h-16 flex items-center justify-center bg-gradient-to-br from-red-600 to-red-700 rounded-full text-white font-bold text-xs shadow-[0_0_25px_rgba(220,38,38,0.5)] group-hover:shadow-[0_0_35px_rgba(220,38,38,0.8)] transition-all duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <div class="text-white font-bold text-lg mb-1">WhatsApp Nativo</div>
                <div class="text-sm text-gray-400">Business API Oficial</div>
            </div>
            <div class="group bg-gradient-to-br from-zinc-900 to-black rounded-2xl border border-red-600/30 py-8 px-4 flex flex-col items-center hover:border-red-600 transition-all duration-300 hover:shadow-[0_0_40px_rgba(220,38,38,0.3)] hover:scale-105">
                <div class="mb-3 w-16 h-16 flex items-center justify-center bg-gradient-to-br from-red-600 to-red-700 rounded-full text-white font-bold text-xs shadow-[0_0_25px_rgba(220,38,38,0.5)] group-hover:shadow-[0_0_35px_rgba(220,38,38,0.8)] transition-all duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div class="text-white font-bold text-lg mb-1">Google Calendar</div>
                <div class="text-sm text-gray-400">Sincronização Automática</div>
            </div>
            <div class="group bg-gradient-to-br from-zinc-900 to-black rounded-2xl border border-red-600/30 py-8 px-4 flex flex-col items-center hover:border-red-600 transition-all duration-300 hover:shadow-[0_0_40px_rgba(220,38,38,0.3)] hover:scale-105">
                <div class="mb-3 w-16 h-16 flex items-center justify-center bg-gradient-to-br from-red-600 to-red-700 rounded-full text-white font-bold text-xs shadow-[0_0_25px_rgba(220,38,38,0.5)] group-hover:shadow-[0_0_35px_rgba(220,38,38,0.8)] transition-all duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
                <div class="text-white font-bold text-lg mb-1">IA com Memória</div>
                <div class="text-sm text-gray-400">Upload de Documentos</div>
            </div>
            <div class="group bg-gradient-to-br from-zinc-900 to-black rounded-2xl border border-red-600/30 py-8 px-4 flex flex-col items-center hover:border-red-600 transition-all duration-300 hover:shadow-[0_0_40px_rgba(220,38,38,0.3)] hover:scale-105">
                <div class="mb-3 w-16 h-16 flex items-center justify-center bg-gradient-to-br from-red-600 to-red-700 rounded-full text-white font-bold text-xs shadow-[0_0_25px_rgba(220,38,38,0.5)] group-hover:shadow-[0_0_35px_rgba(220,38,38,0.8)] transition-all duration-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="text-white font-bold text-lg mb-1">Instalação Rápida</div>
                <div class="text-sm text-gray-400">Setup em 5 Minutos</div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-6 justify-center mb-12">
            <a href="https://chat.ibox.delivery/register" class="group bg-gradient-to-r from-red-600 to-red-500 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-[0_0_30px_rgba(220,38,38,0.4)] hover:shadow-[0_0_50px_rgba(220,38,38,0.7)] transition-all duration-300 hover:scale-105 flex items-center justify-center">
                Criar Meu Agente de IA
                <span class="inline-block ml-2 group-hover:translate-x-1 transition-transform">→</span>
            </a>
            <button class="group bg-transparent border-2 border-red-600 text-red-500 px-8 py-4 rounded-xl font-bold text-lg hover:bg-red-600/10 transition-all duration-300 hover:shadow-[0_0_30px_rgba(220,38,38,0.4)] hover:scale-105">
                Ver Demo em Ação
                <span class="inline-block ml-2 group-hover:scale-110 transition-transform">▶</span>
            </button>
        </div>
        <div class="flex flex-wrap gap-8 justify-center items-center mt-12 opacity-50 hover:opacity-70 transition-opacity">
            <div class="text-gray-500 text-sm font-semibold">IBM Media</div>
            <div class="text-gray-500 text-sm font-semibold">SEO Mind</div>
            <div class="text-gray-500 text-sm font-semibold">Boosterio</div>
            <div class="text-gray-500 text-sm font-semibold">Rankie SEO</div>
            <div class="text-gray-500 text-sm font-semibold">Yoday</div>
            <div class="text-gray-500 text-sm font-semibold">GREENHOST</div>
        </div>
    </div>
</div>
