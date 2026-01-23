# 🚀 Comandos Essenciais - StudyFlow

Referência rápida dos comandos mais utilizados no projeto StudyFlow.

---

## ✅ Verificar e Iniciar Docker

### Verificar se Docker está a correr

```bash
# Verificar status do Docker
docker info

# Verificar versão
docker --version
docker-compose --version
```

### Iniciar Docker Desktop (macOS/Windows)

**macOS:**
```bash
# Abrir Docker Desktop via terminal
open -a Docker

# Ou iniciar manualmente:
# 1. Abrir Docker Desktop da pasta Applications
# 2. Aguardar o ícone do Docker aparecer na barra de menu (topo)
# 3. Verificar se está verde/ativo
```

**Windows:**
```bash
# Iniciar Docker Desktop
# 1. Procurar "Docker Desktop" no menu Iniciar
# 2. Aguardar o ícone aparecer na system tray
# 3. Verificar se está "Running"
```

**Linux:**
```bash
# Iniciar serviço Docker
sudo systemctl start docker

# Verificar status
sudo systemctl status docker

# Habilitar para iniciar automaticamente
sudo systemctl enable docker
```

### Erro: "Cannot connect to Docker daemon"

**Solução:**
1. **Verificar se Docker Desktop está a correr:**
   - macOS: Verificar ícone na barra de menu (topo)
   - Windows: Verificar ícone na system tray
   - Linux: `sudo systemctl status docker`

2. **Reiniciar Docker Desktop:**
   - macOS/Windows: Fechar e abrir Docker Desktop novamente
   - Linux: `sudo systemctl restart docker`

3. **Verificar permissões (Linux):**
   ```bash
   # Adicionar utilizador ao grupo docker
   sudo usermod -aG docker $USER
   # Fazer logout e login novamente
   ```

4. **Testar conexão:**
   ```bash
   docker ps
   ```

---

## 🐳 Docker - Comandos Principais

### Iniciar o Projeto

```bash
# Iniciar todos os containers
docker-compose up -d

# Iniciar e reconstruir (após alterações no Dockerfile)
docker-compose up -d --build

# Iniciar com script automático
chmod +x docker/init.sh
./docker/init.sh
```

### Parar o Projeto

```bash
# Parar containers (mantém dados)
docker-compose down

# Parar e remover volumes (⚠️ apaga dados!)
docker-compose down -v

# Parar apenas
docker-compose stop
```

### Reiniciar

```bash
# Reiniciar containers
docker-compose restart

# Reiniciar serviço específico
docker-compose restart web
docker-compose restart db
```

### Ver Logs

```bash
# Ver todos os logs
docker-compose logs -f

# Ver logs de um serviço específico
docker-compose logs -f web
docker-compose logs -f db
docker-compose logs -f phpmyadmin

# Ver últimas 100 linhas
docker-compose logs --tail=100
```

### Estado dos Containers

```bash
# Ver status dos containers
docker-compose ps

# Ver recursos utilizados
docker stats

# Ver informações detalhadas
docker-compose config
```

---

## 🗄️ Base de Dados

### Inicialização

```bash
# Aguardar MySQL estar pronto (10-15 segundos)
sleep 15

# Inicializar base de dados
docker-compose exec web php scripts/init-database.php

# Criar utilizadores padrão
docker-compose exec web php scripts/seed-users.php
```

### Acesso ao MySQL

```bash
# Aceder ao MySQL via container
docker-compose exec db mysql -u studyflow_user -pstudyflow_pass studyflow

# Executar comando SQL
docker-compose exec db mysql -u studyflow_user -pstudyflow_pass studyflow -e "SHOW TABLES;"
```

### Backup e Restore

```bash
# Exportar base de dados
docker-compose exec db mysqldump -u studyflow_user -pstudyflow_pass studyflow > backup.sql

# Importar base de dados
docker-compose exec -T db mysql -u studyflow_user -pstudyflow_pass studyflow < backup.sql
```

---

## 🔧 Executar Comandos nos Containers

### Container Web (PHP/Apache)

```bash
# Aceder ao shell do container web
docker-compose exec web bash

# Executar script PHP
docker-compose exec web php scripts/init-database.php
docker-compose exec web php scripts/seed-users.php

# Ver versão PHP
docker-compose exec web php -v
```

### Container DB (MySQL)

```bash
# Aceder ao shell do container db
docker-compose exec db bash

# Aceder ao MySQL
docker-compose exec db mysql -u studyflow_user -pstudyflow_pass studyflow
```

---

## 🧹 Limpeza e Reset

### Limpar Tudo e Começar de Novo

```bash
# Parar e remover tudo (incluindo volumes)
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

### Limpar Cache Docker

```bash
# Remover containers parados
docker container prune

# Remover imagens não utilizadas
docker image prune

# Limpar tudo (⚠️ cuidado!)
docker system prune -a
```

---

## 🌐 Acessos

### URLs

- **Aplicação**: http://localhost:5500
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3307

### Credenciais Padrão

**Aplicação:**
- Admin: `admin@studyflow.pt` / `admin123`
- Estudante: `estudante@studyflow.pt` / `estudante123`

**phpMyAdmin:**
- Servidor: `db`
- Utilizador: `studyflow_user`
- Password: `studyflow_pass`

**MySQL:**
- Host: `localhost`
- Porta: `3307`
- Utilizador: `studyflow_user`
- Password: `studyflow_pass`
- Base de dados: `studyflow`

---

## 🔍 Troubleshooting

### Verificar Portas Ocupadas

```bash
# macOS/Linux
lsof -i :5500
lsof -i :8081
lsof -i :3307

# Windows
netstat -ano | findstr :5500
```

### Verificar Logs de Erro

```bash
# Logs do Apache
docker-compose exec web tail -f /var/log/apache2/error.log

# Logs do MySQL
docker-compose logs db | grep -i error
```

### Reconstruir Após Alterações

```bash
# Reconstruir apenas o serviço web
docker-compose build --no-cache web
docker-compose up -d web
```

---

## 📝 Comandos Rápidos (Copy & Paste)

### Setup Inicial Completo

```bash
docker-compose up -d --build
sleep 15
docker-compose exec web php scripts/init-database.php
docker-compose exec web php scripts/seed-users.php
```

### Reiniciar Tudo

```bash
docker-compose restart
```

### Ver Status e Logs

```bash
docker-compose ps && docker-compose logs --tail=50
```

### Backup Rápido

```bash
docker-compose exec db mysqldump -u studyflow_user -pstudyflow_pass studyflow > backup_$(date +%Y%m%d_%H%M%S).sql
```

---

## 🔄 Atualizar Código

```bash
# Alterações no código são refletidas automaticamente
# Apenas recarregue a página no navegador

# Se alterar Dockerfile ou docker-compose.yml:
docker-compose up -d --build
```

---

## 📌 Notas Importantes

- Os dados persistem mesmo após `docker-compose down` (usam volumes)
- Para apagar dados: `docker-compose down -v`
- O ficheiro `.env` não deve ser commitado
- Alterações no código PHP são refletidas automaticamente (devido ao volume)
- MySQL pode demorar 10-20 segundos a iniciar

---

**💡 Dica**: Guarde este ficheiro como referência rápida!
