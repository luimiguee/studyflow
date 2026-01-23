<?php
// Email Service usando PHPMailer
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/email.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private static $instance = null;
    private $mailer;

    private function __construct() {
        $this->mailer = new PHPMailer(true);
        
        if (USE_SMTP) {
            // Configuração SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = SMTP_HOST;
            $this->mailer->SMTPAuth = SMTP_AUTH;
            $this->mailer->Username = SMTP_USERNAME;
            $this->mailer->Password = SMTP_PASSWORD;
            $this->mailer->SMTPSecure = SMTP_ENCRYPTION;
            $this->mailer->Port = SMTP_PORT;
            $this->mailer->CharSet = 'UTF-8';
        } else {
            // Usar mail() do PHP (para desenvolvimento local)
            $this->mailer->isMail();
        }
        
        // Configurações comuns
        $this->mailer->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $this->mailer->isHTML(true);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Enviar email genérico
     */
    public function send($to, $subject, $body, $altBody = '') {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->AltBody = $altBody ?: strip_tags($body);
            
            $result = $this->mailer->send();
            
            if ($result) {
                error_log("Email enviado com sucesso para: $to");
                return ['success' => true, 'message' => 'Email enviado com sucesso'];
            } else {
                error_log("Falha ao enviar email para: $to");
                return ['success' => false, 'message' => 'Falha ao enviar email'];
            }
        } catch (Exception $e) {
            error_log("Erro ao enviar email: " . $this->mailer->ErrorInfo);
            return ['success' => false, 'message' => 'Erro ao enviar email: ' . $this->mailer->ErrorInfo];
        }
    }

    /**
     * Enviar email de boas-vindas
     */
    public function sendWelcomeEmail($email, $name) {
        $subject = 'Bem-vindo ao StudyFlow! Confirme sua conta';
        $body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #4a90e2; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; background-color: #f9f9f9; }
                    .footer { padding: 20px; text-align: center; color: #666; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Bem-vindo ao StudyFlow!</h1>
                    </div>
                    <div class='content'>
                        <h2>Olá, {$name}!</h2>
                        <p>Obrigado por se registrar no StudyFlow.</p>
                        <p>Sua conta foi criada com sucesso!</p>
                        <p>Agora você pode começar a organizar seus estudos de forma eficiente.</p>
                        <br>
                        <p>Atenciosamente,<br><strong>Equipe StudyFlow</strong></p>
                    </div>
                    <div class='footer'>
                        <p>Este é um email automático, por favor não responda.</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        return $this->send($email, $subject, $body);
    }

    /**
     * Enviar email de notificação
     */
    public function sendNotification($email, $subject, $message) {
        $body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .content { padding: 20px; background-color: #f9f9f9; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='content'>
                        {$message}
                        <br><br>
                        <p>Atenciosamente,<br><strong>Equipe StudyFlow</strong></p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        return $this->send($email, $subject, $body);
    }
}

?>
