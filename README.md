# Boilerplate SaaS Laravel 13

Aquest és un boilerplate complet i modern per a la construcció d'aplicacions SaaS (Software as a Service) utilitzant **Laravel 13** i **PHP 8.3**. Està dissenyat per proporcionar una base sòlida, orientada a APIs, amb suport natiu per a multi-tenancy, pagaments via Stripe, autenticació i control de permisos.

## 🚀 Característiques Principals

* **Framework Modern:** Construït sobre Laravel 13 i PHP 8.3.
* **Autenticació d'API:** Sistema segur d'inici de sessió i registre utilitzant [Laravel Sanctum](https://laravel.com/docs/sanctum).
* **Multi-Tenancy:** Estructura preparada per a Espais de Treball/Organitzacions (Tenants). Un usuari pot tenir un tenant per defecte i canviar entre ells.
* **Sistema d'Invitacions:** Funcionalitat nativa per convidar nous membres a un Tenant per correu electrònic (amb tokens d'acceptació).
* **Subscripcions i Facturació (Billing):** Integració completa amb Stripe mitjançant [Laravel Cashier](https://laravel.com/docs/billing), incloent-hi suport per a webhooks, portal de facturació, compra de crèdits i canvi de plans. El `Tenant` és l'entitat pagadora (Billable).
* **Rols i Permisos:** Gestió robusta d'accessos integrada amb el paquet [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission).
* **Frontend Preparat:** Eines configurades amb **Vite** i **Tailwind CSS v4** per a actius dinàmics i ràpids.
* **Scripts de Productivitat:** Comandes optimitzades al `composer.json` per executar l'entorn de desenvolupament de forma concurrent (Servidor PHP, Cues i Vite).

## 🛠️ Tecnologies Utilitzades

* [Laravel 13](https://laravel.com)
* [PHP 8.3+](https://www.php.net/)
* [Laravel Cashier](https://laravel.com/docs/billing) (v16.5)
* [Laravel Sanctum](https://laravel.com/docs/sanctum) (v4.0)
* [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) (v7.2)
* [Tailwind CSS v4](https://tailwindcss.com/) & [Vite](https://vitejs.dev/)
* Base de dades: SQLite (configuració per defecte) / MySQL / PostgreSQL.

## ⚙️ Requisits Previs

Assegureu-vos de tenir les següents dependències instal·lades al vostre entorn de desenvolupament:
* PHP 8.3 o superior
* Composer
* Node.js (v18+) i NPM/Yarn/PNPM

## 📦 Instal·lació i Configuració

Gràcies als scripts personalitzats de Composer, la configuració inicial del projecte és molt senzilla.

1. **Clona el repositori:**

    git clone https://github.com/el-teu-usuari/boilerplate-sass-laravel-13.git
    cd boilerplate-sass-laravel-13

2. **Executa l'script de configuració automàtica:**
   Aquesta comanda instal·larà les dependències de PHP, crearà l'arxiu `.env`, generarà la clau de l'aplicació, executarà les migracions, instal·larà les dependències de Node i compilarà els actius.

    composer setup

3. **Configuració de l'Entorn (.env):**
   Obriu l'arxiu `.env` creat recentment i ajusteu les configuracions necessàries. Presteu especial atenció a les claus de Stripe i a les configuracions del superadministrador:

    # Credencials de Stripe
    STRIPE_KEY=pk_test_...
    STRIPE_SECRET=sk_test_...
    STRIPE_WEBHOOK_SECRET=whsec_...

    # Configuració de la Facturació (Moneda)
    CASHIER_CURRENCY=BRL
    CASHIER_CURRENCY_LOCALE=pt_BR

    # Configuracions Administratives
    SUPER_ADMIN_EMAIL=superadmin@app.com
    SYSTEM_ADMIN_EMAIL=admin@app.com
    DEFAULT_TENANT_NAME="Acme Corp"

## 🖥️ Execució de l'Aplicació

Per iniciar el servidor de desenvolupament, el processament de cues i Vite simultàniament, utilitzeu la comanda:

    composer dev

Això iniciarà:
* El servidor integrat de Laravel (`php artisan serve`)
* El treballador de cues (`php artisan queue:listen`)
* Laravel Pail per als registres (logs)
* El servidor de desenvolupament de Vite (`npm run dev`)

## 🛣️ Estructura de Rutes (API)

Les rutes de l'aplicació estan centrades a respondre via API (`routes/api.php`), protegides per Sanctum i pel middleware de context del Tenant.

### Públiques
* `POST /api/auth/register`: Registre de nous usuaris.
* `POST /api/auth/login`: Autenticació.
* `POST /api/invites/{token}/accept-new`: Acceptació d'invitació per a usuaris sense compte.
* `POST /api/stripe/webhook`: Endpoint perquè Cashier processi els esdeveniments de Stripe.

### Protegides (Requereixen Token)
* **Auth:** Tancament de sessió i dades de l'usuari connectat (`/api/auth/me`).
* **Tenant (`/api/tenant`):** * Llistar, crear, obtenir el tenant actual i establir el tenant per defecte.
  * Canviar entre tenants (`/switch/{tenant}`).
  * Gestió d'invitacions (`/invites`).
* **Invites:** Acceptar invitacions amb un usuari ja connectat.
* **Billing (`/api/billing`):**
  * Estat de la subscripció i factures.
  * Enllaç al Portal del Client de Stripe (`/portal`).
  * Checkouts per a subscripcions (`/subscription/checkout`), compra única o crèdits.
  * Cancel·lació, represa i canvi de plans.

## 🤝 Contribució

Sentiu-vos lliures d'enviar *Pull Requests* o obrir *Issues* per informar d'errors i suggerir noves funcionalitats per a aquest boilerplate.

## 📄 Llicència

Aquest boilerplate és programari de codi obert amb llicència [MIT](https://opensource.org/licenses/MIT).