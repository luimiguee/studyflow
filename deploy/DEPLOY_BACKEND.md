# 🚀 Deploy do Backend - StudyFlow

Guia completo para fazer deploy do backend em serviços gratuitos.

---

## 🎯 Opções de Hosting Gratuito

### Opção A: Render (Recomendado - Mais Fácil)

✅ **Vantagens:**
- Grátis para sempre (com limites)
- Suporta Docker
- Configuração simples
- Auto-deploy do GitHub
- SSL automático

### Opção B: Railway

✅ **Vantagens:**
- Grátis com créditos mensais
- Excelente para Docker
- Interface moderna

---

## 📋 Pré-requisitos

1. **Conta GitHub** (já tem)
2. **Conta Render** ou **Railway** (criar agora)
3. **Base de Dados MySQL** (pode usar o do serviço ou externo)

---

## 🔧 Opção A: Deploy no Render

### Passo 1: Criar Conta no Render

1. Aceda: https://render.com
2. Clique em "Get Started for Free"
3. Faça login com GitHub

### Passo 2: Criar Web Service

1. No dashboard, clique em **"New +"** > **"Web Service"**
2. Conecte o seu repositório GitHub
3. Configure:
   - **Name**: `studyflow-backend`
   - **Environment**: `Docker`
   - **Region**: Escolha o mais próximo (ex: Frankfurt)
   - **Branch**: `main` (ou a branch principal)
   - **Root Directory**: Deixar vazio
   - **Dockerfile Path**: `deploy/Dockerfile.backend`

### Passo 3: Configurar Base de Dados

#### Opção 3.1: MySQL do Render (Gratuito)

1. No dashboard, clique em **"New +"** > **"PostgreSQL"** (não tem MySQL gratuito)
2. Ou use **PlanetScale** (MySQL gratuito)

#### Opção 3.2: PlanetScale (Recomendado para MySQL)

1. Aceda: https://planetscale.com
2. Crie conta gratuita
3. Crie novo database:
   - **Name**: `studyflow`
   - **Region**: Escolha o mais próximo
4. Copie as credenciais:
   - Host
   - Username
   - Password
   - Database name

### Passo 4: Configurar Variáveis de Ambiente

No Render, no seu Web Service:

1. Vá em **"Environment"**
2. Adicione as seguintes variáveis:

```
DB_HOST=seu-host-do-planetscale.com
DB_PORT=3306
DB_USER=seu-usuario
DB_PASSWORD=sua-password
DB_NAME=studyflow
JWT_SECRET=gerar-um-secret-aleatorio-aqui
```

**Gerar JWT_SECRET:**
```bash
openssl rand -base64 32
```

### Passo 5: Deploy

1. Clique em **"Manual Deploy"** > **"Deploy latest commit"**
2. Aguarde o build completar (2-5 minutos)
3. Anote a URL gerada: `https://studyflow-backend.onrender.com`

### Passo 6: Inicializar Base de Dados

Após o deploy, execute o schema:

```bash
# Aceder ao container (se possível) ou usar script
curl -X POST https://studyflow-backend.onrender.com/api/init-database.php
```

Ou via MySQL diretamente na PlanetScale:
1. Vá no dashboard da PlanetScale
2. Clique em "Console"
3. Cole o conteúdo de `database/schema.sql`

---

## 🚂 Opção B: Deploy no Railway

### Passo 1: Criar Conta

1. Aceda: https://railway.app
2. Login com GitHub
3. Escolha plan gratuito ($5 créditos/mês)

### Passo 2: Criar Novo Projeto

1. Clique em **"New Project"**
2. Selecione **"Deploy from GitHub repo"**
3. Escolha o repositório

### Passo 3: Adicionar Docker Service

1. Railway detecta automaticamente o Dockerfile
2. Se não detectar, adicione manualmente:
   - Settings > Build > Dockerfile Path: `deploy/Dockerfile.backend`

### Passo 4: Adicionar MySQL Database

1. No projeto, clique em **"New"** > **"Database"** > **"MySQL"**
2. Railway cria automaticamente
3. Copie as credenciais (aparecem como variáveis)

### Passo 5: Configurar Variáveis

1. No seu service, vá em **"Variables"**
2. Adicione:
   - `DB_HOST` (Railway cria automaticamente se usar MySQL deles)
   - `DB_PORT` = `3306`
   - `DB_USER` (Railway cria)
   - `DB_PASSWORD` (Railway cria)
   - `DB_NAME` = `railway`
   - `JWT_SECRET` = (gerar com `openssl rand -base64 32`)

### Passo 6: Deploy

1. Railway faz deploy automático
2. Aguarde completar
3. Anote a URL: `https://studyflow-backend.up.railway.app`

---

## 🔐 Configurar Base de Dados

### Opção 1: Via PlanetScale Console

1. Aceda ao dashboard PlanetScale
2. Clique em **"Console"**
3. Execute o conteúdo de `database/schema.sql`

### Opção 2: Via Script PHP (se disponível)

```bash
curl -X POST https://seu-backend-url/api/init-database.php
```

### Opção 3: Via CLI (PlanetScale)

```bash
# Instalar CLI
brew install planetscale/tap/pscale

# Login
pscale auth login

# Executar schema
pscale db connect studyflow --execute < database/schema.sql
```

---

## ✅ Verificar se Funciona

Teste a API:

```bash
# Teste de health check (criar endpoint se necessário)
curl https://seu-backend-url/api/auth.php?action=test

# Teste de registo
curl -X POST https://seu-backend-url/api/auth.php?action=register \
  -H "Content-Type: application/json" \
  -d '{"name":"Teste","email":"teste@teste.com","password":"teste123"}'
```

---

## 🔗 Atualizar Frontend

Depois de ter a URL do backend:

1. No repositório GitHub, edite `js/api.js`
2. Ou crie um ficheiro de configuração:

```javascript
// js/config.js
window.API_URL = 'https://seu-backend-url.onrender.com/api';
```

3. Inclua antes de `api.js`:
```html
<script src="js/config.js"></script>
<script src="js/api.js"></script>
```

---

## 📝 Checklist

- [ ] Conta criada no Render/Railway
- [ ] Base de dados criada (PlanetScale ou do serviço)
- [ ] Variáveis de ambiente configuradas
- [ ] Deploy feito com sucesso
- [ ] Schema da BD executado
- [ ] API testada e funcionando
- [ ] Frontend atualizado com URL do backend

---

## 🆘 Troubleshooting

### Erro: "Cannot connect to database"
- Verifique variáveis de ambiente
- Confirme que a BD está acessível publicamente
- Verifique firewall/whitelist da BD

### Erro: "404 Not Found" na API
- Verifique se o Dockerfile copia os ficheiros corretos
- Confirme estrutura de pastas no container

### Build falha
- Verifique logs no Render/Railway
- Confirme que o Dockerfile está correto
- Verifique se todas as dependências estão instaladas

---

**Próximo passo**: Após backend deployado, configurar frontend no GitHub Pages!
