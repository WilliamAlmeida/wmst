<div class="bg-gradient-to-br from-secondary/5 to-primary/5 bg-fixed" x-data="{
	mostrarFormulario: false,
	setup: 3000.00,
	base1: 900.00,
	base3: 750.00,
	base6: 600.00,
	whatsapp: 98.99,
	instagram: 98.99,
	proxy: 10.00, // Valor fixo do proxy WhatsApp QrCode

	// Campos do formulário de cálculo
	plano: 'padrao',
	quantidade: 1,
	qtdWhatsapp: 1,
	qtdWhatsappNaoOficial: 1, // Novo campo
	qtdInstagram: 0,

	// Resultados
	valorUnitario: 0,
	subtotal: 0,
	impostos: 0,
	totalMensal: 0,
	totalAnual: 0,
	valorSetup: 0,
	valorWhatsappTotal: 0,
	valorWhatsappNaoOficialTotal: 0, // Novo campo
	valorInstagramTotal: 0,
	mensagemEconomia: '',
	mostrarResultado: false,

	calcularPlano() {
	if (!this.plano) {
		alert('Selecione um plano válido.');
		return;
	}
	if (!this.quantidade || this.quantidade < 1) {
		alert('Informe uma quantidade válida de profissionais.');
		return;
	}
	if (this.qtdWhatsapp < 0 || this.qtdInstagram < 0 || this.qtdWhatsappNaoOficial < 0) {
		alert('Quantidade de WhatsApp/Instagram inválida.');
		return;
	}
	let desconto = 0;

	// Determinar o valor unitário com base no plano e na quantidade
	if (this.plano === 'padrao') {
		if (this.quantidade >= 6) {
		this.valorUnitario = this.base6;
		} else if (this.quantidade >= 3) {
		this.valorUnitario = this.base3;
		} else {
		this.valorUnitario = this.base1;
		}
		desconto = 0;
	} else if (this.plano === 'premium') {
		if (this.quantidade >= 6) {
		this.valorUnitario = this.base6 * 1.5;
		} else if (this.quantidade >= 3) {
		this.valorUnitario = this.base3 * 1.5;
		} else {
		this.valorUnitario = this.base1 * 1.5;
		}
		desconto = 0.1;
	}

	// Custos adicionais
	this.valorSetup = this.setup;
	this.valorWhatsappTotal = (this.qtdWhatsapp || 0) * this.whatsapp;
	this.valorWhatsappNaoOficialTotal = (this.qtdWhatsappNaoOficial || 0) * this.proxy;
	this.valorInstagramTotal = (this.qtdInstagram || 0) * this.instagram;

	// Subtotal inclui profissionais + whatsapp oficial + WhatsApp QrCode + instagram + setup
	this.subtotal = (this.valorUnitario * this.quantidade) + this.valorWhatsappTotal + this.valorWhatsappNaoOficialTotal + this.valorInstagramTotal + this.valorSetup;
	// Valor mensal recorrente (sem setup)
	this.totalMensal = (this.valorUnitario * this.quantidade) + this.valorWhatsappTotal + this.valorWhatsappNaoOficialTotal + this.valorInstagramTotal;
	// 1ª mensalidade (com setup)
	this.totalPrimeiraMensalidade = this.subtotal;
	this.totalAnual = this.totalMensal * 12 + this.valorSetup;

	if (desconto > 0) {
		this.mensagemEconomia = `Economize até ${(desconto * 100).toFixed(0)}% em relação ao plano padrão!`;
	} else {
		this.mensagemEconomia = '';
	}

	this.mostrarResultado = true;
	},

	formatarMoeda(valor) {
	return `R$ ${valor.toFixed(2)}`;
	}
}">
	<main class="max-w-6xl mx-auto p-6 space-y-4">
		<header class="mb-6 flex items-center justify-between">
			<div>
				<h1 class="text-2xl font-semibold">Planos AI Clinic — Configurador Interativo</h1>
				<p class="text-sm text-gray-600 mt-1">Visão técnica e comparativa entre Plano Padrão e Plano Premium
					(+50% valor — limites duplicados).</p>
			</div>
			<button @click="mostrarFormulario = !mostrarFormulario"
				class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">Configurar
				Valores</button>
		</header>

		<!-- Formulário de configuração -->
		<section x-show="mostrarFormulario" x-transition
			class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
			<h2 class="text-lg font-medium mb-4">Configuração de Preços e Custos</h2>
			<div class="grid md:grid-cols-3 gap-4 text-sm">
				<div>
					<label class="block font-medium">Setup Inicial (R$)</label>
					<input type="number" step="0.01" x-model.number="setup" class="w-full border rounded-md p-2" />
				</div>
				<div>
					<label class="block font-medium">Valor (1 Profissional)</label>
					<input type="number" step="0.01" x-model.number="base1" class="w-full border rounded-md p-2" />
				</div>
				<div>
					<label class="block font-medium">Valor (3+ Profissionais)</label>
					<input type="number" step="0.01" x-model.number="base3" class="w-full border rounded-md p-2" />
				</div>
				<div>
					<label class="block font-medium">Valor (6+ Profissionais)</label>
					<input type="number" step="0.01" x-model.number="base6" class="w-full border rounded-md p-2" />
				</div>
				<div>
					<label class="block font-medium">WhatsApp (R$)</label>
					<input type="number" step="0.01" x-model.number="whatsapp" class="w-full border rounded-md p-2" />
				</div>
				<div>
					<label class="block font-medium">Instagram (R$)</label>
					<input type="number" step="0.01" x-model.number="instagram" class="w-full border rounded-md p-2" />
				</div>
			</div>
			<div class="mt-4 flex justify-end">
				<button @click="mostrarFormulario = false"
					class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Aplicar</button>
			</div>
		</section>

		<section class="grid lg:grid-cols-2 gap-6">
			<!-- Card Padrão -->
			<article class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
				<div class="flex items-center justify-between mb-4">
					<div>
						<h2 class="text-lg font-medium">Plano Padrão</h2>
						<p class="text-xs text-gray-500">Base funcional com automação e multicanal</p>
					</div>
					<div class="text-right">
						<div class="text-sm text-gray-500">Valor mensal</div>
						<div class="text-xl font-semibold">R$ <span x-text="base1.toFixed(2)"></span></div>
						<div class="text-xs text-gray-500">(1 profissional)</div>
						<div class="text-xs text-gray-500">R$ <span x-text="base3.toFixed(2)"></span> (3+)</div>
						<div class="text-xs text-gray-500">R$ <span x-text="base6.toFixed(2)"></span> (6+)</div>
					</div>
				</div>

				<div class="border-t border-gray-100 pt-4">
					<h3 class="text-sm font-medium mb-2">Setup</h3>
					<p class="text-sm text-gray-600 mb-3">R$ <span x-text="setup.toFixed(2)"></span> — configuração de
						cadastros e automações iniciais</p>

					<h3 class="text-sm font-medium mb-2">Custos adicionais</h3>
					<ul class="text-sm text-gray-600 list-disc pl-5 mb-3">
						<li>Tokens IA: variável (consumo)</li>
						<li>WhatsApp QrCode: R$ 10,00 (proxy)</li>
						<li>WhatsApp: R$ <span x-text="whatsapp.toFixed(2)"></span> (Meta API)</li>
						<li>Instagram: R$ <span x-text="instagram.toFixed(2)"></span> (Meta API)</li>
					</ul>

					<h3 class="text-sm font-medium mb-2">Limites operacionais (por clínica)</h3>
					<table class="w-full text-sm border-collapse">
						<tbody>
							<tr class="border-t">
								<td class="py-2 font-medium">Contas de Usuário</td>
								<td class="py-2 text-right">2</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Agendamentos Mensais</td>
								<td class="py-2 text-right">1.000</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Pacientes Registrados</td>
								<td class="py-2 text-right">5.000</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Leads no CRM</td>
								<td class="py-2 text-right">10.000</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Funis</td>
								<td class="py-2 text-right">5</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Tags</td>
								<td class="py-2 text-right">20</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Automações CRM</td>
								<td class="py-2 text-right">10</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Campanhas WhatsApp</td>
								<td class="py-2 text-right">10 / mês (1.000 leads/campanha)</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Notificações</td>
								<td class="py-2 text-right">E-mail e WhatsApp</td>
							</tr>
						</tbody>
					</table>
				</div>
			</article>

			<!-- Card Premium -->
			<article class="bg-white border border-indigo-50 rounded-2xl shadow-sm p-6 ring-1 ring-indigo-100">
				<div class="flex items-center justify-between mb-4">
					<div>
						<h2 class="text-lg font-medium">Plano Premium</h2>
						<p class="text-xs text-gray-500">Mesma base de profissionais — limites ampliados</p>
					</div>
					<div class="text-right">
						<div class="text-sm text-gray-500">Valor mensal</div>
						<div class="text-xl font-semibold">R$ 1.350,00</div>
						<div class="text-xs text-gray-500">(1 profissional)</div>
						<div class="text-xs text-gray-500">R$ 1.125,00 (3+)</div>
						<div class="text-xs text-gray-500">R$ 900,00 (6+)</div>
					</div>
				</div>

				<div class="border-t border-indigo-50 pt-4">
					<h3 class="text-sm font-medium mb-2">Setup</h3>
					<p class="text-sm text-gray-600 mb-3">R$ <span x-text="setup.toFixed(2)"></span> — mesma
						configuração inicial</p>

					<h3 class="text-sm font-medium mb-2">Custos adicionais</h3>
					<ul class="text-sm text-gray-600 list-disc pl-5 mb-3">
						<li>Tokens IA: variável (consumo)</li>
						<li>WhatsApp QrCode: R$ 10,00 (proxy)</li>
						<li>WhatsApp: R$ <span x-text="whatsapp.toFixed(2)"></span></li>
						<li>Instagram: R$ <span x-text="instagram.toFixed(2)"></span></li>
					</ul>

					<h3 class="text-sm font-medium mb-2">Limites operacionais (por clínica)</h3>
					<table class="w-full text-sm border-collapse">
						<tbody>
							<tr class="border-t">
								<td class="py-2 font-medium">Contas de Usuário</td>
								<td class="py-2 text-right">2</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Agendamentos Mensais</td>
								<td class="py-2 text-right">2.000</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Pacientes Registrados</td>
								<td class="py-2 text-right">10.000</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Leads no CRM</td>
								<td class="py-2 text-right">20.000</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Funis</td>
								<td class="py-2 text-right">10</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Tags</td>
								<td class="py-2 text-right">40</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Automações CRM</td>
								<td class="py-2 text-right">20</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Campanhas WhatsApp</td>
								<td class="py-2 text-right">20 / mês (2.000 leads/campanha)</td>
							</tr>
							<tr class="border-t">
								<td class="py-2 font-medium">Notificações</td>
								<td class="py-2 text-right">E-mail e WhatsApp</td>
							</tr>
						</tbody>
					</table>

				</div>
			</article>
		</section>

		<!-- Nova seção: Calculadora de Planos -->
		<section class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
			<h2 class="text-lg font-medium mb-4">Calculadora de Planos</h2>
			<div class="grid md:grid-cols-3 gap-4 text-sm">
				<div>
					<label class="block font-medium" for="plano">Selecione o Plano:</label>
					<select id="plano" x-model="plano" class="select-input w-full border rounded-md p-2">
						<!-- <option value="">Escolha um plano...</option> -->
						<option value="padrao">Plano Padrão</option>
						<option value="premium">Plano Premium</option>
					</select>
				</div>
				<div>
					<label class="block font-medium" for="quantidade">Quantidade de Profissionais:</label>
					<input type="number" id="quantidade" x-model.number="quantidade"
						class="number-input w-full border rounded-md p-2" min="1" value="1"
						placeholder="Digite a quantidade">
				</div>
				<div>
					<label class="block font-medium" for="qtdWhatsapp">Qtd. WhatsApp:</label>
					<input type="number" id="qtdWhatsapp" x-model.number="qtdWhatsapp"
						class="number-input w-full border rounded-md p-2" min="0" value="0" placeholder="Qtd WhatsApp">
				</div>
				<div>
					<label class="block font-medium" for="qtdWhatsappNaoOficial">Qtd. Proxy WhatsApp:</label>
					<input type="number" id="qtdWhatsappNaoOficial" x-model.number="qtdWhatsappNaoOficial"
						class="number-input w-full border rounded-md p-2" min="0" value="0"
						placeholder="Qtd WhatsApp QrCode">
				</div>
				<div>
					<label class="block font-medium" for="qtdInstagram">Qtd. Instagram:</label>
					<input type="number" id="qtdInstagram" x-model.number="qtdInstagram"
						class="number-input w-full border rounded-md p-2" min="0" value="0" placeholder="Qtd Instagram">
				</div>
			</div>

			<div class="mt-4 flex justify-end">
				<button @click="calcularPlano()"
					class="btn-calcular px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Calcular</button>
			</div>

			<div x-show="mostrarResultado" class="resultado mt-6">
				<h3 class="text-sm font-medium mb-2">Resumo dos Custos</h3>
				<div class="bg-gray-100 p-4 rounded-lg space-y-4">
					<!-- Profissionais -->
					<div>
						<div class="font-semibold text-gray-700 mb-1 border-b border-gray-200 pb-1">Profissionais</div>
						<div class="grid grid-cols-2 gap-2 text-sm">
							<div class="font-medium">Valor por profissional:</div>
							<div class="text-right" x-text="formatarMoeda(valorUnitario)"></div>
							<div class="font-medium">Quantidade de profissionais:</div>
							<div class="text-right" x-text="quantidade"></div>
							<div class="font-medium">Setup Inicial:</div>
							<div class="text-right" x-text="formatarMoeda(valorSetup)"></div>
						</div>
					</div>
					<!-- Canais -->
					<div>
						<div class="font-semibold text-gray-700 mb-1 border-b border-gray-200 pb-1">Canais Oficiais
						</div>
						<div class="flex flex-col gap-2 text-sm *:grid *:grid-cols-2 *:gap-2">
							<div x-show="qtdWhatsapp > 0">
								<div class="font-medium">Qtd. WhatsApp:</div>
								<div class="text-right" x-text="qtdWhatsapp"></div>
								<div class="font-medium">Valor WhatsApp:</div>
								<div class="text-right" x-text="formatarMoeda(valorWhatsappTotal)"></div>
							</div>
							<div x-show="valorWhatsappNaoOficialTotal > 0">
								<div class="font-medium">Qtd. Proxy WhatsApp:</div>
								<div class="text-right" x-text="qtdWhatsappNaoOficial"></div>
								<div class="font-medium">Valor Proxy WhatsApp:</div>
								<div class="text-right" x-text="formatarMoeda(valorWhatsappNaoOficialTotal)"></div>
							</div>
							<div x-show="qtdInstagram > 0">
								<div class="font-medium">Qtd. Instagram:</div>
								<div class="text-right" x-text="qtdInstagram"></div>
								<div class="font-medium">Valor Instagram:</div>
								<div class="text-right" x-text="formatarMoeda(valorInstagramTotal)"></div>
							</div>
						</div>
					</div>
					<!-- Totais -->
					<div>
						<div class="font-semibold text-gray-700 mb-1 border-b border-gray-200 pb-1">Totais</div>
						<div class="grid grid-cols-2 gap-2 text-sm">
							<div class="font-medium">Subtotal 1ª mensalidade (com setup):</div>
							<div class="text-right font-semibold" x-text="formatarMoeda(totalPrimeiraMensalidade)">
							</div>
							<div class="font-medium">Valor mensal recorrente (sem setup):</div>
							<div class="text-right font-semibold" x-text="formatarMoeda(totalMensal)"></div>
							<div class="font-medium">Total Anual (12 meses + setup):</div>
							<div class="text-right font-semibold" x-text="formatarMoeda(totalAnual)"></div>
						</div>
					</div>
				</div>
				<div class="economia mt-4">
					<p x-text="mensagemEconomia" class="text-sm text-gray-600"></p>
				</div>
			</div>
		</section>

		<section class="mt-6">
			<div class="bg-white border border-gray-100 rounded-xl p-4 text-sm text-gray-600">
				<strong>Observações técnicas:</strong>
				<ul class="list-disc pl-5 mt-2">
					<li>Limites podem ser ajustados após análise técnica e contrato.</li>
					<li>Consumo de tokens IA deve ser monitorado por cliente; recomenda-se dashboard mensal de uso.</li>
					<li>Recomendar separação de número WhatsApp para campanhas para reduzir risco de bloqueio.</li>
				</ul>
			</div>
		</section>

		<footer class="mt-6 text-sm text-gray-500">
			<a href="https://agendaclinic.com" class="text-indigo-600 hover:underline" target="_blank">AI Clinic</a> —
			Configurador Interativo de Planos © 2025
		</footer>
	</main>
</div>