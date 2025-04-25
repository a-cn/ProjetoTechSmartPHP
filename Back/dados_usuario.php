<?php
require_once 'conexao_sqlserver.php';
require_once 'verifica_sessao.php';

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT 
    u.usuario_id, u.nome, u.cpf_cnpj, FORMAT(u.data_nascimento, 'dd/MM/yyyy') AS data_nascimento,
    u.email, u.num_principal, u.num_recado,
    e.cep, e.logradouro, e.numero, e.complemento, e.bairro, e.cidade, e.estado,
    t.descricao AS tipo_usuario,
    u.fk_pergunta_seguranca, p.pergunta,
    u.resposta_seguranca
FROM Usuario u
JOIN Endereco e ON u.fk_endereco = e.endereco_id
JOIN Tipo_Usuario t ON u.fk_tipo_usuario = t.tipo_usuario_id
JOIN Pergunta_Seguranca p ON u.fk_pergunta_seguranca = p.pergunta_seguranca_id
WHERE u.usuario_id = ?";

$params = array($usuario_id);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo json_encode($row);
} else {
    echo json_encode(['erro' => 'Erro ao buscar os dados do usuário.']);
}
?>