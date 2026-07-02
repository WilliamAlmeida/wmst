# DESIGN.md — WMST Design System

> Documento-base para o **redesign do sistema e das páginas** da WMST.
> Fonte de verdade da identidade visual. Ao reiniciar o redesign, **comece por aqui**.
> Idioma do projeto: **PT-BR**.

---

## 0. TL;DR (para retomar o redesign)

1. A **logo** (`public/images/logo-wmst.png`) usa **navy `#16233F` + verde `#35C24A`**.
2. O **CSS atual** (`resources/css/app.css`) usa **azul `#0066cc` + magenta `#cc0066`** → **fora da identidade**. Precisa ser trocado.
3. Passo 1 do redesign = **substituir os tokens de marca** (§3) e alinhar `BrandMark`, header, footer, `theme-color`.
4. Depois, redesenhar página a página seguindo o **inventário + checklist** (§9 e §11).

---

## 1. A marca

- **WMST** = **W.M** (as iniciais de **William M.**, o fundador) + **Soluções Tecnológicas**.
- Descritor: **Software House** / no header aparece como **"Software House & Automações IA"**.
- Empresa **brasileira**: desenvolvimento de software sob demanda, produtos SaaS próprios e automações com IA.
- Tom de voz: **técnico, direto, confiável, sem enrolação**. Português brasileiro. Trata o cliente como parceiro, foca em resultado/entrega.

### Produtos/cases já existentes (têm mockups em `public/images/`)
- **iBox Delivery** — `mockup-ibox-delivery`, `logotipo-iboxdelivery`
- **AgendaClinic** — `mockup-agendaclinic`, `logotipo-agendaclinic`
- **Conecta** — `mockup-conecta`, `logotipo-conecta`
- **Chatwoot** (integração/atendimento) — `mockup-chatwoot`
- **Dashboard** genérico — `mockup-dashboard`

---

## 2. Símbolo (logo) — anatomia

- **Monograma W↔M** dentro de um **círculo** de traço grosso. Linhas espelhadas, ângulos retos/45°, sem curvas orgânicas — passa "engenharia/solidez".
- **4 colchetes de mira** (cantos) em **verde** → foco/precisão + alusão a tags de código `[ ]` / mira de câmera (scan).
- **Wordmark `W.M`** em black; o **ponto (`.`) é verde** — é o único respiro de cor no lettering.
- **Tagline** `SOLUÇÕES TECNOLÓGICAS` (navy, caixa-alta, tracking largo) + **descritor** `SOFTWARE HOUSE` (verde, menor).

### Regras do símbolo
- Deve funcionar **sozinho** (favicon / app icon / avatar) sem o wordmark → `BrandMark.vue` já tem prop `showWordmark`.
- **Área de proteção:** mínimo = altura do "M" do wordmark em todos os lados.
- **Não fazer:** distorcer proporção; trocar o verde por outro tom; wordmark navy sobre fundo escuro sem inversão.

### Arquivos de logo disponíveis
| Arquivo | Uso |
|---|---|
| `public/images/logo-wmst.png` / `.avif` | Logo completa (símbolo + wordmark + tagline) — versão vertical |
| `public/images/logo-wmst2.png` | Variante |
| `public/images/logotipo-wmst.png` / `.avif` | Ícone/símbolo (usado no `BrandMark.vue`) |

---

## 3. Cores (tokens) — **a corrigir no redesign**

### 3.1 Paleta da marca (alvo)

| Papel | Hex | HSL aprox. | Contraste s/ branco |
|---|---|---|---|
| **Navy / Primária** | `#16233F` | `219 48% 17%` | ~13:1 (AAA) |
| Navy 700 | `#1F3358` | `217 47% 24%` | — |
| Navy 900 (texto forte) | `#0F1A30` | `219 53% 12%` | — |
| **Verde / Acento** | `#35C24A` | `129 56% 48%` | ~2.4:1 |
| Verde 600 (hover/CTA) | `#2BA83E` | `131 59% 41%` | ~3.1:1 |
| Verde 700 (texto sobre claro) | `#1F8A30` | `132 63% 33%` | ~4.6:1 (AA texto grande) |
| Navy soft (fundos/hover) | `#16233F14` (8%) | — | — |
| Verde soft | `#35C24A1F` (12%) | — | — |

> ⚠️ **Acessibilidade:** o verde `#35C24A` é **decorativo/acento** — NÃO usar em texto pequeno essencial sobre branco. Para texto verde sobre claro use **`#1F8A30` (verde 700)**. Para CTA verde use fundo `#2BA83E`/`#35C24A` com **texto branco** (branco sobre `#2BA83E` ≈ 3.1:1 → só para texto ≥18px bold; se precisar AA em texto pequeno, escurecer o fundo para `#1F8A30`).

### 3.2 Neutros
Manter a escala **zinc** do Tailwind (já usada: `zinc-50/200/500/700/900`). Fundo de página atual: `bg-zinc-50`.

### 3.3 Substituições no `resources/css/app.css`

