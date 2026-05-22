# Ship-Smart Analytics — Guia do Projeto Laravel

**Cliente:** Fernanda Oliveira — Gerente de Fulfillment  
**Contexto:** Conferente de centro de triagem (Cajamar, SP)  
**Necessidade:** Rastreamento de Pacotes  
**Cor primária:** #DB2777 (Rosa escuro)  
**Layout:** Completo (menu superior + lateral)

---

## Índice

1. [Instalação e Configuração](#1-instalação-e-configuração)
2. [Estrutura de Arquivos](#2-estrutura-de-arquivos)
3. [Banco de Dados (Migrations)](#3-banco-de-dados)
4. [Models](#4-models)
5. [Controllers](#5-controllers)
6. [Views (Blade)](#6-views)
7. [Rotas](#7-rotas)
8. [Como Executar os Testes](#8-testes)

---

## 2. Estrutura de Arquivos

```
ship-smart-analytics/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/                  
│   │   ├── DashboardController.php
│   │   ├── PacoteController.php
│   │   └── UsuarioController.php
│   └── Models/
│       ├── User.php
│       └── Pacote.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php    
│   │   └── ..._create_pacotes_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php         
│   ├── dashboard.blade.php
│   ├── pacotes/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   └── auth/                      
└── routes/
    └── web.php
```

---
