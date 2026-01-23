# 🚀 Como Usar o StudyFlow com XAMPP

Guia completo para executar o projeto StudyFlow usando **XAMPP** (Apache + MySQL + PHP).

---

## 📋 Pré-requisitos

- **XAMPP instalado** (versão 7.4+ ou 8.0+)
- **Navegador moderno** (Chrome, Firefox, Safari, Edge)

---

## 🔧 Passo 1: Instalar e Configurar XAMPP

### 1.1. Instalar XAMPP

1. Baixe o XAMPP: https://www.apachefriends.org/
2. **⚠️ Problema no macOS: "Ficheiro não aberto" / "Apple não conseguiu confirmar"**

   Se receber um aviso de segurança ao tentar abrir o instalador do XAMPP, siga estes passos:

   **Solução 1: Permitir manualmente (Recomendado)**
   
   1. Abra **Preferências do Sistema** (System Preferences)
   2. Vá a **Segurança e Privacidade** (Security & Privacy)
   3. Clique no separador **Geral** (General)
   4. Deve ver uma mensagem: *"xampp-osx-8.0.28-0-installer" foi bloqueado porque vem de um desenvolvedor não identificado*
   5. Clique em **"Abrir mesmo assim"** (Open Anyway)
   6. Confirme clicando em **"Abrir"** (Open) novamente

   **Solução 2: Via Terminal (Alternativa)**
   
   ```bash
   # Navegar para a pasta Downloads (ou onde está o instalador)
   cd ~/Downloads
   
   # Remover a quarentena do macOS
   xattr -d com.apple.quarantine xampp-osx-8.0.28-0-installer.dmg
   
   # Agora pode abrir normalmente
   open xampp-osx-8.0.28-0-installer.dmg
   ```

   **Solução 3: Clique direito**
   
   1. Clique com o botão direito no ficheiro `.dmg`
   2. Selecione **"Abrir"** (Open)
   3. Clique em **"Abrir"** na confirmação

3. Instale seguindo o assistente
4. **Localização padrão:**
   - **Windows**: `C:\xampp\`
   - **macOS**: `/Applications/XAMPP/`
   - **Linux**: `/opt/lampp/`

### 1.2. Iniciar Serviços XAMPP

Abra o **XAMPP Control Panel** e inicie:

- ✅ **Apache** (servidor web)
- ✅ **MySQL** (base de dados)

**Nota:** Deixe estes serviços a correr enquanto trabalha no projeto.

---

## 📁 Passo 2: Colocar o Projeto no XAMPP

### 2.1. Localizar a Pasta htdocs

A pasta `htdocs` é onde coloca os seus projetos:

- **Windows**: `C:\xampp\htdocs\`
- **macOS**: `/Applications/XAMPP/htdocs/`
- **Linux**: `/opt/lampp/htdocs/`

### 2.2. Copiar o Projeto

**Opção A: Copiar pasta completa**
```bash
# Copiar toda a pasta studyflow para htdocs
cp -r /Users/miguelpato/Documents/APP_AUI/studyflow /Applications/XAMPP/htdocs/
```

**Opção B: Criar link simbólico (recomendado - macOS/Linux)**
```bash
# Criar link simbólico (mudanças no projeto original refletem automaticamente)
ln -s /Users/miguelpato/Documents/APP_AUI/studyflow /Applications/XAMPP/htdocs/studyflow
```

**Opção C: Mover o projeto**
```bash
# Mover o projeto para htdocs
mv /Users/miguelpato/Documents/APP_AUI/studyflow /Applications/XAMPP/htdocs/
```

### 2.3. Estrutura Final

Depois de copiar, a estrutura deve ser:

```
htdocs/
└── studyflow/
    ├── api/
    ├── config/
    ├── css/
    ├── js/
    ├── pages/
    ├── scripts/
    └── index.html
```

---

## 🗄️ Passo 3: Configurar a Base de Dados

### 3.1. Aceder ao phpMyAdmin

1. Abra o navegador
2. Acesse: `http://localhost/phpmyadmin`
3. Faça login (geralmente sem senha ou senha vazia)

### 3.2. Criar Base de Dados

**Método 1: Via phpMyAdmin (Interface Gráfica)**

1. Clique em **"Novo"** ou **"New"** no menu lateral
2. Nome da base de dados: `studyflow`
3. Collation: `utf8mb4_general_ci`
4. Clique em **"Criar"** ou **"Create"**

**Método 2: Via Script PHP (Recomendado)**

```bash
# No terminal, navegar para a pasta do projeto
cd /Applications/XAMPP/htdocs/studyflow

# Executar script de inicialização
php scripts/init-database.php
```

