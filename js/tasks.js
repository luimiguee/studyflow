// Sistema de Tarefas
const Tasks = {
  // Obter todas as tarefas
  getAll() {
    return JSON.parse(localStorage.getItem('studyflow-tasks') || '[]');
  },

  // Obter tarefas do usuário atual
  getUserTasks() {
    const user = Auth.getCurrentUser();
    if (!user) return [];
    return this.getAll().filter(t => t.userId === user.id);
  },

  // Obter tarefas por usuário (admin)
  getTasksByUser(userId) {
    return this.getAll().filter(t => t.userId === userId);
  },

  // Adicionar tarefa
  add(task) {
    const user = Auth.getCurrentUser();
    const newTask = {
      ...task,
      id: Date.now().toString(),
      createdAt: new Date().toISOString(),
      userId: user ? user.id : 'anonymous',
      completed: false,
    };

    const tasks = this.getAll();
    tasks.push(newTask);
    localStorage.setItem('studyflow-tasks', JSON.stringify(tasks));

    // Log de atividade
    if (user) {
      ActivityLog.add(user.id, user.email, 'Criação de tarefa', `Tarefa "${task.title}" criada`);
    }

    return newTask;
  },

  // Atualizar tarefa
  update(id, updates) {
    const tasks = this.getAll();
    const taskIndex = tasks.findIndex(t => t.id === id);
    
    if (taskIndex === -1) return false;

    const task = tasks[taskIndex];
    tasks[taskIndex] = { ...task, ...updates };
    localStorage.setItem('studyflow-tasks', JSON.stringify(tasks));

    // Log de atividade
    const user = Auth.getCurrentUser();
    if (user) {
      if (updates.completed !== undefined) {
        ActivityLog.add(
          user.id,
          user.email,
          updates.completed ? 'Conclusão de tarefa' : 'Reabertura de tarefa',
          `Tarefa "${task.title}" ${updates.completed ? 'concluída' : 'reaberta'}`
        );
      } else {
        ActivityLog.add(user.id, user.email, 'Atualização de tarefa', `Tarefa "${task.title}" atualizada`);
      }
    }

    return true;
  },

  // Deletar tarefa
  delete(id) {
    const tasks = this.getAll();
    const task = tasks.find(t => t.id === id);
    
    if (!task) return false;

    const filtered = tasks.filter(t => t.id !== id);
    localStorage.setItem('studyflow-tasks', JSON.stringify(filtered));

    // Log de atividade
    const user = Auth.getCurrentUser();
    if (user) {
      ActivityLog.add(user.id, user.email, 'Eliminação de tarefa', `Tarefa "${task.title}" eliminada`);
    }

    return true;
  },

  // Alternar conclusão
  toggle(id) {
    const tasks = this.getAll();
    const task = tasks.find(t => t.id === id);
    if (task) {
      return this.update(id, { completed: !task.completed });
    }
    return false;
  },

  // Filtrar por categoria
  getByCategory(category) {
    return this.getUserTasks().filter(t => t.category === category);
  },

  // Filtrar por prioridade
  getByPriority(priority) {
    return this.getUserTasks().filter(t => t.priority === priority);
  },

  // Obter tarefas atrasadas
  getOverdue() {
    const now = new Date();
    return this.getUserTasks().filter(t => {
      if (t.completed) return false;
      return new Date(t.dueDate) < now;
    });
  },

  // Obter tarefas próximas (3 dias)
  getUpcoming(days = 3) {
    const now = new Date();
    const future = new Date();
    future.setDate(future.getDate() + days);
    
    return this.getUserTasks().filter(t => {
      if (t.completed) return false;
      const dueDate = new Date(t.dueDate);
      return dueDate >= now && dueDate <= future;
    });
  },
};

