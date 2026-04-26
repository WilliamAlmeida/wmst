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
                'website' => 'https://agendaclinic.com',
                'tagline' => self::pick($locale, [
                    'pt_BR' => 'A plataforma completa para gestao de clinicas e profissionais de saúde com IA',
                    'es' => 'La plataforma completa para gestion de clinicas y profesionales de salud con IA',
                    'en' => 'The complete platform for clinic and healthcare professional management with AI',
                ]),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Sistema #1 em agendamento médico do Brasil. Atendimento automatico 24/7 via WhatsApp e Instagram com agentes de IA, calendario inteligente, CRM, prontuario, financeiro e API REST. Mais de 5.000 profissionais ja confiam.',
                    'es' => 'Sistema #1 en agendamiento médico de Brasil. Atencion automatica 24/7 via WhatsApp e Instagram con agentes de IA, calendario inteligente, CRM, historial, finanzas y API REST. Mas de 5.000 profesionales ya confian.',
                    'en' => 'The #1 medical scheduling system in Brazil. 24/7 automated service via WhatsApp and Instagram with AI agents, smart calendar, CRM, EMR, finance and REST API. Trusted by over 5,000 healthcare professionals.',
                ]),
                'logo' => '/images/logotipo-agendaclinic.png',
                'cover' => '/images/logotipo-agendaclinic.png',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, quero conhecer o Agenda Clinic!'),
                'features' => [
                    [
                        'icon' => 'CalendarDays',
                        'title' => self::pick($locale, ['pt_BR' => 'Calendario inteligente drag & drop', 'es' => 'Calendario inteligente drag & drop', 'en' => 'Smart drag & drop calendar']),
                        'description' => self::pick($locale, ['pt_BR' => 'Agendas por profissional ou exame, kanban, datas indisponíveis e reagendamento em massa.', 'es' => 'Agendas por profesional o examen, kanban, fechas no disponibles y reagendamiento masivo.', 'en' => 'Agendas per professional or exam, kanban, blocked dates and bulk rescheduling.']),
                    ],
                    [
                        'icon' => 'Bot',
                        'title' => self::pick($locale, ['pt_BR' => 'Agente de IA 24/7 no WhatsApp e Instagram', 'es' => 'Agente de IA 24/7 en WhatsApp e Instagram', 'en' => '24/7 AI agent on WhatsApp and Instagram']),
                        'description' => self::pick($locale, ['pt_BR' => 'Agendamento conversacional, confirmação de consultas, triagem e respostas personalizadas que reduzem faltas em ate 60%.', 'es' => 'Agendamiento conversacional, confirmaciones, triaje y respuestas personalizadas que reducen faltas hasta 60%.', 'en' => 'Conversational booking, appointment confirmation, triage and personalized replies that cut no-shows by up to 60%.']),
                    ],
                    [
                        'icon' => 'Users',
                        'title' => self::pick($locale, ['pt_BR' => 'CRM, kanban de leads e campanhas', 'es' => 'CRM, kanban de leads y campanas', 'en' => 'CRM, lead kanban and campaigns']),
                        'description' => self::pick($locale, ['pt_BR' => 'Pipelines personalizaveis, automações, tags e campanhas de marketing para captar e fidelizar pacientes.', 'es' => 'Pipelines personalizables, automatizaciones, tags y campanas para captar y fidelizar pacientes.', 'en' => 'Custom pipelines, automations, tags and marketing campaigns to attract and retain patients.']),
                    ],
                    [
                        'icon' => 'MessageCircle',
                        'title' => self::pick($locale, ['pt_BR' => 'Lembretes multicanal automaticos', 'es' => 'Recordatorios multicanal automaticos', 'en' => 'Multichannel automated reminders']),
                        'description' => self::pick($locale, ['pt_BR' => 'WhatsApp, e-mail e SMS com 24h e 2h de antecedencia, templates personalizaveis e SMTP proprio.', 'es' => 'WhatsApp, email y SMS con 24h y 2h de antelacion, plantillas personalizables y SMTP propio.', 'en' => 'WhatsApp, email and SMS sent 24h and 2h ahead, custom templates and your own SMTP.']),
                    ],
                    [
                        'icon' => 'FileText',
                        'title' => self::pick($locale, ['pt_BR' => 'Cadastro de pacientes e exames', 'es' => 'Registro de pacientes y examenes', 'en' => 'Patient and exam records']),
                        'description' => self::pick($locale, ['pt_BR' => 'Base de dados completa de pacientes, especialistas, exames personalizados, planos de saúde e metodos de pagamento.', 'es' => 'Base completa de pacientes, especialistas, examenes personalizados, planes de salud y metodos de pago.', 'en' => 'Full database of patients, specialists, custom exams, health plans and payment methods.']),
                    ],
                    [
                        'icon' => 'Webhook',
                        'title' => self::pick($locale, ['pt_BR' => 'API REST, webhooks e tokens', 'es' => 'API REST, webhooks y tokens', 'en' => 'REST API, webhooks and tokens']),
                        'description' => self::pick($locale, ['pt_BR' => 'Integre com qualquer sistema via API documentada, receba eventos em tempo real e gere tokens seguros.', 'es' => 'Integra con cualquier sistema via API documentada, recibe eventos en tiempo real y genera tokens seguros.', 'en' => 'Integrate with any system via documented API, receive real-time events and generate secure tokens.']),
                    ],
                ],
                'metrics' => [
                    ['label' => self::pick($locale, ['pt_BR' => 'Profissionais usando', 'es' => 'Profesionales usando', 'en' => 'Professionals using']), 'value' => '5.000+'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Redução de faltas', 'es' => 'Reduccion de faltas', 'en' => 'No-show reduction']), 'value' => '-60%'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Horas economizadas/semana', 'es' => 'Horas ahorradas/semana', 'en' => 'Hours saved per week']), 'value' => '15h'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Satisfação dos clientes', 'es' => 'Satisfaccion de clientes', 'en' => 'Customer satisfaction']), 'value' => '98%'],
                ],
                'tech' => ['Laravel', 'Vue', 'WhatsApp Cloud API', 'Instagram API', 'OpenAI', 'n8n', 'API REST'],
                'useCases' => [
                    self::pick($locale, ['pt_BR' => 'Clinicas medicas, odontologicas e veterinarias', 'es' => 'Clinicas medicas, odontologicas y veterinarias', 'en' => 'Medical, dental and veterinary clinics']),
                    self::pick($locale, ['pt_BR' => 'Centros de exames e diagnostico por imagem', 'es' => 'Centros de examenes y diagnostico por imagen', 'en' => 'Exam and diagnostic imaging centers']),
                    self::pick($locale, ['pt_BR' => 'Profissionais autonomos (psicologos, fisio, esteticistas)', 'es' => 'Profesionales autonomos (psicologos, fisio, esteticistas)', 'en' => 'Independent professionals (psychologists, physio, aesthetics)']),
                    self::pick($locale, ['pt_BR' => 'Operadoras de saúde com multiplas unidades', 'es' => 'Operadoras de salud con multiples unidades', 'en' => 'Healthcare operators with multiple branches']),
                ],
                'integrations' => ['WhatsApp Business', 'Instagram Direct', 'n8n', 'Google Calendar', 'API REST', 'Webhooks'],
                'pricingNote' => self::pick($locale, [
                    'pt_BR' => '30 dias gratis. Planos sob consulta para profissionais autonomos e clinicas premium.',
                    'es' => '30 dias gratis. Planes bajo consulta para profesionales autonomos y clinicas premium.',
                    'en' => '30-day free trial. Plans on request for independents and premium clinics.',
                ]),
                'process' => [
                    ['title' => self::pick($locale, ['pt_BR' => 'Onboarding em 5 minutos', 'es' => 'Onboarding en 5 minutos', 'en' => '5-minute onboarding']), 'description' => self::pick($locale, ['pt_BR' => 'Crie sua conta, importe pacientes e comece a agendar.', 'es' => 'Crea tu cuenta, importa pacientes y empieza a agendar.', 'en' => 'Create your account, import patients and start scheduling.'])],
                    ['title' => self::pick($locale, ['pt_BR' => 'Configuração guiada', 'es' => 'Configuracion guiada', 'en' => 'Guided setup']), 'description' => self::pick($locale, ['pt_BR' => 'Especialistas, exames, horarios, planos e metodos de pagamento personalizados.', 'es' => 'Especialistas, examenes, horarios, planes y metodos de pago personalizados.', 'en' => 'Specialists, exams, hours, plans and custom payment methods.'])],
                    ['title' => self::pick($locale, ['pt_BR' => 'Agente de IA ativo', 'es' => 'Agente de IA activo', 'en' => 'AI agent live']), 'description' => self::pick($locale, ['pt_BR' => 'Conecte WhatsApp e Instagram e ative agendamento automatico 24/7.', 'es' => 'Conecta WhatsApp e Instagram y activa agendamiento automatico 24/7.', 'en' => 'Connect WhatsApp and Instagram and turn on 24/7 automated booking.'])],
                    ['title' => self::pick($locale, ['pt_BR' => 'Suporte continuo', 'es' => 'Soporte continuo', 'en' => 'Ongoing support']), 'description' => self::pick($locale, ['pt_BR' => 'Acompanhamento dedicado, treinamento e evolução constante do produto.', 'es' => 'Acompanamiento dedicado, formacion y evolucion constante del producto.', 'en' => 'Dedicated follow-up, training and continuous product evolution.'])],
                ],
            ],
            [
                'slug' => 'ibox-delivery',
                'name' => 'iBox Delivery',
                'badge' => 'Food-Tech',
                'website' => 'https://ibox.delivery',
                'tagline' => self::pick($locale, [
                    'pt_BR' => 'Sistema inteligente de delivery multicategoria com apps iOS e Android',
                    'es' => 'Sistema inteligente de delivery multicategoria con apps iOS y Android',
                    'en' => 'Smart multi-category delivery system with native iOS and Android apps',
                ]),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Plataforma de delivery completa para restaurantes, mercados, pet shops, bebidas, sorvetes e muito mais. Apps nativos para clientes e motoboys, painel de gestao em tempo real e integracoes prontas para escalar seu negócio.',
                    'es' => 'Plataforma de delivery completa para restaurantes, mercados, pet shops, bebidas, helados y mas. Apps nativas para clientes y mensajeros, panel de gestion en tiempo real e integraciones listas para escalar tu negócio.',
                    'en' => 'Full delivery platform for restaurants, supermarkets, pet shops, beverages, ice cream and more. Native apps for customers and couriers, real-time management dashboard and ready integrations to scale your business.',
                ]),
                'logo' => '/images/logotipo-iboxdelivery.jpg',
                'cover' => '/images/logotipo-iboxdelivery.jpg',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, quero conhecer o iBox Delivery!'),
                'features' => [
                    ['icon' => 'Smartphone', 'title' => self::pick($locale, ['pt_BR' => 'Apps nativos iOS e Android', 'es' => 'Apps nativas iOS y Android', 'en' => 'Native iOS and Android apps']), 'description' => self::pick($locale, ['pt_BR' => 'App publicado na Apple Store e Google Play, com push notifications e experiencia mobile-first.', 'es' => 'App publicada en Apple Store y Google Play, con push notifications y experiencia mobile-first.', 'en' => 'App live on Apple Store and Google Play, with push notifications and mobile-first experience.'])],
                    ['icon' => 'LayoutGrid', 'title' => self::pick($locale, ['pt_BR' => 'Multi-categoria', 'es' => 'Multi-categoria', 'en' => 'Multi-category']), 'description' => self::pick($locale, ['pt_BR' => 'Restaurantes, mercados, pet shops, bebidas, sorvetes, farmacias e qualquer outro segmento de delivery.', 'es' => 'Restaurantes, mercados, pet shops, bebidas, helados, farmacias y cualquier otro segmento de delivery.', 'en' => 'Restaurants, supermarkets, pet shops, beverages, ice cream, pharmacies and any other delivery segment.'])],
                    ['icon' => 'Bike', 'title' => self::pick($locale, ['pt_BR' => 'App e painel para motoboys', 'es' => 'App y panel para mensajeros', 'en' => 'Courier app and dashboard']), 'description' => self::pick($locale, ['pt_BR' => 'Roteirização otimizada, rastreio em tempo real e gestao de comissoes.', 'es' => 'Ruteo optimizado, rastreo en tiempo real y gestion de comisiones.', 'en' => 'Optimized routing, live tracking and commission management.'])],
                    ['icon' => 'CreditCard', 'title' => self::pick($locale, ['pt_BR' => 'Pagamentos integrados (Pix, cartao, voucher)', 'es' => 'Pagos integrados (Pix, tarjeta, voucher)', 'en' => 'Integrated payments (Pix, card, voucher)']), 'description' => self::pick($locale, ['pt_BR' => 'Antifraude, conciliação automatica e split de pagamento entre lojistas.', 'es' => 'Antifraude, conciliacion automatica y split de pago entre comercios.', 'en' => 'Anti-fraud, automatic reconciliation and payment split between merchants.'])],
                    ['icon' => 'Megaphone', 'title' => self::pick($locale, ['pt_BR' => 'Marketing e fidelidade', 'es' => 'Marketing y fidelidad', 'en' => 'Marketing and loyalty']), 'description' => self::pick($locale, ['pt_BR' => 'Cupons, cashback, push notifications e campanhas segmentadas para reter clientes.', 'es' => 'Cupones, cashback, push notifications y campanas segmentadas para retener clientes.', 'en' => 'Coupons, cashback, push notifications and segmented campaigns to retain customers.'])],
                    ['icon' => 'BarChart3', 'title' => self::pick($locale, ['pt_BR' => 'Painel de gestao em tempo real', 'es' => 'Panel de gestion en tiempo real', 'en' => 'Real-time management panel']), 'description' => self::pick($locale, ['pt_BR' => 'Dashboards de pedidos, faturamento, performance de motoboys e relatorios por loja.', 'es' => 'Dashboards de pedidos, facturacion, performance de mensajeros y reportes por tienda.', 'en' => 'Order, revenue, courier performance dashboards and per-store reports.'])],
                ],
                'metrics' => [
                    ['label' => self::pick($locale, ['pt_BR' => 'Aumento medio de ticket', 'es' => 'Aumento medio de ticket', 'en' => 'Average ticket lift']), 'value' => '+32%'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Tempo medio de entrega', 'es' => 'Tiempo medio de entrega', 'en' => 'Average delivery time']), 'value' => '28min'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Lojistas ativos', 'es' => 'Comercios activos', 'en' => 'Active merchants']), 'value' => '80+'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Apps publicados', 'es' => 'Apps publicadas', 'en' => 'Published apps']), 'value' => 'iOS + Android'],
                ],
                'tech' => ['Laravel', 'Vue', 'Flutter', 'Pix', 'Mapbox', 'Push Notifications', 'Apple Store', 'Google Play'],
                'useCases' => [
                    self::pick($locale, ['pt_BR' => 'Restaurantes, hamburguerias, pizzarias e dark kitchens', 'es' => 'Restaurantes, hamburgueserias, pizzerias y dark kitchens', 'en' => 'Restaurants, burger shops, pizzerias and dark kitchens']),
                    self::pick($locale, ['pt_BR' => 'Mercados, hortifruti e adegas', 'es' => 'Mercados, hortifruti y bodegas', 'en' => 'Supermarkets, produce stores and wine shops']),
                    self::pick($locale, ['pt_BR' => 'Pet shops, farmacias e lojas de conveniencia', 'es' => 'Pet shops, farmacias y tiendas de conveniencia', 'en' => 'Pet shops, pharmacies and convenience stores']),
                    self::pick($locale, ['pt_BR' => 'Marketplaces regionais e shoppings de bairro', 'es' => 'Marketplaces regionales y shoppings de barrio', 'en' => 'Regional marketplaces and neighborhood hubs']),
                ],
                'integrations' => ['Pix', 'Cartao de credito/debito', 'Mapbox', 'WhatsApp', 'Push Notifications', 'Apple Pay', 'Google Pay'],
                'pricingNote' => self::pick($locale, [
                    'pt_BR' => 'Modelo white-label ou marketplace. Planos sob consulta conforme volume de pedidos.',
                    'es' => 'Modelo white-label o marketplace. Planes bajo consulta segun volumen de pedidos.',
                    'en' => 'White-label or marketplace model. Pricing on request based on order volume.',
                ]),
                'process' => [
                    ['title' => self::pick($locale, ['pt_BR' => 'Cadastro e personalização', 'es' => 'Registro y personalizacion', 'en' => 'Sign-up and personalization']), 'description' => self::pick($locale, ['pt_BR' => 'Configuramos sua marca, categorias, taxas e regiao de entrega.', 'es' => 'Configuramos tu marca, categorias, tasas y region de entrega.', 'en' => 'We set up your brand, categories, fees and delivery area.'])],
                    ['title' => self::pick($locale, ['pt_BR' => 'Cadastro de lojas e produtos', 'es' => 'Registro de tiendas y productos', 'en' => 'Store and product setup']), 'description' => self::pick($locale, ['pt_BR' => 'Importação de cardapios, modificadores e politicas de cada lojista.', 'es' => 'Importacion de menus, modificadores y politicas de cada comercio.', 'en' => 'Menu import, modifiers and policies for each merchant.'])],
                    ['title' => self::pick($locale, ['pt_BR' => 'Apps publicados nas lojas', 'es' => 'Apps publicadas en las tiendas', 'en' => 'Apps live on stores']), 'description' => self::pick($locale, ['pt_BR' => 'Lancamos seu app cliente e motoboy nativos na Apple Store e Google Play.', 'es' => 'Lanzamos tu app de cliente y mensajero nativas en Apple Store y Google Play.', 'en' => 'We launch your native customer and courier apps on Apple Store and Google Play.'])],
                    ['title' => self::pick($locale, ['pt_BR' => 'Operação e marketing', 'es' => 'Operacion y marketing', 'en' => 'Operation and marketing']), 'description' => self::pick($locale, ['pt_BR' => 'Suporte 24/7, campanhas de aquisição, fidelidade e relatorios contiuos.', 'es' => 'Soporte 24/7, campanas de adquisicion, fidelidad y reportes continuos.', 'en' => '24/7 support, acquisition and loyalty campaigns and continuous reports.'])],
                ],
            ],
            [
                'slug' => 'conecta',
                'name' => 'Conecta',
                'badge' => 'Marketing-Tech',
                'website' => 'https://conecta.wmst.com.br',
                'tagline' => self::pick($locale, [
                    'pt_BR' => 'Automação de Instagram para agencias e empresas multi-conta',
                    'es' => 'Automatizacion de Instagram para agencias y empresas multi-cuenta',
                    'en' => 'Instagram automation for agencies and multi-account companies',
                ]),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Plataforma multi-tenant para gerenciar varias contas de Instagram em um so painel: OAuth oficial, envio de mensagens, posts, stories, reacoes, webhooks em tempo real e API REST completa para integrar com chatbots e CRMs.',
                    'es' => 'Plataforma multi-tenant para gestionar varias cuentas de Instagram en un solo panel: OAuth oficial, envio de mensajes, posts, stories, reacciones, webhooks en tiempo real y API REST completa para integrar con chatbots y CRMs.',
                    'en' => 'Multi-tenant platform to manage multiple Instagram accounts from a single panel: official OAuth, message sending, posts, stories, reactions, real-time webhooks and a complete REST API to integrate with chatbots and CRMs.',
                ]),
                'logo' => '/images/logotipo-conecta.png',
                'cover' => '/images/logotipo-conecta.png',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, quero conhecer o Conecta!'),
                'features' => [
                    ['icon' => 'Building2', 'title' => self::pick($locale, ['pt_BR' => 'Multi-tenancy avancado', 'es' => 'Multi-tenancy avanzado', 'en' => 'Advanced multi-tenancy']), 'description' => self::pick($locale, ['pt_BR' => 'Gerencie multiplas agencias e clientes com dados totalmente isolados em um so painel.', 'es' => 'Gestiona multiples agencias y clientes con datos totalmente aislados en un solo panel.', 'en' => 'Manage multiple agencies and clients with fully isolated data from a single dashboard.'])],
                    ['icon' => 'Instagram', 'title' => self::pick($locale, ['pt_BR' => 'Integração oficial com Instagram', 'es' => 'Integracion oficial con Instagram', 'en' => 'Official Instagram integration']), 'description' => self::pick($locale, ['pt_BR' => 'OAuth, envio de DMs, posts, stories, stickers, reacoes e gerenciamento de perfis via API oficial.', 'es' => 'OAuth, envio de DMs, posts, stories, stickers, reacciones y gestion de perfiles via API oficial.', 'en' => 'OAuth, DM sending, posts, stories, stickers, reactions and profile management via official API.'])],
                    ['icon' => 'Code', 'title' => self::pick($locale, ['pt_BR' => 'API RESTful documentada', 'es' => 'API RESTful documentada', 'en' => 'Documented RESTful API']), 'description' => self::pick($locale, ['pt_BR' => 'Endpoints completos para integração com sistemas externos, automações e chatbots.', 'es' => 'Endpoints completos para integracion con sistemas externos, automatizaciones y chatbots.', 'en' => 'Complete endpoints for integration with external systems, automations and chatbots.'])],
                    ['icon' => 'Webhook', 'title' => self::pick($locale, ['pt_BR' => 'Webhooks em tempo real', 'es' => 'Webhooks en tiempo real', 'en' => 'Real-time webhooks']), 'description' => self::pick($locale, ['pt_BR' => 'Receba notificacoes automaticas sobre novas mensagens, comentarios e eventos da conta.', 'es' => 'Recibe notificaciones automaticas sobre nuevos mensajes, comentarios y eventos de la cuenta.', 'en' => 'Get automatic notifications about new messages, comments and account events.'])],
                    ['icon' => 'KeyRound', 'title' => self::pick($locale, ['pt_BR' => 'Tokens de acesso seguros', 'es' => 'Tokens de acceso seguros', 'en' => 'Secure access tokens']), 'description' => self::pick($locale, ['pt_BR' => 'Gere tokens revogaveis para integrar com segurança outros sistemas e ferramentas.', 'es' => 'Genera tokens revocables para integrar con seguridad otros sistemas y herramientas.', 'en' => 'Issue revocable tokens to securely integrate other systems and tools.'])],
                    ['icon' => 'LayoutDashboard', 'title' => self::pick($locale, ['pt_BR' => 'Dashboard personalizavel', 'es' => 'Dashboard personalizable', 'en' => 'Personalized dashboard']), 'description' => self::pick($locale, ['pt_BR' => 'Paineis por agencia e por conta Instagram, visualização de tokens, webhooks e metricas.', 'es' => 'Paneles por agencia y cuenta Instagram, visualizacion de tokens, webhooks y metricas.', 'en' => 'Per-agency and per-account dashboards, token, webhook and metric visualization.'])],
                ],
                'metrics' => [
                    ['label' => self::pick($locale, ['pt_BR' => 'Plano inicial', 'es' => 'Plan inicial', 'en' => 'Starting plan']), 'value' => 'R$ 98,99/mes'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Trial gratuito', 'es' => 'Trial gratis', 'en' => 'Free trial']), 'value' => '7-30 dias'],
                    ['label' => self::pick($locale, ['pt_BR' => 'Contas Instagram', 'es' => 'Cuentas Instagram', 'en' => 'Instagram accounts']), 'value' => 'Ilimitado'],
                    ['label' => self::pick($locale, ['pt_BR' => 'API & Webhooks', 'es' => 'API y Webhooks', 'en' => 'API & Webhooks']), 'value' => '100%'],
                ],
                'tech' => ['Laravel', 'Vue', 'Instagram Graph API', 'OAuth 2.0', 'API REST', 'Webhooks', 'Multi-tenant'],
                'useCases' => [
                    self::pick($locale, ['pt_BR' => 'Agencias de marketing gerenciando varios clientes', 'es' => 'Agencias de marketing gestionando varios clientes', 'en' => 'Marketing agencies managing multiple clients']),
                    self::pick($locale, ['pt_BR' => 'Empresas com multiplas marcas e contas Instagram', 'es' => 'Empresas con multiples marcas y cuentas Instagram', 'en' => 'Companies with multiple brands and Instagram accounts']),
                    self::pick($locale, ['pt_BR' => 'Desenvolvedores criando chatbots e integracoes proprias', 'es' => 'Desarrolladores creando chatbots e integraciones propias', 'en' => 'Developers building chatbots and custom integrations']),
                    self::pick($locale, ['pt_BR' => 'SaaS que precisam de Instagram embedado no produto', 'es' => 'SaaS que necesitan Instagram embebido en el producto', 'en' => 'SaaS that need Instagram embedded in their product']),
                ],
                'integrations' => ['Instagram Graph API', 'OAuth 2.0', 'Webhooks', 'API REST', 'n8n', 'Zapier', 'CRMs externos'],
                'pricingNote' => self::pick($locale, [
                    'pt_BR' => 'A partir de R$ 98,99/mes por conta. 30 dias de teste gratis sem compromisso.',
                    'es' => 'Desde R$ 98,99/mes por cuenta. 30 dias de prueba gratis sin compromiso.',
                    'en' => 'Starting at R$ 98.99/month per account. 30-day free trial, no commitment.',
                ]),
                'process' => [
                    ['title' => self::pick($locale, ['pt_BR' => 'Cadastre sua agencia', 'es' => 'Registra tu agencia', 'en' => 'Sign up your agency']), 'description' => self::pick($locale, ['pt_BR' => 'Crie a conta da agencia e adicione seus clientes em poucos cliques.', 'es' => 'Crea la cuenta de la agencia y anade tus clientes en pocos clics.', 'en' => 'Create the agency account and add your clients in a few clicks.'])],
                    ['title' => self::pick($locale, ['pt_BR' => 'Conecte contas Instagram', 'es' => 'Conecta cuentas Instagram', 'en' => 'Connect Instagram accounts']), 'description' => self::pick($locale, ['pt_BR' => 'Autorize via OAuth oficial e o sistema gerencia tokens, refresh e permissoes.', 'es' => 'Autoriza via OAuth oficial y el sistema gestiona tokens, refresh y permisos.', 'en' => 'Authorize via official OAuth and the system handles tokens, refresh and permissions.'])],
                    ['title' => self::pick($locale, ['pt_BR' => 'Configure webhooks e tokens', 'es' => 'Configura webhooks y tokens', 'en' => 'Configure webhooks and tokens']), 'description' => self::pick($locale, ['pt_BR' => 'Aponte os webhooks para o seu CRM e gere tokens para integracoes seguras.', 'es' => 'Apunta los webhooks a tu CRM y genera tokens para integraciones seguras.', 'en' => 'Point webhooks at your CRM and issue tokens for secure integrations.'])],
                    ['title' => self::pick($locale, ['pt_BR' => 'Automatize o atendimento', 'es' => 'Automatiza la atencion', 'en' => 'Automate engagement']), 'description' => self::pick($locale, ['pt_BR' => 'Use a API para enviar mensagens, responder DMs e publicar conteúdo programado.', 'es' => 'Usa la API para enviar mensajes, responder DMs y publicar contenido programado.', 'en' => 'Use the API to send messages, reply to DMs and publish scheduled content.'])],
                ],
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
                'title' => self::pick($locale, ['pt_BR' => 'Automação WhatsApp Business', 'es' => 'Automatizacion WhatsApp Business', 'en' => 'WhatsApp Business automation']),
                'highlight' => self::pick($locale, ['pt_BR' => '300% mais conversões', 'es' => '300% mas conversiones', 'en' => '300% more conversions']),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Atendimento 24/7, qualificação de leads e vendas automatizadas com IA via WhatsApp Business API.',
                    'es' => 'Atencion 24/7, calificacion de leads y ventas automatizadas con IA via WhatsApp Business API.',
                    'en' => '24/7 support, lead qualification and AI-powered sales via WhatsApp Business API.',
                ]),
                'badge' => 'WhatsApp API + IA + N8N',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, gostaria de saber mais sobre a Automação WhatsApp Business'),
                'deliverables' => [
                    self::pick($locale, ['pt_BR' => 'Setup e verificação da Cloud API', 'es' => 'Setup y verificacion de Cloud API', 'en' => 'Cloud API setup and verification']),
                    self::pick($locale, ['pt_BR' => 'Chatbot com IA conectado ao seu CRM', 'es' => 'Chatbot con IA conectado a tu CRM', 'en' => 'AI chatbot connected to your CRM']),
                    self::pick($locale, ['pt_BR' => 'Funis de qualificação e vendas', 'es' => 'Funnels de calificacion y ventas', 'en' => 'Qualification and sales funnels']),
                    self::pick($locale, ['pt_BR' => 'Painel de metricas em tempo real', 'es' => 'Panel de metricas en tiempo real', 'en' => 'Real-time metrics dashboard']),
                ],
                'process' => self::defaultProcess($locale),
            ],
            [
                'slug' => 'automacao-instagram',
                'icon' => 'Instagram',
                'title' => self::pick($locale, ['pt_BR' => 'Automação Instagram', 'es' => 'Automatizacion Instagram', 'en' => 'Instagram automation']),
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
                    self::pick($locale, ['pt_BR' => 'Sequencias de nutrição automatica', 'es' => 'Secuencias de nutricion automatica', 'en' => 'Automatic nurture sequences']),
                    self::pick($locale, ['pt_BR' => 'Relatorios de engajamento por post', 'es' => 'Reportes de engagement por post', 'en' => 'Per-post engagement reports']),
                ],
                'process' => self::defaultProcess($locale),
            ],
            [
                'slug' => 'automacao-processos',
                'icon' => 'Workflow',
                'title' => self::pick($locale, ['pt_BR' => 'Automação de Processos Internos', 'es' => 'Automatizacion de procesos internos', 'en' => 'Internal process automation']),
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
                    self::pick($locale, ['pt_BR' => 'Integração com seus sistemas via API', 'es' => 'Integracion con tus sistemas via API', 'en' => 'Integration with your systems via API']),
                    self::pick($locale, ['pt_BR' => 'Dashboards de acompanhamento', 'es' => 'Dashboards de seguimiento', 'en' => 'Tracking dashboards']),
                ],
                'process' => self::defaultProcess($locale),
            ],
            [
                'slug' => 'integracao-sistemas',
                'icon' => 'Database',
                'title' => self::pick($locale, ['pt_BR' => 'Integração de Sistemas Legados', 'es' => 'Integracion de sistemas legados', 'en' => 'Legacy systems integration']),
                'highlight' => self::pick($locale, ['pt_BR' => 'Zero downtime garantido', 'es' => 'Zero downtime garantizado', 'en' => 'Zero downtime guaranteed']),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Conectamos sistemas antigos com novas tecnologias sem interromper operações, com segurança e auditoria.',
                    'es' => 'Conectamos sistemas antiguos con nuevas tecnologias sin interrumpir operaciones, con seguridad y auditoria.',
                    'en' => 'We connect legacy systems with new technologies without interrupting operations, with security and audit.',
                ]),
                'badge' => 'Laravel + REST + Microservicos',
                'whatsapp' => $whatsappBase.rawurlencode('Olá, gostaria de saber mais sobre Integração de Sistemas'),
                'deliverables' => [
                    self::pick($locale, ['pt_BR' => 'Discovery e arquitetura de integração', 'es' => 'Discovery y arquitectura de integracion', 'en' => 'Integration discovery and architecture']),
                    self::pick($locale, ['pt_BR' => 'API gateway e autenticação centralizada', 'es' => 'API gateway y autenticacion centralizada', 'en' => 'API gateway and centralized auth']),
                    self::pick($locale, ['pt_BR' => 'Pipelines ETL e sincronização', 'es' => 'Pipelines ETL y sincronizacion', 'en' => 'ETL pipelines and sync']),
                    self::pick($locale, ['pt_BR' => 'Monitoramento e observabilidade', 'es' => 'Monitoreo y observabilidad', 'en' => 'Monitoring and observability']),
                ],
                'process' => self::defaultProcess($locale),
            ],
            [
                'slug' => 'ia-analise-preditiva',
                'icon' => 'Brain',
                'title' => self::pick($locale, ['pt_BR' => 'IA para Analise Preditiva', 'es' => 'IA para analisis predictivo', 'en' => 'AI for predictive analytics']),
                'highlight' => self::pick($locale, ['pt_BR' => '85% precisao em previsões', 'es' => '85% precision en pronosticos', 'en' => '85% forecast accuracy']),
                'description' => self::pick($locale, [
                    'pt_BR' => 'Insights automaticos, previsões precisas e decisoes baseadas em dados com modelos de IA proprios e LLMs.',
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
                'highlight' => self::pick($locale, ['pt_BR' => '100% adequação ao negócio', 'es' => '100% adecuacion al negócio', 'en' => '100% business fit']),
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
                    self::pick($locale, ['pt_BR' => 'Suporte e evolução continua', 'es' => 'Soporte y evolucion continua', 'en' => 'Ongoing support and evolution']),
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
            ['title' => self::pick($locale, ['pt_BR' => 'Diagnostico', 'es' => 'Diagnostico', 'en' => 'Discovery']), 'description' => self::pick($locale, ['pt_BR' => 'Mapeamos seus processos atuais e oportunidades de automação.', 'es' => 'Mapeamos tus procesos actuales y oportunidades de automatizacion.', 'en' => 'We map your current processes and automation opportunities.'])],
            ['title' => self::pick($locale, ['pt_BR' => 'Proposta', 'es' => 'Propuesta', 'en' => 'Proposal']), 'description' => self::pick($locale, ['pt_BR' => 'Escopo, cronograma e ROI esperado em ate 48h.', 'es' => 'Alcance, cronograma y ROI esperado en hasta 48h.', 'en' => 'Scope, timeline and expected ROI within 48h.'])],
            ['title' => self::pick($locale, ['pt_BR' => 'Implementação', 'es' => 'Implementacion', 'en' => 'Implementation']), 'description' => self::pick($locale, ['pt_BR' => 'Sprints semanais com entregas continuas e validação.', 'es' => 'Sprints semanales con entregas continuas y validacion.', 'en' => 'Weekly sprints with continuous delivery and validation.'])],
            ['title' => self::pick($locale, ['pt_BR' => 'Operação', 'es' => 'Operacion', 'en' => 'Operation']), 'description' => self::pick($locale, ['pt_BR' => 'Suporte, monitoramento e melhorias continuas pos go-live.', 'es' => 'Soporte, monitoreo y mejoras continuas post go-live.', 'en' => 'Support, monitoring and continuous improvement after go-live.'])],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function cases(string $locale = 'pt_BR'): array
    {
        return [
            [
                'slug' => 'ecommerce-moda-conversões',
                'icon' => 'TrendingUp',
                'metric' => '300%',
                'segment' => self::pick($locale, ['pt_BR' => 'E-commerce de Moda', 'es' => 'E-commerce de Moda', 'en' => 'Fashion e-commerce']),
                'title' => self::pick($locale, ['pt_BR' => 'Aumento de 300% em conversões', 'es' => 'Aumento de 300% en conversiones', 'en' => '300% conversion lift']),
                'challenge' => self::pick($locale, [
                    'pt_BR' => 'Time pequeno de atendimento perdendo vendas fora do horario comercial e sem qualificar leads.',
                    'es' => 'Equipo pequeno perdiendo ventas fuera de horario y sin calificar leads.',
                    'en' => 'Small team losing sales after hours and unable to qualify leads.',
                ]),
                'solution' => self::pick($locale, [
                    'pt_BR' => 'Chatbot com IA conectado ao catalogo, qualificando leads, sugerindo produtos e fechando venda direto pelo WhatsApp.',
                    'es' => 'Chatbot con IA conectado al catalogo, calificando leads, sugiriendo productos y cerrando ventas por WhatsApp.',
                    'en' => 'AI chatbot wired to the catalog, qualifying leads, recommending products and closing sales over WhatsApp.',
                ]),
                'result' => self::pick($locale, [
                    'pt_BR' => '300% mais conversões em 90 dias e atendimento 24/7 sem aumento de equipe.',
                    'es' => '300% mas conversiones en 90 dias y atencion 24/7 sin ampliar equipo.',
                    'en' => '300% more conversions in 90 days with 24/7 service and no headcount increase.',
                ]),
                'tags' => ['WhatsApp', 'IA', 'E-commerce'],
            ],
            [
                'slug' => 'clinica-medica-agendamentos',
                'icon' => 'Clock',
                'metric' => '85%',
                'segment' => self::pick($locale, ['pt_BR' => 'Clinica Medica', 'es' => 'Clinica Medica', 'en' => 'Medical clinic']),
                'title' => self::pick($locale, ['pt_BR' => 'Redução de 85% no tempo de agendamentos', 'es' => 'Reduccion de 85% en tiempo de agendamientos', 'en' => '85% faster scheduling']),
                'challenge' => self::pick($locale, [
                    'pt_BR' => 'Recepção sobrecarregada, fila de espera longa e alto indice de no-show.',
                    'es' => 'Recepcion saturada, fila de espera larga y alto no-show.',
                    'en' => 'Overloaded reception, long waiting list and high no-show rate.',
                ]),
                'solution' => self::pick($locale, [
                    'pt_BR' => 'Implementação do Agenda Clinic com IA para agendar, lembrar e remarcar via WhatsApp automaticamente.',
                    'es' => 'Implementacion del Agenda Clinic con IA para agendar, recordar y reprogramar via WhatsApp.',
                    'en' => 'Agenda Clinic rollout with AI to schedule, remind and reschedule via WhatsApp automatically.',
                ]),
                'result' => self::pick($locale, [
                    'pt_BR' => '85% menos tempo gasto em agendamento e queda de 78% no no-show em 60 dias.',
                    'es' => '85% menos tiempo en agendamiento y caida de 78% en no-show en 60 dias.',
                    'en' => '85% less time spent scheduling and 78% drop in no-shows within 60 days.',
                ]),
                'tags' => ['Agenda Clinic', 'WhatsApp', 'saúde'],
            ],
            [
                'slug' => 'logistica-roi-automações',
                'icon' => 'Target',
                'metric' => '500%',
                'segment' => self::pick($locale, ['pt_BR' => 'Empresa de Logistica', 'es' => 'Empresa de Logistica', 'en' => 'Logistics company']),
                'title' => self::pick($locale, ['pt_BR' => 'ROI de 500% em automações', 'es' => 'ROI de 500% en automatizaciones', 'en' => '500% ROI in automation']),
                'challenge' => self::pick($locale, [
                    'pt_BR' => '40h semanais gastas em conferencia manual de planilhas e relatorios.',
                    'es' => '40h semanales gastadas en conferencia manual de planillas y reportes.',
                    'en' => '40 weekly hours spent on manual spreadsheet and report review.',
                ]),
                'solution' => self::pick($locale, [
                    'pt_BR' => 'Workflows N8N integrando ERP, planilhas e e-mail, com aprovacoes automatizadas e dashboards em tempo real.',
                    'es' => 'Workflows N8N integrando ERP, planillas y email, con aprobaciones automatizadas y dashboards en tiempo real.',
                    'en' => 'N8N workflows integrating ERP, spreadsheets and email with automated approvals and real-time dashboards.',
                ]),
                'result' => self::pick($locale, [
                    'pt_BR' => '40h economizadas por semana e ROI de 500% no primeiro ano.',
                    'es' => '40h ahorradas por semana y ROI de 500% en el primer ano.',
                    'en' => '40 hours saved per week and 500% ROI in year one.',
                ]),
                'tags' => ['N8N', 'Automação', 'Logistica'],
            ],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    public static function testimonials(string $locale = 'pt_BR'): array
    {
        return [
            [
                'name' => 'Carlos Silva',
                'role' => self::pick($locale, ['pt_BR' => 'CEO', 'es' => 'CEO', 'en' => 'CEO']),
                'company' => 'TechStart Solucoes',
                'testimonial' => self::pick($locale, [
                    'pt_BR' => 'A automação de WhatsApp da WMST revolucionou nosso atendimento. Vendemos 24/7 sem precisar de equipe gigante. Recuperamos o investimento em 2 meses!',
                    'es' => 'La automatizacion de WhatsApp de WMST revoluciono nuestra atencion. Vendemos 24/7 sin equipo gigante. Recuperamos la inversion en 2 meses!',
                    'en' => "WMST's WhatsApp automation transformed our support. We sell 24/7 without a huge team. We got our investment back in 2 months!",
                ]),
            ],
            [
                'name' => 'Dra. Ana Santos',
                'role' => self::pick($locale, ['pt_BR' => 'Diretora', 'es' => 'Directora', 'en' => 'Director']),
                'company' => 'Clinica Vida & saúde',
                'testimonial' => self::pick($locale, [
                    'pt_BR' => 'O Agenda Clinic eliminou nossa fila de espera. A IA agenda tudo automaticamente e os pacientes adoram a praticidade.',
                    'es' => 'Agenda Clinic elimino nuestra fila de espera. La IA agenda todo y los pacientes aman la practicidad.',
                    'en' => 'Agenda Clinic eliminated our waiting list. The AI books everything automatically and patients love the convenience.',
                ]),
            ],
            [
                'name' => 'Roberto Lima',
                'role' => self::pick($locale, ['pt_BR' => 'Proprietario', 'es' => 'Propietario', 'en' => 'Owner']),
                'company' => 'Delivery Express',
                'testimonial' => self::pick($locale, [
                    'pt_BR' => 'O IBOX Delivery nos colocou no mesmo nivel dos grandes players. Sistema robusto, suporte excepcional e resultados desde o primeiro dia.',
                    'es' => 'IBOX Delivery nos puso al nivel de los grandes. Sistema robusto, soporte excepcional y resultados desde el primer dia.',
                    'en' => 'IBOX Delivery put us on par with the major players. Robust system, exceptional support and results from day one.',
                ]),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function company(string $locale = 'pt_BR'): array
    {
        return [
            'mission' => self::pick($locale, [
                'pt_BR' => 'Transformar empresas em maquinas de crescimento atraves de software sob medida e automações inteligentes com IA.',
                'es' => 'Transformar empresas en maquinas de crecimiento mediante software a medida y automatizaciones inteligentes con IA.',
                'en' => 'Turn companies into growth machines through custom software and AI-powered intelligent automation.',
            ]),
            'vision' => self::pick($locale, [
                'pt_BR' => 'Ser referencia em automação e IA aplicada para PMEs no Brasil e America Latina.',
                'es' => 'Ser referente en automatizacion e IA aplicada para PYMEs en Brasil y America Latina.',
                'en' => 'Be the reference in applied automation and AI for SMBs in Brazil and Latin America.',
            ]),
            'story' => self::pick($locale, [
                'pt_BR' => 'Fundada em Lorena, a WMST nasceu da união entre engenharia de software e obsessão por resultados de negócio. Em poucos anos entregamos mais de 500 projetos para clinicas, restaurantes, e-commerces, industrias e prestadores de servico que precisam crescer sem ampliar equipe.',
                'es' => 'Fundada en Lorena, WMST nacio de la union entre ingenieria de software y obsesion por resultados de negócio. En pocos anos entregamos mas de 500 proyectos para clinicas, restaurantes, e-commerces, industrias y prestadores de servicios que necesitan crecer sin ampliar equipo.',
                'en' => 'Founded in Lorena, WMST was born from the union of software engineering and a relentless focus on business results. In just a few years we have delivered over 500 projects for clinics, restaurants, e-commerce, manufacturers and service providers that need to grow without scaling headcount.',
            ]),
            'values' => [
                [
                    'icon' => 'Zap',
                    'title' => self::pick($locale, ['pt_BR' => 'Velocidade com qualidade', 'es' => 'Velocidad con calidad', 'en' => 'Speed with quality']),
                    'description' => self::pick($locale, ['pt_BR' => 'Entregas continuas em sprints curtos com testes automatizados.', 'es' => 'Entregas continuas en sprints cortos con tests automatizados.', 'en' => 'Continuous delivery in short sprints with automated testing.']),
                ],
                [
                    'icon' => 'ShieldCheck',
                    'title' => self::pick($locale, ['pt_BR' => 'Segurança em primeiro lugar', 'es' => 'Seguridad primero', 'en' => 'Security first']),
                    'description' => self::pick($locale, ['pt_BR' => 'Boas praticas OWASP, criptografia e LGPD em cada projeto.', 'es' => 'Buenas practicas OWASP, cifrado y GDPR/LGPD en cada proyecto.', 'en' => 'OWASP best practices, encryption and GDPR/LGPD compliance in every project.']),
                ],
                [
                    'icon' => 'Heart',
                    'title' => self::pick($locale, ['pt_BR' => 'Parceria de longo prazo', 'es' => 'Asociacion de largo plazo', 'en' => 'Long-term partnership']),
                    'description' => self::pick($locale, ['pt_BR' => 'Nosso time vive o seu negócio: suporte humano e evolução continua.', 'es' => 'Nuestro equipo vive tu negócio: soporte humano y evolucion continua.', 'en' => 'Our team lives your business: human support and continuous evolution.']),
                ],
                [
                    'icon' => 'Sparkles',
                    'title' => self::pick($locale, ['pt_BR' => 'Inovação com proposito', 'es' => 'Innovacion con proposito', 'en' => 'Innovation with purpose']),
                    'description' => self::pick($locale, ['pt_BR' => 'IA aplicada para gerar receita e cortar custos, não apenas hype.', 'es' => 'IA aplicada para generar ingresos y reducir costos, no solo hype.', 'en' => 'Applied AI to grow revenue and cut costs, not just hype.']),
                ],
            ],
            'metrics' => [
                ['label' => self::pick($locale, ['pt_BR' => 'Projetos entregues', 'es' => 'Proyectos entregados', 'en' => 'Projects delivered']), 'value' => '500+'],
                ['label' => self::pick($locale, ['pt_BR' => 'Satisfação de clientes', 'es' => 'Satisfaccion de clientes', 'en' => 'Customer satisfaction']), 'value' => '98%'],
                ['label' => self::pick($locale, ['pt_BR' => 'Disponibilidade media', 'es' => 'Disponibilidad media', 'en' => 'Average uptime']), 'value' => '99.9%'],
                ['label' => self::pick($locale, ['pt_BR' => 'Suporte especializado', 'es' => 'Soporte especializado', 'en' => 'Specialist support']), 'value' => '24/7'],
            ],
            'contact' => [
                'whatsapp' => 'https://wa.me/5512982184879?text='.rawurlencode('Olá, gostaria de falar com a WMST!'),
                'phone' => '+55 12 98218-4879',
                'email' => 'contato@wmst.com.br',
                'address' => 'Lorena - SP, Brasil',
                'hours' => self::pick($locale, ['pt_BR' => 'Seg-Sex 9h - 18h | Suporte 24/7', 'es' => 'Lun-Vie 9-18 | Soporte 24/7', 'en' => 'Mon-Fri 9am-6pm | 24/7 support']),
            ],
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
