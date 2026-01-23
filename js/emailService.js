// Serviço de Email (Simulado)
const EmailService = {
  // Enviar email
  async send(data) {
    await new Promise(resolve => setTimeout(resolve, 500));

    const emailRecord = {
      ...data,
      sentAt: new Date().toISOString(),
      status: 'sent',
    };

    const existingEmails = JSON.parse(localStorage.getItem('studyflow-sent-emails') || '[]');
    existingEmails.push(emailRecord);
    localStorage.setItem('studyflow-sent-emails', JSON.stringify(existingEmails));

    console.log('📧 Email enviado:', emailRecord);
    return true;
  },

  // Enviar email de boas-vindas
  async sendWelcomeEmail(email, name) {
    return this.send({
      to: email,
      subject: 'Bem-vindo ao StudyFlow! Confirme sua conta',
      body: `
        <h2>Olá, ${name}!</h2>
        <p>Obrigado por se registrar no StudyFlow.</p>
        <p>Sua conta foi criada com sucesso!</p>
        <p>Agora você pode começar a organizar seus estudos de forma eficiente.</p>
        <br>
        <p>Atenciosamente,<br>Equipe StudyFlow</p>
      `,
      type: 'confirmation',
    });
  },

  // Enviar email de notificação
  async sendNotification(email, subject, message) {
    return this.send({
      to: email,
      subject,
      body: message,
      type: 'notification',
    });
  },

  // Obter emails enviados
  getSentEmails() {
    return JSON.parse(localStorage.getItem('studyflow-sent-emails') || '[]');
  },
};

