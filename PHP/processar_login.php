<?php
session_start();


$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "Gestta";


$tabela_usuarios = "usuarios";


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['email_ou_telefone']) && isset($_POST['senha'])) {

        $email = htmlspecialchars(trim($_POST['email_ou_telefone']));
        $senha_digitada = $_POST['senha'];

        // Seleciona a senha e o nome do usuário
        $sql = "SELECT senha, nome FROM $tabela_usuarios WHERE email = ?";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute([$email]);
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $senha_hash_do_banco = $resultado['senha'];

            if (password_verify($senha_digitada, $senha_hash_do_banco)) {
                
                $_SESSION['logado'] = true;
                $_SESSION['email_usuario'] = $email;
                $_SESSION['nome_usuario'] = $resultado['nome'];

                header("Location: dashboard.php");
                exit();
            } else {
                echo "Senha incorreta.";
            }
        } else {
            echo "E-mail ou telefone não encontrado.";
        }
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }
}
?>
