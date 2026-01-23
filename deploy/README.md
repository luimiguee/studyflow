# 🚀 Guia Completo de Deploy - StudyFlow

Este guia explica como fazer deploy completo do StudyFlow usando **GitHub Pages + Backend Externo**.

---

## 📋 Visão Geral

**Arquitetura:**
- **Frontend**: GitHub Pages (grátis)
- **Backend**: Render ou Railway (grátis)
- **Base de Dados**: PlanetScale ou do serviço (grátis)

---

## 🎯 Índice

1. [Deploy do Backend](#-deploy-do-backend)
2. [Deploy do Frontend](#-deploy-do-frontend)
3. [Configuração Final](#-configuração-final)
4. [Troubleshooting](#-troubleshooting)

---

## 🔧 Deploy do Backend

👉 **Siga o guia completo**: [DEPLOY_BACKEND.md](DEPLOY_BACKEND.md)

**Resumo rápido:**

1. Escolha serviço: **Render** (recomendado) ou **Railway**
2. Crie base de dados: **PlanetScale** (MySQL grátis)
3. Configure variáveis de ambiente
4. Faça deploy
5. Anote a URL do backend (ex: `https://studyflow-backend.onrender.com`)

---

## 🌐 Deploy do Frontend

👉 **Siga o guia completo**: [DEPLOY_FRONTEND.md](DEPLOY_FRONTEND.md)

**Resumo rápido:**

1. Crie repositório no GitHub
2. Faça push do código
3. Configure `js/config.production.js` com URL do backend
4. Ative GitHub Pages nas Settings
5. Aceda: `https://seu-usuario.github.io/studyflow`

---

## ⚙️ Configuração Final

### 1. Atualizar Configuração do Frontend

Edite `js/config.production.js`:

```javascript
window.API_URL = 'https://seu-backend-url.onrender.com/api';
```

### 2. Ativar em Produção

Nos ficheiros HTML, descomente a linha:

```html
<!-- De: -->
<!-- <script src="js/config.production.js"></script> -->

<!-- Para: -->
<script src="js/config.production.js"></script>
```

Ficheiros a atualizar:
- `login.html`
- `register.html`
- `dashboard.html`
- `pages/*.html`

### 3. Commitar e Fazer Push

```bash
git add js/config.production.js
git add *.html
git commit -m "Configure production API URL"
git push
```

---

## ✅ Checklist Completo

### Backend
- [ ] Conta criada no Render/Railway
- [ ] Base de dados criada (PlanetScale)
- [ ] Variáveis de ambiente configuradas
- [ ] Deploy feito com sucesso
- [ ] Schema da BD executado
- [ ] API testada e funcionando
- [ ] URL do backend anotada

### Frontend
- [ ] Repositório criado no GitHub
- [ ] Código feito push
- [ ] `config.production.js` configurado
- [ ] Scripts descomentados nos HTMLs
- [ ] GitHub Pages ativado
- [ ] Site acessível
- [ ] Testado conexão com backend

---

## 🔗 URLs Finais

Após deploy completo:

- **Frontend**: `https://seu-usuario.github.io/studyflow`
- **Backend API**: `https://seu-backend-url.onrender.com/api`
- **Base de Dados**: Gerenciada pelo PlanetScale

---

## 🆘 Troubleshooting

Veja guias detalhados:
- [DEPLOY_BACKEND.md](DEPLOY_BACKEND.md) - Seção Troubleshooting
- [DEPLOY_FRONTEND.md](DEPLOY_FRONTEND.md) - Seção Troubleshooting

**Problemas comuns:**

1. **Erro de conexão**: Verifique URL do backend em `config.production.js`
2. **CORS errors**: Verifique headers CORS no backend
3. **404 na API**: Verifique estrutura de pastas no Dockerfile

---

## 📝 Próximos Passos

Após deploy:

1. Testar todas as funcionalidades
2. Configurar domínio customizado (opcional)
3. Configurar backups da BD
4. Monitorizar logs do backend

---

**Boa sorte com o deploy! 🚀**
