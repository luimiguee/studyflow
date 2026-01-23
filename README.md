# StudyFlow - Sistema de Gestão de Tarefas

Sistema completo de gestão de tarefas desenvolvido com HTML, CSS, JavaScript (frontend) e PHP/MySQL (backend).

## 📋 Índice

- [Características](#características)
- [Tecnologias](#tecnologias)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Utilização](#utilização)
- [Credenciais Padrão](#credenciais-padrão)
- [API](#api)
- [Estrutura de Dados](#estrutura-de-dados)
- [Funcionalidades](#funcionalidades)
- [Desenvolvimento](#desenvolvimento)

## ✨ Características

- ✅ **Autenticação completa** com JWT (JSON Web Tokens)
- ✅ **Gestão de tarefas** (CRUD completo)
- ✅ **Calendário visual** com visualização de tarefas por data
- ✅ **Dashboard administrativo** com estatísticas e gráficos
- ✅ **Gestão de utilizadores** (criar, editar, eliminar)
- ✅ **Logs de atividade** para auditoria
- ✅ **Interface moderna e responsiva** com design clean
- ✅ **Tema claro/escuro** (pronto para implementação)
- ✅ **API RESTful** completa

## 🛠 Tecnologias

### Frontend
- **HTML5** - Estrutura semântica
- **CSS3** - Estilos modernos com variáveis CSS e design clean
- **JavaScript (Vanilla)** - Lógica do cliente
- **Chart.js** - Gráficos para dashboard administrativo

### Backend
- **PHP 7.4+** - Linguagem do servidor
- **MySQL/MariaDB** - Base de dados
- **PDO** - Acesso à base de dados
- **JWT** - Autenticação segura

## 📁 Estrutura do Projeto

```
studyflow/
├── api/                    # Backend PHP
│   ├── index.php          # Router principal da API
│   ├── auth.php           # Endpoints de autenticação
│   ├── tasks.php          # Endpoints de tarefas
│   ├── admin.php          # Endpoints administrativos
│   ├── database.php       # Classe de conexão à BD
│   └── jwt.php            # Geração e validação de JWT
│
├── config/                 # Configurações
│   └── database.php       # Configuração da base de dados
│
├── css/                   # Estilos
│   ├── style.css         # Estilos principais
│   └── clean.css         # Estilos adicionais (design clean)
│
├── js/                    # JavaScript
│   ├── api.js            # Cliente API
│   ├── admin.js          # Funcionalidades admin
│   └── utils.js          # Funções utilitárias
│
├── pages/                 # Páginas do sistema
│   ├── tarefas.html      # Gestão de tarefas
│   ├── calendario.html   # Calendário visual
│   ├── perfil.html       # Perfil do utilizador
│   ├── admin-dashboard.html    # Dashboard admin
│   ├── admin-users.html        # Gestão de utilizadores
│   ├── admin-logs.html         # Logs de atividade
│   └── admin-settings.html     # Configurações
│
├── scripts/               # Scripts de setup
│   ├── init-database.php # Inicializar base de dados
│   └── seed-users.php    # Criar utilizadores padrão
│
├── dashboard.html         # Dashboard do estudante
├── index.html            # Página inicial
├── login.html            # Página de login
├── register.html         # Página de registo
│
├── .htaccess             # Configuração Apache (URL rewriting)
├── README.md             # Este ficheiro
└── docs/                 # Documentação adicional
    └── ...
```

## 🚀 Instalação

### Pré-requisitos

- **PHP 7.4+** com extensões: `pdo`, `pdo_mysql`, `json`, `mbstring`
- **MySQL 5.7+** ou **MariaDB 10.3+**
- **Servidor web** (XAMPP, Apache, Nginx) ou servidor PHP integrado
- **Navegador moderno** (Chrome, Firefox, Safari, Edge)

### Opções de Instalação

**Opção 1: XAMPP** (Recomendado para iniciantes)
- Veja o guia completo: [docs/COMO_USAR_XAMPP.md](docs/COMO_USAR_XAMPP.md)

**Opção 2: Servidor PHP Integrado** (Desenvolvimento rápido)
- Veja o guia: [docs/COMO_EXECUTAR_PHP.md](docs/COMO_EXECUTAR_PHP.md)

### Passos de Instalação

1. **Clone ou descarregue o projeto**

```bash
cd /caminho/para/o/projeto
```

2. **Configure a base de dados**

Edite o ficheiro `config/database.php` com as suas credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'studyflow');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
```

3. **Crie a base de dados**

```bash
mysql -u root -p
```

```sql
CREATE DATABASE studyflow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

4. **Inicialize a base de dados**

```bash
php scripts/init-database.php
```

5. **Crie utilizadores padrão**

```bash
php scripts/seed-users.php
```

6. **Inicie o servidor**

**Opção A: XAMPP** (Recomendado - veja [docs/COMO_USAR_XAMPP.md](docs/COMO_USAR_XAMPP.md))
- Copie o projeto para `htdocs/studyflow/`
- Inicie Apache e MySQL no XAMPP Control Panel
- Acesse: `http://localhost/studyflow/`

**Opção B: Servidor PHP integrado** (Desenvolvimento rápido)

```bash
php -S localhost:8000
```

Acesse: `http://localhost:8000`

**Opção C: Apache/Nginx**

Configure o VirtualHost apontando para a pasta do projeto.

7. **Acesse a aplicação**

- **XAMPP**: `http://localhost/studyflow/login.html`
- **Servidor PHP**: `http://localhost:8000/login.html`

## ⚙️ Configuração

### Configuração da Base de Dados

Edite `config/database.php`:

```php
define('DB_HOST', 'localhost');      // Host da BD
define('DB_NAME', 'studyflow');      // Nome da BD
define('DB_USER', 'usuario');        // Utilizador da BD
define('DB_PASS', 'senha');          // Palavra-passe da BD
define('DB_CHARSET', 'utf8mb4');     // Charset
```

### Configuração da API

A URL base da API está configurada em `js/api.js`:

```javascript
BASE_URL: window.API_URL || 'http://localhost:8000/api'
```

Para produção, defina `window.API_URL` antes de carregar o script.

### Configuração JWT

O secret do JWT está em `api/jwt.php`. **Altere para produção!**

```php
define('JWT_SECRET', 'seu-secret-super-seguro-aqui');
```

## 📖 Utilização

### Utilizador Estudante

1. **Registo/Login**: Aceda à página inicial e faça login ou registe-se
2. **Dashboard**: Veja o resumo das suas tarefas
3. **Tarefas**: Crie, edite e elimine tarefas
4. **Calendário**: Visualize tarefas organizadas por data
5. **Perfil**: Gerir informações pessoais

### Administrador

1. **Login**: Use as credenciais de administrador
2. **Dashboard Admin**: Visualize estatísticas do sistema
3. **Utilizadores**: Gerir todos os utilizadores do sistema
4. **Logs**: Consulte logs de atividade
5. **Configurações**: Ajuste configurações do sistema

## 🔐 Credenciais Padrão

Após executar `seed-users.php`, as seguintes credenciais estarão disponíveis:

### Administrador
- **Email**: `admin@studyflow.com`
- **Palavra-passe**: `admin123`

### Estudante
- **Email**: `estudante@studyflow.com`
- **Palavra-passe**: `estudante123`

**⚠️ IMPORTANTE**: Altere estas credenciais em produção!

## 🔌 API

### Autenticação

#### Login
```
POST /api/auth.php?action=login
Body: { "email": "user@example.com", "password": "password" }
Response: { "token": "jwt-token", "user": {...} }
```

#### Registo
```
POST /api/auth.php?action=register
Body: { "name": "Nome", "email": "user@example.com", "password": "password" }
Response: { "token": "jwt-token", "user": {...} }
```

#### Obter Utilizador Atual
```
GET /api/auth.php?action=me
Headers: { "Authorization": "Bearer jwt-token" }
Response: { "user": {...} }
```

### Tarefas

#### Listar Tarefas
```
GET /api/tasks.php
Headers: { "Authorization": "Bearer jwt-token" }
Response: { "tasks": [...] }
```

#### Obter Tarefa
```
GET /api/tasks.php?id=1
Headers: { "Authorization": "Bearer jwt-token" }
Response: { "task": {...} }
```

#### Criar Tarefa
```
POST /api/tasks.php
Headers: { "Authorization": "Bearer jwt-token" }
Body: {
  "title": "Título",
  "description": "Descrição",
  "status": "pendente|em_progresso|concluida",
  "priority": "baixa|media|alta",
  "due_date": "2024-12-31"
}
Response: { "task": {...} }
```

#### Atualizar Tarefa
```
PUT /api/tasks.php?id=1
Headers: { "Authorization": "Bearer jwt-token" }
Body: { ... }
Response: { "task": {...} }
```

#### Eliminar Tarefa
```
DELETE /api/tasks.php?id=1
Headers: { "Authorization": "Bearer jwt-token" }
Response: { "message": "Tarefa eliminada" }
```

### Admin

#### Estatísticas
```
GET /api/admin.php?action=stats
Headers: { "Authorization": "Bearer jwt-token" }
Response: { "stats": {...} }
```

#### Listar Utilizadores
```
GET /api/admin.php?action=users
Headers: { "Authorization": "Bearer jwt-token" }
Response: { "users": [...] }
```

#### Logs
```
GET /api/admin.php?action=logs&page=1&limit=50
Headers: { "Authorization": "Bearer jwt-token" }
Response: { "logs": [...], "pagination": {...} }
```

## 📊 Estrutura de Dados

### Tabela: users

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | ID único |
| name | VARCHAR(100) | Nome do utilizador |
| email | VARCHAR(100) | Email (único) |
| password | VARCHAR(255) | Hash da palavra-passe |
| role | ENUM | 'admin' ou 'student' |
| created_at | DATETIME | Data de criação |

### Tabela: tasks

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | ID único |
| user_id | INT | ID do utilizador |
| title | VARCHAR(255) | Título da tarefa |
| description | TEXT | Descrição |
| status | ENUM | 'pendente', 'em_progresso', 'concluida' |
| priority | ENUM | 'baixa', 'media', 'alta' |
| due_date | DATE | Data limite |
| created_at | DATETIME | Data de criação |
| updated_at | DATETIME | Data de atualização |

### Tabela: activity_logs

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | ID único |
| user_id | INT | ID do utilizador |
| action | VARCHAR(100) | Ação realizada |
| details | TEXT | Detalhes da ação |
| ip_address | VARCHAR(45) | Endereço IP |
| created_at | DATETIME | Data/hora |

## 🎯 Funcionalidades

### ✅ Implementado

- [x] Autenticação completa (login, registo, logout)
- [x] Gestão de tarefas (criar, ler, atualizar, eliminar)
- [x] Calendário visual com tarefas
- [x] Dashboard do estudante
- [x] Dashboard administrativo
- [x] Gestão de utilizadores (admin)
- [x] Logs de atividade
- [x] Interface responsiva
- [x] Design moderno e clean
- [x] API RESTful completa
- [x] Validação de dados
- [x] Mensagens de erro/sucesso
- [x] Filtros e busca

### 🔄 Planeado

- [ ] Tema escuro/claro
- [ ] Notificações push
- [ ] Exportação de dados (PDF, CSV)
- [ ] Anexos de ficheiros
- [ ] Comentários em tarefas
- [ ] Etiquetas (tags)
- [ ] Projetos/Grupos
- [ ] Partilha de tarefas
- [ ] API pública documentada (Swagger/OpenAPI)

## 🔧 Desenvolvimento

### Estrutura de Código

- **Frontend**: HTML semântico, CSS modular, JavaScript vanilla
- **Backend**: PHP orientado a objetos com PDO
- **API**: RESTful com JWT para autenticação
- **Base de Dados**: MySQL com relacionamentos adequados

### Padrões de Código

- **PHP**: PSR-12 (style guide)
- **JavaScript**: ES6+
- **CSS**: BEM methodology (parcial)
- **API**: RESTful conventions

### Debug

Para ativar logs de erro PHP, configure em `php.ini`:

```ini
display_errors = On
error_reporting = E_ALL
```

Para debug no navegador, use as ferramentas de desenvolvimento (F12).

## 📝 Licença

Este projeto é open source e está disponível sob a licença MIT.

## 👨‍💻 Autor

Desenvolvido com ❤️ para gestão eficiente de tarefas.---**Nota**: Para questões ou problemas, verifique os logs do servidor e do navegador.