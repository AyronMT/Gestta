<?php
// Inicia a sessão para gerenciar o estado do usuário.
session_start();

// ----------------------------------------------------
// 1. CONFIGURAÇÕES DO BANCO DE DADOS
// ----------------------------------------------------

// Altere essas variáveis para as suas credenciais reais.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestta";

// O nome da tabela de usuários no seu banco de dados.
$tabela_usuarios = "usuarios";


// ----------------------------------------------------
// 2. TENTA CONECTAR AO BANCO DE DADOS
// ----------------------------------------------------
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Define o modo de erro do PDO para exceções
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Em caso de falha na conexão, exibe um erro e interrompe o script.
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// ----------------------------------------------------
// 3. PROCESSA O FORMULÁRIO DE CADASTRO
// ----------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos foram enviados.
    if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])) {

        // Limpa a entrada do usuário para prevenir ataques.
        $nome = htmlspecialchars(trim($_POST['nome']));
        $email = htmlspecialchars(trim($_POST['email']));
        $senha_pura = $_POST['senha'];

        // Criptografa a senha antes de salvar no banco de dados.
        // Essa é a etapa crucial de segurança!
        $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

        try {
            // Prepara a consulta SQL para inserir o novo usuário.
            $sql = "INSERT INTO $tabela_usuarios (nome, email, senha) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            // Vincula os valores e executa a inserção.
            $stmt->execute([$nome, $email, $senha_hash]);

            // Redireciona para a página de login após o cadastro.
            // O usuário pode então fazer login com a nova conta.
            header("Location: Login.html?cadastro=sucesso");
            exit();

        } catch(PDOException $e) {
            // Se houver um erro (ex: e-mail já existe), exibe a mensagem.
            // Em um ambiente de produção, você não exibiria o erro do banco de dados,
            // mas sim uma mensagem mais amigável.
            if ($e->getCode() == '23000') {
                echo "Erro: Este e-mail já está cadastrado.";
            } else {
                echo "Erro ao cadastrar: " . $e->getMessage();
            }
        }
    } else {
        // Campos do formulário não foram enviados corretamente.
        echo "Por favor, preencha todos os campos do formulário.";
    }
}
?>
