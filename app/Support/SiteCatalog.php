<?php

namespace App\Support;

class SiteCatalog
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function products(string $locale = 'pt_BR'): array
    {
        $whatsappBase = 'https://wa.me/5512982184879?text=';

        return [
            [
                'slug' => 'agenda-clinic',
                'name' => 'Agenda Clinic',
                'badge' => 'Health-Tech',
                'tagline' => self::pick($locale, [
                    'pt_BR' => 'Gestao completa para clinicas e operacao de atendimento',
                    'es' => 'Gestion completa para clinicas y operacion de atencion',
                    'en' => 'Complete management for clinics and care operations',
                ]),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Sistema completo de agendamento com IA, lembretes automaticos via WhatsApp, prontuario eletronico e gestao financeira pensado para clinicas modernas.',
                    'es' => 'Sistema completo de agendamiento con IA, recordatorios automaticos por WhatsApp, historial clinico y gestion financiera para clinicas modernas.',
                    'en' => 'Full scheduling system with AI, WhatsApp automated reminders, electronic medical records and financial management for modern clinics.',
                ]),
                'logo' => '/images/logotipo-agendaclinic.png',
                'cover' => '/images/logotipo-agendaclinic.png',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, quero conhecer o Agenda Clinic!'),
                'features' => [
                    [
                        'icon' => 'CalendarDays',
                        'title' => self::pick($locale, ['pt_BR' => 'Agenda inteligente com IA', 'es' => 'Agenda inteligente con IA', 'en' => 'AI-powered scheduling']),
                        'description' => self::pick($locale, ['pt_BR' => 'Confirmacoes automaticas, encaixes e priorizacao baseada em historico do paciente.', 'es' => 'Confirmaciones automaticas, encajes y priorizacion basada en historial del paciente.', 'en' => 'Auto confirmations, slot juggling and priority based on patient history.']),
                    ],
                    [
                        'icon' => 'MessageCircle',
                        'title' => self::pick($locale, ['pt_BR' => 'Lembretes via WhatsApp', 'es' => 'Recordatorios por WhatsApp', 'en' => 'WhatsApp reminders']),
                        'description' => self::pick($locale, ['pt_BR' => 'Reduza no-show com sequencias automatizadas e respostas inteligentes.', 'es' => 'Reduce ausencias con secuencias automatizadas y respuestas inteligentes.', 'en' => 'Cut no-shows with automated sequences and smart replies.']),
                    ],
                    [
                        'icon' => 'FileText',
                        'title' => self::pick($locale, ['pt_BR' => 'Prontuario eletronico', 'es' => 'Historial clinico', 'en' => 'Electronic medical records']),
                        'description' => self::pick($locale, ['pt_BR' => 'Anamnese, evolucao, anexos e assinatura digital tudo em um so lugar.', 'es' => 'Anamnesis, evolucion, anexos y firma digital en un solo lugar.', 'en' => 'Anamnesis, evolution, attachments and digital signature in one place.']),
                    ],
                    [
                        'icon' => 'Wallet',
                        'title' => self::pick($locale, ['pt_BR' => 'Financeiro integrado', 'es' => 'Finanzas integradas', 'en' => 'Integrated finance']),
                        'description' => self::pick($locale, ['pt_BR' => 'Faturamento, conciliacao bancaria e relatorios de DRE em tempo real.', 'es' => 'Facturacion, conciliacion bancaria y reportes DRE en tiempo real.', 'en' => 'Billing, bank reconciliation and live P&L reports.']),
                    ],
                ],
                'metrics' => [
                    ['label' => self::pick($locale, ['pt_BR' => 'Reducao de no-show', 'es' => 'Reduccion de no-show', 'en' => 'No-show reduction']), 'value' => '78%'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Tempo medio de agendamento', 'es' => 'Tiempo medio de agendamiento', 'en' => 'Average booking time']), 'value' => '<2min'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Clinicas atendidas', 'es' => 'Clinicas atendidas', 'en' => 'Clinics served']), 'value' => '120+'],
                ],
                'tech' => ['Laravel', 'Vue', 'WhatsApp API', 'OpenAI'],
            ],
            [
                'slug' => 'ibox-delivery',
                'name' => 'IBOX Delivery',
                'badge' => 'Food-Tech',
                'tagline' => self::pick($locale, [
                    'pt_BR' => 'Pedidos, entrega e retencao para restaurantes',
                    'es' => 'Pedidos, entrega y retencion para restaurantes',
                    'en' => 'Orders, delivery and retention for restaurants',
                ]),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Plataforma white-label de delivery: cardapio digital, app de motoboy, painel de gestao, fidelidade e integracao com WhatsApp.',
                    'es' => 'Plataforma white-label de delivery: menu digital, app de mensajero, panel de gestion, fidelidad e integracion con WhatsApp.',
                    'en' => 'White-label delivery platform: digital menu, courier app, management panel, loyalty and WhatsApp integration.',
                ]),
                'logo' => '/images/logotipo-iboxdelivery.jpg',
                'cover' => '/images/logotipo-iboxdelivery.jpg',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, quero conhecer o IBOX Delivery!'),
                'features' => [
                    ['icon' => 'Smartphone', 'title' => self::pick($locale, ['pt_BR' => 'Cardapio digital responsivo', 'es' => 'Menu digital responsivo', 'en' => 'Responsive digital menu']), 'description' => self::pick($locale, ['pt_BR' => 'Personalizacao total de marca, fotos, modificadores e combos.', 'es' => 'Personalizacion total de marca, fotos, modificadores y combos.', 'en' => 'Full brand customization, photos, modifiers and combos.'])],
                    ['icon' => 'Bike', 'title' => self::pick($locale, ['pt_BR' => 'App nativo de motoboy', 'es' => 'App nativa de mensajero', 'en' => 'Native courier app']), 'description' => self::pick($locale, ['pt_BR' => 'Roteirizacao otimizada, entrega assinada e tracking em tempo real.', 'es' => 'Ruteo optimizado, entrega firmada y tracking en tiempo real.', 'en' => 'Optimized routing, signed delivery and live tracking.'])],
                    ['icon' => 'CreditCard', 'title' => self::pick($locale, ['pt_BR' => 'Pagamentos integrados', 'es' => 'Pagos integrados', 'en' => 'Integrated payments']), 'description' => self::pick($locale, ['pt_BR' => 'Pix, cartao e voucher com antifraude e conciliacao automatica.', 'es' => 'Pix, tarjeta y voucher con antifraude y conciliacion automatica.', 'en' => 'Pix, card and voucher with anti-fraud and auto reconciliation.'])],
                    ['icon' => 'Heart', 'title' => self::pick($locale, ['pt_BR' => 'Programa de fidelidade', 'es' => 'Programa de fidelidad', 'en' => 'Loyalty program']), 'description' => self::pick($locale, ['pt_BR' => 'Cashback, cupons segmentados e campanhas via WhatsApp e push.', 'es' => 'Cashback, cupones segmentados y campanas por WhatsApp y push.', 'en' => 'Cashback, segmented coupons and WhatsApp/push campaigns.'])],
                ],
                'metrics' => [
                    ['label' => self::pick($locale, ['pt_BR' => 'Aumento medio de ticket', 'es' => 'Aumento medio de ticket', 'en' => 'Average ticket lift']), 'value' => '+32%'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Tempo medio de entrega', 'es' => 'Tiempo medio de entrega', 'en' => 'Average delivery time']), 'value' => '28min'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Restaurantes ativos', 'es' => 'Restaurantes activos', 'en' => 'Active restaurants']), 'value' => '80+'],
                ],
                'tech' => ['Laravel', 'Vue', 'Flutter', 'Pix', 'Mapbox'],
            ],
            [
                'slug' => 'conecta',
                'name' => 'Conecta',
                'badge' => 'Marketing-Tech',
                'tagline' => self::pick($locale, [
                    'pt_BR' => 'Automacao de marketing, CRM e social selling em um so lugar',
                    'es' => 'Automatizacion de marketing, CRM y social selling en un solo lugar',
                    'en' => 'Marketing automation, CRM and social selling in one place',
                ]),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Centralize Instagram, WhatsApp e e-mail em um unico CRM com automacoes de IA para qualificar leads, nutrir e vender mais com menos esforco.',
                    'es' => 'Centraliza Instagram, WhatsApp y email en un unico CRM con automatizaciones de IA para calificar leads, nutrir y vender mas con menos esfuerzo.',
                    'en' => 'Centralize Instagram, WhatsApp and email in one CRM with AI automations to qualify leads, nurture and sell more with less effort.',
                ]),
                'logo' => '/images/logotipo-conecta.png',
                'cover' => '/images/logotipo-conecta.png',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, quero conhecer o Conecta!'),
                'features' => [
                    ['icon' => 'Inbox', 'title' => self::pick($locale, ['pt_BR' => 'Inbox unificado multicanal', 'es' => 'Inbox unificado multicanal', 'en' => 'Unified multichannel inbox']), 'description' => self::pick($locale, ['pt_BR' => 'Atenda WhatsApp, Instagram, e-mail e webchat em uma unica caixa.', 'es' => 'Atiende WhatsApp, Instagram, email y webchat en una sola bandeja.', 'en' => 'Handle WhatsApp, Instagram, email and webchat in one inbox.'])],
                    ['icon' => 'Workflow', 'title' => self::pick($locale, ['pt_BR' => 'Funis automaticos com IA', 'es' => 'Funnels automaticos con IA', 'en' => 'AI-powered funnels']), 'description' => self::pick($locale, ['pt_BR' => 'Qualificacao, agendamento e follow-up disparados automaticamente.', 'es' => 'Calificacion, agendamiento y follow-up disparados automaticamente.', 'en' => 'Qualification, scheduling and follow-up triggered automatically.'])],
                    ['icon' => 'Megaphone', 'title' => self::pick($locale, ['pt_BR' => 'Disparos em massa segmentados', 'es' => 'Envios masivos segmentados', 'en' => 'Segmented mass campaigns']), 'description' => self::pick($locale, ['pt_BR' => 'Templates aprovados, agendamento e A/B test sem complicacao.', 'es' => 'Plantillas aprobadas, agendamiento y A/B test sin complicaciones.', 'en' => 'Approved templates, scheduling and easy A/B testing.'])],
                    ['icon' => 'BarChart3', 'title' => self::pick($locale, ['pt_BR' => 'Relatorios em tempo real', 'es' => 'Reportes en tiempo real', 'en' => 'Real-time reports']), 'description' => self::pick($locale, ['pt_BR' => 'Acompanhe SLA, conversao por canal e ROI por campanha.', 'es' => 'Sigue SLA, conversion por canal y ROI por campana.', 'en' => 'Track SLA, conversion per channel and ROI per campaign.'])],
                ],
                'metrics' => [
                    ['label' => self::pick($locale, ['pt_BR' => 'Conversao media', 'es' => 'Conversion media', 'en' => 'Average conversion']), 'value' => '+45%'],
                    ['label' => self::pick($locale, ['pt_BR' => 'SLA de primeira resposta', 'es' => 'SLA primera respuesta', 'en' => 'First response SLA']), 'value' => '<60s'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Empresas usando', 'es' => 'Empresas usando', 'en' => 'Companies onboard']), 'value' => '60+'],
                ],
                'tech' => ['Laravel', 'Vue', 'WhatsApp API', 'Instagram API', 'OpenAI'],
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function services(string $locale = 'pt_BR'): array
    {
        $whatsappBase = 'https://wa.me/5512982184879?text=';

        return [
            [
                'slug' => 'automacao-whatsapp',
                'icon' => 'MessageCircle',
                'title' => self::pick($locale, ['pt_BR' => 'Automacao WhatsApp Business', 'es' => 'Automatizacion WhatsApp Business', 'en' => 'WhatsApp Business automation']),
                'highlight' => self::pick($locale, ['pt_BR' => '300% mais conversoes', 'es' => '300% mas conversiones', 'en' => '300% more conversions']),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Atendimento 24/7, qualificacao de leads e vendas automatizadas com IA via WhatsApp Business API.',
                    'es' => 'Atencion 24/7, calificacion de leads y ventas automatizadas con IA via WhatsApp Business API.',
                    'en' => '24/7 support, lead qualification and AI-powered sales via WhatsApp Business API.',
                ]),
                'badge' => 'WhatsApp API + IA + N8N',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, gostaria de saber mais sobre a Automação WhatsApp Business'),
                'deliverables' => [
                    self::pick($locale, ['pt_BR' => 'Setup e verificacao da Cloud API', 'es' => 'Setup y verificacion de Cloud API', 'en' => 'Cloud API setup and verification']),
                    self::pick($locale, ['pt_BR' => 'Chatbot com IA conectado ao seu CRM', 'es' => 'Chatbot con IA conectado a tu CRM', 'en' => 'AI chatbot connected to your CRM']),
                    self::pick($locale, ['pt_BR' => 'Funis de qualificacao e vendas', 'es' => 'Funnels de calificacion y ventas', 'en' => 'Qualification and sales funnels']),
                    self::pick($locale, ['pt_BR' => 'Painel de metricas em tempo real', 'es' => 'Panel de metricas en tiempo real', 'en' => 'Real-time metrics dashboard']),
                ],
                'process' => self::defaultProcess($locale),
            ],
            [
                'slug' => 'automacao-instagram',
                'icon' => 'Instagram',
                'title' => self::pick($locale, ['pt_BR' => 'Automacao Instagram', 'es' => 'Automatizacion Instagram', 'en' => 'Instagram automation']),
                'highlight' => self::pick($locale, ['pt_BR' => '500% mais leads qualificados', 'es' => '500% mas leads calificados', 'en' => '500% more qualified leads']),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Captura de leads, respostas inteligentes em comentarios e DMs, engajamento e venda direto pelo Instagram.',
                    'es' => 'Captura de leads, respuestas inteligentes en comentarios y DMs, engagement y venta directo por Instagram.',
                    'en' => 'Lead capture, smart replies on comments and DMs, engagement and selling on Instagram.',
                ]),
                'badge' => 'Instagram API + IA',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, gostaria de saber mais sobre a Automação Instagram'),
                'deliverables' => [
                    self::pick($locale, ['pt_BR' => 'Auto-resposta em comentarios e DMs', 'es' => 'Auto-respuesta en comentarios y DMs', 'en' => 'Auto-reply for comments and DMs']),
                    self::pick($locale, ['pt_BR' => 'Captura e envio de leads para CRM', 'es' => 'Captura y envio de leads al CRM', 'en' => 'Capture and send leads to CRM']),
                    self::pick($locale, ['pt_BR' => 'Sequencias de nutricao automatica', 'es' => 'Secuencias de nutricion automatica', 'en' => 'Automatic nurture sequences']),
                    self::pick($locale, ['pt_BR' => 'Relatorios de engajamento por post', 'es' => 'Reportes de engagement por post', 'en' => 'Per-post engagement reports']),
                ],
                'process' => self::defaultProcess($locale),
            ],
            [
                'slug' => 'automacao-processos',
                'icon' => 'Workflow',
                'title' => self::pick($locale, ['pt_BR' => 'Automacao de Processos Internos', 'es' => 'Automatizacion de procesos internos', 'en' => 'Internal process automation']),
                'highlight' => self::pick($locale, ['pt_BR' => '40h economizadas por semana', 'es' => '40h ahorradas por semana', 'en' => '40 hours saved per week']),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Aprovacoes automaticas, integracoes entre sistemas e relatorios em tempo real para times mais produtivos.',
                    'es' => 'Aprobaciones automaticas, integraciones entre sistemas y reportes en tiempo real para equipos mas productivos.',
                    'en' => 'Automated approvals, system integrations and real-time reports for more productive teams.',
                ]),
                'badge' => 'N8N + APIs + IA',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, gostaria de saber mais sobre Automação de Processos Internos'),
                'deliverables' => [
                    self::pick($locale, ['pt_BR' => 'Mapeamento de processos com IA', 'es' => 'Mapeo de procesos con IA', 'en' => 'AI-driven process mapping']),
                    self::pick($locale, ['pt_BR' => 'Workflows N8N customizados', 'es' => 'Workflows N8N personalizados', 'en' => 'Custom N8N workflows']),
                    self::pick($locale, ['pt_BR' => 'Integracao com seus sistemas via API', 'es' => 'Integracion con tus sistemas via API', 'en' => 'Integration with your systems via API']),
                    self::pick($locale, ['pt_BR' => 'Dashboards de acompanhamento', 'es' => 'Dashboards de seguimiento', 'en' => 'Tracking dashboards']),
                ],
                'process' => self::defaultProcess($locale),
            ],
            [
                'slug' => 'integracao-sistemas',
                'icon' => 'Database',
                'title' => self::pick($locale, ['pt_BR' => 'Integracao de Sistemas Legados', 'es' => 'Integracion de sistemas legados', 'en' => 'Legacy systems integration']),
                'highlight' => self::pick($locale, ['pt_BR' => 'Zero downtime garantido', 'es' => 'Zero downtime garantizado', 'en' => 'Zero downtime guaranteed']),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Conectamos sistemas antigos com novas tecnologias sem interromper operacoes, com seguranca e auditoria.',
                    'es' => 'Conectamos sistemas antiguos con nuevas tecnologias sin interrumpir operaciones, con seguridad y auditoria.',
                    'en' => 'We connect legacy systems with new technologies without interrupting operations, with security and audit.',
                ]),
                'badge' => 'Laravel + REST + Microservicos',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, gostaria de saber mais sobre Integração de Sistemas'),
                'deliverables' => [
                    self::pick($locale, ['pt_BR' => 'Discovery e arquitetura de integracao', 'es' => 'Discovery y arquitectura de integracion', 'en' => 'Integration discovery and architecture']),
                    self::pick($locale, ['pt_BR' => 'API gateway e autenticacao centralizada', 'es' => 'API gateway y autenticacion centralizada', 'en' => 'API gateway and centralized auth']),
                    self::pick($locale, ['pt_BR' => 'Pipelines ETL e sincronizacao', 'es' => 'Pipelines ETL y sincronizacion', 'en' => 'ETL pipelines and sync']),
                    self::pick($locale, ['pt_BR' => 'Monitoramento e observabilidade', 'es' => 'Monitoreo y observabilidad', 'en' => 'Monitoring and observability']),
                ],
                'process' => self::defaultProcess($locale),
            ],
            [
                'slug' => 'ia-analise-preditiva',
                'icon' => 'Brain',
                'title' => self::pick($locale, ['pt_BR' => 'IA para Analise Preditiva', 'es' => 'IA para analisis predictivo', 'en' => 'AI for predictive analytics']),
                'highlight' => self::pick($locale, ['pt_BR' => '85% precisao em previsoes', 'es' => '85% precision en pronosticos', 'en' => '85% forecast accuracy']),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Insights automaticos, previsoes precisas e decisoes baseadas em dados com modelos de IA proprios e LLMs.',
                    'es' => 'Insights automaticos, pronosticos precisos y decisiones basadas en datos con modelos de IA propios y LLMs.',
                    'en' => 'Automated insights, accurate forecasts and data-driven decisions with custom AI models and LLMs.',
                ]),
                'badge' => 'OpenAI / Claude / Gemini',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, gostaria de saber mais sobre IA para Análise Preditiva'),
                'deliverables' => [
                    self::pick($locale, ['pt_BR' => 'Coleta e limpeza de dados', 'es' => 'Recoleccion y limpieza de datos', 'en' => 'Data collection and cleaning']),
                    self::pick($locale, ['pt_BR' => 'Modelos preditivos customizados', 'es' => 'Modelos predictivos personalizados', 'en' => 'Custom predictive models']),
                    self::pick($locale, ['pt_BR' => 'Dashboards interativos', 'es' => 'Dashboards interactivos', 'en' => 'Interactive dashboards']),
                    self::pick($locale, ['pt_BR' => 'Alertas e recomendacoes via IA', 'es' => 'Alertas y recomendaciones via IA', 'en' => 'AI-powered alerts and recommendations']),
                ],
                'process' => self::defaultProcess($locale),
            ],
            [
                'slug' => 'sistemas-sob-medida',
                'icon' => 'Globe',
                'title' => self::pick($locale, ['pt_BR' => 'Sistemas Web Sob Medida', 'es' => 'Sistemas web a medida', 'en' => 'Custom web systems']),
                'highlight' => self::pick($locale, ['pt_BR' => '100% adequacao ao negocio', 'es' => '100% adecuacion al negocio', 'en' => '100% business fit']),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Plataformas customizadas, dashboards executivos e portais de cliente completos, performaticos e escalaveis.',
                    'es' => 'Plataformas personalizadas, dashboards ejecutivos y portales de cliente completos, performantes y escalables.',
                    'en' => 'Custom platforms, executive dashboards and complete client portals, performant and scalable.',
                ]),
                'badge' => 'Laravel + Inertia + Vue',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, gostaria de saber mais sobre Sistemas Web Sob Medida'),
                'deliverables' => [
                    self::pick($locale, ['pt_BR' => 'Discovery de produto e UX', 'es' => 'Discovery de producto y UX', 'en' => 'Product discovery and UX']),
                    self::pick($locale, ['pt_BR' => 'Desenvolvimento agil em sprints', 'es' => 'Desarrollo agil en sprints', 'en' => 'Agile development in sprints']),
                    self::pick($locale, ['pt_BR' => 'Deploy automatizado e CI/CD', 'es' => 'Deploy automatizado y CI/CD', 'en' => 'Automated deploy and CI/CD']),
                    self::pick($locale, ['pt_BR' => 'Suporte e evolucao continua', 'es' => 'Soporte y evolucion continua', 'en' => 'Ongoing support and evolution']),
                ],
                'process' => self::defaultProcess($locale),
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>|null
     */
    public static function findProduct(string $slug, string $locale = 'pt_BR'): ?array
    {
        foreach (self::products($locale) as $product) {
            if ($product['slug'] === $slug) {
                return $product;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findService(string $slug, string $locale = 'pt_BR'): ?array
    {
        foreach (self::services($locale) as $service) {
            if ($service['slug'] === $slug) {
                return $service;
            }
        }

        return null;
    }

    /**
     * @return array<int, array<string, string>>
     */
    private static function defaultProcess(string $locale): array
    {
        return [
            ['title' => self::pick($locale, ['pt_BR' => 'Diagnostico', 'es' => 'Diagnostico', 'en' => 'Discovery']), 'description' => self::pick($locale, ['pt_BR' => 'Mapeamos seus processos atuais e oportunidades de automacao.', 'es' => 'Mapeamos tus procesos actuales y oportunidades de automatizacion.', 'en' => 'We map your current processes and automation opportunities.'])],
            ['title' => self::pick($locale, ['pt_BR' => 'Proposta', 'es' => 'Propuesta', 'en' => 'Proposal']), 'description' => self::pick($locale, ['pt_BR' => 'Escopo, cronograma e ROI esperado em ate 48h.', 'es' => 'Alcance, cronograma y ROI esperado en hasta 48h.', 'en' => 'Scope, timeline and expected ROI within 48h.'])],
            ['title' => self::pick($locale, ['pt_BR' => 'Implementacao', 'es' => 'Implementacion', 'en' => 'Implementation']), 'description' => self::pick($locale, ['pt_BR' => 'Sprints semanais com entregas continuas e validacao.', 'es' => 'Sprints semanales con entregas continuas y validacion.', 'en' => 'Weekly sprints with continuous delivery and validation.'])],
            ['title' => self::pick($locale, ['pt_BR' => 'Operacao', 'es' => 'Operacion', 'en' => 'Operation']), 'description' => self::pick($locale, ['pt_BR' => 'Suporte, monitoramento e melhorias continuas pos go-live.', 'es' => 'Soporte, monitoreo y mejoras continuas post go-live.', 'en' => 'Support, monitoring and continuous improvement after go-live.'])],
        ];
    }

    /**
     * @param  array<string, string>  $values
     */
    private static function pick(string $locale, array $values): string
    {
        return $values[$locale] ?? $values['pt_BR'];
    }
}
