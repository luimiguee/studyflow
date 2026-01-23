# 🔧 Solução: Erro de Conexão com a API

## Problema

Aparece o erro: **"Erro de conexão. Verifique se o servidor está a correr e se a URL da API está correta."**

## Causas Possíveis

1. **Página aberta via `file://` em vez de `http://`**
2. **Containers Docker não estão a correr**
3. **URL da API incorreta**
4. **Problemas de CORS**

## ✅ Solução Passo a Passo

### 1. Verificar se os Containers Estão a Correr

```bash
docker-compose ps
```

Deve mostrar 3 containers com status "Up":
- `studyflow-web`
- `studyflow-db`
- `studyflow-phpmyadmin`

Se não estiverem a correr:

```bash
docker-compose up -d
```

### 2. Aceder à Aplicação pela URL Correta

⚠️ **IMPORTANTE**: Não abra o ficheiro HTML diretamente (file://)

**Use uma destas URLs:**

- **Docker**: http://localhost:5500
- **Login**: http://localhost:5500/login.html
- **Registo**: http://localhost:5500/register.html

### 3. Verificar se a API Está Respondendo

Teste no terminal:

```bash
# Testar API de registo
curl -X POST 'http://localhost:5500/api/auth.php?action=register' \
  -H 'Content-Type: application/json' \
  -d '{"name":"Test","email":"test@test.com","password":"test123"}'
```

Deve retornar JSON (pode ser erro de validação, mas deve responder).

### 4. Verificar no Navegador

1. Abra o DevTools (F12)
2. Vá ao separador **Console**
3. Digite:
   ```javascript
   console.log(window.API.BASE_URL);
   ```
4. Deve mostrar: `http://localhost:5500/api`

### 5. Verificar Logs de Erro

```bash
# Logs do container web
docker-compose logs -f web

# Logs de erro do Apache
docker-compose exec web tail -f /var/log/apache2/error.log
```

## 🐛 Debug no Navegador

Abra o Console do navegador (F12) e verifique:

1. **Erros de rede**: Vá ao separador "Network" e tente fazer registo
2. **Erros de JavaScript**: Vá ao separador "Console"
3. **URL da API**: Execute `window.API.BASE_URL` no console

## ✅ Checklist Rápido

- [ ] Containers Docker estão a correr (`docker-compose ps`)
- [ ] Acesso via `http://localhost:5500` (não `file://`)
- [ ] API responde (`curl` ou DevTools Network)
- [ ] Sem erros no Console do navegador
- [ ] `window.API.BASE_URL` mostra URL correta

## 🔄 Se Ainda Não Funcionar

1. **Reiniciar containers:**
   ```bash
   docker-compose restart
   ```

2. **Verificar portas ocupadas:**
   ```bash
   # macOS/Linux
   lsof -i :5500
   
   # Se estiver ocupada, pode alterar no docker-compose.yml
   ```

3. **Limpar e recomeçar:**
   ```bash
   docker-compose down
   docker-compose up -d --build
   ```

## 📝 Nota Importante

**Nunca abra ficheiros HTML diretamente do sistema de ficheiros!**

Sempre use: `http://localhost:5500`

---

**Última atualização**: Correção aplicada na detecção automática da URL da API.
