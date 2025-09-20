<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: Login.html");
    exit();
}

$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "Gestta";

$lowStockCount = "--";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
} else {
    $sql = "SELECT COUNT(*) FROM Estoque WHERE quantidade < 10";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_row();
        $lowStockCount = $row[0];
    } else {
        error_log("Query failed: " . $conn->error);
    }

    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="pt-BR" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestta - Dashboard</title>
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

        .sidebar {
            transform: translateX(100%);
        }
        .sidebar.open {
            transform: translateX(0);
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s, visibility 0.3s;
        }
        .overlay.visible {
            visibility: visible;
            opacity: 1;
        }
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
                        'purple-btn': '#c618f2',
                        'green-btn': '#42b72a',
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
                <h2 class="text-xl font-medium text-muted">Dashboard</h2>
            </div>
            <div class="flex items-center space-x-4">
                <button class="text-muted hover:text-default transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>
                <button id="profile-btn" class="text-muted hover:text-default transition duration-300 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>
            </div>
        </header>

        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-card rounded-xl shadow-md p-6 flex flex-col items-center justify-center text-center transition-colors duration-300">
                        <span class="text-5xl text-blue-500 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M10 16h.01" />
                            </svg>
                        </span>
                        <h3 class="text-lg font-semibold text-default">Ordens de Serviço em Andamento</h3>
                        <p id="os-count" class="text-4xl font-bold text-primary-blue mt-2">--</p>
                    </div>
                    <div class="bg-card rounded-xl shadow-md p-6 flex flex-col items-center justify-center text-center transition-colors duration-300">
                        <span class="text-5xl text-red-500 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.398 17c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>
                        <h3 class="text-lg font-semibold text-default">Itens com Estoque Baixo</h3>
                        <p id="low-stock-count" class="text-4xl font-bold text-red-500 mt-2"><?php echo htmlspecialchars($lowStockCount); ?></p>
                    </div>
                    <div class="bg-card rounded-xl shadow-md p-6 flex flex-col items-center justify-center text-center transition-colors duration-300">
                        <span class="text-5xl text-green-500 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6z" />
                            </svg>
                        </span>
                        <h3 class="text-lg font-semibold text-default">Faturamento Recente</h3>
                        <p id="revenue-amount" class="text-4xl font-bold text-green-500 mt-2">R$ --</p>
                    </div>
                </div>

                <div class="bg-card rounded-xl shadow-md p-6 transition-colors duration-300">
                    <h3 class="text-xl font-semibold text-default mb-4">Acessos Rápidos</h3>
                    <div class="flex flex-wrap gap-4">

                        <a href="../HTML/OrdensdeServico.html" class="flex-1 min-w-[200px] px-6 py-4 rounded-xl font-medium transition duration-300 text-center text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <div class="flex items-center justify-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M10 16h.01" />
                                </svg>
                                <span>Gerenciar OS</span>
                            </div>
                        </a>

                        <a href="Estoque.php" class="flex-1 min-w-[200px] px-6 py-4 rounded-xl font-medium transition duration-300 text-center text-white bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <div class="flex items-center justify-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10m-4-2h8a2 2 0 002-2V9a2 2 0 00-2-2H8a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                                <span>Gerenciar Estoque</span>
                            </div>
                        </a>
                        <a href="../HTML/Faturamento.html" class="flex-1 min-w-[200px] px-6 py-4 rounded-xl font-medium transition duration-300 text-center text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <div class="flex items-center justify-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9m6 0a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Ver Faturamento</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="overlay" class="overlay fixed inset-0 z-40"></div>

    <aside id="sidebar" class="sidebar fixed top-0 right-0 w-64 h-full bg-card shadow-lg z-50 p-6 transition-transform duration-300 ease-in-out">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-default">Perfil</h3>
            <button id="close-sidebar-btn" class="text-muted hover:text-default">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="text-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto mb-2 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <p id="user-name" class="text-lg font-semibold text-default"><?php echo htmlspecialchars($nome_usuario); ?></p>
            <p id="user-email" class="text-sm text-muted"><?php echo htmlspecialchars($email_usuario); ?></p>
        </div>

        <nav class="flex flex-col space-y-4">
            <button id="dark-mode-btn" class="flex items-center space-x-3 px-4 py-2 rounded-full text-default hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <span>Modo Escuro</span>
            </button>
            <a href="logout.php" id="logout-btn" class="flex items-center space-x-3 px-4 py-2 rounded-full text-red-500 hover:bg-red-100 dark:hover:bg-red-800 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Sair</span>
            </a>
        </nav>
    </aside>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const osCount = 42;
            const revenueAmount = 1250.75;

            document.getElementById('os-count').textContent = osCount;
            document.getElementById('revenue-amount').textContent = `R$ ${revenueAmount.toFixed(2).replace('.', ',')}`;

            const profileBtn = document.getElementById('profile-btn');
            const sidebar = document.getElementById('sidebar');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const overlay = document.getElementById('overlay');

            function toggleSidebar() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('visible');
            }

            profileBtn.addEventListener('click', toggleSidebar);
            closeSidebarBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);

            const darkModeBtn = document.getElementById('dark-mode-btn');
            const html = document.documentElement;
            const isDarkMode = localStorage.getItem('darkMode') === 'true';

            if (isDarkMode) {
                html.classList.add('dark');
            }

            darkModeBtn.addEventListener('click', () => {
                const newDarkMode = !html.classList.contains('dark');
                if (newDarkMode) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
                localStorage.setItem('darkMode', newDarkMode);
            });
        });
    </script>
</body>
</html>
