<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ControladorMail
{
    /**
     * obtiene id usuario
     */
    public function obtenerIdUsuario($idCompra)
    {
        $abmCompra = new abmCompra();
        $param = ['idcompra' => $idCompra];
        $resultado = $abmCompra->buscar($param);
        if (!empty($resultado) && isset($resultado[0]['idusuario'])) {
            $idUsuario = $resultado[0]['idusuario'];
        } else {
            $idUsuario = null;
        }
        return $idUsuario;
    }
    /**
     * Obtiene los datos de la compra
     * @param mixed $idUsuario
     * @return array
     */
    public function obtenerDatosCompra($idCompra)
    {
        $idUsuario = $this->obtenerIdUsuario($idCompra);
        $abmUsuario = new abmUsuario();
        $param = ['idusuario' => $idUsuario];
        $resultado = $abmUsuario->buscar($param);
        if (!empty($resultado) && isset($resultado[0]['usnombre']) && isset($resultado[0]['usmail'])) {
            $nombreUsuario = $resultado[0]['usnombre'];
            $email = $resultado[0]['usmail'];
        } else {
            $nombreUsuario = null;
            $email = null;
        }
        return ['nombreUsuario' => $nombreUsuario, 'email' => $email];
    }

    /**
     * Obtiene el estado de la compra
     * @param mixed $idCompra
     * @return mixed
     */
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
    /**
     * Crea el mensaje del mail
     * @param mixed $nombreUsuario
     * @param mixed $email
     * @return string
     */
    public function crearMensaje($nombreUsuario, $estadoCompra)
    {
        $mensaje = "<h1>¡Hola, $nombreUsuario!</h1>";
        $mensaje .= "<p>Nos comunicamos para informarle del estado actual de su compra:</p>";
        $mensaje .= "<p> $estadoCompra </p>";
        $mensaje .= "<p>El detalle de sus productos se encuentra en un pdf adjunto!</p>";
        $mensaje .= "<p>¡Hasta Luego!</p>";

        return $mensaje;
    }

    /**
     * Crea el mail
     * @param mixed $idUsuario
     * @param mixed $resumendecompra
     * @return void
     */
    public function crearMail($estadoCompra, $idCompra)
    {
        $datos = $this->obtenerDatosCompra($idCompra);
        $nombreUsuario = $datos['nombreUsuario'];
        $email = $datos['email'];
        $estadoCompra = $this->obtenerEstadoCompra($estadoCompra);
        $mensaje = $this->crearMensaje($nombreUsuario, $estadoCompra);

        $mailEnviado = $this->enviarMail($email, $nombreUsuario, $estadoCompra, $mensaje);
        return $mailEnviado;
    }
    /**
     * Envia el mail
     * @param mixed $email
     * @param mixed $nombreUsuario
     * @param mixed $asunto 
     * @param mixed $mensaje
     * @param mixed $resumendecompra
     * @return void
     */
    public function enviarMail($email, $nombreUsuario, $asunto, $mensaje, $pdfFilePath)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'florencia.russo@est.fi.uncom.edu.ar';                     //SMTP username
            $mail->Password   = '39869124';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('tiendaLenny@pwd.com', 'Tienda Lenny');
            $mail->addAddress($email, $nombreUsuario);     //Add a recipient
            $mail->addAttachment($pdfFilePath);
            //Attachments
            // $mail->addAttachment($resumendecompra);         //Add attachments

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;

            $mail->send();
            echo 'El mensaje fue enviado';
        } catch (Exception $e) {
            echo "No se pudo enviar el correo: {$mail->ErrorInfo}";
        }
        
    
    }
}
