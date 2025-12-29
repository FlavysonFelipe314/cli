# Guia de Hospedagem Compartilhada - CLIVUS

## ✅ É possível hospedar em hospedagem compartilhada?

**SIM**, mas com algumas considerações e adaptações necessárias.

## 📋 Requisitos Mínimos

### PHP
- **PHP 8.2 ou superior** (obrigatório - Laravel 12 requer PHP 8.2+)
- Extensões PHP necessárias:
  - `openssl`
  - `pdo`
  - `mbstring`
  - `tokenizer`
  - `xml`
  - `ctype`
  - `json`
  - `bcmath`
  - `fileinfo`
  - `curl`

### Banco de Dados
- **MySQL 5.7+** ou **MariaDB 10.3+** (recomendado)
- Ou **PostgreSQL 10+**
- **SQLite** (não recomendado para produção em hospedagem compartilhada)

### Outros Requisitos
- Acesso SSH (recomendado, mas não obrigatório)
- Composer (geralmente disponível via SSH)
- Node.js/NPM (para build dos assets - pode fazer localmente)

## ⚠️ Limitações da Hospedagem Compartilhada

### 1. **Comandos Artisan via SSH**
- Alguns comandos precisam ser executados via SSH
- Se não tiver SSH, use o painel de controle da hospedagem

### 2. **Permissões de Arquivos**
- Pasta `storage/` e `bootstrap/cache/` precisam de permissão de escrita (755 ou 775)
- Pode precisar ajustar via FTP ou painel de controle

### 3. **Queue Workers**
- Hospedagem compartilhada geralmente não permite processos em background
- **Solução**: Usar `QUEUE_CONNECTION=sync` no `.env` (processa filas síncronamente)

### 4. **Webhooks do Asaas**
- Precisa de URL pública acessível
- Verifique se a hospedagem permite receber requisições POST externas

### 5. **Cron Jobs**
- Algumas hospedagens compartilhadas permitem cron jobs
- Necessário para tarefas agendadas (se houver)

## 📦 Passos para Deploy

### 1. Preparar o Projeto Localmente

```bash
# 1. Instalar dependências
composer install --optimize-autoloader --no-dev

# 2. Build dos assets
npm install
npm run build

# 3. Otimizar para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Upload dos Arquivos

**Estrutura de pastas na hospedagem:**
```
public_html/          (ou www/, htdocs/, etc)
├── index.php
├── .htaccess
└── assets/
```

**Pastas que NÃO vão para public_html:**
- `app/`
- `bootstrap/`
- `config/`
- `database/`
- `resources/`
- `routes/`
- `storage/`
- `vendor/`
- `.env`

**Opções de estrutura:**

#### Opção A: Tudo na raiz (mais simples)
```
/
├── app/
├── bootstrap/
├── config/
├── database/
├── public_html/  (ou www/)
│   ├── index.php
│   ├── .htaccess
│   └── assets/
├── resources/
├── routes/
├── storage/
├── vendor/
└── .env
```

#### Opção B: Projeto em subpasta (mais organizado)
```
/
├── clivus/
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/
│   │   ├── index.php
│   │   ├── .htaccess
│   │   └── assets/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   └── .env
└── public_html/  (apontar para clivus/public)
```

### 3. Configurar .htaccess na Raiz (se necessário)

Se o projeto estiver em subpasta, crie `.htaccess` na raiz:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ clivus/public/$1 [L]
</IfModule>
```

### 4. Configurar .env

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario_banco
DB_PASSWORD=senha_banco

# Queue (usar sync em hospedagem compartilhada)
QUEUE_CONNECTION=sync

# Cache
CACHE_DRIVER=file
SESSION_DRIVER=file

# Mail (configurar SMTP da hospedagem)
MAIL_MAILER=smtp
MAIL_HOST=mail.seudominio.com.br
MAIL_PORT=587
MAIL_USERNAME=seu_email@seudominio.com.br
MAIL_PASSWORD=sua_senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email@seudominio.com.br
MAIL_FROM_NAME="${APP_NAME}"

# Asaas
ASAAS_API_KEY=sua_chave_api
ASAAS_SANDBOX=false
```

### 5. Ajustar index.php

Se o projeto estiver em subpasta, ajuste o `public/index.php`:

```php
// Antes
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Depois (se estiver em subpasta)
require __DIR__.'/../../clivus/vendor/autoload.php';
$app = require_once __DIR__.'/../../clivus/bootstrap/app.php';
```

### 6. Configurar Permissões

Via SSH ou FTP, ajuste permissões:
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework
```

### 7. Executar Migrations

Via SSH:
```bash
php artisan migrate --force
```

Ou via painel de controle (se disponível).

## 🔧 Ajustes Necessários para Hospedagem Compartilhada

### 1. Desabilitar Queue Workers

No `.env`:
```env
QUEUE_CONNECTION=sync
```

### 2. Usar Cache de Arquivo

No `.env`:
```env
CACHE_DRIVER=file
SESSION_DRIVER=file
```

### 3. Ajustar Timeout (se necessário)

Criar `public/.user.ini` (se permitido):
```ini
max_execution_time = 300
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 10M
```

## ✅ Checklist de Deploy

- [ ] PHP 8.2+ instalado
- [ ] Extensões PHP necessárias habilitadas
- [ ] Banco de dados criado e configurado
- [ ] Arquivos enviados via FTP/SFTP
- [ ] `.env` configurado corretamente
- [ ] Permissões de pastas ajustadas
- [ ] `APP_KEY` gerado (`php artisan key:generate`)
- [ ] Migrations executadas
- [ ] Assets compilados (`npm run build`)
- [ ] Cache otimizado (`php artisan config:cache`)
- [ ] Testar acesso ao site
- [ ] Testar webhook do Asaas (URL pública)
- [ ] Configurar cron jobs (se necessário)

## 🚨 Problemas Comuns

### Erro 500
- Verificar logs em `storage/logs/laravel.log`
- Verificar permissões de pastas
- Verificar se `.env` está configurado

### Erro de Permissão
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework
```

### Assets não carregam
- Verificar se `npm run build` foi executado
- Verificar se pasta `public/assets` existe
- Verificar permissões da pasta `public`

### Webhook não funciona
- Verificar se URL é acessível publicamente
- Verificar se hospedagem permite POST externo
- Testar com ferramenta como Postman

## 📞 Suporte

Se encontrar problemas, verifique:
1. Logs em `storage/logs/laravel.log`
2. Logs do servidor (via painel de controle)
3. Documentação da hospedagem sobre Laravel

## 💡 Recomendações

Para melhor performance e menos problemas:
- **Hospedagem VPS** (mais controle)
- **Hospedagem Cloud** (escalável)
- **Hospedagem especializada em Laravel** (Hostinger, Laravel Forge, etc)

Mas hospedagem compartilhada **funciona** se seguir este guia!

