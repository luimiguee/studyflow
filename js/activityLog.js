// Sistema de Logs de Atividade
const ActivityLog = {
  // Adicionar log
  add(userId, userEmail, action, details, ipAddress) {
    const log = {
      id: Date.now().toString(),
      userId,
      userEmail,
      action,
      details,
      ipAddress: ipAddress || this.getSimulatedIP(),
      timestamp: new Date().toISOString(),
    };

    const logs = this.getAll();
    logs.push(log);
    localStorage.setItem('studyflow-activity-logs', JSON.stringify(logs));

    return log;
  },

  // Obter todos os logs
  getAll() {
    return JSON.parse(localStorage.getItem('studyflow-activity-logs') || '[]');
  },

  // Obter logs do usuário atual
  getUserLogs() {
    const user = Auth.getCurrentUser();
    if (!user) return [];
    return this.getAll().filter(l => l.userId === user.id);
  },

  // Obter logs por usuário (admin)
  getLogsByUser(userId) {
    return this.getAll().filter(l => l.userId === userId);
  },

  // Filtrar logs
  filter(filters) {
    let logs = this.getAll();

    if (filters.userId) {
      logs = logs.filter(l => l.userId === filters.userId);
    }

    if (filters.action) {
      logs = logs.filter(l => l.action === filters.action);
    }

    if (filters.startDate) {
      logs = logs.filter(l => new Date(l.timestamp) >= new Date(filters.startDate));
    }

    if (filters.endDate) {
      logs = logs.filter(l => new Date(l.timestamp) <= new Date(filters.endDate));
    }

    if (filters.search) {
      const search = filters.search.toLowerCase();
      logs = logs.filter(l => 
        l.details.toLowerCase().includes(search) ||
        l.action.toLowerCase().includes(search) ||
        l.userEmail.toLowerCase().includes(search)
      );
    }

    return logs.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
  },

  // Simular IP
  getSimulatedIP() {
    return `192.168.1.${Math.floor(Math.random() * 255)}`;
  },
};

