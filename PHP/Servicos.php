<?php
include 'data.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../HTML/Login.html");
    exit();
}

$conn->begin_transaction();

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if (isset($_POST['add_servico'])) {
            $produtos_selecionados_json = [];
            $custo_total = 0;

            if (isset($_POST['produtos_id'])) {
                foreach ($_POST['produtos_id'] as $index => $id) {
                    $qtd = (int)$_POST['produtos_qtd'][$index];
                    
                    $stmt_check = $conn->prepare("SELECT nome, quantidade, preco FROM estoque WHERE codigo = ? FOR UPDATE");
                    $stmt_check->bind_param("i", $id);
                    $stmt_check->execute();
                    $produto = $stmt_check->get_result()->fetch_assoc();

                    if (!$produto || $produto['quantidade'] < $qtd) {
                        throw new Exception("Estoque insuficiente para o produto: " . ($produto['nome'] ?? 'ID ' . $id));
                    }
                    
                    $produtos_selecionados_json[] = ["id" => $id, "nome" => $produto['nome'], "quantidade" => $qtd];
                    $custo_total += $produto['preco'] * $qtd;
                }
            }

            foreach ($produtos_selecionados_json as $prod) {
                $stmt_update = $conn->prepare("UPDATE estoque SET quantidade = quantidade - ? WHERE codigo = ?");
                $stmt_update->bind_param("ii", $prod['quantidade'], $prod['id']);
                $stmt_update->execute();
            }

            $sql = "INSERT INTO servicos (NomeCli, Descricao, datadeabertura, situacao, produtos, Custo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql);
            $produtos_json_string = json_encode($produtos_selecionados_json);
            $stmt_insert->bind_param("sssssd", $_POST['nome_cli'], $_POST['descricao'], $_POST['data_abertura'], $_POST['situacao'], $produtos_json_string, $custo_total);
            $stmt_insert->execute();
        }

        // --- EDITAR ORDEM DE SERVIÇO ---
        if (isset($_POST['edit_servico'])) {
            $ordem_id = $_POST['ordem_id'];
            
            $stmt_get_old = $conn->prepare("SELECT produtos FROM servicos WHERE NumeroOrdem = ?");
            $stmt_get_old->bind_param("i", $ordem_id);
            $stmt_get_old->execute();
            $old_produtos_json = $stmt_get_old->get_result()->fetch_assoc()['produtos'];
            $old_produtos = json_decode($old_produtos_json, true) ?: [];
            
            $map_old_produtos = [];
            foreach ($old_produtos as $p) { $map_old_produtos[$p['id']] = $p['quantidade']; }

            $map_new_produtos = [];
            if (isset($_POST['produtos_id'])) {
                foreach ($_POST['produtos_id'] as $index => $id) { $map_new_produtos[$id] = (int)$_POST['produtos_qtd'][$index]; }
            }
            
            $stock_changes = [];
            foreach ($map_new_produtos as $id => $new_qtd) {
                $old_qtd = $map_old_produtos[$id] ?? 0;
                $change = $new_qtd - $old_qtd;
                if ($change != 0) { $stock_changes[$id] = $change; }
            }
            foreach ($map_old_produtos as $id => $old_qtd) {
                if (!isset($map_new_produtos[$id])) { $stock_changes[$id] = -$old_qtd; }
            }
            
            foreach ($stock_changes as $id => $change) {
                if ($change > 0) {
                    $stmt_check = $conn->prepare("SELECT quantidade, nome FROM estoque WHERE codigo = ? FOR UPDATE");
                    $stmt_check->bind_param("i", $id);
                    $stmt_check->execute();
                    $produto = $stmt_check->get_result()->fetch_assoc();
                    if (!$produto || $produto['quantidade'] < $change) {
                        throw new Exception("Estoque insuficiente para atualizar o produto: " . $produto['nome']);
                    }
                }
            }
            
            foreach ($stock_changes as $id => $change) {
                $stmt_update_stock = $conn->prepare("UPDATE estoque SET quantidade = quantidade - ? WHERE codigo = ?");
                $stmt_update_stock->bind_param("ii", $change, $id);
                $stmt_update_stock->execute();
            }
            
            $new_produtos_json_array = [];
            $custo_total = 0;
             if (isset($_POST['produtos_id'])) {
                foreach ($_POST['produtos_id'] as $index => $id) {
                    $qtd = (int)$_POST['produtos_qtd'][$index];
                    $stmt_preco = $conn->prepare("SELECT nome, preco FROM estoque WHERE codigo = ?");
                    $stmt_preco->bind_param("i", $id);
                    $stmt_preco->execute();
                    $produto_info = $stmt_preco->get_result()->fetch_assoc();
                    
                    $new_produtos_json_array[] = ["id" => $id, "nome" => $produto_info['nome'], "quantidade" => $qtd];
                    $custo_total += $produto_info['preco'] * $qtd;
                }
            }

            $final_produtos_json = json_encode($new_produtos_json_array);
            $sql_update_ordem = "UPDATE servicos SET NomeCli=?, Descricao=?, datadeabertura=?, situacao=?, produtos=?, Custo=? WHERE NumeroOrdem=?";
            $stmt_update_ordem = $conn->prepare($sql_update_ordem);
            $stmt_update_ordem->bind_param("sssssdi", $_POST['nome_cli'], $_POST['descricao'], $_POST['data_abertura'], $_POST['situacao'], $final_produtos_json, $custo_total, $ordem_id);
            $stmt_update_ordem->execute();
        }

        if (isset($_POST['delete_servico'])) {
            $id = $_POST['id_servico'];
            
            $stmt_get = $conn->prepare("SELECT situacao, produtos FROM servicos WHERE NumeroOrdem = ?");
            $stmt_get->bind_param("i", $id);
            $stmt_get->execute();
            $ordem = $stmt_get->get_result()->fetch_assoc();

            if ($ordem && ($ordem['situacao'] == 'Aberta' || $ordem['situacao'] == 'Em andamento')) {
                $produtos_devolver = json_decode($ordem['produtos'], true);
                if (is_array($produtos_devolver)) {
                    foreach ($produtos_devolver as $prod) {
                        $stmt_devolve = $conn->prepare("UPDATE estoque SET quantidade = quantidade + ? WHERE codigo = ?");
                        $stmt_devolve->bind_param("ii", $prod['quantidade'], $prod['id']);
                        $stmt_devolve->execute();
                    }
                }
            }
            
            $stmt_delete = $conn->prepare("DELETE FROM servicos WHERE NumeroOrdem = ?");
            $stmt_delete->bind_param("i", $id);
            $stmt_delete->execute();
        }
        
        $conn->commit();
        header("Location: Servicos.php");
        exit();

    }
} catch (Exception $e) {
    $conn->rollback();
    die("Erro na operação: " . $e->getMessage());
}