### 3.3. Configurar Credenciais

Edite o ficheiro `config/database.php`:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USER', 'root');        // Padrão XAMPP
define('DB_PASSWORD', '');        // Padrão XAMPP (vazio)
define('DB_NAME', 'studyflow');
define('JWT_SECRET', 'seu_secret_jwt_aqui_mude_em_producao');
?>
```

**Nota:** Por padrão, o XAMPP usa:
- Usuário: `root`
- Senha: (vazia - deixe em branco)

Se alterou a senha do MySQL no XAMPP, use essa senha.

### 3.4. Criar Utilizadores Padrão

```bash
# Na pasta do projeto
cd /Applications/XAMPP/htdocs/studyflow
php scripts/seed-users.php
```

---

## 🌐 Passo 4: Acessar a Aplicação

### 4.1. URL da Aplicação

Com o XAMPP a correr, acesse:

- **Página inicial**: `http://localhost/studyflow/`
- **Login**: `http://localhost/studyflow/login.html`
- **API**: `http://localhost/studyflow/api/tasks.php`

### 4.2. Testar a API

Abra no navegador:
- `http://localhost/studyflow/api/health`

Deve retornar:
```json
{
  "status": "ok",
  "database": "connected",
  "timestamp": "2024-..."
}
```

---

## ⚙️ Passo 5: Configurar o Frontend

### 5.1. Ajustar URL da API

Se necessário, edite `js/api.js`:

```javascript
const API = {
  BASE_URL: window.API_URL || 'http://localhost/studyflow/api',
  // ...
}
```

**Nota:** Geralmente não é necessário alterar, pois o código já detecta automaticamente.

### 5.2. Verificar CORS

Os ficheiros PHP já incluem headers CORS. Se houver problemas:

1. Verifique se está a acessar via `http://localhost` (não `file://`)
2. Certifique-se de que os headers CORS estão presentes nos ficheiros PHP

---

## 🔍 Verificar se Está Tudo a Funcionar

### Checklist:

- [ ] XAMPP Control Panel aberto
- [ ] Apache está a correr (verde)
- [ ] MySQL está a correr (verde)
- [ ] Projeto copiado para `htdocs/studyflow/`
- [ ] Base de dados `studyflow` criada
- [ ] `config/database.php` configurado
- [ ] Scripts executados (`init-database.php` e `seed-users.php`)
- [ ] Acessa `http://localhost/studyflow/login.html`

### Testar:

1. **Teste de conexão à base de dados:**
   ```bash
   cd /Applications/XAMPP/htdocs/studyflow
   php -r "require 'config/database.php'; echo 'OK';"
   ```

2. **Teste da API:**
   - Navegador: `http://localhost/studyflow/api/health`
   - Deve retornar JSON com status

3. **Teste de login:**
   - Acesse: `http://localhost/studyflow/login.html`
   - Credenciais:
     - Admin: `admin@studyflow.com` / `admin123`
     - Estudante: `estudante@studyflow.com` / `estudante123`

---

## 🐛 Resolução de Problemas

### Erro: "Cannot connect to database"

**Solução:**
1. Verifique se o MySQL está a correr no XAMPP Control Panel
2. Verifique as credenciais em `config/database.php`
3. Teste a conexão no phpMyAdmin: `http://localhost/phpmyadmin`

### Erro: "404 Not Found"

**Solução:**
1. Verifique se o projeto está em `htdocs/studyflow/`
2. Verifique se o Apache está a correr
3. Tente: `http://localhost/studyflow/index.html`

### Erro: "Access denied for user 'root'@'localhost'"

**Solução:**
1. Verifique a senha do MySQL no XAMPP
2. Se alterou a senha, atualize `config/database.php`
3. Ou redefina a senha do MySQL no XAMPP

### Erro: "Port 80 already in use"

