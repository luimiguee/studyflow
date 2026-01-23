// Gerenciador de Tema (Claro/Escuro)
const ThemeManager = {
  // Obter tema atual
  getTheme() {
    return localStorage.getItem('theme') || 'light';
  },

  // Definir tema
  setTheme(theme) {
    if (theme !== 'light' && theme !== 'dark') {
      theme = 'light';
    }
    
    localStorage.setItem('theme', theme);
    document.documentElement.setAttribute('data-theme', theme);
    
    // Atualizar ícone do toggle se existir
    this.updateThemeToggle();
    
    // Disparar evento customizado
    window.dispatchEvent(new CustomEvent('themechange', { detail: { theme } }));
  },

  // Alternar entre claro e escuro
  toggleTheme() {
    const currentTheme = this.getTheme();
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    this.setTheme(newTheme);
    return newTheme;
  },

  // Inicializar tema
  init() {
    const theme = this.getTheme();
    this.setTheme(theme);
  },

  // Atualizar ícone do toggle
  updateThemeToggle() {
    const theme = this.getTheme();
    const toggles = document.querySelectorAll('.theme-toggle');
    
    toggles.forEach(toggle => {
      const icon = toggle.querySelector('.theme-icon');
      if (icon) {
        if (theme === 'dark') {
          icon.innerHTML = '☀️'; // Sol para modo escuro (clicar para voltar ao claro)
          icon.setAttribute('title', 'Alternar para tema claro');
        } else {
          icon.innerHTML = '🌙'; // Lua para modo claro (clicar para ir ao escuro)
          icon.setAttribute('title', 'Alternar para tema escuro');
        }
      }
    });
  },

  // Criar botão de toggle
  createToggleButton() {
    const button = document.createElement('button');
    button.className = 'theme-toggle';
    button.setAttribute('aria-label', 'Alternar tema');
    button.innerHTML = `
      <span class="theme-icon">🌙</span>
    `;
    button.onclick = () => this.toggleTheme();
    return button;
  }
};

// Inicializar tema quando o script carregar
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => ThemeManager.init());
} else {
  ThemeManager.init();
}
