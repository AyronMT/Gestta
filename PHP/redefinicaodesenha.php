<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../Livrarias/PHPMailer/src/Exception.php';
require '../Livrarias/PHPMailer/src/PHPMailer.php';
require '../Livrarias/PHPMailer/src/SMTP.php';

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestta";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $token = bin2hex(random_bytes(32));
        $expiracao = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt = $conn->prepare("UPDATE usuarios SET token_redefinicao = ?, token_expiracao = ? WHERE id = ?");
        $stmt->execute([$token, $expiracao, $usuario['id']]);

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ayronmt@gmail.com';
            $mail->Password   = 'kjja abzv fdyo zvxm';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('no-reply@gestta.com', 'Gestta');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Redefinicao de Senha';
            $mail->Body    = "Olá,<br><br>Seu token para redefinir a senha é: <strong>$token</strong><br><br>Este token irá expirar em 1 hora.";

            $mail->send();
            
            header("Location: ../HTML/ValidarToken.html");
            exit();
            
        } catch (Exception $e) {
            echo "Não foi possível enviar o e-mail. Erro: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Se este e-mail estiver cadastrado, um token de redefinição foi enviado.';
    }
}
