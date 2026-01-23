# 🚀 Como Executar os Ficheiros PHP - StudyFlow

Este guia explica como executar os diferentes tipos de ficheiros PHP no projeto.

## 📋 Tipos de Ficheiros PHP

### 1. **Scripts de Setup** (executar via linha de comando)
- `scripts/init-database.php` - Inicializar base de dados
- `scripts/seed-users.php` - Criar utilizadores padrão

### 2. **API Backend** (executar via servidor web)
- `api/auth.php` - Autenticação
- `api/tasks.php` - Gestão de tarefas
- `api/admin.php` - Funcionalidades admin
- `api/index.php` - Router principal

---

## 🔧 Passo 1: Configurar a Base de Dados

Antes de executar qualquer coisa, configure a base de dados:

### 1.1. Editar Configuração

Abra o ficheiro `config/database.php` e ajuste as credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USER', 'seu_usuario_mysql');
define('DB_PASS', 'sua_senha_mysql');
define('DB_NAME', 'studyflow');
```

### 1.2. Executar Scripts de Setup

Abra um terminal na pasta do projeto e execute:

```bash
# Navegar para a pasta do projeto
cd /Users/miguelpato/Documents/APP_AUI/studyflow

# 1. Criar a base de dados e tabelas
php scripts/init-database.php

# 2. Criar utilizadores padrão
php scripts/seed-users.php
```

**Resultado esperado:**
```
✅ Base de dados criada com sucesso!
✅ Utilizadores padrão criados!
```

---

## 🌐 Passo 2: Iniciar o Servidor PHP

Para que a API funcione, precisa de um servidor web. Tem duas opções:

### Opção A: Servidor PHP Integrado (Recomendado para desenvolvimento)

```bash
# Na pasta do projeto
php -S localhost:8000
```

**Resultado:**
```
PHP 8.x.x Development Server started
Listening on http://localhost:8000
Document root is /Users/miguelpato/Documents/APP_AUI/studyflow
```

**✅ O servidor está a correr!** Deixe este terminal aberto.

### Opção B: Apache/Nginx (Produção)

Se tiver Apache ou Nginx configurado, coloque os ficheiros na pasta `htdocs` ou `www` e acesse via:
- `http://localhost/studyflow`

---

## 🧪 Passo 3: Testar a API

Com o servidor a correr, pode testar os endpoints:

### 3.1. Testar no Navegador

Abra o navegador e acesse:

- **API Principal**: `http://localhost:8000/api/index.php`
- **Health Check**: `http://localhost:8000/api/health`
- **Tarefas** (vai dar erro de token, mas confirma que está a funcionar): 
  `http://localhost:8000/api/tasks.php`

### 3.2. Testar via Terminal (cURL)

```bash
# Testar health check
curl http://localhost:8000/api/health

# Testar login
curl -X POST http://localhost:8000/api/auth.php?action=login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@studyflow.com","password":"admin123"}'
```

---

## 📝 Resumo: Ordem de Execução

```bash
# 1. Configurar database.php
# Editar: config/database.php

# 2. Criar base de dados
php scripts/init-database.php

# 3. Criar utilizadores
php scripts/seed-users.php

# 4. Iniciar servidor (deixar a correr)
php -S localhost:8000

# 5. Abrir no navegador
# http://localhost:8000/login.html
```

---

## 🎯 Executar Scripts PHP Individuais

### Scripts de Setup

```bash
# Inicializar base de dados
php scripts/init-database.php

# Criar utilizadores padrão
php scripts/seed-users.php
```

### Testar Ficheiros PHP Individualmente

Se quiser testar um ficheiro PHP específico:

```bash
# Testar conexão à base de dados
php -r "require 'config/database.php'; echo 'OK';"

# Testar JWT
php -r "require 'api/jwt.php'; \$token = JWT::encode(['id' => 1]); echo \$token;"
```

---

## 🔍 Verificar se Está Tudo a Funcionar

### 1. Verificar PHP

```bash
php -v
# Deve mostrar: PHP 7.4.x ou superior
```

### 2. Verificar Extensões PHP

```bash
php -m | grep pdo_mysql
# Deve mostrar: pdo_mysql
```

### 3. Verificar MySQL

```bash
mysql -u seu_usuario -p
# Deve conectar à base de dados
```

### 4. Verificar Servidor

Com o servidor a correr (`php -S localhost:8000`), acesse:
- `http://localhost:8000/api/health`

Deve retornar JSON:
```json
{
  "status": "ok",
  "database": "connected",
  "timestamp": "2024-..."
}
```

---

## 🐛 Problemas Comuns

### Erro: "Porta 8000 já em uso"

```bash
# Use outra porta
php -S localhost:8080
```

Depois ajuste em `js/api.js`:
```javascript
BASE_URL: 'http://localhost:8080/api'
```

### Erro: "Cannot connect to database"

1. Verifique se o MySQL está a correr:
   ```bash
   # macOS
   brew services list
   # ou
   sudo /usr/local/mysql/support-files/mysql.server start
   ```

2. Verifique as credenciais em `config/database.php`

3. Verifique se a base de dados existe:
   ```bash
   mysql -u root -p -e "SHOW DATABASES;"
   ```

### Erro: "Class not found" ou "require_once failed"

Certifique-se de que está a executar os comandos na pasta correta:
```bash
cd /Users/miguelpato/Documents/APP_AUI/studyflow
```

### Scripts não executam

Certifique-se de que tem permissões:
```bash
chmod +x scripts/*.php
```

---

## 📱 Acessar a Aplicação

Depois de iniciar o servidor:

1. **Abrir navegador**: `http://localhost:8000`
2. **Página de login**: `http://localhost:8000/login.html`
3. **Credenciais padrão**:
   - Admin: `admin@studyflow.com` / `admin123`
   - Estudante: `estudante@studyflow.com` / `estudante123`

---

## 🔄 Fluxo Completo de Trabalho

```bash
# Terminal 1: Servidor PHP (deixar a correr)
cd /Users/miguelpato/Documents/APP_AUI/studyflow
php -S localhost:8000

# Terminal 2: Executar scripts quando necessário
cd /Users/miguelpato/Documents/APP_AUI/studyflow
php scripts/init-database.php
php scripts/seed-users.php
```

---

## 📚 Comandos Úteis

```bash
# Ver processos PHP a correr
ps aux | grep php

# Parar servidor PHP
# Pressione Ctrl+C no terminal onde está a correr

# Ver logs do PHP (se configurado)
tail -f /var/log/php_errors.log

# Testar endpoint específico
curl http://localhost:8000/api/tasks.php \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

---

## ✅ Checklist

- [ ] PHP instalado (versão 7.4+)
- [ ] MySQL instalado e a correr
- [ ] Extensões PHP: pdo, pdo_mysql, json, mbstring
- [ ] `config/database.php` configurado
- [ ] Base de dados criada (`php scripts/init-database.php`)
- [ ] Utilizadores criados (`php scripts/seed-users.php`)
- [ ] Servidor PHP a correr (`php -S localhost:8000`)
- [ ] Navegador acessa `http://localhost:8000`

---

**🎉 Pronto! Agora pode usar a aplicação!**




