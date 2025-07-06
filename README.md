<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Deploy do Projeto Laravel na Hostinger (Hospedagem Compartilhada)

## Pré-requisitos
- Conta na Hostinger com acesso SSH ou FTP
- Subdomínio criado e apontando para a pasta correta (ex: `public_html/portfolio/airbnb`)
- Projeto Laravel pronto no seu repositório Git
- Node.js e Composer instalados **localmente** (na sua máquina)

---

## Passo a Passo para Deploy

### 1. Clone o projeto no servidor

Acesse o SSH da Hostinger e vá até a pasta do subdomínio:
```bash
cd ~/public_html/portfolio/airbnb
```
Clone o repositório:
```bash
git clone <URL_DO_SEU_REPOSITORIO_GIT> .
```

---

### 2. Instale as dependências PHP

Se a Hostinger permitir:
```bash
composer install --no-dev --optimize-autoloader
```
Se não tiver composer no servidor, rode localmente e envie a pasta `vendor` para o servidor.

---

### 3. Instale as dependências e faça o build dos assets (Vite/Tailwind) **localmente**

No seu computador:
```bash
npm install
npm run build
```
Depois, envie a pasta `public/build` (ou `public/assets`) para o servidor, sobrescrevendo a pasta correspondente.

---

### 4. Crie o arquivo do banco de dados SQLite

No SSH:
```bash
mkdir -p database
cd database
[ -f database.sqlite ] || touch database.sqlite
chmod 664 database.sqlite
cd ..
```

---

### 5. Copie e configure o arquivo `.env`

No SSH:
```bash
cp .env.example .env
```
Edite o arquivo `.env` e configure:
```
APP_ENV=production
APP_DEBUG=false
APP_KEY= # será gerada no próximo passo

DB_CONNECTION=sqlite
DB_DATABASE=/home/SEU_USUARIO/public_html/portfolio/airbnb/database/database.sqlite
```

---

### 6. Gere a chave da aplicação

No SSH:
```bash
php artisan key:generate
```

---

### 7. Rode as migrações

No SSH:
```bash
php artisan migrate --force
```

---

### 8. Ajuste permissões

No SSH:
```bash
chmod -R 775 storage bootstrap/cache
```

---

### 9. (Opcional) Limpe o cache de configuração

No SSH:
```bash
php artisan config:cache
```

---

## Estrutura Recomendada

```
public_html/
└── portfolio/
    └── airbnb/
        ├── app/
        ├── bootstrap/
        ├── config/
        ├── database/
        │   └── database.sqlite
        ├── public/
        │   ├── index.php
        │   ├── build/
        │   └── ...
        ├── resources/
        ├── routes/
        ├── storage/
        ├── vendor/
        ├── .env
        └── ...
```

- O subdomínio deve apontar para a pasta `public` do projeto (`public_html/portfolio/airbnb/public`).

---

## Dicas
- Sempre rode `npm run build` **localmente** e envie os arquivos gerados para o servidor.
- O arquivo `.env` **nunca** deve ser enviado para repositórios públicos.
- Se usar outro banco (MySQL), ajuste as variáveis do `.env` conforme os dados do painel da Hostinger.
- Se der erro de permissão, ajuste com `chmod` conforme acima.

---

## Dúvidas Frequentes
- **Página padrão da Hostinger aparecendo:**
  Verifique se o subdomínio está apontando para a pasta `public` do Laravel.
- **Erro de chave de aplicação:**
  Rode `php artisan key:generate`.
- **Erro de banco de dados:**
  Verifique o caminho do banco no `.env` e as permissões do arquivo.

---

Pronto! Seu projeto Laravel estará rodando na Hostinger. Se precisar de mais detalhes, consulte a [documentação oficial do Laravel](https://laravel.com/docs) ou o suporte da Hostinger.
