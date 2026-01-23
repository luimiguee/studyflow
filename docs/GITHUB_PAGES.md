# 📚 Como Publicar StudyFlow no GitHub Pages

## ⚠️ Limitações Importantes

**GitHub Pages serve APENAS ficheiros estáticos:**
- ✅ HTML, CSS, JavaScript (frontend)
- ❌ PHP não funciona
- ❌ MySQL não funciona
- ❌ Backend não funciona

## 🎯 Opções para Publicar

### Opção 1: Frontend no GitHub Pages + Backend Separado (Recomendado)

**Funcionalidades que funcionam:**
- ✅ Interface visual
- ✅ Design responsivo
- ✅ Estrutura das páginas
- ❌ Autenticação (precisa backend)
- ❌ Tarefas (precisa backend)
- ❌ Dados persistentes (precisa base de dados)

**Passos:**

1. **Criar repositório no GitHub**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/seu-usuario/studyflow.git
   git push -u origin main
   ```

2. **Configurar GitHub Pages:**
   - Vá em Settings > Pages
   - Source: Deploy from a branch
   - Branch: `main` / `/` (root)
   - Salvar

3. **Publicar backend separadamente:**
   - Use serviços como:
     - **Heroku** (gratuito para demos)
     - **Railway** (gratuito)
     - **Render** (gratuito)
     - **000webhost** (gratuito com PHP)
     - **InfinityFree** (gratuito com PHP/MySQL)

4. **Atualizar URL da API no código:**
   ```javascript
   // Em js/api.js, configurar:
   window.API_URL = 'https://seu-backend.herokuapp.com/api';
   ```

### Opção 2: Apenas Demo do Frontend (Sem Funcionalidades)

Para mostrar apenas o design/interface:

1. **Ajustar código para modo demo:**
   - O `auth.js` já tem fallback para localStorage
   - Mas funcionalidades completas precisam de backend

2. **Publicar no GitHub Pages** (mesmo processo acima)

### Opção 3: Usar Serviços com PHP/MySQL (Melhor Opção)

**Serviços que suportam PHP/MySQL:**

1. **000webhost** (gratuito)
   - Suporta PHP e MySQL
   - Upload via FTP ou interface web

2. **InfinityFree** (gratuito)
   - PHP 8.2
   - MySQL
   - cPanel

3. **Render** (gratuito para demos)
   - Precisa adaptar para Docker

4. **Railway** (gratuito com limites)
   - Suporta Docker

## 📝 Configuração para GitHub Pages

### 1. Ficheiro `.nojekyll`

Criar na raiz do projeto para GitHub Pages não usar Jekyll:

```bash
touch .nojekyll
```

### 2. Atualizar `.gitignore`

Garantir que ficheiros sensíveis não são commitados:

```gitignore
# Não commitar ficheiros sensíveis
.env
.env.local
*.log
docker/apache/logs/
```

### 3. README para GitHub

Criar/atualizar README.md com instruções claras.

## 🚀 Deploy Completo Recomendado

### Backend + Frontend Juntos:

1. **Backend no Heroku/Railway/Render:**
   - Upload apenas pasta `api/` e `config/`
   - Configurar variáveis de ambiente
   - Usar base de dados fornecida pelo serviço

2. **Frontend no GitHub Pages:**
   - Upload HTML, CSS, JS
   - Configurar `API_URL` para apontar para backend

3. **Base de Dados:**
   - Usar MySQL do serviço de hosting
   - Ou usar serviços como PlanetScale (MySQL gratuito)

## 🔧 Configuração Rápida

### Para Demo no GitHub Pages:

```bash
# 1. Criar branch gh-pages (opcional)
git checkout -b gh-pages

# 2. Remover ficheiros que não funcionam no Pages
# (manter apenas frontend)

# 3. Commit e push
git add .
git commit -m "Deploy to GitHub Pages"
git push origin gh-pages
```

### Variáveis de Ambiente (se usar backend externo):

No serviço de hosting, configurar:
```
DB_HOST=...
DB_USER=...
DB_PASSWORD=...
DB_NAME=...
JWT_SECRET=...
API_URL=...
```

## ✅ Checklist para GitHub Pages

- [ ] Criar `.nojekyll` na raiz
- [ ] Verificar `.gitignore` (não commitar senhas)
- [ ] Atualizar `js/api.js` com URL do backend externo (se usar)
- [ ] Testar localmente antes de fazer push
- [ ] Ativar GitHub Pages nas Settings
- [ ] Verificar se funciona em `https://seu-usuario.github.io/studyflow`

## 🎯 Resultado Final

**Com GitHub Pages + Backend Externo:**
- Frontend: `https://seu-usuario.github.io/studyflow`
- Backend: `https://seu-backend.herokuapp.com/api`
- ✅ Tudo funcional!

**Apenas GitHub Pages:**
- Frontend: `https://seu-usuario.github.io/studyflow`
- ❌ Backend não funciona
- ⚠️ Apenas demonstração visual

---

## 💡 Recomendação

Para ter tudo funcional **gratuitamente**:
1. **Frontend**: GitHub Pages
2. **Backend**: Render ou Railway (suportam Docker)
3. **Base de Dados**: MySQL do serviço ou PlanetScale

Ou use **000webhost/InfinityFree** que oferece tudo junto (PHP + MySQL).

---

**Nota**: GitHub Pages é ótimo para mostrar código, mas para aplicações com backend precisa de serviços adicionais.
