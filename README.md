# Desafio Full Stack - Grupo Adriano Cobuccio

Este projeto foi desenvolvido como parte do desafio para full stack no Grupo Adriano Cobuccio. Ele consiste em uma aplicação web full stack, demonstrando habilidades em desenvolvimento web e manipulação de dados, conforme os requisitos estabelecidos no teste.

O ambiente principal de execução utiliza **Windows**, com um **subsistema Ubuntu** e **containers Docker** configurados via **Dockerfile**. Para outros ambientes, adapte os comandos conforme necessário.

---

## 🚀 Passo a passo para rodar o projeto

### 1️⃣ Clone o repositório

```sh
git clone https://github.com/JulioCavenaghi/BrasilCard.git
```

### 2️⃣ Acesse o diretório do projeto

```sh
cd BrasilCard
```

### 3️⃣ Configure as variáveis de ambiente

Renomeie o arquivo `.env.example` para `.env`:

```sh
cp .env.example .env
```

Edite o arquivo `.env` e personalize as variáveis conforme suas necessidades:

```ini
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha
```

### 4️⃣ Instale e inicialize as dependências do frontend

Execute o seguinte comando no diretório raiz do projeto para instalar as dependências do frontend fora do container:

```sh
npm install
npm run dev
```

### 5️⃣ Inicialize os containers Docker

```sh
docker compose up -d
```

Acesse o container da aplicação:

```sh
docker compose exec app bash
```

### 6️⃣ Instale as dependências do backend

```sh
composer install
```

### 7️⃣ Gere a chave da aplicação Laravel

```sh
php artisan key:generate
```

### 8️⃣ Execute as migrações do banco de dados

```sh
php artisan migrate
```

### 9️⃣ Acesse a aplicação

Abra no navegador a URL configurada em `APP_URL`.

---

## 🛠 Tecnologias utilizadas

- **Laravel** (Framework Backend)
- **Docker** (Containers)
- **MySQL** (Banco de Dados)
- **Composer** (Gerenciador de dependências PHP)
- **NPM** (Gerenciador de pacotes Node.js)

Caso tenha dúvidas ou problemas na instalação, consulte a documentação oficial de cada tecnologia ou abra uma **issue** no repositório.

---

📌 **Autor:** Julio Cavenaghi\
📧 **Contato:** [[Julio Cavenaghi]](https://www.linkedin.com/in/juliocavenaghi/)

