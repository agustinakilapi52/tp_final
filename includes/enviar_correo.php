<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCorreo($correo,$estado_compra){
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP para Mailtrap
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';  
        $mail->SMTPAuth = true;
        $mail->Username = 'f90b23faf64c21';
        $mail->Password = '8ac301a3dcc6b5';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;

        // Configuración del remitente y destinatario
        $mail->setFrom('libreria@example.com', 'Libreria');
        $mail->addAddress($correo);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Estado de tu Compra';
        $mail->Body = "
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Actualización del Estado de tu Compra</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    width: 100%;
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                .header {
                    text-align: center;
                    padding-bottom: 20px;
                    border-bottom: 2px solid #eee;
                }
                .header h1 {
                    font-size: 24px;
                    color: #333;
                }
                .content {
                    margin-top: 20px;
                }
                .content p {
                    font-size: 16px;
                    line-height: 1.6;
                    color: #555;
                }
                .content strong {
                    color: #007BFF;
                }
                .footer {
                    text-align: center;
                    margin-top: 30px;
                    padding-top: 20px;
                    border-top: 2px solid #eee;
                    font-size: 14px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Actualizacion del Estado de tu Compra</h1>
                </div>
                <div class='content'>
                    <p>Hola,</p>
                    <p>El estado de tu compra ha sido actualizado a: <strong>$estado_compra</strong>.</p>
                    <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
                </div>
                <div class='footer'>
                    <p>&copy; 2024 Libreria. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Manejo de errores
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
        return false;
    }


}
  

?>
