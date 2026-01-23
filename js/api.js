// Serviço de API para comunicação com o backend
const API = {
  // URL base da API (ajuste conforme necessário)
  // Detecção automática:
  // - Se estiver na porta 5500 ou 8080, assume Docker
  // - Se estiver em localhost sem porta, assume XAMPP (porta 80)
  // - Caso contrário, usa porta 8000 (servidor PHP integrado)
  BASE_URL: (function() {
    // Se API_URL já estiver definido, usar esse
    if (window.API_URL) {
      return window.API_URL;
    }
    
    // Detectar URL baseada na localização atual
    const port = window.location.port || '';
    const hostname = window.location.hostname || 'localhost';
    const protocol = window.location.protocol || 'http:';
    
    // Se estiver via file:// ou sem protocolo http, usar localhost:5500 (Docker padrão)
    if (protocol === 'file:' || !window.location.host) {
      return 'http://localhost:5500/api';
    }
    
    // Se estiver na porta 5500 ou 8080, usar essa porta
    if (port === '5500' || port === '8080') {
      return `${protocol}//${hostname}:${port}/api`;
    }
    
    // Se estiver em localhost sem porta especificada, assumir XAMPP
    if (hostname === 'localhost' && (!port || port === '80' || port === '443')) {
      return 'http://localhost/studyflow/api';
    }
    
    // Caso contrário, tentar usar a mesma origem
    if (hostname && port) {
      return `${protocol}//${hostname}:${port}/api`;
    }
    
    // Fallback: porta 8000 (servidor PHP integrado)
    return 'http://localhost:8000/api';
  })(),

  // Obter token do localStorage
  getToken() {
    return localStorage.getItem('studyflow-token');
  },

  // Salvar token no localStorage
  setToken(token) {
    localStorage.setItem('studyflow-token', token);
  },

  // Remover token
  removeToken() {
    localStorage.removeItem('studyflow-token');
  },

  // Fazer requisição HTTP
  async request(endpoint, options = {}) {
    // Construir URL: BASE_URL já inclui /api, então apenas adicionar o endpoint
    let url;
    if (endpoint.startsWith('http://') || endpoint.startsWith('https://')) {
      // URL completa - usar diretamente
      url = endpoint;
    } else if (endpoint.startsWith('/api/')) {
      // Endpoint já inclui /api/ - usar origem + endpoint
      url = window.location.origin + endpoint;
    } else {
      // Endpoint relativo - adicionar à BASE_URL
      // BASE_URL já deve terminar em /api, então apenas adicionar / antes do endpoint se necessário
      const cleanEndpoint = endpoint.startsWith('/') ? endpoint : '/' + endpoint;
      url = this.BASE_URL + cleanEndpoint;
    }
    const token = this.getToken();

    const config = {
      method: options.method || 'GET',
      headers: {
        'Content-Type': 'application/json',
        ...(token && { Authorization: `Bearer ${token}` }),
        ...options.headers
      },
      ...options
    };

    // Remover body do config se não for necessário
    if (config.method === 'GET' || config.method === 'DELETE') {
      delete config.body;
    }

    if (config.body && typeof config.body === 'object') {
      config.body = JSON.stringify(config.body);
    }

    try {
      console.log('API Request:', url, config.method || 'GET'); // Debug
      const response = await fetch(url, config);
      
      // Verificar se a resposta é JSON
      const contentType = response.headers.get('content-type');
      let data;
      
      if (contentType && contentType.includes('application/json')) {
        data = await response.json();
      } else {
        const text = await response.text();
        console.error('Resposta não é JSON:', text.substring(0, 200));
        throw new Error(text || `HTTP ${response.status}: ${response.statusText}`);
      }

      if (!response.ok) {
        console.error('Erro na resposta:', data);
        throw new Error(data.error || data.message || `Erro HTTP ${response.status}`);
      }

      return data;
    } catch (error) {
      console.error('Erro na API:', error);
      console.error('URL tentada:', url);
      // Se for um erro de rede, dar mensagem mais clara
      if (error.message === 'Failed to fetch' || error.message.includes('Load failed') || error.name === 'TypeError') {
        throw new Error(`Erro de conexão. Verifique se o servidor está a correr em ${url.replace('/api', '')} e se a URL da API está correta.`);
      }
      throw error;
    }
  },

  // Autenticação
  async login(email, password) {
    const data = await this.request('/auth.php?action=login', {
      method: 'POST',
      body: { email, password }
    });

    if (data.token) {
      this.setToken(data.token);
      if (data.user) {
        localStorage.setItem('studyflow-user', JSON.stringify(data.user));
      }
    }

    return data;
  },

  async register(name, email, password) {
    const data = await this.request('/auth.php?action=register', {
      method: 'POST',
      body: { name, email, password }
    });

    if (data.token) {
      this.setToken(data.token);
      if (data.user) {
        localStorage.setItem('studyflow-user', JSON.stringify(data.user));
      }
    }

    return data;
  },

  async getCurrentUser() {
    try {
      const data = await this.request('/auth.php?action=me');
      if (data.user) {
        localStorage.setItem('studyflow-user', JSON.stringify(data.user));
      }
      return data.user;
    } catch (error) {
      this.removeToken();
      localStorage.removeItem('studyflow-user');
      return null;
    }
  },

  logout() {
    this.removeToken();
    localStorage.removeItem('studyflow-user');
    window.location.href = 'index.html';
  },

  // Tarefas
  async getTasks() {
    const data = await this.request('/tasks.php');
    return data.tasks || [];
  },

  async getTask(id) {
    const data = await this.request(`/tasks.php?id=${id}`);
    return data.task;
  },

  async createTask(task) {
    const data = await this.request('/tasks.php', {
      method: 'POST',
      body: task
    });
    return data.task;
  },

  async updateTask(id, task) {
    const data = await this.request(`/tasks.php?id=${id}`, {
      method: 'PUT',
      body: task
    });
    return data.task;
  },

  async deleteTask(id) {
    const data = await this.request(`/tasks.php?id=${id}`, {
      method: 'DELETE'
    });
    return data;
  },

  // Admin - Estatísticas
  async getAdminStats() {
    const data = await this.request('/admin.php?action=stats');
    return data.stats;
  },

  // Admin - Usuários
  async getUsers() {
    const data = await this.request('/admin.php?action=users');
    return data.users || [];
  },

  async getUser(id) {
    const data = await this.request(`/admin.php?action=user&id=${id}`);
    return data.user;
  },

  async createUser(userData) {
    const data = await this.request('/admin.php?action=create-user', {
      method: 'POST',
      body: userData
    });
    return data;
  },

  async updateUser(id, userData) {
    const data = await this.request(`/admin.php?action=update-user&id=${id}`, {
      method: 'PUT',
      body: userData
    });
    return data;
  },

  async deleteUser(id) {
    const data = await this.request(`/admin.php?action=delete-user&id=${id}`, {
      method: 'DELETE'
    });
    return data;
  },

  // Admin - Logs
  async getLogs(params = {}) {
    const queryParams = new URLSearchParams(params).toString();
    const data = await this.request(`/admin.php?action=logs&${queryParams}`);
    return data;
  }
};

// Exportar para uso global
if (typeof window !== 'undefined') {
  window.API = API;
}

