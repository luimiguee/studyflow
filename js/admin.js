// Módulo Admin
const Admin = {
  // Carregar estatísticas
  async loadStats() {
    try {
      const stats = await API.getAdminStats();
      return stats;
    } catch (error) {
      console.error('Erro ao carregar estatísticas:', error);
      throw error;
    }
  },

  // Carregar usuários
  async loadUsers() {
    try {
      const users = await API.getUsers();
      return users;
    } catch (error) {
      console.error('Erro ao carregar usuários:', error);
      throw error;
    }
  },

  // Carregar logs
  async loadLogs(params = {}) {
    try {
      const data = await API.getLogs(params);
      return data;
    } catch (error) {
      console.error('Erro ao carregar logs:', error);
      throw error;
    }
  },

  // Formatar data
  formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
      return date.toLocaleString('pt-PT');
  },

  // Formatar data relativa
  formatRelativeDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);

    if (minutes < 1) return 'Agora';
    if (minutes < 60) return `${minutes} min atrás`;
    if (hours < 24) return `${hours}h atrás`;
    if (days < 7) return `${days} dias atrás`;
    return date.toLocaleDateString('pt-PT');
  },

  // Formatar role
  formatRole(role) {
    const roles = {
      'admin': 'Administrador',
      'estudante': 'Estudante'
    };
    return roles[role] || role;
  },

  // Formatar status
  formatStatus(status) {
    const statuses = {
      'pendente': 'Pendente',
      'em_progresso': 'Em Progresso',
      'concluida': 'Concluída'
    };
    return statuses[status] || status;
  },

  // Formatar prioridade
  formatPriority(priority) {
    const priorities = {
      'baixa': 'Baixa',
      'media': 'Média',
      'alta': 'Alta'
    };
    return priorities[priority] || priority;
  }
};

// Exportar
if (typeof window !== 'undefined') {
  window.Admin = Admin;
}

