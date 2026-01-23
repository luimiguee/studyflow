# 🌐 Deploy do Frontend - GitHub Pages

Guia para publicar o frontend no GitHub Pages e conectá-lo ao backend.

---

## 📋 Pré-requisitos

- ✅ Backend já deployado e funcionando
- ✅ URL do backend (ex: `https://studyflow-backend.onrender.com`)
- ✅ Repositório GitHub criado

---

## 🚀 Passo 1: Preparar Repositório Local

### 1.1 Inicializar Git (se ainda não fez)

```bash
cd /caminho/para/studyflow
git init
git add .
git commit -m "Initial commit"
```

### 1.2 Criar Repositório no GitHub

1. Aceda: https://github.com/new
2. Nome: `studyflow` (ou o que preferir)
3. Público ou Privado
4. **NÃO** marque "Add README" (já tem)
5. Clique em "Create repository"

### 1.3 Conectar e Fazer Push

```bash
git remote add origin https://github.com/seu-usuario/studyflow.git
git branch -M main
git push -u origin main
```

---

## 🔧 Passo 2: Configurar URL do Backend

### 2.1 Atualizar Configuração

1. Edite o ficheiro `js/config.production.js`
2. Substitua a URL:

```javascript
window.API_URL = 'https://seu-backend-url.onrender.com/api';
```

3. Substitua `seu-backend-url` pela URL real do seu backend

### 2.2 Atualizar Páginas HTML

Adicione o script de configuração **ANTES** de `api.js` em todas as páginas:

**Em `login.html`, `register.html`, `dashboard.html`, etc:**

```html
<!-- ANTES do api.js -->
<script src="js/config.production.js"></script>
<script src="js/api.js"></script>
```

**Exemplo completo:**

```html
<script src="js/config.production.js"></script>
<script src="js/api.js"></script>
<script src="js/auth.js"></script>
```

### 2.3 Commitar Alterações

```bash
git add js/config.production.js
git add *.html
git commit -m "Configure API URL for production"
git push
```

---

## 📦 Passo 3: Configurar GitHub Pages

### 3.1 Ativar GitHub Pages

1. Vá ao repositório no GitHub
2. Clique em **"Settings"** (Configurações)
3. No menu lateral, clique em **"Pages"**
4. Em **"Source"**, escolha:
   - Branch: `main` (ou `master`)
   - Folder: `/` (root)
5. Clique em **"Save"**

### 3.2 Aguardar Deploy

- GitHub Pages leva 1-2 minutos para fazer deploy
- Verá uma mensagem: "Your site is published at..."
- URL será: `https://seu-usuario.github.io/studyflow`

---

## ✅ Passo 4: Verificar se Funciona

### 4.1 Testar no Navegador

1. Aceda: `https://seu-usuario.github.io/studyflow`
2. Abra DevTools (F12)
3. Vá ao Console e digite:
   ```javascript
   console.log(window.API_URL);
   ```
4. Deve mostrar a URL do backend configurada

### 4.2 Testar Funcionalidades

1. Tentar fazer registo
2. Verificar se aparece no Console:
   - `API Request: https://seu-backend-url/api/auth.php?action=register`
3. Verificar se dados aparecem na base de dados

---

## 🔄 Passo 5: Atualizar Código (Opcional - Auto Deploy)

GitHub Pages faz **deploy automático** sempre que fizer push:

```bash
# Fazer alterações
git add .
git commit -m "Descrição das alterações"
git push
```

Em 1-2 minutos, as alterações estarão no ar!

---

## 🛠️ Estrutura de Ficheiros no GitHub

GitHub Pages servirá apenas:

```
studyflow/
├── index.html          ✅
├── login.html          ✅
├── register.html       ✅
├── dashboard.html      ✅
├── pages/              ✅
│   └── *.html
├── css/                ✅
├── js/                 ✅
│   ├── config.production.js  ✅
│   └── ...
└── api/                ❌ (não funciona, mas pode manter)
```

**Nota**: A pasta `api/` pode estar no repositório, mas não funcionará no GitHub Pages (precisa de servidor PHP).

---

## 🎯 Resumo das URLs

Após configurar tudo:

- **Frontend**: `https://seu-usuario.github.io/studyflow`
- **Backend**: `https://seu-backend-url.onrender.com/api`
- **Base de Dados**: Gerenciada pelo serviço do backend

---

## 🔐 CORS (Se Necessário)

Se o backend estiver em domínio diferente, pode precisar configurar CORS no backend.

**No `api/auth.php`, `api/tasks.php`, etc, já tem:**

```php
header('Access-Control-Allow-Origin: *');
```

Isso permite qualquer origem. Para produção, pode restringir:

```php
header('Access-Control-Allow-Origin: https://seu-usuario.github.io');
```

---

## 🆘 Troubleshooting

### Erro: "Cannot connect to API"

1. Verifique `config.production.js` tem URL correta
2. Verifique Console do navegador para erros
3. Teste backend diretamente:
   ```bash
   curl https://seu-backend-url/api/auth.php?action=test
   ```

### Página não atualiza

1. Limpe cache do navegador (Ctrl+F5)
2. Verifique se fez push das alterações
3. Aguarde 2-3 minutos (GitHub Pages pode demorar)

### CORS Errors

- Verifique headers CORS no backend
- Adicione origem do GitHub Pages nos headers

---

## ✅ Checklist Final

- [ ] Repositório criado no GitHub
- [ ] Código feito push
- [ ] `config.production.js` configurado com URL do backend
- [ ] Scripts adicionados nas páginas HTML
- [ ] GitHub Pages ativado
- [ ] Site acessível em `https://seu-usuario.github.io/studyflow`
- [ ] API conectando corretamente
- [ ] Testado registo/login

---

**Pronto!** Agora tem o frontend no GitHub Pages e backend separado funcionando! 🎉
