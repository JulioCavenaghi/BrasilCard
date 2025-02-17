
let dadosSessao = null;

async function carregarDadosSessao() {
    try {
        const response = await fetch('/dadosSessao');
        const data = await response.json();

        if (data.error) {
            console.log('Erro ao obter dados da sessão:', data.error);
            return;
        }

        dadosSessao = data;
    } catch (error) {
        console.log('Erro ao buscar dados do usuário:', error);
    }
}

async function atualizarExtrato() {
    if (!dadosSessao) await carregarDadosSessao();

    fetch('/api/extrato/' + dadosSessao.id, {  
        method: 'GET',
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.transacoes && Array.isArray(data.transacoes)) {
            let listaTransacoes = document.getElementById('lista-transacoes');
            listaTransacoes.innerHTML = '';

            data.transacoes.forEach(transacao => {
                let item = document.createElement('div');
                item.classList.add('flex', 'flex-col', 'mb-4');

                let dataFormatada = new Date(transacao.data).toLocaleDateString();
                let valorFormatado = parseFloat(transacao.valor).toFixed(2).replace('.', ',');

                let tipoClasse = '', sinal = '', botaoTexto = '', botaoAcao = '';

                switch (transacao.tipo) {
                    case 1: tipoClasse = 'text-green-600'; sinal = '+'; botaoTexto = 'Solicitar Resgate'; botaoAcao = "solicitarResgate"; break;
                    case 2: tipoClasse = 'text-red-600'; sinal = '-'; break;
                    case 3: tipoClasse = 'text-green-600'; sinal = '+'; botaoTexto = 'Reembolsar'; botaoAcao = "reembolsar"; break;
                    case 4: tipoClasse = 'text-red-600'; sinal = '-'; break;
                    case 5: tipoClasse = 'text-red-600'; sinal = '-'; botaoTexto = 'Solicitar Estorno'; botaoAcao = "solicitarEstorno"; break;
                    case 6: tipoClasse = 'text-green-600'; sinal = '+'; break;
                    case 7: tipoClasse = 'text-red-600'; sinal = '-'; break;
                    case 8: tipoClasse = 'text-green-600'; sinal = '+'; break;

                }

                item.innerHTML = ` 
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-gray-800">${transacao.descricao}</span>
                            <span class="text-sm text-gray-500">Realizado em: ${dataFormatada}</span>
                        </div>
                        <div class="text-right">
                            <span class="${tipoClasse} font-semibold">
                                ${sinal} R$ ${valorFormatado}
                            </span>
                        </div>
                    </div>

                    <input type="hidden" name="transacao_id" value="${transacao.id}">

                    ${transacao.transacao_revertida == 1 ? ` 
                        <div class="mt-2">
                            <button 
                                class="w-full bg-gray-400 text-gray-600 px-4 py-2 rounded-md text-sm font-semibold hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50" disabled>
                                Não é mais possível realizar esta operação
                            </button>
                        </div>` : 
                    (botaoTexto ? `
                        <div class="mt-2">
                            <button 
                                class="w-full bg-black text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50"
                                onclick="${botaoAcao}('${transacao.descricao}', ${transacao.valor}, ${transacao.id})">
                                ${botaoTexto}
                            </button>
                        </div>` : '')}
                    <div class="border-b border-gray-200 mt-4"></div>
                `;
                listaTransacoes.appendChild(item);
            });
        } else {
            console.error('Erro: a propriedade "transacoes" está ausente ou não é um array', data);
        }
    })
    .catch(error => console.error('Erro ao atualizar as transações:', error));
}

// Funções para as ações
async function solicitarResgate(descricao, valor, transacaoId) {
    if (!dadosSessao) await carregarDadosSessao();

    if (confirm(`Deseja solicitar o resgate de "${descricao}" no valor de R$ ${valor.toFixed(2).replace('.', ',')}?`)) {
        fetch('/api/resgate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: transacaoId,
                numero_conta: dadosSessao.numero_conta,
                valor: valor,
                tipo: 2
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Resgate solicitado com sucesso!');
            atualizarExtrato();
        })
        .catch(error => console.error('Erro ao solicitar resgate:', error));
    }
}

async function reembolsar(descricao, valor, transacaoId) {
    if (!dadosSessao) await carregarDadosSessao();

    if (confirm(`Deseja reembolsar "${descricao}" no valor de R$ ${valor.toFixed(2).replace('.', ',')}?`)) {
        fetch('/api/reembolso', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: transacaoId,
                numero_conta: dadosSessao.numero_conta,
                valor: valor,
                tipo: 4
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Reembolso realizado com sucesso!');
            atualizarExtrato();
        })
        .catch(error => console.error('Erro ao reembolsar:', error));
    }
}

async function solicitarEstorno(descricao, valor, transacaoId) {
    if (!dadosSessao) await carregarDadosSessao();

    if (confirm(`Deseja solicitar o estorno de "${descricao}" no valor de R$ ${valor.toFixed(2).replace('.', ',')}?`)) {
        fetch('/api/estorno', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: transacaoId,
                numero_conta: dadosSessao.numero_conta,
                valor: valor,
                tipo: 6
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Estorno solicitado com sucesso!');
            atualizarExtrato();
        })
        .catch(error => console.error('Erro ao solicitar estorno:', error));
    }
}

carregarDadosSessao(); 
setInterval(atualizarExtrato, 5000);
  