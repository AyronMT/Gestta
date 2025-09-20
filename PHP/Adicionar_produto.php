<?php
include 'data.php';

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: Login.html");
    exit();
}

$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
?>
<!DOCTYPE html>
<html lang="pt-BR" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestta - Adicionar Produto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        body { background-color: #f0f2f5; }
        .bg-card { background-color: #ffffff; }
        .text-default { color: #1f2937; }
        .text-muted { color: #6b7280; }

        .dark body { background-color: #121212; }
        .dark .bg-card { background-color: #1f2937; }
        .dark .text-default { color: #e5e7eb; }
        .dark .text-muted { color: #9ca3af; }
    </style>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary-blue': '#1877f2',
                    }
                }
            }
        }
    </script>
</head>
<body class="transition-colors duration-300">
    <div class="min-h-screen flex flex-col">
        <header class="bg-card shadow p-4 flex items-center justify-between transition-colors duration-300">
            <div class="flex items-center space-x-4">
                <h1 class="text-3xl font-bold text-primary-blue">Gestta</h1>
                <span class="text-xl text-muted">|</span>
                <h2 class="text-xl font-medium text-muted">Adicionar Produto</h2>
            </div>
        </header>

        <main class="flex-1 p-6">
            <div class="max-w-3xl mx-auto bg-card rounded-xl shadow-md p-6 transition-colors duration-300">
                <form action="data.php" method="POST">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-4">
                        <label for="nome" class="block text-sm font-medium text-muted">Nome do Produto</label>
                        <input type="text" id="nome" name="nome" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-default bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
                    </div>
                    <div class="mb-4">
                        <label for="sku" class="block text-sm font-medium text-muted">Código do Produto (SKU)</label>
                        <input type="text" id="sku" name="sku" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-default bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
                    </div>
                    <div class="mb-4">
                        <label for="quantidade" class="block text-sm font-medium text-muted">Quantidade em Estoque</label>
                        <input type="number" id="quantidade" name="quantidade" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-default bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
                    </div>
                    <div class="mb-6">
                        <label for="preco" class="block text-sm font-medium text-muted">Preço de Venda (R$)</label>
                        <input type="number" step="0.01" id="preco" name="preco" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-default bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
                    </div>
                    <div class="flex justify-end">
                        <a href="estoque.php" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg mr-2 hover:bg-gray-300 transition-colors duration-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Cancelar</a>
                        <button type="submit" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-300">Salvar Produto</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
