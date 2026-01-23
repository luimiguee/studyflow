# 🔧 Depuração - Dados não aparecem na Base de Dados

## Problema Identificado

Os dados inseridos no site não aparecem na base de dados.

## Diagnóstico Realizado

1. ✅ **Conexão com BD**: Funcionando corretamente
2. ✅ **Tabelas**: Todas criadas (users, tasks, activity_logs)
3. ✅ **Inserção direta**: Funciona via script PHP
4. ⚠️ **API/Frontend**: Possível problema na comunicação

## Correções Aplicadas

### 1. Melhor tratamento de erros na API (`api/tasks.php`)

Adicionado:
- Validação de JSON
- Verificação se inserção foi bem-sucedida
- Verificação se `lastInsertId()` retornou valor válido
- Tratamento de erros com logs detalhados
- Logs de atividade não críticos (não interrompem se falharem)

### 2. Correção da URL da API (`js/api.js`)

Problema anterior:
- A lógica de construção de URL estava incorreta
- Podia resultar em URLs mal formadas

Correção:
- Lógica simplificada e mais robusta
- Sempre adiciona endpoint à BASE_URL corretamente

## Como Testar

### 1. Verificar se os dados estão a ser inseridos

```bash
# Ver tarefas na base de dados
docker-compose exec -T db mysql -u studyflow_user -pstudyflow_pass studyflow -e "SELECT * FROM tasks;"

# Ver utilizadores
docker-compose exec -T db mysql -u studyflow_user -pstudyflow_pass studyflow -e "SELECT id, name, email FROM users;"
```

### 2. Verificar logs de erro

```bash
# Logs do Apache
docker-compose exec web tail -f /var/log/apache2/error.log

# Logs do container web
docker-compose logs -f web
```

### 3. Testar inserção manual

```bash
# Executar script de teste
docker-compose exec web php scripts/test-db-insert.php
```

### 4. Verificar no navegador

1. Abrir DevTools (F12)
2. Ir para o separador **Console**
3. Tentar criar uma tarefa
4. Verificar se há erros no console
5. Ir para o separador **Network**
6. Verificar a requisição POST para `/api/tasks.php`
7. Verificar:
   - Status code (deve ser 201)
   - Response body (deve conter a tarefa criada)
   - Request payload (deve conter os dados corretos)

## Problemas Comuns

### Erro: "Token não fornecido"

**Causa**: Utilizador não está autenticado

**Solução**:
1. Fazer logout e login novamente
2. Verificar se o token está no localStorage:
   ```javascript
   localStorage.getItem('studyflow-token')
   ```

### Erro: "Cannot connect to database"

**Causa**: Container da BD não está a correr ou conexão incorreta

**Solução**:
```bash
# Verificar status
docker-compose ps

# Reiniciar containers
docker-compose restart
```

### Dados não aparecem mas API retorna sucesso

**Causa**: Possível problema com autocommit ou transações

**Solução**:
1. Verificar logs de erro do PHP
2. Verificar se a query realmente foi executada
3. Verificar se há problemas de permissões

## Comandos Úteis

```bash
# Ver todas as tarefas
docker-compose exec -T db mysql -u studyflow_user -pstudyflow_pass studyflow -e "SELECT id, user_id, title, status, created_at FROM tasks ORDER BY created_at DESC;"

# Ver logs de atividade
docker-compose exec -T db mysql -u studyflow_user -pstudyflow_pass studyflow -e "SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10;"

# Limpar todas as tarefas (cuidado!)
docker-compose exec -T db mysql -u studyflow_user -pstudyflow_pass studyflow -e "DELETE FROM tasks;"

# Ver estrutura da tabela
docker-compose exec -T db mysql -u studyflow_user -pstudyflow_pass studyflow -e "DESCRIBE tasks;"
```

## Próximos Passos

1. Testar criação de tarefa através da interface
2. Verificar logs em tempo real
3. Verificar se a resposta da API está correta
4. Confirmar que os dados aparecem na BD após inserção

---

**Nota**: Se o problema persistir, verificar:
- Configuração de CORS
- Headers HTTP corretos
- Autenticação JWT válida
- Permissões da base de dados
