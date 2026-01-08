{{-- Plans Section: Tabela de planos --}}
<div class="w-full py-20 px-4 relative overflow-hidden" id="planos">
    {{-- Background Effects --}}
    <div class="absolute top-1/3 left-1/4 w-96 h-96 bg-red-600/10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-1/3 right-1/4 w-80 h-80 bg-yellow-600/10 rounded-full blur-[100px]"></div>
    
    <div class="max-w-6xl mx-auto text-center relative z-10">
        <div class="text-yellow-500 font-bold text-sm mb-3 tracking-wider">• SELECIONE O PLANO QUE MELHOR ATENDE ÀS SUAS NECESSIDADES •</div>
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-16">Escolha seu <span class="bg-gradient-to-r from-red-600 to-yellow-500 bg-clip-text text-transparent">Plano</span></h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Plano Plus --}}
            <div class="group bg-gradient-to-br from-zinc-900/80 via-black to-zinc-900/70 backdrop-blur-sm border border-red-600/30 rounded-3xl p-8 flex flex-col items-center hover:border-red-600/60 hover:shadow-[0_0_50px_rgba(220,38,38,0.3)] transition-all duration-500 hover:scale-[1.02]">
                <div class="mb-6 w-20 h-20 flex items-center justify-center bg-gradient-to-br from-zinc-800 to-zinc-900 rounded-2xl text-red-500 text-3xl font-bold shadow-[0_0_25px_rgba(220,38,38,0.3)]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div class="font-bold text-white text-2xl mb-3">PLUS</div>
                <div class="mb-2">
                    <span class="text-5xl font-black bg-gradient-to-r from-red-600 to-red-500 bg-clip-text text-transparent">R$ 197</span>
                    <span class="text-gray-400 text-lg">.00</span>
                </div>
                <div class="text-sm text-gray-400 mb-8">Pacote Mensal</div>
                <ul class="text-white text-left mb-8 space-y-3 w-full">
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>1 Instância WhatsApp</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>4 Agentes (IA/Humana)</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>CRM Integrado</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Capturar contatos</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Conversas ilimitadas</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Áudios da IA</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Enviar Arquivos Pela IA</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Agendamentos IA</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Base de Conhecimento</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Kanban Ilimitado</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Departamentos/Setores</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Disparo em massa</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Tokens GPT-4 inclusos</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Multi-usuários ilimitados</span></li>
                    <li class="flex items-start gap-2"><span class="text-red-500 mt-1">✓</span> <span>Suporte via Whatsapp</span></li>
                </ul>
                <a href="https://chat.ibox.delivery/register" class="w-full bg-transparent border-2 border-red-600 text-red-500 px-6 py-3 rounded-xl font-bold hover:bg-red-600/10 transition-all duration-300 hover:shadow-[0_0_25px_rgba(220,38,38,0.4)] text-center block">Escolher Plano</a>
            </div>

            {{-- Plano PRO (Destaque) --}}
            <div class="group bg-gradient-to-br from-red-950/80 via-black to-red-950/60 backdrop-blur-sm border-4 border-red-600 rounded-3xl p-8 flex flex-col items-center shadow-[0_0_60px_rgba(220,38,38,0.4)] hover:shadow-[0_0_80px_rgba(220,38,38,0.6)] transition-all duration-500 scale-105 hover:scale-110 relative">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-red-600 to-yellow-500 text-white text-xs font-bold px-6 py-2 rounded-full shadow-[0_0_20px_rgba(220,38,38,0.6)]">MAIS POPULAR</div>
                <div class="mb-6 w-24 h-24 flex items-center justify-center bg-gradient-to-br from-red-600 to-red-700 rounded-2xl text-white text-4xl font-bold shadow-[0_0_40px_rgba(220,38,38,0.7)]">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                </div>
                <div class="font-bold text-white text-3xl mb-3">PRO</div>
                <div class="mb-2">
                    <span class="text-6xl font-black bg-gradient-to-r from-red-500 via-red-400 to-yellow-500 bg-clip-text text-transparent drop-shadow-[0_0_30px_rgba(220,38,38,0.8)]">R$ 297</span>
                    <span class="text-gray-300 text-xl">.00</span>
                </div>
                <div class="text-sm text-gray-300 mb-8">Pacote Mensal</div>
                <ul class="text-white text-left mb-8 space-y-3 w-full">
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">2 Instâncias WhatsApp</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">8 Agentes (IA/Humana)</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">CRM Integrado</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Capturar contatos</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Conversas ilimitadas</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Áudios da IA</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Enviar Arquivos Pela IA</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Agendamentos IA</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Base de Conhecimento</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Kanban Ilimitado</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Departamentos/Setores</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Disparo em massa</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Tokens GPT-4 inclusos</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Multi-usuários ilimitados</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1 text-lg">✓</span> <span class="font-semibold">Suporte via Whatsapp</span></li>
                </ul>
                <a href="https://chat.ibox.delivery/register" class="w-full bg-gradient-to-r from-red-600 to-red-500 text-white px-6 py-4 rounded-xl font-bold text-lg shadow-[0_0_30px_rgba(220,38,38,0.6)] hover:shadow-[0_0_50px_rgba(220,38,38,0.9)] transition-all duration-300 hover:scale-105 text-center block">Escolher Plano</a>
            </div>

            {{-- Plano Executive --}}
            <div class="group bg-gradient-to-br from-zinc-900/80 via-black to-zinc-900/70 backdrop-blur-sm border border-red-600/30 rounded-3xl p-8 flex flex-col items-center hover:border-red-600/60 hover:shadow-[0_0_50px_rgba(220,38,38,0.3)] transition-all duration-500 hover:scale-[1.02]">
                <div class="mb-6 w-20 h-20 flex items-center justify-center bg-gradient-to-br from-yellow-600 to-yellow-700 rounded-2xl text-white text-3xl font-bold shadow-[0_0_25px_rgba(251,191,36,0.5)]">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div class="font-bold text-white text-2xl mb-3">EXECUTIVE</div>
                <div class="mb-2">
                    <span class="text-5xl font-black bg-gradient-to-r from-yellow-600 to-yellow-500 bg-clip-text text-transparent">R$ 497</span>
                    <span class="text-gray-400 text-lg">.00</span>
                </div>
                <div class="text-sm text-gray-400 mb-8">Pacote Mensal</div>
                <ul class="text-white text-left mb-8 space-y-3 w-full">
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>4 Instâncias WhatsApp</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>12 Agentes (IA/Humana)</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>CRM Integrado</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Capturar contatos</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Conversas ilimitadas</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Áudios da IA</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Enviar Arquivos Pela IA</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Agendamentos IA</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Base de Conhecimento</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Kanban Ilimitado</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Departamentos/Setores</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Disparo em massa</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Tokens GPT-4 inclusos</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Multi-usuários ilimitados</span></li>
                    <li class="flex items-start gap-2"><span class="text-yellow-500 mt-1">✓</span> <span>Suporte via Whatsapp</span></li>
                </ul>
                <a href="https://chat.ibox.delivery/register" class="w-full bg-gradient-to-r from-yellow-600 to-yellow-500 text-black px-6 py-3 rounded-xl font-bold hover:shadow-[0_0_30px_rgba(251,191,36,0.6)] transition-all duration-300 hover:scale-105 text-center block">Escolher Plano</a>
            </div>
        </div>
    </div>
</div>
