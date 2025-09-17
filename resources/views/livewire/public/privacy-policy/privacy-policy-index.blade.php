<div class="min-h-screen w-full bg-gray-50 p-4">
    <div class="absolute top-2 left-2">
        <x-button class="btn btn-xs" icon="o-arrow-left" :link="$back_to_url" />
    </div>

    <div class="flex flex-col gap-y-3 justify-center items-center select-none">
        <x-logotipo-wmst class="h-32 w-32" />

        <x-card class="mx-auto py-10 max-w-screen-md max-h-[80vh] shadow-md overflow-y-auto">
            <section id="politica-privacidade" lang="pt-BR">
                <div class="flex flex-col md:flex-row md:justify-between items-center">
                    <h1 class="text-lg font-bold">Política de Privacidade – {{ config('app.name') }}</h1>
                    <p class="text-sm">Última atualização: 27/08/2025</p>
                </div>

                <h2 class="mt-2 font-medium">1. Controlador de Dados</h2>
                <p>
                    A <strong>{{ config('app.name') }}</strong> (<a href="{{ route('/') }}" rel="noopener">{{ url('/') }}</a>) é a controladora dos dados coletados por meio de sua plataforma de gerenciamento de agendamentos e comunicação com pacientes.
                </p>

                <h2 class="mt-2 font-medium">2. Tipos de Dados Coletados e Finalidades</h2>
                <ul>
                    <li><strong>Identificação e contato:</strong> nome, e-mail, telefone — para cadastro, autenticação, notificações e lembretes.</li>
                    <li><strong>Dados sensíveis (saúde):</strong> quando aplicável, mediante consentimento explícito, para operações como lembretes de consultas/exames.</li>
                    <li><strong>Navegação e uso:</strong> endereço IP, registros de acesso e preferências — para segurança, métricas e melhoria da experiência.</li>
                </ul>

                <h2 class="mt-2 font-medium">3. Bases Legais para o Tratamento</h2>
                <p>
                    O tratamento é realizado com fundamento em: <em>execução de contrato</em>, <em>legítimo interesse</em>, <em>cumprimento de obrigação legal</em> e, quando exigido, <em>consentimento explícito</em> (especialmente para dados sensíveis).
                </p>

                <h2 class="mt-2 font-medium">4. Direitos dos Titulares</h2>
                <p>
                    Você pode solicitar: confirmação de tratamento; acesso; correção; anonimização; bloqueio ou eliminação; portabilidade; informações sobre compartilhamento; e revogação do consentimento. Para exercer, use o contato indicado ao final.
                </p>

                <h2 class="mt-2 font-medium">5. Compartilhamento de Dados</h2>
                <p>
                    Compartilhamos dados com prestadores de serviços que suportam a operação (por exemplo, provedores de hospedagem e envio de mensagens), sempre sob instruções da {{ config('app.name') }} e com medidas de proteção. Poderá haver compartilhamento para atender a obrigação legal ou ordem de autoridade competente.
                </p>

                <h2 class="mt-2 font-medium">6. Retenção dos Dados</h2>
                <p>
                    Mantemos os dados pelo tempo necessário às finalidades descritas ou conforme prazos legais/regulatórios aplicáveis. Após esse período, realizamos a eliminação segura ou anonimização.
                </p>

                <h2 class="mt-2 font-medium">7. Segurança da Informação</h2>
                <p>
                    Adotamos medidas técnicas e organizacionais, como controle de acesso, criptografia em trânsito e repouso (quando aplicável), registro de eventos e políticas internas, visando proteger os dados contra acessos não autorizados, perda, alteração ou destruição.
                </p>

                <h2 class="mt-2 font-medium">8. Cookies e Tecnologias Similares</h2>
                <p>
                    Utilizamos cookies funcionais e de medição agregada para autenticação, preferências e estatísticas. Você pode gerenciar cookies nas configurações do navegador; a desativação pode afetar funcionalidades.
                </p>

                <h2 class="mt-2 font-medium">9. Comunicações e Opt-out</h2>
                <p>
                    Enviamos comunicações operacionais e, quando aplicável, mensagens de marketing. Você pode cancelar o recebimento a qualquer momento por meio do link de descadastramento presente no rodapé dos e-mails.
                </p>

                <h2 class="mt-2 font-medium">10. Transferências Internacionais</h2>
                <p>
                    Caso haja transferências internacionais de dados (por exemplo, uso de provedores em outros países), adotamos salvaguardas compatíveis com a legislação aplicável.
                </p>

                <h2 class="mt-2 font-medium">11. Atualizações desta Política</h2>
                <p>
                    Esta política pode ser atualizada para refletir mudanças legais ou operacionais. A versão vigente estará disponível neste endereço, com indicação da data de atualização. Mudanças relevantes poderão ser comunicadas por avisos no sistema ou por e-mail.
                </p>

                <h2 class="mt-2 font-medium">12. Contato do Controlador</h2>
                <p>
                    Dúvidas, solicitações ou exercício de direitos: <a href="mailto:contato@agendaclinic.com">contato@agendaclinic.com</a>.
                </p>
            </section>
        </x-card>
    </div>
</div>