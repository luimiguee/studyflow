# Estrutura do Projeto StudyFlow - Versão HTML/CSS/JS

## ✅ Arquivos Criados

### 📄 Páginas HTML Principais
- ✅ `index.html` - Página inicial (Landing)
- ✅ `login.html` - Página de login
- ✅ `register.html` - Página de registro  
- ✅ `dashboard.html` - Dashboard do estudante
- ✅ `README.md` - Documentação do projeto

### 🎨 CSS
- ✅ `css/style.css` - Estilos globais completos

### 💻 JavaScript
- ✅ `js/auth.js` - Sistema de autenticação completo
- ✅ `js/tasks.js` - Sistema de gerenciamento de tarefas
- ✅ `js/activityLog.js` - Sistema de logs de atividade
- ✅ `js/emailService.js` - Serviço de email (simulado)
- ✅ `js/utils.js` - Funções utilitárias

## 📋 Páginas Restantes a Criar (Opcional)

Você pode criar as seguintes páginas baseado no mesmo padrão:

### Páginas do Estudante
- `pages/tarefas.html` - Lista e gestão de tarefas
- `pages/calendario.html` - Calendário de tarefas
- `pages/perfil.html` - Perfil do usuário
- `pages/edit-profile.html` - Editar perfil

### Páginas Administrativas
- `pages/admin-dashboard.html` - Dashboard administrativo
- `pages/admin-users.html` - Gestão de usuários
- `pages/admin-logs.html` - Visualização de logs
- `pages/admin-settings.html` - Configurações globais

## 🔧 Como Usar

1. **Servidor Local:**
   ```bash
   cd studyflow-html
   python3 -m http.server 8000
   # ou
   php -S localhost:8000
   ```

2. **Acessar:**
   - Abra `http://localhost:8000`
   - Use as credenciais de teste:
     - Admin: `admin@studyflow.com` / `admin123`
     - Estudante: `estudante@studyflow.com` / `estudante123`

## 📝 Notas Importantes

- Todos os dados são salvos no `localStorage` do navegador
- Não há backend - tudo funciona no cliente
- As páginas principais já estão funcionais
- Você pode expandir criando as páginas adicionais seguindo o mesmo padrão

## 🎯 Próximos Passos

Se quiser completar o projeto, você pode:

1. Criar as páginas restantes (`pages/tarefas.html`, etc.)
2. Adicionar funcionalidades extras
3. Melhorar o design CSS
4. Adicionar PHP para um backend real (opcional)