```css
@theme inline {
    /* WMST — identidade alinhada à logo */
    --wmst-navy:       #16233F;
    --wmst-navy-700:   #1F3358;
    --wmst-navy-900:   #0F1A30;
    --wmst-green:      #35C24A;
    --wmst-green-600:  #2BA83E;
    --wmst-green-700:  #1F8A30;
    --wmst-navy-soft:  #16233F14;
    --wmst-green-soft: #35C24A1F;

    /* Aliases usados no código (substituem os antigos #0066cc / #cc0066) */
    --color-brand:        var(--wmst-navy);   /* era #0066cc  */
    --color-brand-2:      var(--wmst-green);  /* era #cc0066  */
    --color-brand-soft:   var(--wmst-navy-soft);
    --color-brand-2-soft: var(--wmst-green-soft);
}
```

Ajustar também no mesmo arquivo:
```css
.gradient-text {
    /* era 90deg #0066cc → #7a3ad6 → #cc0066 */
    background-image: linear-gradient(90deg, #16233F 0%, #1F3358 55%, #35C24A 100%);
}

.bg-brand-radial {
    background-image:
        radial-gradient(900px circle at 10% 0%, rgba(22, 35, 63, 0.10), transparent 45%),
        radial-gradient(700px circle at 95% 10%, rgba(53, 194, 74, 0.10), transparent 40%);
}
```

### 3.4 Outros pontos com cor hard-coded a revisar
- `resources/js/layouts/PublicLayout.vue` → `<meta name="theme-color" content="#0066cc">` → trocar para `#16233F`.
- `PublicHeader.vue` / `PublicFooter.vue` → usam `var(--color-brand)` em hovers (ok depois que o token mudar).
- Botão WhatsApp usa **verde oficial do WhatsApp `#25D366`** — **manter** (é a cor da plataforma, não da marca). Não confundir com o verde WMST.

---

## 4. Tipografia

Carregada via Google Fonts em `PublicLayout.vue` (`Inter` + `Sora`).

| Papel | Família | Pesos |
|---|---|---|
| **Display / títulos** (`.font-display`) | **Sora** | 500/600/700/800 |
| **Corpo / UI** (`--font-sans`) | **Inter** | 400/500/600/700 |

Escala sugerida (redesign):
- H1 hero: `text-4xl md:text-6xl font-display font-bold tracking-tight`
- H2 seção: `text-3xl md:text-4xl font-display font-semibold`
- H3 card: `text-lg font-display font-semibold`
- Corpo: `text-base leading-relaxed text-zinc-600`
- Overline/eyebrow: `text-xs font-semibold uppercase tracking-widest text-[color:var(--wmst-green-700)]`

Lettering de marca (imita a logo): caixa-alta, `tracking` largo, o "ponto"/acento em verde.

---

## 5. Layout & grid

- Container: `mx-auto max-w-7xl px-4 md:px-8` (padrão já usado no header/footer).
- Seções: `py-16 md:py-24`, título centralizado com eyebrow verde + H2.
- Header: **sticky**, `bg-white/85 backdrop-blur`, borda inferior `zinc-200/60`.
- Cantos: `--radius: 0.5rem`. Cards geralmente `rounded-xl`/`rounded-2xl`; botões `rounded-lg`.
- Sombras: leves (`shadow-sm` em CTAs), evitar sombras pesadas — estética "tech clean".

---

## 6. Componentes — padrões

### Botões
- **Primário (CTA):** fundo `--wmst-navy`, texto branco, `rounded-lg px-5 py-2.5 font-semibold`, hover `--wmst-navy-700`.
- **Acento/ação positiva:** fundo `--wmst-green-600`, texto branco, hover `--wmst-green-700`.
- **WhatsApp:** fundo `#25D366`, hover `#1fb955` (já existe — manter).
- **Secundário/ghost:** borda `zinc-200`, texto `zinc-800`, hover `bg-zinc-50`.

### Cards
- `rounded-2xl border border-zinc-200 bg-white p-6` + hover sutil (`hover:border-[--wmst-green]/40` ou `shadow`).
- Ícones: **lucide-vue-next** (já é a lib de ícones do projeto). Ícone em navy, ou dentro de "chip" verde-soft.

### Chips / badges
- `bg-[--wmst-green-soft] text-[--wmst-green-700] text-xs font-semibold px-2.5 py-1 rounded-full`.

### Detalhes de marca reutilizáveis (motivo "mira")
- Recriar os **4 colchetes verdes** da logo como elemento decorativo em torno de imagens/heros/mockups (moldura de "scan"). É a assinatura visual mais forte da marca — usar com parcimônia como acento.

### Animação
- Classe `.reveal` / `.reveal.is-visible` (já existe) para fade-up on-scroll. Curva `cubic-bezier(0.22, 1, 0.36, 1)`, 700ms. Reusar no redesign; respeitar `prefers-reduced-motion`.

---

## 7. Dark mode

- Tokens `.dark` já definidos (escala neutra). No redesign público, priorizar **light**; garantir que navy vire branco/near-white e o verde permaneça legível. `BrandMark.vue` já troca o wordmark para branco no dark.

---

## 8. Stack técnica (contexto para implementar)

