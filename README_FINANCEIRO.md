# Sistema Financeiro CLIVUS

Sistema completo de gestão financeira com Contas Bancárias e Transações.

## 🚀 Instalação e Configuração

### 1. Executar Migrations

```bash
php artisan migrate
```

### 2. Criar Usuário de Teste

Execute o seeder para criar um usuário padrão:

```bash
php artisan db:seed
```

**Credenciais padrão:**
- Email: `admin@clivus.com`
- Senha: `password`

Ou crie manualmente via Tinker:

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Seu Nome',
    'email' => 'seu@email.com',
    'password' => bcrypt('sua-senha'),
]);
```

### 3. Iniciar o Servidor

```bash
php artisan serve
```

Acesse: `http://127.0.0.1:8000`

## 📋 Funcionalidades

### Contas Bancárias
- ✅ Criar, editar e remover contas
- ✅ Campos completos: nome, tipo, banco, agência, conta, titular, CPF, PIX
- ✅ Visualização de saldo
- ✅ Interface responsiva mobile-first

### Transações
- ✅ Criar receitas e despesas
- ✅ Atualização automática de saldo da conta
- ✅ Filtros por conta e tipo
- ✅ Resumo financeiro (receitas, despesas, saldo)
- ✅ Histórico completo de transações

## 🎨 Sistema de Temas

O sistema possui 6 paletas de cores diferentes:

1. **Carbon Pro** (padrão)
2. **Neo Glass**
3. **Cyber Minimal**
4. **Material You**
5. **Ocean (Clivus)**
6. **Padrão**

Cada tema possui modo claro e escuro, acessível pelo botão no header.

## 📱 Responsividade

O sistema foi desenvolvido com abordagem **mobile-first**, garantindo:
- ✅ Layout adaptável para todos os dispositivos
- ✅ Menu mobile com sidebar deslizante
- ✅ Cards e formulários responsivos
- ✅ Touch-friendly em dispositivos móveis

## 🔐 Autenticação

- Login: `/login`
- Logout: Botão "Sair" no header
- Rotas protegidas com middleware `auth`

## 📁 Estrutura de Arquivos

```
app/
├── Http/Controllers/
│   ├── Auth/LoginController.php
│   └── Finance/
│       ├── AccountController.php
│       └── TransactionController.php
├── Models/
│   ├── Account.php
│   ├── Transaction.php
│   └── User.php
└── Policies/
    ├── AccountPolicy.php
    └── TransactionPolicy.php

resources/views/
├── auth/
│   └── login.blade.php
├── finance/
│   ├── accounts/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── transactions/
│       ├── index.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
└── layouts/
    └── app.blade.php
```

## 🛠️ Tecnologias

- **Backend:** Laravel 12
- **Frontend:** Blade PHP + Tailwind CSS 4
- **Database:** SQLite (configurável)
- **Autenticação:** Laravel Session

## 📝 Notas

- As transações atualizam automaticamente o saldo das contas
- Ao remover uma transação, o saldo é revertido automaticamente
- As contas são "removidas" logicamente (campo `active = false`)
- O sistema suporta múltiplos usuários com isolamento de dados

## 🐛 Troubleshooting

### Erro: "Route [login] not defined"
✅ **Resolvido!** O sistema de autenticação foi implementado.

### Erro: "Class 'App\Models\Account' not found"
Execute: `composer dump-autoload`

### Erro de permissões
Execute as migrations: `php artisan migrate`

## 📞 Suporte

Para dúvidas ou problemas, verifique:
1. Se as migrations foram executadas
2. Se o usuário foi criado
3. Se está logado corretamente
4. Logs em `storage/logs/laravel.log`

