# 🐳 Como Usar o StudyFlow com Docker

Guia completo para executar o projeto StudyFlow usando **Docker** e **Docker Compose**.

---

## 📋 Pré-requisitos

- **Docker Desktop** instalado e a correr
  - Windows/Mac: https://www.docker.com/products/docker-desktop
  - Linux: https://docs.docker.com/engine/install/
- **Docker Compose** (geralmente incluído no Docker Desktop)

---

## 🚀 Início Rápido

### Método 1: Script Automático (Recomendado)

```bash
# Dar permissão de execução ao script
chmod +x docker/init.sh

# Executar script de inicialização
./docker/init.sh
```

O script irá:
- ✅ Verificar se o Docker está a correr
- ✅ Criar ficheiro `.env` se não existir
- ✅ Construir os containers
- ✅ Iniciar os serviços
- ✅ Inicializar a base de dados
- ✅ Criar utilizadores padrão

### Método 2: Manual

```bash
# 1. Criar ficheiro .env (se não existir)
cp .env.example .env

# 2. Construir e iniciar containers
docker-compose up -d --build

# 3. Aguardar MySQL estar pronto (10-15 segundos)
sleep 15

# 4. Inicializar base de dados
docker-compose exec web php scripts/init-database.php

# 5. Criar utilizadores padrão
docker-compose exec web php scripts/seed-users.php
```

---

## 🌐 Acessos

Após iniciar os containers, acesse:

| Serviço | URL | Credenciais |
|---------|-----|-------------|
| **Aplicação** | http://localhost:8080 | Ver abaixo |
| **phpMyAdmin** | http://localhost:8081 | Ver abaixo |
| **MySQL** | localhost:3307 | Ver abaixo |

### Credenciais Padrão

**Aplicação (Login):**
- **Admin:**
  - Email: `admin@studyflow.pt`
  - Password: `admin123`
- **Estudante:**
  - Email: `estudante@studyflow.pt`
  - Password: `estudante123`

**phpMyAdmin:**
- Servidor: `db`
- Utilizador: `studyflow_user`
- Password: `studyflow_pass`

**MySQL (Acesso Direto):**
- Host: `localhost`
- Porta: `3307`
- Utilizador: `studyflow_user`
- Password: `studyflow_pass`
- Base de dados: `studyflow`

---

## 📁 Estrutura dos Containers

```
studyflow/
├── web/          → PHP 8.2 + Apache (porta 8080)
├── db/           → MySQL 8.0 (porta 3307)
└── phpmyadmin/   → phpMyAdmin (porta 8081)
```

---

## 🛠️ Comandos Úteis

### Gestão de Containers

```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Parar e remover volumes (⚠️ apaga dados!)
docker-compose down -v

# Reiniciar containers
docker-compose restart

# Ver logs
docker-compose logs -f

# Ver logs de um serviço específico
docker-compose logs -f web
docker-compose logs -f db
```

### Executar Comandos nos Containers

```bash
# Executar comando PHP no container web
docker-compose exec web php scripts/init-database.php
docker-compose exec web php scripts/seed-users.php

# Aceder ao shell do container web
docker-compose exec web bash

# Aceder ao MySQL
docker-compose exec db mysql -u studyflow_user -pstudyflow_pass studyflow
```

### Reconstruir Containers

```bash
# Reconstruir após alterações no Dockerfile
docker-compose build --no-cache
docker-compose up -d
```

---

## ⚙️ Configuração

### Variáveis de Ambiente

Edite o ficheiro `.env` para personalizar:

```env
# Base de Dados
DB_HOST=db
DB_PORT=3306
DB_USER=studyflow_user
DB_PASSWORD=studyflow_pass
DB_NAME=studyflow

# JWT Secret (mude em produção!)
JWT_SECRET=seu_secret_jwt_aqui_mude_em_producao

# API URL
API_URL=http://localhost:8080/api

# MySQL Root Password
MYSQL_ROOT_PASSWORD=root_password
```

### Alterar Portas