**Solução:**
1. Outro serviço está a usar a porta 80
2. **Windows:** Pare o IIS ou Skype
3. **macOS/Linux:** Verifique outros servidores web
4. Ou altere a porta do Apache no XAMPP:
   - Edite `httpd.conf` (geralmente em `C:\xampp\apache\conf\`)
   - Mude `Listen 80` para `Listen 8080`
   - Acesse via: `http://localhost:8080/studyflow/`

### Erro: "Ficheiro não aberto" / "Apple não conseguiu confirmar" (macOS)

**Solução:**
Este é um aviso de segurança normal do macOS. O XAMPP é seguro, mas não está assinado pela Apple.

**Método 1: Via Preferências do Sistema**
1. Abra **Preferências do Sistema** → **Segurança e Privacidade**
2. Clique em **"Abrir mesmo assim"** quando aparecer a mensagem
3. Confirme clicando em **"Abrir"**

**Método 2: Via Terminal**
```bash
# Remover quarentena do instalador
xattr -d com.apple.quarantine ~/Downloads/xampp-osx-*.dmg

# Ou para o XAMPP já instalado
sudo xattr -rd com.apple.quarantine /Applications/XAMPP
```

**Método 3: Clique direito**
- Clique direito no ficheiro → **"Abrir"** → Confirme

**Nota:** Se não conseguir abrir mesmo assim, pode precisar de desativar temporariamente o Gatekeeper:
```bash
sudo spctl --master-disable
# Depois de instalar, reative:
sudo spctl --master-enable
```

### Erro: "CORS" ou "Failed to fetch"

**Solução:**
1. Certifique-se de acessar via `http://localhost` (não `file://`)
2. Verifique se os headers CORS estão nos ficheiros PHP
3. Limpe o cache do navegador (Ctrl+Shift+R)

### Scripts PHP não executam

**Solução:**
```bash
# Verificar se o PHP está no PATH
php -v

# Se não estiver, use o caminho completo do XAMPP
# Windows:
C:\xampp\php\php.exe scripts/init-database.php

# macOS:
/Applications/XAMPP/xamppfiles/bin/php scripts/init-database.php
```

---

## 📝 Comandos Úteis

### Executar Scripts PHP

```bash
# Navegar para a pasta do projeto
cd /Applications/XAMPP/htdocs/studyflow

# Inicializar base de dados
php scripts/init-database.php

# Criar utilizadores
php scripts/seed-users.php
```

### Acessar MySQL via Terminal

```bash
# Windows
C:\xampp\mysql\bin\mysql.exe -u root

# macOS/Linux
/Applications/XAMPP/xamppfiles/bin/mysql -u root
```

### Ver Logs do Apache

- **Windows**: `C:\xampp\apache\logs\error.log`
- **macOS**: `/Applications/XAMPP/xamppfiles/logs/error_log`
- **Linux**: `/opt/lampp/logs/error_log`

### Ver Logs do PHP

- **Windows**: `C:\xampp\php\logs\php_error_log`
- **macOS**: `/Applications/XAMPP/xamppfiles/logs/php_error_log`
- **Linux**: `/opt/lampp/logs/php_error_log`

---

## 🔄 Fluxo de Trabalho Diário

### Iniciar Desenvolvimento:

1. Abrir **XAMPP Control Panel**
2. Iniciar **Apache** e **MySQL**
3. Abrir navegador: `http://localhost/studyflow/`
4. Trabalhar no projeto

### Parar Desenvolvimento:

1. Parar **Apache** e **MySQL** no XAMPP Control Panel
2. (Opcional) Fechar o XAMPP Control Panel

---

## 🎯 URLs Importantes

Com o XAMPP a correr:

| Recurso | URL |
|---------|-----|
| **Aplicação** | `http://localhost/studyflow/` |
| **Login** | `http://localhost/studyflow/login.html` |
| **API Health** | `http://localhost/studyflow/api/health` |
| **phpMyAdmin** | `http://localhost/phpmyadmin` |
| **XAMPP Dashboard** | `http://localhost/dashboard/` |

---

## ✅ Resumo Rápido

```bash
# 1. Iniciar XAMPP (Apache + MySQL)

# 2. Copiar projeto para htdocs
cp -r /Users/miguelpato/Documents/APP_AUI/studyflow /Applications/XAMPP/htdocs/

# 3. Configurar database.php
# Editar: htdocs/studyflow/config/database.php

# 4. Criar base de dados
cd /Applications/XAMPP/htdocs/studyflow
php scripts/init-database.php

# 5. Criar utilizadores
php scripts/seed-users.php

# 6. Acessar no navegador
# http://localhost/studyflow/login.html
```

---

## 🔒 Segurança (Produção)

⚠️ **IMPORTANTE:** O XAMPP não é seguro para produção!

Para produção:
- Use um servidor web profissional (Apache/Nginx configurado)
- Configure HTTPS
- Altere todas as senhas padrão
- Configure firewall
- Use variáveis de ambiente para credenciais
- Não exponha o phpMyAdmin publicamente

---

## 📚 Recursos Adicionais

- [Documentação XAMPP](https://www.apachefriends.org/docs.html)
- [phpMyAdmin Docs](https://www.phpmyadmin.net/docs/)
- [Apache Documentation](https://httpd.apache.org/docs/)

---

**🎉 Pronto! Agora pode usar o StudyFlow com XAMPP!**

