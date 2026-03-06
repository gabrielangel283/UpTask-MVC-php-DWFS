<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        // Looking to send emails in production? Check out our Email API/SMTP product!
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = 'tls';

        $mail->setFrom('cuentas@uptask.com'); // el que envia el mensaje
        $mail->addAddress($this->email, $this->nombre); // el que lo recibe
        $mail->Subject = 'Confirmar tu cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<body style='font-family: Arial, sans-serif; background-color: #f6f9fc; margin: 0; padding: 40px;'>";
        $contenido .= "<div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-top: 6px solid #4f46e5;'>";

        // Encabezado / Logo
        $contenido .= "<h1 style='color: #1a202c; text-align: center; font-size: 24px; margin-bottom: 20px;'>Bienvenido a UpTask</h1>";

        // Cuerpo
        $contenido .= "<p style='font-size: 16px; color: #4a5568; line-height: 1.6;'>Hola <strong style='color: #1a202c;'>" . $this->nombre . "</strong>,</p>";
        $contenido .= "<p style='font-size: 16px; color: #4a5568; line-height: 1.6;'>Has creado tu cuenta en UpTask de manera exitosa. Para comenzar a gestionar tus proyectos, solo necesitas confirmar tu cuenta haciendo clic en el siguiente botón:</p>";

        // Botón
        $contenido .= "<div style='text-align: center; margin: 30px 0;'>";
        $contenido .= "<a href='" . $_ENV['PROJECT_URL'] . "/confirmar-cuenta?token=" . $this->token . "' style='background-color: #4f46e5; color: white; padding: 14px 28px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>Confirmar Cuenta</a>";
        $contenido .= "</div>";

        // Pie de página
        $contenido .= "<hr style='border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;'>";
        $contenido .= "<p style='font-size: 13px; color: #718096; text-align: center;'>Si tú no creaste esta cuenta, puedes ignorar este mensaje con total seguridad.</p>";
        $contenido .= "<p style='font-size: 13px; color: #a0aec0; text-align: center;'>&copy; " . date('Y') . " UpTask. Todos los derechos reservados.</p>";

        $contenido .= "</div>";
        $contenido .= "</body>";
        $contenido .= "</html>";

        $contenido .= '</html>';

        $mail->Body = $contenido;

        $mail->SMTPDebug = 2;
        $mail->send();
    }

    public function enviarInstrucciones()
    {
        // Looking to send emails in production? Check out our Email API/SMTP product!
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];
        $mail->SMTPSecure = 'tls';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Reestablece tu password';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<body style='font-family: Arial, sans-serif; background-color: #f6f9fc; margin: 0; padding: 40px;'>";
        $contenido .= "<div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-top: 6px solid #00a1ff;'>";

        // Encabezado
        $contenido .= "<h1 style='color: #1a202c; text-align: center; font-size: 24px; margin-bottom: 20px;'>Recuperar Contraseña</h1>";

        // Cuerpo del mensaje
        $contenido .= "<p style='font-size: 16px; color: #4a5568; line-height: 1.6;'>Hola <strong style='color: #1a202c;'>" . $this->nombre . "</strong>,</p>";
        $contenido .= "<p style='font-size: 16px; color: #4a5568; line-height: 1.6;'>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en <strong>UpTask</strong>. No te preocupes, sucede a veces.</p>";
        $contenido .= "<p style='font-size: 16px; color: #4a5568; line-height: 1.6;'>Haz clic en el botón de abajo para elegir una nueva contraseña:</p>";

        // Botón de Acción
        $contenido .= "<div style='text-align: center; margin: 30px 0;'>";
        $contenido .= "<a href='" . $_ENV['PROJECT_URL'] . "/reestablecer?token=" . $this->token . "' style='background-color: #00a1ff; color: white; padding: 14px 28px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>Restablecer Contraseña</a>";
        $contenido .= "</div>";

        // Aviso de seguridad
        $contenido .= "<div style='background-color: #fffaf0; border-left: 4px solid #ed8936; padding: 15px; margin-bottom: 20px;'>";
        $contenido .= "<p style='font-size: 14px; color: #744210; margin: 0;'><strong>Nota:</strong> Este enlace de recuperación expirará pronto por razones de seguridad.</p>";
        $contenido .= "</div>";

        // Pie de página
        $contenido .= "<hr style='border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;'>";
        $contenido .= "<p style='font-size: 13px; color: #718096; text-align: center;'>Si tú no solicitaste este cambio, puedes ignorar este mensaje; tu contraseña actual seguirá funcionando.</p>";
        $contenido .= "<p style='font-size: 13px; color: #a0aec0; text-align: center;'>&copy; " . date('Y') . " UpTask. Gestión de Proyectos.</p>";

        $contenido .= "</div>";
        $contenido .= "</body>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->SMTPDebug = 2;
        $mail->send();
    }
}
