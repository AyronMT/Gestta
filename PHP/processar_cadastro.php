<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestta";

$tabela_usuarios = "usuarios";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])) {

        $nome = htmlspecialchars(trim($_POST['nome']));
        $email = htmlspecialchars(trim($_POST['email']));
        $senha_pura = $_POST['senha'];

        try {
            $sql_verificar = "SELECT COUNT(*) FROM $tabela_usuarios WHERE email = ?";
            $stmt_verificar = $conn->prepare($sql_verificar);
            $stmt_verificar->execute([$email]);
            $email_existente = $stmt_verificar->fetchColumn();

            if ($email_existente > 0) {
                echo '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro de Cadastro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ["Inter", "sans-serif"],
                    },
                    colors: {
                        "primary-blue": "#1877f2",
                        "purple-btn": "#c618f2",
                        "green-btn": "#42b72a",
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: "Inter", sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
            box-sizing: border-box;
        }

        .error-box {
            background-color: #ffffff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .btn-link {
            display: inline-block;
            margin-top: 20px;
            color: #ffffff;
            background-color: #d9534f;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .btn-link:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <h2 class="text-3xl font-bold text-red-600 mb-4">Oops!</h2>
        <p class="text-lg text-gray-600 mb-6">Este e-mail já está cadastrado.</p>
        <a href="../HTML/TeladeCadastro.html" class="btn-link">Tentar Novamente</a>
    </div>
</body>
</html>';
            } else {
                $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);
                $sql_inserir = "INSERT INTO $tabela_usuarios (nome, email, senha) VALUES (?, ?, ?)";
                $stmt_inserir = $conn->prepare($sql_inserir);
                $stmt_inserir->execute([$nome, $email, $senha_hash]);

                echo '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Concluído</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ["Inter", "sans-serif"],
                    },
                    colors: {
                        "primary-blue": "#1877f2",
                        "purple-btn": "#c618f2",
                        "green-btn": "#42b72a",
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: "Inter", sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
            box-sizing: border-box;
        }

        .success-box {
            background-color: #ffffff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .btn-link {
            display: inline-block;
            margin-top: 20px;
            color: #ffffff;
            background-color: #1877f2;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .btn-link:hover {
            background-color: #1562c2;
        }
    </style>
</head>
<body>
    <div class="success-box">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Parabéns!</h2>
        <p class="text-lg text-gray-600 mb-6">Cadastro realizado com sucesso!</p>
        <a href="../HTML/Login.html" class="btn-link">Ir para o Login</a>
    </div>
</body>
</html>';
            }
        } catch(PDOException $e) {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }
}

?>