- **Laravel + Inertia + Vue 3 (`<script setup lang="ts">`) + Tailwind v4** (`@import 'tailwindcss'`).
- Ícones: **lucide-vue-next**. Navegação: **`@inertiajs/vue3` `<Link>`**.
- i18n por locale na URL (`pt_BR` sem prefixo; outros com `/{locale}`). Paths derivados em `PublicLayout.vue`.
- Componentes públicos em `resources/js/components/public/`; páginas em `resources/js/pages/public/`.
- UI kit base (shadcn-vue style) em `resources/js/components/ui/`.

---

## 9. Inventário de páginas (`resources/js/pages/public/`)

| Página | Arquivo | Objetivo | Prioridade redesign |
|---|---|---|---|
| **Home** | `Home.vue` | Hero + prova social + produtos + CTA | 🔴 Alta |
| **Produtos (lista)** | `products/Index.vue` | Grade dos SaaS (iBox, AgendaClinic, Conecta…) | 🔴 Alta |
| **Produto (detalhe)** | `products/Show.vue` | Feature page do produto + mockup + CTA | 🔴 Alta |
| **Soluções (lista)** | `services/Index.vue` | Serviços (dev sob demanda, automações IA) | 🟡 Média |
| **Solução (detalhe)** | `services/Show.vue` | Detalhe do serviço | 🟡 Média |
| **Cases** | `Cases.vue` | Resultados/portfólio com mockups | 🟡 Média |
| **Sobre** | `Sobre.vue` | História (William/WM), valores, time | 🟢 Baixa |
| **Blog (lista)** | `blog/Index.vue` | Conteúdo/SEO | 🟢 Baixa |
| **Blog (post)** | `blog/Show.vue` | Artigo | 🟢 Baixa |
| **Contato** | `Contato.vue` | Form + WhatsApp | 🟡 Média |
| **AgentAccess** | `AgentAccess.vue` | Acesso/portal | 🟢 Baixa |

Componentes de layout: `PublicHeader.vue`, `PublicFooter.vue`, `WhatsAppFloat.vue`, `BrandMark.vue`, `TrialModal.vue`.

---

## 10. Princípios de design (guia de decisões)

1. **Navy é a base, verde é o acento.** Verde nunca domina — pontua CTAs, eyebrows, o "scan frame", estados de sucesso.
2. **Clean & técnico.** Muito branco/zinc, tipografia forte (Sora), pouca sombra, cantos suaves.
3. **Mostre o produto.** Sempre que possível, mockups reais (`public/images/mockup-*`) enquadrados no motivo "mira".
4. **1 CTA claro por seção** (falar no WhatsApp / testar / ver produto).
5. **PT-BR, direto ao ponto.** Copy curta, orientada a benefício e entrega.
6. **Acessibilidade:** contraste AA no texto; verde-escuro (`#1F8A30`) para texto verde; `prefers-reduced-motion`.

---

## 11. Plano de redesign (checklist)

### Fase 0 — Fundação (fazer primeiro)
- [ ] Trocar tokens de marca no `resources/css/app.css` (§3.3): navy/verde no lugar de azul/magenta.
- [ ] Atualizar `gradient-text`, `bg-brand-radial` (§3.3).
- [ ] `theme-color` → `#16233F` no `PublicLayout.vue`.
- [ ] Conferir `BrandMark.vue` (fonte da imagem, alt, dark).
- [ ] Definir componentes base reutilizáveis: `Button`, `Card`, `Badge/Chip`, `SectionHeading` (eyebrow verde + H2), `ScanFrame` (moldura de mira).

### Fase 1 — Páginas de alto impacto
- [ ] `Home.vue` — hero, seção de produtos, prova social, CTA final.
- [ ] `products/Index.vue` + `products/Show.vue`.
- [ ] Header/Footer: revisar hovers, CTA, consistência de cor.

### Fase 2 — Conversão & portfólio
- [ ] `Cases.vue`, `services/Index.vue`, `services/Show.vue`, `Contato.vue`.

### Fase 3 — Conteúdo & resto
- [ ] `Sobre.vue`, `blog/Index.vue`, `blog/Show.vue`, `AgentAccess.vue`, `TrialModal.vue`.

### Fase 4 — Polimento
- [ ] Auditoria de contraste/acessibilidade.
- [ ] Consistência de espaçamento/tipografia entre páginas.
- [ ] Dark mode sanity check.
- [ ] Build/preview: `npm run dev` (ou `build`) e revisão visual.

---

## 12. Referência rápida (copiar/colar)

```
NAVY   #16233F   (primária / texto / símbolo)
NAVY700 #1F3358
GREEN  #35C24A   (acento / mira / "." / sucesso)
GREEN600 #2BA83E (CTA hover)
GREEN700 #1F8A30 (texto verde sobre claro — AA)
WHATSAPP #25D366 (só botão WhatsApp)
Fundo: zinc-50 · Texto: zinc-900 · Muted: zinc-500/600 · Borda: zinc-200
Fontes: Sora (display) · Inter (corpo)
Container: max-w-7xl px-4 md:px-8 · Seção: py-16 md:py-24 · Radius base: 0.5rem
```
