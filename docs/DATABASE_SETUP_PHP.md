# 📊 Configuração do Banco de Dados com PHP - StudyFlow

Este guia explica como configurar o acesso ao banco de dados usando **PHP** para o StudyFlow.

## 📋 Pré-requisitos

- PHP 7.4 ou superior (com extensão PDO MySQL)
- MySQL/MariaDB instalado e rodando
- Servidor web (Apache, Nginx) ou servidor PHP embutido

## 🚀 Instalação Rápida

### 1. Configurar Variáveis de Ambiente

Copie o arquivo de exemplo e configure suas credenciais:

```bash
cp .env.php.example .env.php
```

Edite o arquivo `.env.php` com suas credenciais do banco de dados:

```php
putenv('DB_HOST=localhost');
putenv('DB_PORT=3306');
putenv('DB_USER=seu_usuario');
putenv('DB_PASSWORD=sua_senha');
putenv('DB_NAME=studyflow');
putenv('JWT_SECRET=seu_secret_jwt_aqui_mude_em_producao');
```

### 2. Criar Banco de Dados

#### Opção A: Usando o Script PHP

```bash
php scripts/init-database.php
```

#### Opção B: Usando o Script SQL diretamente

```bash
mysql -u seu_usuario -p < database/schema.sql
```

### 3. Inserir Usuários Padrão

```bash
php scripts/seed-users.php
```

### 4. Iniciar Servidor PHP

```bash
php -S localhost:8000
```

O servidor estará rodando em `http://localhost:8000`

## 🔧 Estrutura dos Arquivos PHP

```
studyflow/
├── api/
│   ├── index.php        # Roteamento principal
│   ├── auth.php         # API de autenticação
│   ├── tasks.php        # API de tarefas
│   ├── database.php     # Classe de conexão com banco
│   └── jwt.php          # Implementação JWT
├── config/
│   └── database.php     # Configurações
├── database/
│   └── schema.sql       # Schema do banco de dados
└── scripts/
    ├── init-database.php  # Script de inicialização
    └── seed-users.php     # Script de usuários padrão
```

## 📡 Endpoints da API

### Autenticação (`/api/auth.php`)

- `POST /api/auth.php?action=login` - Login
  ```json
  {
    "email": "admin@studyflow.com",
    "password": "admin123"
  }
  ```

- `POST /api/auth.php?action=register` - Registro
  ```json
  {
    "name": "Nome do Usuário",
    "email": "email@example.com",
    "password": "senha123"
  }
  ```

- `GET /api/auth.php?action=me` - Obter usuário atual (requer token)

### Tarefas (`/api/tasks.php`)

- `GET /api/tasks.php` - Listar tarefas (requer token)
- `GET /api/tasks.php?id=1` - Obter tarefa por ID (requer token)
- `POST /api/tasks.php` - Criar tarefa (requer token)
  ```json
  {
    "title": "Título da Tarefa",
    "description": "Descrição",
    "status": "pendente",
    "priority": "media",
    "due_date": "2024-12-31"
  }
  ```
- `PUT /api/tasks.php?id=1` - Atualizar tarefa (requer token)
- `DELETE /api/tasks.php?id=1` - Deletar tarefa (requer token)

### Saúde

- `GET /api/health` - Status da API e banco de dados

## 🔐 Autenticação

Todas as rotas protegidas requerem um token JWT no header:

```
Authorization: Bearer <token>
```

O token é retornado no login/registro e deve ser armazenado no frontend.

## 🔌 Conexão com o Frontend

### Configurar a URL da API

No arquivo `js/api.js`, a URL base está configurada como:

```javascript
BASE_URL: window.API_URL || 'http://localhost:8000/api'
```

Você pode definir a URL antes de carregar os scripts:

```html
<script>
  window.API_URL = 'http://localhost:8000/api';
</script>
<script src="js/api.js"></script>
```

### Incluir o módulo API

Inclua o arquivo `api.js` nas suas páginas HTML:

```html
<script src="js/api.js"></script>
```

### Usar a API

```javascript
// Login
const response = await API.login('admin@studyflow.com', 'admin123');

// Registrar
const response = await API.register('Nome', 'email@example.com', 'senha');

// Obter tarefas
const tasks = await API.getTasks();

// Criar tarefa
const task = await API.createTask({
  title: 'Nova Tarefa',
  description: 'Descrição',
  status: 'pendente',
  priority: 'media'
});
```

## 🔐 Credenciais Padrão

Após executar `php scripts/seed-users.php`, os seguintes usuários estarão disponíveis:

### Administrador
- Email: `admin@studyflow.com`
- Senha: `admin123`

### Estudante
- Email: `estudante@studyflow.com`
- Senha: `estudante123`

**⚠️ IMPORTANTE:** Altere essas senhas em produção!

## 🐛 Troubleshooting

### Erro de Conexão

- Verifique se o MySQL está rodando:
  ```bash
  mysql -u root -p
  ```
- Verifique as credenciais no arquivo `.env.php`
- Verifique se o banco de dados existe
- Verifique se a extensão PDO MySQL está habilitada no PHP:
  ```bash
  php -m | grep pdo_mysql
  ```

### Erro de Autenticação

- Verifique se o JWT_SECRET está configurado no `.env.php`
- Limpe o localStorage do navegador
- Verifique os logs do PHP

### Porta em Uso

Mude a porta no comando:

```bash
php -S localhost:8080
```

### Erro 500 (Internal Server Error)

- Verifique os logs de erro do PHP
- Verifique as permissões dos arquivos
- Verifique se todas as extensões necessárias estão instaladas

### CORS (Cross-Origin Resource Sharing)

Os arquivos PHP já incluem headers CORS. Se ainda houver problemas:

1. Verifique se está acessando pelo mesmo domínio/porta
2. Para desenvolvimento local, use `localhost` consistentemente
3. Para produção, configure CORS no servidor web (Apache/Nginx)

## 🔒 Segurança

⚠️ **Importante para Produção:**

1. **NUNCA** commite o arquivo `.env.php` no git
2. Use senhas fortes para o banco de dados
3. Use um `JWT_SECRET` forte e único
4. Configure HTTPS em produção
5. Configure acesso restrito ao banco de dados
6. Valide e sanitize todas as entradas
7. Use prepared statements (já implementado)

## 📚 Recursos Adicionais

- [Documentação PHP PDO](https://www.php.net/manual/pt_BR/book.pdo.php)
- [Documentação MySQL](https://dev.mysql.com/doc/)
- [PHP Built-in Server](https://www.php.net/manual/pt_BR/features.commandline.webserver.php)

## 🆘 Suporte

Se encontrar problemas, verifique:
1. Logs do PHP (erros no terminal ou arquivo de log)
2. Console do navegador (F12)
3. Network tab do navegador para ver requisições HTTP
4. Teste a conexão com o banco de dados diretamente





