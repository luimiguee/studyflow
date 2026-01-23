# 🚀 StudyFlow - Versão PHP

Esta é a versão PHP do backend do StudyFlow, usando PHP puro com PDO para acesso ao banco de dados MySQL.

## 📋 Pré-requisitos

- PHP 7.4+ (com extensão PDO MySQL)
- MySQL/MariaDB
- Servidor web (Apache/Nginx) ou servidor PHP embutido

## 🚀 Instalação Rápida

### 1. Configurar Variáveis de Ambiente

```bash
cp .env.php.example .env.php
```

Edite `.env.php` com suas credenciais:

```php
putenv('DB_HOST=localhost');
putenv('DB_PORT=3306');
putenv('DB_USER=root');
putenv('DB_PASSWORD=sua_senha');
putenv('DB_NAME=studyflow');
putenv('JWT_SECRET=seu_secret_jwt_aqui');
```

### 2. Criar Banco de Dados

```bash
php scripts/init-database.php
php scripts/seed-users.php
```

### 3. Iniciar Servidor

```bash
php -S localhost:8000
```

Acesse: `http://localhost:8000`

## 📡 Endpoints

- `POST /api/auth.php?action=login` - Login
- `POST /api/auth.php?action=register` - Registro  
- `GET /api/auth.php?action=me` - Usuário atual
- `GET /api/tasks.php` - Listar tarefas
- `POST /api/tasks.php` - Criar tarefa
- `PUT /api/tasks.php?id=1` - Atualizar tarefa
- `DELETE /api/tasks.php?id=1` - Deletar tarefa

## 🔐 Usuários Padrão

- Admin: `admin@studyflow.com` / `admin123`
- Estudante: `estudante@studyflow.com` / `estudante123`

## 📚 Documentação Completa

Consulte `DATABASE_SETUP_PHP.md` para documentação detalhada.





