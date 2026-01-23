#!/bin/bash
# Script de inicialização do Docker para StudyFlow

echo "🚀 Inicializando StudyFlow com Docker..."
echo ""

# Verificar se o Docker está a correr
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker não está a correr. Por favor, inicie o Docker Desktop."
    exit 1
fi

# Verificar se o docker-compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ docker-compose não está instalado."
    exit 1
fi

# Criar ficheiro .env se não existir
if [ ! -f .env ]; then
    echo "📝 Criando ficheiro .env a partir de .env.example..."
    cp .env.example .env
    echo "✅ Ficheiro .env criado. Por favor, ajuste os valores se necessário."
fi

# Construir e iniciar os containers
echo "🔨 Construindo containers..."
docker-compose build

echo "🚀 Iniciando containers..."
docker-compose up -d

# Aguardar MySQL estar pronto
echo "⏳ Aguardando MySQL estar pronto..."
sleep 10

# Executar script de inicialização da base de dados
echo "📦 Inicializando base de dados..."
docker-compose exec -T web php scripts/init-database.php

# Executar script de seed de utilizadores
echo "👥 Criando utilizadores padrão..."
docker-compose exec -T web php scripts/seed-users.php

echo ""
echo "✅ StudyFlow está a correr!"
echo ""
echo "📍 Acessos:"
echo "   - Aplicação: http://localhost:8080"
echo "   - phpMyAdmin: http://localhost:8081"
echo ""
echo "📋 Comandos úteis:"
echo "   - Ver logs: docker-compose logs -f"
echo "   - Parar: docker-compose down"
echo "   - Reiniciar: docker-compose restart"
echo ""

