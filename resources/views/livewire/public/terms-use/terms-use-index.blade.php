<div class="min-h-screen w-full bg-gray-50 p-4">
    <div class="absolute top-2 left-2">
        <x-button class="btn btn-xs" icon="o-arrow-left" :link="$back_to_url" />
    </div>

    <div class="flex flex-col gap-y-3 justify-center items-center select-none">
        <x-logotipo-wmst class="h-32 w-32" />

        <x-card class="mx-auto py-10 max-w-screen-md max-h-[80vh] shadow-md overflow-y-auto">
            <section id="termos-uso" lang="pt-BR">
                <div class="flex flex-col md:flex-row md:justify-between items-center">
                    <h1 class="text-lg font-bold">Termos de Uso – {{ config('app.name') }}</h1>
                    <p class="text-sm">Última atualização: 27/08/2025</p>
                </div>

                <h2 class="mt-2 font-medium">1. Aceitação dos Termos</h2>
                <p>
                    Ao acessar e utilizar a plataforma <strong>{{ config('app.name') }}</strong> 
                    (<a href="{{ url('/') }}" rel="noopener">{{ url('/') }}</a>), você concorda com os presentes Termos de Uso e com a
                    <a href="{{ route('privacy-policy') }}">Política de Privacidade</a>. Caso não concorde, não utilize nossos serviços.
                </p>

                <h2 class="mt-2 font-medium">2. Objeto</h2>
                <p>
                    Os presentes Termos regulam o uso da plataforma de gerenciamento de agendamentos, comunicação com pacientes e demais funcionalidades disponibilizadas pela {{ config('app.name') }}.
                </p>

                <h2 class="mt-2 font-medium">3. Cadastro e Conta de Usuário</h2>
                <ul>
                    <li>O usuário deve fornecer informações verdadeiras, completas e atualizadas.</li>
                    <li>O usuário é responsável por manter a confidencialidade de suas credenciais de acesso.</li>
                    <li>O uso da conta é pessoal e intransferível, sendo vedado o compartilhamento.</li>
                </ul>

                <h2 class="mt-2 font-medium">4. Obrigações do Usuário</h2>
                <ul>
                    <li>Utilizar a plataforma de forma ética e em conformidade com a legislação vigente.</li>
                    <li>Não realizar práticas ilícitas, como fraudes, invasões ou disseminação de malware.</li>
                    <li>Não utilizar os serviços para fins discriminatórios, abusivos ou que violem direitos de terceiros.</li>
                </ul>

                <h2 class="mt-2 font-medium">5. Responsabilidades da {{ config('app.name') }}</h2>
                <p>
                    A {{ config('app.name') }} compromete-se a manter a disponibilidade da plataforma, salvo em situações de manutenção, caso fortuito ou força maior. 
                    Não nos responsabilizamos por indisponibilidades ou falhas decorrentes de terceiros, conexões de internet ou equipamentos do usuário.
                </p>

                <h2 class="mt-2 font-medium">6. Serviços de Terceiros</h2>
                <p>
                    A plataforma pode integrar serviços de terceiros (ex.: provedores de pagamento, envio de mensagens). O uso desses serviços pode estar sujeito a termos e políticas próprias desses terceiros.
                </p>

                <h2 class="mt-2 font-medium">7. Limitação de Responsabilidade</h2>
                <p>
                    A {{ config('app.name') }} não se responsabiliza por danos indiretos, lucros cessantes ou prejuízos decorrentes do uso ou da impossibilidade de uso da plataforma, exceto nos casos previstos em lei.
                </p>

                <h2 class="mt-2 font-medium">8. Propriedade Intelectual</h2>
                <p>
                    Todo o conteúdo disponibilizado pela {{ config('app.name') }}, incluindo logotipos, marcas, interfaces e funcionalidades, é protegido por direitos de propriedade intelectual, sendo vedada a reprodução sem autorização expressa.
                </p>

                <h2 class="mt-2 font-medium">9. Rescisão e Suspensão</h2>
                <p>
                    A {{ config('app.name') }} poderá suspender ou encerrar o acesso do usuário em caso de violação destes Termos, uso indevido da plataforma ou descumprimento de obrigações legais.
                </p>

                <h2 class="mt-2 font-medium">10. Alterações dos Termos</h2>
                <p>
                    Estes Termos podem ser atualizados a qualquer momento. A versão vigente estará disponível neste endereço, com indicação da data da última atualização. Mudanças relevantes poderão ser comunicadas por avisos no sistema ou por e-mail.
                </p>

                <h2 class="mt-2 font-medium">11. Legislação e Foro</h2>
                <p>
                    Estes Termos são regidos pelas leis brasileiras. Eventuais controvérsias serão dirimidas no foro da comarca da sede da {{ config('app.name') }}, salvo disposição legal em contrário.
                </p>

                <h2 class="mt-2 font-medium">12. Contato</h2>
                <p>
                    Para dúvidas ou solicitações relacionadas a estes Termos, entre em contato: 
                    <a href="mailto:contato@agendaclinic.com">contato@agendaclinic.com</a>.
                </p>
            </section>
        </x-card>
    </div>
</div>