<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class ControladorMail
{
    public function obtenerEstadoCompra($estadoCompra)
    {
        $msjCompra = "";
        if ($estadoCompra === '1') {
            $msjCompra = "Usted ha iniciado un proceso de compra en Tienda Lenny";
        } elseif ($estadoCompra === '2') {
            $msjCompra = "Su compra en Tienda Lenny ha sido aceptada";
        } elseif ($estadoCompra === '3') {
            $msjCompra = "Su compra en Tienda Lenny fue enviada";
        } elseif ($estadoCompra === '4') {
            $msjCompra = "Su compra en Tienda Lenny ha sido cancelada";
        }
        return $msjCompra;
    }

    public function crearMensaje($nombreUsuario, $estadoCompra)
    {
        $mensaje = "<h1>¡Hola, $nombreUsuario!</h1>";
        $mensaje .= "<p>Nos comunicamos para informarle del estado actual de su compra:</p>";
        $mensaje .= "<p> $estadoCompra </p>";
        $mensaje .= "<p>El detalle de sus productos se encuentra en un pdf adjunto!</p>";
        $mensaje .= "<p>¡Hasta Luego!</p>";

        return $mensaje;
    }

    public function crearMail($estadoCompra, $pdfFilePath, $email, $nombreUsuario, $mensaje)
    {
        $estadoCompra = $this->obtenerEstadoCompra($estadoCompra);
        $mensaje = $this->crearMensaje($nombreUsuario, $estadoCompra);

        $this->enviarMail($email, $nombreUsuario, $estadoCompra, $mensaje, $pdfFilePath);
    }

    public function enviarMail($email, $nombreUsuario, $asunto, $mensaje, $pdfFilePath)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Disable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'phpmailermatiasgabriel@gmail.com';                     //SMTP username
            $mail->Password   = 'xyir aghy hmrp vqys';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('tiendaLenny@pwd.com', 'Tienda Lenny');
            $mail->addAddress($email, $nombreUsuario);     //Add a recipient
            $mail->addAttachment($pdfFilePath); // Adjuntar el archivo PDF

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;

            $mail->send();
            // echo 'El mensaje fue enviado';
        } catch (Exception $e) {
            echo "No se pudo enviar el correo: {$mail->ErrorInfo}";
        }
    }
}
