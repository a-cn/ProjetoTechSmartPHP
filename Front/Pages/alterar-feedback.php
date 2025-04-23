<?php
require_once '../../Back/verifica_sessao.php'; //Garante que somente usuários logados possam acessar a página
require_once '../../Back/consulta_feedback.php'; //Utiliza parâmetros em comum com este arquivo incluído

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id'])) {
    $feedback_id = htmlspecialchars($_POST['feedback_id']);

    if (!$conn || !is_resource($conn)) {
        die("Erro na conexão com o banco de dados: " . print_r(sqlsrv_errors(), true));
    }

    $sql = "SELECT * FROM Feedback WHERE feedback_id = ?";
    $stmt = sqlsrv_query($conn, $sql, [$feedback_id]);

    if (!$stmt) {
        die("Erro ao buscar o registro: " . print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($row) {
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/formulario-feedback.css">
    <title>Editar Feedback</title>
</head>
<body>
    <?php include 'sidebar-header.php'; ?> <!-- Cabeçalho e barra lateral -->
    <header>Editar Feedback</header>

    <form action="../../Back/atualizar_feedback.php" method="POST">
        <input type="hidden" name="feedback_id" value="<?php echo htmlspecialchars($row['feedback_id']); ?>">

        <label for="avaliacao">Avaliação (1 a 5):</label><br>
        <div class="rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" name="avaliacao" id="star<?php echo $i; ?>" value="<?php echo $i; ?>" 
                    <?php if ((int)$row['avaliacao'] === $i) echo 'checked'; ?>>
                <label for="star<?php echo $i; ?>">&#9733;</label>
            <?php endfor; ?>
        </div>

        <label for="observacao">Observação:</label><br>
        <textarea name="observacao" id="observacao" rows="4" cols="30"><?php echo htmlspecialchars($row['observacao']); ?></textarea><br><br>

        <button type="submit">Salvar</button>
    </form>
</body>
</html>
<?php
    } else {
        echo "Registro não encontrado.";
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
} else {
    echo "Método inválido ou feedback_id ausente.";
}
?>