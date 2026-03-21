# Boilerplate SaaS Laravel 13

Este é um *boilerplate* completo e moderno para a construção de aplicações SaaS (*Software as a Service*) utilizando o **Laravel 13** e o **PHP 8.3**. Foi concebido para fornecer uma base sólida, orientada a APIs, com suporte nativo para *multi-tenancy*, pagamentos via Stripe, autenticação e controlo de permissões.

## 🚀 Principais Funcionalidades

* **Framework Moderno:** Construído sobre o Laravel 13 e o PHP 8.3.
* **Autenticação de API:** Sistema seguro de início de sessão e registo utilizando o Laravel Sanctum 4.0.
* **Multi-Tenancy:** Estrutura preparada para Workspaces/Organizações (*Tenants*). Um utilizador pode ter um *tenant* predefinido e alternar entre eles.
* **Sistema de Convites:** Funcionalidade nativa para convidar novos membros para um *Tenant* por e-mail, incluindo gestão de tokens e expiração.
* **Assinaturas e Faturação (Billing):** Integração completa com o Stripe através do Laravel Cashier 16.5, com suporte para *webhooks*, portal de faturação e diversos fluxos de checkout.
* **Funções e Permissões:** Gestão robusta de acessos via Spatie Laravel Permission 7.2.
* **Frontend Preparado:** Configurado com Vite 7.0 e Tailwind CSS v4 para um desenvolvimento ágil.
* **Scripts de Produtividade:** Comandos customizados no `composer.json` para facilitar o *setup* e a execução concorrente do ambiente de desenvolvimento.

## 🛠️ Tecnologias Utilizadas

* [Laravel 13](https://laravel.com)
* [PHP 8.3+](https://www.php.net/)
* [Laravel Cashier](https://laravel.com/docs/billing) (v16.5)
* [Laravel Sanctum](https://laravel.com/docs/sanctum) (v4.0)
* [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) (v7.2)
* [Tailwind CSS v4](https://tailwindcss.com/) & [Vite](https://vitejs.dev/)
* Base de dados: SQLite (predefinido), com suporte para MySQL/PostgreSQL.

## ⚙️ Pré-requisitos

Certifique-se de ter as seguintes dependências instaladas:
* PHP 8.3 ou superior
* Composer
* Node.js (v18+) e NPM

## 📦 Instalação e Configuração

1. **Clone o repositório:**
   ```bash
   git clone [https://github.com/heuderdev/boilerplate-sass-laravel-13.git](https://github.com/heuderdev/boilerplate-sass-laravel-13.git)
   cd boilerplate-sass-laravel-13
Execute o script de configuração automática:
Este comando instala dependências, configura o .env, gera chaves e executa migrações.

Bash
composer setup
Configuração do Ambiente (.env):
Ajuste as chaves do Stripe e as credenciais de administrador no seu ficheiro .env:

Snippet de código
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
CASHIER_CURRENCY=BRL
CASHIER_CURRENCY_LOCALE=pt_BR

SUPER_ADMIN_EMAIL=superadmin@app.com
DEFAULT_TENANT_NAME="Acme Corp"
🖥️ Execução da Aplicação
Para iniciar o servidor, as filas e o Vite simultaneamente:

Bash
composer dev
Isto executará:

php artisan serve (Servidor)

php artisan queue:listen (Filas)

php artisan pail (Logs em tempo real)

npm run dev (Vite)

🛣️ Estrutura de Rotas (API)
As rotas estão organizadas em routes/api.php:

Públicas
POST /api/auth/register: Registo de utilizadores.

POST /api/auth/login: Autenticação.

POST /api/stripe/webhook: Receção de eventos do Stripe.

Protegidas (Auth & Tenant Context)
Auth: /api/auth/me e /api/auth/logout.

Tenant: Gestão de workspaces, troca de contexto e convites.

Billing: Status de subscrição, faturas, portal Stripe e fluxos de checkout.

📄 Licença
Este projeto é um software de código aberto sob a licença MIT.