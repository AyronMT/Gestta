<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestta";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            $stmt = $conn->prepare("INSERT INTO estoque (nome, codigo, quantidade, preco) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $_POST['nome'], $_POST['codigo'], $_POST['quantidade'], $_POST['preco']);
            $stmt->execute();
            $stmt->close();
            break;

        case 'edit':
            $stmt = $conn->prepare("UPDATE estoque SET nome = ?, quantidade = ?, preco = ? WHERE codigo = ?");
            $stmt->bind_param("sids", $_POST['nome'], $_POST['quantidade'], $_POST['preco'], $_POST['codigo']);
            $stmt->execute();
            $stmt->close();
            break;

        case 'delete':
            $stmt = $conn->prepare("DELETE FROM estoque WHERE codigo = ?");
            $stmt->bind_param("s", $_POST['codigo']);
            $stmt->execute();
            $stmt->close();
            break;
    }

    header("Location: estoque.php");
    exit();
}

$sql = "SELECT * FROM estoque";
$result = $conn->query($sql);

$produtos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}

$conn->close();
?>
