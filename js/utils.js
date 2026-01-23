// Utilitários
const Utils = {
  // Formatar data
  formatDate(date, format = 'dd/MM/yyyy') {
    if (!date) return '';
    const d = new Date(date);
    
    if (format === 'dd/MM/yyyy') {
      return d.toLocaleDateString('pt-PT');
    }
    
    if (format === 'dd/MM/yyyy HH:mm') {
      return d.toLocaleString('pt-PT');
    }
    
    if (format === 'relative') {
      const now = new Date();
      const diff = now - d;
      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      
      if (days === 0) return 'Hoje';
      if (days === 1) return 'Ontem';
      if (days < 7) return `${days} dias atrás`;
      if (days < 30) return `${Math.floor(days / 7)} semanas atrás`;
      if (days < 365) return `${Math.floor(days / 30)} meses atrás`;
      return `${Math.floor(days / 365)} anos atrás`;
    }
    
    return d.toLocaleDateString('pt-BR');
  },

  // Verificar se está autenticado e redirecionar
  requireAuth(allowedRoles = []) {
    const user = Auth.getCurrentUser();
    
    if (!user) {
      window.location.href = 'login.html';
      return false;
    }

    if (allowedRoles.length > 0 && !allowedRoles.includes(user.role)) {
      window.location.href = 'dashboard.html';
      return false;
    }

    return true;
  },

  // Navegação
  navigate(path) {
    window.location.href = path;
  },

  // Mostrar erro
  showError(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
      element.innerHTML = `<div class="alert alert-error">${message}</div>`;
      element.style.display = 'block';
    }
  },

  // Mostrar sucesso
  showSuccess(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
      element.innerHTML = `<div class="alert alert-success">${message}</div>`;
      element.style.display = 'block';
      setTimeout(() => {
        element.style.display = 'none';
      }, 3000);
    }
  },

  // Limpar alertas
  clearAlerts(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
      element.innerHTML = '';
      element.style.display = 'none';
    }
  },
};