Se as portas 8080, 8081 ou 3307 estiverem ocupadas, edite `docker-compose.yml`:

```yaml
services:
  web:
    ports:
      - "8080:80"  # Altere 8080 para outra porta
      
  phpmyadmin:
    ports:
      - "8081:80"  # Altere 8081 para outra porta
      
  db:
    ports:
      - "3307:3306"  # Altere 3307 para outra porta
```

**Nota:** Se alterar a porta do web, atualize também `API_URL` no `.env`.

---

## 🔧 Resolução de Problemas

### Erro: "Port already in use"

**Solução:**
```bash
# Verificar qual processo está a usar a porta
# macOS/Linux:
lsof -i :8080

# Windows:
netstat -ano | findstr :8080

# Parar o processo ou alterar a porta no docker-compose.yml
```

### Erro: "Cannot connect to database"

**Solução:**
1. Verificar se o container `db` está a correr:
   ```bash
   docker-compose ps
   ```

2. Verificar logs do MySQL:
   ```bash
   docker-compose logs db
   ```

3. Aguardar mais tempo (MySQL pode demorar 10-20 segundos a iniciar):
   ```bash
   sleep 20
   docker-compose exec web php scripts/init-database.php
   ```

### Erro: "Permission denied" no macOS/Linux

**Solução:**
```bash
# Dar permissões ao script
chmod +x docker/init.sh

# Ou executar comandos manualmente
docker-compose exec web php scripts/init-database.php
```

### Limpar Tudo e Começar de Novo

```bash
# Parar e remover tudo
docker-compose down -v

# Remover imagens
docker-compose rm -f

# Reconstruir do zero
docker-compose build --no-cache
docker-compose up -d

# Aguardar e inicializar
sleep 15
docker-compose exec web php scripts/init-database.php
docker-compose exec web php scripts/seed-users.php
```

### Verificar Estado dos Containers

```bash
# Ver status
docker-compose ps

# Ver recursos utilizados
docker stats

# Ver informações detalhadas
docker-compose config
```

---

## 📊 Persistência de Dados

Os dados da base de dados são guardados num **volume Docker** chamado `db_data`. 

Isso significa que:
- ✅ Os dados persistem mesmo após `docker-compose down`
- ✅ Os dados são removidos apenas com `docker-compose down -v`

### Fazer Backup

```bash
# Exportar base de dados
docker-compose exec db mysqldump -u studyflow_user -pstudyflow_pass studyflow > backup.sql

# Importar base de dados
docker-compose exec -T db mysql -u studyflow_user -pstudyflow_pass studyflow < backup.sql
```

---

## 🔄 Atualizar o Projeto

Quando fizer alterações no código:

```bash
# As alterações são refletidas automaticamente (devido ao volume)
# Apenas recarregue a página no navegador

# Se alterar o Dockerfile ou docker-compose.yml:
docker-compose up -d --build
```

---

## 🚀 Produção

Para produção, considere:

1. **Alterar JWT_SECRET** no `.env`
2. **Alterar passwords** padrão
3. **Usar HTTPS** (adicionar nginx reverso proxy)
4. **Configurar backups** automáticos
5. **Monitorizar logs** e recursos
6. **Usar variáveis de ambiente** seguras (não commit `.env`)

---

## 📝 Notas

- O ficheiro `.env` não deve ser commitado (já está no `.gitignore`)
- Os volumes mapeiam o código para desenvolvimento rápido
- phpMyAdmin é opcional e pode ser removido do `docker-compose.yml` se não precisar
- A base de dados é inicializada automaticamente via `schema.sql` no primeiro arranque

---

## ✅ Resumo Rápido

```bash
# 1. Iniciar tudo
docker-compose up -d

# 2. Aguardar MySQL (10-15 segundos)
sleep 15

# 3. Inicializar base de dados
docker-compose exec web php scripts/init-database.php
docker-compose exec web php scripts/seed-users.php

# 4. Acessar
# http://localhost:8080
```

---

**Problemas?** Consulte a secção de resolução de problemas acima ou verifique os logs:
```bash
docker-compose logs -f
```

