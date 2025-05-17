#  GreenBank — Setup com Docker

Este guia descreve como configurar e executar o projeto **GreenBank** utilizando Docker, MySQL, Laravel e Vite.

---

##  Requisitos

- Docker e Docker Compose
- Node.js (versão 18+ recomendada)
- Git
- Portas utilizadas que precisam estar livres
	- Nginx - 80
	- MySQL - 3306

---

##  Passo a passo

### 1. Clone o repositório

```bash
git clone https://github.com/andr-vini/green-bank.git
cd green-bank
```

### 2. Copie o arquivo de ambiente

```bash
cp .env.example .env
```

> O arquivo `.env` já está configurado para uso com Docker (MySQL + Nginx).

### 3. Suba os containers com Docker Compose

```bash
docker-compose up -d --build
```

### 4. Instale as dependências PHP (dentro do container)

```bash
docker exec -it laravel-app composer install
```

### 5. Corrija permissões de diretórios

```bash
docker exec -it laravel-app bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
exit
```

### 6. Gere a chave da aplicação Laravel

```bash
docker exec -it laravel-app php artisan key:generate
```

---

##  Front-end (Vite)

O build do front-end deve ser feito **fora do Docker**, na sua máquina:

```bash
npm install
npm run build
```

---
##  Testes de Integração

Este projeto possui testes de integração para verificar o funcionamento completo de fluxos como o cadastro de usuário.

###  Configuração do Ambiente de Testes

Por padrão, os testes utilizam um banco de dados separado chamado `testing`. Para que os testes funcionem corretamente, é necessário criar esse banco de dados no container MySQL.

Execute o comando abaixo para criar o banco `testing`:
`docker exec -i laravel-db mysql -uroot -prootsecret -e "CREATE DATABASE IF NOT EXISTS testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"`

###  Executar os Testes

Para rodar os testes:
`docker exec -it laravel-app php artisan test`

---
##  Acessar o sistema

Abra o navegador e acesse:

 [http://localhost](http://localhost)

---

##  Comandos úteis

- Rodar migrations:

```bash
docker exec -it laravel-app php artisan migrate
```

- Rodar o Vite em modo dev:

```bash
npm run dev
```

---

##  Stack utilizada

- Laravel 12+
- PHP 8.2 (FPM)
- MySQL 8
- Nginx (via Docker)
- Node.js + Vite

---

##  Autor

Desenvolvido por Andre Vinicius — [github.com/andr-vini](https://github.com/andr-vini)
