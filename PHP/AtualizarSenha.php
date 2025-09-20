<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestta";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token_digitado = $_POST['token'];
    $nova_senha = $_POST['nova_senha'];
    $agora = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE token_redefinicao = ? AND token_expiracao > ?");
    $stmt->execute([$token_digitado, $agora]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die('Token inválido ou expirado.');
    }

    $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    $stmt_update = $conn->prepare("UPDATE usuarios SET senha = ?, token_redefinicao = NULL, token_expiracao = NULL WHERE id = ?");
    $stmt_update->execute([$nova_senha_hash, $usuario['id']]);

    echo "Sua senha foi redefinida com sucesso. <a href='../HTML/Login.html'>Faça o login</a>";
    exit();
}
