    # Configuração de Email PHP

Este guia explica como configurar o sistema de email PHP usando PHPMailer no StudyFlow.

## Instalação

O PHPMailer já está configurado no projeto através do Composer. Para instalar as dependências:

### Usando Docker (Recomendado)

```bash
# Reconstruir a imagem Docker para instalar as dependências
docker-compose build web
docker-compose up -d
```

### Instalação Manual

Se você não estiver usando Docker:

```bash
composer install
```

## Configuração

### Variáveis de Ambiente

Configure as seguintes variáveis de ambiente no seu arquivo `.env` ou no `docker-compose.yml`:

```env
# Configurações SMTP
SMTP_HOST=smtp.gmail.com          # Servidor SMTP
SMTP_PORT=587                     # Porta SMTP (587 para TLS, 465 para SSL)
SMTP_USERNAME=seu_email@gmail.com # Seu email
SMTP_PASSWORD=sua_senha_app       # Senha de aplicativo (não use senha normal)
SMTP_FROM_EMAIL=noreply@studyflow.com  # Email remetente
SMTP_FROM_NAME=StudyFlow          # Nome do remetente
SMTP_ENCRYPTION=tls               # tls ou ssl
USE_SMTP=true                     # true para usar SMTP, false para usar mail() do PHP
```

### Exemplo para Gmail

1. Ative a verificação em duas etapas na sua conta Google
2. Gere uma "Senha de aplicativo":
   - Acesse: https://myaccount.google.com/apppasswords
   - Selecione "Email" e "Outro (nome personalizado)"
   - Digite "StudyFlow" e clique em "Gerar"
   - Use essa senha de 16 caracteres como `SMTP_PASSWORD`

```env
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=seu_email@gmail.com
SMTP_PASSWORD=xxxx xxxx xxxx xxxx  # Senha de aplicativo gerada
SMTP_ENCRYPTION=tls
```

### Exemplo para Outlook/Hotmail

```env
SMTP_HOST=smtp-mail.outlook.com
SMTP_PORT=587
SMTP_USERNAME=seu_email@outlook.com
SMTP_PASSWORD=sua_senha
SMTP_ENCRYPTION=tls
```

### Modo de Desenvolvimento (sem SMTP)

Para desenvolvimento local sem servidor SMTP, você pode usar a função `mail()` do PHP:

```env
USE_SMTP=false
```

**Nota:** Isso requer que o servidor tenha um servidor de email configurado (sendmail, postfix, etc.).

## Uso da API

### Enviar Email Genérico

```javascript
const response = await fetch('/api/emails.php?action=send', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    to: 'destinatario@example.com',
    subject: 'Assunto do Email',
    body: '<h1>Conteúdo HTML</h1><p>Mensagem aqui</p>',
    altBody: 'Versão texto simples' // Opcional
  })
});
```

### Enviar Email de Boas-vindas

```javascript
const response = await fetch('/api/emails.php?action=welcome', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: JSON.stringify({
    email: 'usuario@example.com',
    name: 'Nome do Usuário'
  })
});
```

### Enviar Notificação

```javascript
const response = await fetch('/api/emails.php?action=notification', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: JSON.stringify({
    email: 'usuario@example.com',
    subject: 'Nova Notificação',
    message: '<p>Sua mensagem aqui</p>'
  })
});
```

## Uso no PHP

### Exemplo de uso direto

```php
require_once __DIR__ . '/api/email.php';

$emailService = EmailService::getInstance();

// Enviar email genérico
$result = $emailService->send(
    'destinatario@example.com',
    'Assunto',
    '<h1>Conteúdo HTML</h1>',
    'Versão texto' // Opcional
);

// Enviar email de boas-vindas
$result = $emailService->sendWelcomeEmail('usuario@example.com', 'Nome');

// Enviar notificação
$result = $emailService->sendNotification(
    'usuario@example.com',
    'Assunto',
    '<p>Mensagem</p>'
);
```

## Integração com Registro de Usuários

Para enviar emails automaticamente quando um usuário se registra, atualize `api/auth.php`:

```php
require_once __DIR__ . '/email.php';

// Após criar o usuário com sucesso
$emailService = EmailService::getInstance();
$emailService->sendWelcomeEmail($email, $name);
```

## Troubleshooting

### Erro: "SMTP connect() failed"

- Verifique se as credenciais estão corretas
- Para Gmail, use senha de aplicativo, não a senha normal
- Verifique se a porta está correta (587 para TLS, 465 para SSL)
- Verifique se o firewall permite conexões SMTP

### Erro: "Could not instantiate mail function"

- Se `USE_SMTP=false`, certifique-se de que o servidor tem um servidor de email configurado
- Para desenvolvimento, use SMTP mesmo localmente

### Emails não estão sendo enviados

- Verifique os logs do Apache: `docker/apache/logs/error.log`
- Verifique se as variáveis de ambiente estão configuradas corretamente
- Teste a conexão SMTP manualmente

## Testando

Para testar o envio de email, você pode criar um script de teste:

```php
<?php
require_once __DIR__ . '/api/email.php';

$emailService = EmailService::getInstance();
$result = $emailService->sendWelcomeEmail('seu_email@example.com', 'Teste');

print_r($result);
?>
```

Execute:
```bash
php scripts/test-email.php
```

## Segurança

- **Nunca** commite credenciais de email no Git
- Use variáveis de ambiente para configurações sensíveis
- Para produção, use senhas de aplicativo, não senhas normais
- Considere usar serviços de email transacionais (SendGrid, Mailgun, etc.) para produção
