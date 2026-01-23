# ⚡ Quick Start - Deploy StudyFlow

Guia rápido para fazer deploy em 15 minutos.

---

## 🎯 Passo 1: Backend (5 minutos)

### Render (Mais Fácil)

1. **Criar conta**: https://render.com (login com GitHub)

2. **Criar Web Service**:
   - New + → Web Service
   - Connect GitHub repo
   - Name: `studyflow-backend`
   - Environment: **Docker**
   - Dockerfile Path: `deploy/Dockerfile.backend`
   - Region: Frankfurt (ou mais próximo)
   - Branch: `main`

3. **Criar MySQL** (PlanetScale):
   - https://planetscale.com
   - Criar database `studyflow`
   - Copiar credenciais

4. **Configurar Variáveis** (no Render):
   ```
   DB_HOST=seu-host.planetscale.com
   DB_PORT=3306
   DB_USER=seu-usuario
   DB_PASSWORD=sua-password
   DB_NAME=studyflow
   JWT_SECRET=gerar-com-openssl-rand-base64-32
   ```

5. **Deploy**: Manual Deploy → Deploy latest commit

6. **Anotar URL**: `https://studyflow-backend.onrender.com`

---

## 🌐 Passo 2: Frontend (5 minutos)

1. **GitHub**:
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/seu-usuario/studyflow.git
   git push -u origin main
   ```

2. **Configurar API**:
   - Editar `js/config.production.js`
   - Colocar URL do backend: `https://studyflow-backend.onrender.com/api`

3. **Ativar Produção**:
   - Em `login.html`, `register.html`, `dashboard.html`:
   - Descomentar: `<script src="js/config.production.js"></script>`

4. **Fazer Push**:
   ```bash
   git add .
   git commit -m "Configure production"
   git push
   ```

5. **GitHub Pages**:
   - Settings → Pages
   - Source: `main` branch, `/` folder
   - Save

6. **Aguardar**: 1-2 minutos

---

## ✅ Passo 3: Inicializar BD (2 minutos)

### Via PlanetScale Console:

1. Dashboard PlanetScale
2. Clique em "Console"
3. Execute o conteúdo de `database/schema.sql`
4. Execute `scripts/seed-users.php` manualmente ou via API

---

## 🧪 Passo 4: Testar (3 minutos)

1. **Frontend**: `https://seu-usuario.github.io/studyflow`
2. **Fazer registo**: Criar conta
3. **Verificar**: Dados aparecem na BD?

---

## 🎉 Pronto!

- ✅ Frontend: GitHub Pages
- ✅ Backend: Render
- ✅ BD: PlanetScale
- ✅ Tudo grátis e funcionando!

---

## 🆘 Problemas?

- **Erro conexão**: Verificar `config.production.js`
- **CORS**: Backend já tem headers configurados
- **404 API**: Verificar estrutura Dockerfile

Veja guias detalhados em `DEPLOY_BACKEND.md` e `DEPLOY_FRONTEND.md`