$produtos_estoque_result = $conn->query("SELECT codigo, nome, quantidade, preco FROM estoque WHERE quantidade > 0 ORDER BY nome ASC");
$produtos_estoque = [];
while($row = $produtos_estoque_result->fetch_assoc()) {
    $produtos_estoque[] = $row;
}

$result = $conn->query("SELECT * FROM servicos ORDER BY datadeabertura DESC");

function getStatusClass($status) {
    return 'status-' . strtolower(str_replace(' ', '-', $status));
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordens de Serviço - Gestta</title>
    <link rel="stylesheet" href="../CSS/Servicos.css">
</head>
<body>
    <header class="app-header">
        <div class="header-left"><a href="Dashboard.php">← Voltar</a></div>
        <div class="header-center"><span>Gestta</span> | Módulo de Serviços</div>
        <div class="header-right">👤</div>
    </header>
    <main class="main-content">
        <div class="content-card">
            <div class="card-header">
                <h2>Tabela de Ordens de Serviço</h2>
                <div class="card-actions">
                    <input type="text" class="search-bar" placeholder="Pesquisar...">
                    <button class="btn-add" data-modal-target="#addServicoModal">+ Adicionar Ordem</button>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nº Ordem</th>
                        <th>Cliente</th>
                        <th>Produtos</th>
                        <th>Custo Total</th>
                        <th>Situação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['NumeroOrdem']; ?></td>
                            <td><?php echo htmlspecialchars($row['NomeCli']); ?></td>
                            <td>
                                <?php 
                                    $produtos_da_ordem = json_decode($row['produtos'], true);
                                    if (is_array($produtos_da_ordem) && !empty($produtos_da_ordem)) {
                                        foreach ($produtos_da_ordem as $p) {
                                            echo htmlspecialchars($p['quantidade']) . 'x ' . htmlspecialchars($p['nome']) . '<br>';
                                        }
                                    } else {
                                        echo 'Nenhum produto.';
                                    }
                                ?>
                            </td>
                            <td>R$ <?php echo number_format($row['Custo'], 2, ',', '.'); ?></td>
                            <td>
                                <span class="status-tag <?php echo getStatusClass($row['situacao']); ?>">
                                    <?php echo htmlspecialchars($row['situacao']); ?>
                                </span>
                            </td>
                            <td class="table-actions">
                                <span class="action-edit edit-btn" data-modal-target="#editServicoModal" data-row-data='<?php echo htmlspecialchars(json_encode($row)); ?>'>Editar</span>
                                <span class="action-delete delete-btn" data-modal-target="#deleteServicoModal" data-id="<?php echo $row['NumeroOrdem']; ?>">Excluir</span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align: center;">Nenhuma ordem de serviço encontrada.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    
    <div id="addServicoModal" class="modal">
        <div class="modal-content-wrapper">
            <form action="Servicos.php" method="post">
                <div class="modal-header"><h5>Adicionar Nova Ordem</h5><button type="button" class="modal-close-btn">&times;</button></div>
                <div class="modal-body">
                    <div class="form-group"><label class="form-label">Nome do Cliente</label><input type="text" class="form-input" name="nome_cli" required></div>
                    <div class="form-group"><label class="form-label">Data de Abertura</label><input type="date" class="form-input" name="data_abertura" value="<?php echo date('Y-m-d'); ?>" required></div>
                    <div class="form-group"><label class="form-label">Situação</label><select class="form-select" name="situacao"><option value="Aberta">Aberta</option><option value="Em andamento">Em andamento</option></select></div>
                    <div class="form-group"><label class="form-label">Descrição</label><textarea class="form-textarea" name="descricao" rows="2"></textarea></div>
                    <div class="product-selector-area">
                        <label class="form-label">Adicionar Produtos</label>
                        <div class="product-adder">
                            <!-- MUDANÇA: id alterado para class -->
                            <select class="produtoDisponivelAdd"><option value="">-- Selecione --</option><?php foreach ($produtos_estoque as $p): ?><option value="<?php echo $p['codigo']; ?>" data-max="<?php echo $p['quantidade']; ?>"><?php echo htmlspecialchars($p['nome']) . " (Estoque: " . $p['quantidade'] . ")"; ?></option><?php endforeach; ?></select>
                            <!-- MUDANÇA: id alterado para class -->
                            <input type="number" class="produtoQuantidadeAdd" min="1" placeholder="Qtd">
                            <!-- MUDANÇA: id alterado para class -->
                            <button type="button" class="btn-add-item btnAddProdutoItemAdd">Adicionar</button>
                        </div>
                        <!-- MUDANÇA: id alterado para class -->
                        <div class="produtosSelecionadosListaAdd"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary modal-close-btn">Fechar</button><button type="submit" name="add_servico" class="btn btn-add">Salvar Ordem</button></div>
            </form>
        </div>
    </div>

    <div id="editServicoModal" class="modal">
        <div class="modal-content-wrapper">
            <form action="Servicos.php" method="post">
                <input type="hidden" name="ordem_id" id="edit_ordem_id">
                <div class="modal-header"><h5>Editar Ordem de Serviço</h5><button type="button" class="modal-close-btn">&times;</button></div>
                <div class="modal-body">
                    <div class="form-group"><label class="form-label">Nome do Cliente</label><input type="text" class="form-input" name="nome_cli" id="edit_nome_cli" required></div>
                    <div class="form-group"><label class="form-label">Data de Abertura</label><input type="date" class="form-input" name="data_abertura" id="edit_data_abertura" required></div>
                    <div class="form-group"><label class="form-label">Situação</label><select class="form-select" name="situacao" id="edit_situacao"><option value="Aberta">Aberta</option><option value="Em andamento">Em andamento</option><option value="Concluída">Concluída</option><option value="Cancelada">Cancelada</option></select></div>
                    <div class="form-group"><label class="form-label">Descrição</label><textarea class="form-textarea" name="descricao" id="edit_descricao" rows="2"></textarea></div>
                    <div class="product-selector-area">
                        <label class="form-label">Adicionar Mais Produtos</label>
                        <div class="product-adder">
                            <select class="produtoDisponivelEdit"><option value="">-- Selecione --</option><?php foreach ($produtos_estoque as $p): ?><option value="<?php echo $p['codigo']; ?>" data-max="<?php echo $p['quantidade']; ?>"><?php echo htmlspecialchars($p['nome']) . " (Estoque: " . $p['quantidade'] . ")"; ?></option><?php endforeach; ?></select>
                            <input type="number" class="produtoQuantidadeEdit" min="1" placeholder="Qtd"><button type="button" class="btn-add-item btnAddProdutoItemEdit">Adicionar</button>
                        </div>
                        <div class="produtosSelecionadosListaEdit"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary modal-close-btn">Fechar</button><button type="submit" name="edit_servico" class="btn btn-add">Salvar Alterações</button></div>
            </form>
        </div>
    </div>

    <div id="deleteServicoModal" class="modal">
        <div class="modal-content-wrapper">
            <form action="Servicos.php" method="post">
                <div class="modal-header"><h5>Excluir Ordem de Serviço</h5><button type="button" class="modal-close-btn">&times;</button></div>
                <div class="modal-body"><p>Tem certeza? Itens de ordens não concluídas retornarão ao estoque.</p></div>
                <div class="modal-footer">
                    <input type="hidden" name="id_servico" id="delete_id_servico">
                    <button type="button" class="btn btn-secondary modal-close-btn">Cancelar</button>
                    <button type="submit" name="delete_servico" class="btn btn-danger">Confirmar Exclusão</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>const produtosEstoque = <?php echo json_encode($produtos_estoque); ?>;</script>
    <script src="../JAVASCRIPT/servicos.js"></script>
</body>
</html>
<?php
$conn->close();
?>