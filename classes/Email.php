<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){

        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'a17a62c460f279';
        $mail->Password = 'a5201771079954';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        // Contenido mail
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . " </strong> Has creado tu cuenta en AppSalon, solo debes confirmarla dando clic en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí <a href='http://localhost:3000/confirmar-cuenta?token=". $this->token . "'>Confirmar cuenta </a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        // Agregamos el contenido como cuerpo del mail
        $mail->Body = $contenido;

        $mail->send();

    }

    public function enviarInstrucciones(){
        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'a17a62c460f279';
        $mail->Password = 'a5201771079954';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Restablece tu Contraseña';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        // Contenido mail
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . " </strong> Has generado una solitud de restablecimiento de contraseña en tu cuenta en AppSalon, haz clic en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí <a href='http://localhost:3000/recuperar?token=". $this->token . "'> para restablecer la contraseña </a> </p>";
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        // Agregamos el contenido como cuerpo del mail
        $mail->Body = $contenido;

        $mail->send();
    }

}