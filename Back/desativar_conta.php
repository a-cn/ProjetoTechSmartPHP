<?php
require_once 'conexao_sqlserver.php';
require_once 'verifica_sessao.php';

$usuario_id = $_SESSION['usuario_id'];

$sql = "UPDATE Usuario SET ativo = 0 WHERE usuario_id = ?";
$params = array($usuario_id);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt) {
    session_destroy();
    header("Location: ../Front/Pages/index.html");
    exit;
} else {
    echo "Erro ao desativar conta.";
}
?>