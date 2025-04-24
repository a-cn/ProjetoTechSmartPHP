<?php
// Conexão com o banco de dados
$serverName = "GUSTAVO\SQLEXPRESS";
$connectionInfo = array(
    "Database" => "TechSmartDB",
    "UID" => "techsmart_user",
    "PWD" => "Teste123!",
    "CharacterSet" => "UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    die("Erro na conexão: " . print_r(sqlsrv_errors(), true));
}

// Processa atualizações de situação via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['situacao'])) {
    $pedido_id = $_POST['pedido_id'];
    $situacao = $_POST['situacao'];
    
    try {
        // Atualiza apenas a situação do pedido
        $sql = "UPDATE Pedido SET situacao = ? WHERE pedido_id = ?";
        $params = array($situacao, $pedido_id);
        $stmt = sqlsrv_query($conn, $sql, $params);
        
        if ($stmt === false) {
            throw new Exception("Erro na atualização: " . print_r(sqlsrv_errors(), true));
        }
        
        // Retorna sucesso como JSON
        header("Content-Type: application/json");
        echo json_encode(['success' => true]);
        exit;
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Consulta para buscar pedidos ativos
$sql = "SELECT p.pedido_id, p.data_hora, p.situacao, p.valor_total, u.cpf_cnpj 
        FROM Pedido p 
        JOIN Usuario u ON p.fk_usuario = u.usuario_id 
        WHERE p.ativo = 1
        ORDER BY p.data_hora DESC";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die("Erro na consulta: " . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Pedidos - TechSmart</title>
    <link rel="stylesheet" type="text/css" href="../CSS/sidebar-header.css">
    <link rel="stylesheet" type="text/css" href="../CSS/historico_pedidos.css">
    <!-- Removida a linha que tentava carregar o JS externo -->
</head>
<body>

<header class="topbar">
    <div class="menu-icon" onclick="toggleSidebar()">☰</div>
    <span class="logo">TechSmart</span>
</header>

<main>
    <h2>Lista de Pedidos</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="msg-sucesso">
            <?= $_GET['success'] === 'updated' ? "Pedido atualizado com sucesso!" : "" ?>
        </div>
    <?php endif; ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ações</th>
                    <th>ID Pedido</th>
                    <th>CPF/CNPJ</th>
                    <th>Data/Hora</th>
                    <th>Situação</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                <tr id="row-<?= $row['pedido_id'] ?>">
                    <td class="actions">
                        <button type="button" class="edit-btn" onclick="enableEdit(<?= $row['pedido_id'] ?>)">Editar</button>
                        <button type="button" class="save-btn" style="display:none" onclick="saveChanges(<?= $row['pedido_id'] ?>)">Salvar</button>
                        <button type="button" class="feedback-btn" onclick="viewFeedback(<?= $row['pedido_id'] ?>)">Feedback</button>
                    </td>
                    <td><?= $row['pedido_id'] ?></td>
                    <td><?= htmlspecialchars($row['cpf_cnpj']) ?></td>
                    <td><?= $row['data_hora']->format('d/m/Y H:i') ?></td>
                    <td class="situacao-cell"><?= htmlspecialchars($row['situacao']) ?></td>
                    <td>R$ <?= number_format($row['valor_total'], 2, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
// Função para habilitar a edição
function enableEdit(pedidoId) {
    const row = document.getElementById(`row-${pedidoId}`);
    const situacao = row.querySelector('.situacao-cell').textContent.trim();
    
    // Substitui a situação por um select
    row.querySelector('.situacao-cell').innerHTML = `
        <select class="situacao-select" id="select-${pedidoId}">
            <option value="Pendente" ${situacao === 'Pendente' ? 'selected' : ''}>Pendente</option>
            <option value="Em processamento" ${situacao === 'Em processamento' ? 'selected' : ''}>Processando</option>
            <option value="Enviado" ${situacao === 'Enviado' ? 'selected' : ''}>Enviado</option>
            <option value="Entregue" ${situacao === 'Entregue' ? 'selected' : ''}>Entregue</option>
            <option value="Cancelado" ${situacao === 'Cancelado' ? 'selected' : ''}>Cancelado</option>
        </select>`;
    
    // Alterna os botões
    row.querySelector('.edit-btn').style.display = 'none';
    row.querySelector('.save-btn').style.display = 'inline-block';
}

// Função para salvar as alterações
function saveChanges(pedidoId) {
    const situacao = document.getElementById(`select-${pedidoId}`).value;
    
    // Mostra loading
    const saveBtn = document.querySelector(`#row-${pedidoId} .save-btn`);
    saveBtn.disabled = true;
    saveBtn.textContent = 'Salvando...';
    
    fetch('historico_pedidos.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `pedido_id=${pedidoId}&situacao=${encodeURIComponent(situacao)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Situação atualizada com sucesso!');
            location.reload();
        } else {
            throw new Error(data.error || 'Erro ao atualizar');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao atualizar: ' + error.message);
        saveBtn.disabled = false;
        saveBtn.textContent = 'Salvar';
    });
}

// Função para visualizar feedback (simulada)
function viewFeedback(pedidoId) {
    alert(`Visualizar feedback do pedido ${pedidoId}`);
    // Implementação real redirecionaria para feedback.php?pedido_id=X
}
</script>

<?php
// Fecha a conexão com o banco de dados
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>