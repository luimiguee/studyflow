# Guia de Resolução de Problemas - StudyFlow

## Erro: "Erro ao carregar tarefas: Load failed"

Este erro geralmente significa que o servidor PHP não está a responder. Siga estes passos:

### 1. Verificar se o servidor PHP está a correr

Abra um terminal e execute:

```bash
# Verificar se há algum processo PHP a correr na porta 8000
lsof -i :8000

# Ou no Windows/PowerShell
netstat -ano | findstr :8000
```

Se não houver nada, inicie o servidor:

```bash
cd /caminho/para/studyflow
php -S localhost:8000
```

### 2. Verificar se a API está acessível

No navegador, acesse: `http://localhost:8000/api/tasks.php`

**Esperado**: Um erro JSON como `{"error":"Token não fornecido"}`

**Se aparecer erro 404**: O ficheiro não está no local correto ou o servidor não está configurado corretamente.

**Se aparecer erro 500**: Há um erro no código PHP. Verifique os logs do servidor.

### 3. Verificar a URL da API

Abra as ferramentas de desenvolvimento do navegador (F12) e vá ao separador "Console". Procure por erros relacionados com CORS ou fetch.

A URL padrão é: `http://localhost:8000/api`

Se estiver a usar uma porta diferente ou URL diferente, ajuste em `js/api.js`:

```javascript
BASE_URL: window.API_URL || 'http://localhost:8000/api'
```

### 4. Verificar a Base de Dados

Certifique-se de que a base de dados está configurada e acessível:

```bash
# Testar conexão
php -r "
require 'config/database.php';
try {
    \$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
    echo 'Conexão OK\n';
} catch(Exception \$e) {
    echo 'Erro: '.\$e->getMessage().'\n';
}
"
```

### 5. Verificar Token de Autenticação

O erro pode ser causado por falta de autenticação. Certifique-se de:

1. Ter feito login
2. O token estar guardado no localStorage
3. O token não estar expirado

No console do navegador (F12), execute:

```javascript
localStorage.getItem('studyflow-token')
```

Se retornar `null`, faça login novamente.

### 6. Verificar Logs do PHP

Se estiver a usar o servidor integrado do PHP, os erros aparecem no terminal.

Para ver erros mais detalhados, adicione no início de `api/tasks.php`:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**Nota**: Remova estas linhas em produção!

### 7. Verificar CORS

Se estiver a aceder de um domínio diferente (não localhost), pode haver problemas de CORS. Os headers já estão configurados, mas verifique se não há conflitos.

### Solução Rápida

1. **Parar qualquer servidor PHP a correr**
2. **Iniciar o servidor PHP**:
   ```bash
   php -S localhost:8000
   ```
3. **Limpar cache do navegador** (Ctrl+Shift+R ou Cmd+Shift+R)
4. **Fazer logout e login novamente**
5. **Tentar novamente**

### Erros Comuns

#### "Failed to fetch"
- Servidor não está a correr
- URL incorreta
- Problema de rede/firewall

#### "Token não fornecido"
- Não fez login
- Token expirado
- Problema com localStorage

#### "Token inválido"
- Token corrompido
- Secret do JWT mudou
- Token expirado (se implementado)

#### Erro 500
- Erro no código PHP
- Base de dados não configurada
- Ficheiros faltando

### Verificar Conectividade

No console do navegador, teste diretamente:

```javascript
// Testar se o servidor responde
fetch('http://localhost:8000/api/tasks.php')
  .then(r => r.text())
  .then(data => console.log('Resposta:', data))
  .catch(err => console.error('Erro:', err));
```

Se retornar um erro JSON, o servidor está a funcionar. Se der erro de rede, o servidor não está acessível.





