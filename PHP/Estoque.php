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
<title>Gestta - Módulo de Estoque</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="../CSS/Estoque.css">
<style>
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
transition: opacity 0.3s, visibility 0s 0.3s;
}
.overlay.visible {
visibility: visible;
opacity: 1;
transition: opacity 0.3s;
}

.bg-card {
background-color: #ffffff;
}
.text-default {
color: #333333;
}
.text-muted {
color: #6b7280;
}
.dark .bg-card {
background-color: #1a202c;
}
.dark .text-default {
color: #ffffff;
}
.dark .text-muted {
color: #a0aec0;
}
.dark .bg-gray-100 {
background-color: #2d3748;
}
.dark .border-gray-300 {
border-color: #4a5568;
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
<a href="Dashboard.php" class="flex items-center space-x-2 text-primary-blue hover:text-blue-600 transition duration-300">
<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
</svg>
<span class="font-medium hidden sm:inline">Voltar</span>
</a>
<div class="flex items-center space-x-4">
<h1 class="text-3xl font-bold text-primary-blue">Gestta</h1>
<span class="text-xl text-muted">|</span>
<h2 class="text-xl font-medium text-muted">Módulo de Estoque</h2>
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
<div class="bg-card rounded-xl shadow-md p-6 transition-colors duration-300">
<div class="flex flex-col md:flex-row justify-between items-center mb-6">
<h3 class="text-xl font-semibold text-default mb-4 md:mb-0">Tabela de Produtos</h3>
<div class="flex space-x-2 w-full md:w-auto">
<input type="text" id="search-input" placeholder="Pesquisar..." class="w-full md:w-48 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:border-blue-500 text-default bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
<a href="adicionar_produto.php" class="bg-primary-blue text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-600 transition-colors duration-300 flex items-center space-x-2">
<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
<path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
</svg>
<span>Adicionar Produto</span>
</a>
</div>
</div>
<div class="overflow-x-auto">
<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
<thead class="bg-gray-50 dark:bg-gray-800">
<tr>
<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Nome</th>
<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">codigo</th>
<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Quantidade</th>
<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Preço</th>
<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Status</th>
<th scope="col" class="relative px-6 py-3">
<span class="sr-only">Ações</span>
</th>
</tr>
</thead>
<tbody class="bg-card divide-y divide-gray-200 dark:divide-gray-700 text-default">
<?php foreach ($produtos as $produto): ?>
<tr>
<td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($produto['nome']); ?></td>
<td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($produto['codigo']); ?></td>
<td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($produto['quantidade']); ?></td>
<td class="px-6 py-4 whitespace-nowrap">R$ <?php echo htmlspecialchars(number_format($produto['preco'], 2, ',', '.')); ?></td>
<td class="px-6 py-4 whitespace-nowrap">
<?php
$status = '';
$color = '';
if ($produto['quantidade'] > 10) {
$status = 'Em Estoque';
$color = 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200';
} elseif ($produto['quantidade'] > 0) {
$status = 'Baixo Estoque';
$color = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200';
} else {
$status = 'Esgotado';
$color = 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200';
}
?>
<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $color; ?>">
<?php echo $status; ?>
</span>
</td>
<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
<button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($produto)); ?>)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-2">Editar</button>
<button onclick="openDeleteModal('<?php echo htmlspecialchars($produto['codigo']); ?>')" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Excluir</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table> 
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

<div id="edit-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
<div class="bg-card p-6 rounded-lg shadow-xl w-full max-w-lg transition-colors duration-300">
<div class="flex justify-between items-center mb-4">
<h4 class="text-xl font-bold text-default">Editar Produto</h4>
<button onclick="closeEditModal()" class="text-muted hover:text-default">
<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
</svg>
</button>
</div>
<form action="data.php" method="POST">
<input type="hidden" name="action" value="edit">
<input type="hidden" id="edit-codigo" name="codigo">
<div class="mb-4">
<label for="edit-nome" class="block text-sm font-medium text-muted">Nome do Produto</label>
<input type="text" id="edit-nome" name="nome" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-default bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
</div>
<div class="mb-4">
<label for="edit-quantidade" class="block text-sm font-medium text-muted">Quantidade</label>
<input type="number" id="edit-quantidade" name="quantidade" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-default bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
</div>
<div class="mb-6">
<label for="edit-preco" class="block text-sm font-medium text-muted">Preço de Venda</label>
<input type="number" step="0.01" id="edit-preco" name="preco" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-default bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200">
</div>
<div class="flex justify-end">
<button type="button" onclick="closeEditModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg mr-2 hover:bg-gray-300 transition-colors duration-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Cancelar</button>
<button type="submit" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-300">Salvar Alterações</button>
</div>
</form>
</div>
</div>

<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
<div class="bg-card p-6 rounded-lg shadow-xl w-full max-w-sm transition-colors duration-300">
<h4 class="text-xl font-bold text-default mb-4">Confirmar Exclusão</h4>
<p class="text-muted mb-6">Tem certeza que deseja excluir este produto? Essa ação não pode ser desfeita.</p>
<form action="data.php" method="POST" class="flex justify-end">
<input type="hidden" name="action" value="delete">
<input type="hidden" id="delete-codigo" name="codigo">
<button type="button" onclick="closeDeleteModal()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg mr-2 hover:bg-gray-300 transition-colors duration-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">Cancelar</button>
<button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-300">Excluir</button>
</form>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
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

const editModal = document.getElementById('edit-modal');
const deleteModal = document.getElementById('delete-modal');
const editcodigoInput = document.getElementById('edit-codigo');
const editNomeInput = document.getElementById('edit-nome');
const editQuantidadeInput = document.getElementById('edit-quantidade');
const editPrecoInput = document.getElementById('edit-preco');
const deletecodigoInput = document.getElementById('delete-codigo');

function openEditModal(produto) {
editcodigoInput.value = produto.codigo;
editNomeInput.value = produto.nome;
editQuantidadeInput.value = produto.quantidade;
editPrecoInput.value = produto.preco;
editModal.classList.remove('hidden');
editModal.classList.add('flex');
}

function closeEditModal() {
editModal.classList.add('hidden');
editModal.classList.remove('flex');
}

function openDeleteModal(codigo) {
deletecodigoInput.value = codigo;
deleteModal.classList.remove('hidden');
deleteModal.classList.add('flex');
}

function closeDeleteModal() {
deleteModal.classList.add('hidden');
deleteModal.classList.remove('flex');
}

document.getElementById('search-input').addEventListener('keyup', (e) => {
const searchText = e.target.value.toLowerCase();
const rows = document.querySelectorAll('tbody tr');
rows.forEach(row => {
const text = row.textContent.toLowerCase();
row.style.display = text.includes(searchText) ? '' : 'none';
});
});
</script>
</body>
</html>
