document.addEventListener('alpine:init', () => {
    Alpine.data('saldoComponent', () => ({
        open: false,
        saldo: '-',
        nome: '-',
        numeroConta: '-',
        userId: null,

        init() {
            this.fetchUserData();
        },

        fetchUserData() {
            fetch('/dadosSessao')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.log('Erro ao obter dados da sessão:', data.error);
                        return;
                    }

                    this.userId = data.id;
                    this.nome = data.name;
                    this.numeroConta = data.numero_conta;
                    this.fetchSaldo();
                    setInterval(() => {
                        this.fetchSaldo();
                    }, 5000);
                })
                .catch(error => {
                    console.log('Erro ao buscar dados do usuário:', error);
                });
        },

        fetchSaldo() {
            if (!this.userId) {
                console.log('ID do usuário não encontrado.');
                return;
            }

            fetch(`/api/saldo/${this.userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.log('Erro ao obter saldo:', data.error);
                        return;
                    }

                    this.saldo = this.formatCurrency(data.saldo);
                })
                .catch(error => {
                    console.log('Erro ao buscar saldo:', error);
                });
        },

        formatCurrency(value) {
            return parseFloat(value).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        }
    }));
});