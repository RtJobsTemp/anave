<?php

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    if (empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Por favor, preencha todos os campos corretamente.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor
        $mail->isSMTP();
        $mail->Host = 'mail.agenciaanave.com.br'; // Atualize com o seu servidor de e-mail
        $mail->SMTPAuth = true;
        $mail->Username = 'formulario@agenciaanave.com.br'; // Seu e-mail completo
        $mail->Password = 'anave2025'; // A senha do seu e-mail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configurações do e-mail
        $mail->setFrom('formulario@agenciaanave.com.br', $name); // Remetente
        $mail->addAddress('formulario@agenciaanave.com.br'); // Destinatário
        $mail->addReplyTo($email, $name);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = "Mensagem de $name";
        $mail->Body = "Nome: $name<br>Email: $email<br><br>Mensagem:<br>$message";

        $mail->send();
        http_response_code(200);
        echo "Obrigado! Sua mensagem foi enviada com sucesso.";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Ops! Algo deu errado e sua mensagem não foi enviada. Erro: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(403);
    echo "Método não permitido.";
}
