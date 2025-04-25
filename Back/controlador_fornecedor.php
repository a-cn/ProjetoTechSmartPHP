<?php
header('Content-Type: application/json');
include 'conexao_sqlserver.php';

$action = $_POST['action'] ?? 'read';
$response = [];

switch ($action) {
  case 'read':
    $sql = "SELECT 
      f.fornecedor_id, 
      f.nome, 
      f.cpf_cnpj, 
      f.num_principal, 
      f.num_secundario, 
      f.email, 
      f.situacao,
      e.cep,
      e.logradouro,
      e.numero,
      e.complemento,
      e.bairro,
      e.cidade,
      e.estado
    FROM Fornecedor f
    JOIN Endereco e ON e.endereco_id = f.fk_endereco
    WHERE f.ativo = 1";
    $stmt = sqlsrv_query($conn, $sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      $response[] = $row;
    }
    break;

  case 'arquivar':
    $id = $_POST['id'] ?? null;
    if ($id) {
      $stmt = sqlsrv_query($conn, "UPDATE Fornecedor SET situacao = 'ARQUIVADO' WHERE fornecedor_id = ?", [$id]);
      $response['success'] = $stmt ? true : false;
    } else {
      $response['error'] = 'ID não enviado';
    }
    break;

  case 'excluir':
    $id = $_POST['id'] ?? null;
    if ($id) {
      $stmt = sqlsrv_query($conn, "DELETE FROM Fornecedor WHERE fornecedor_id = ?", [$id]);
      $response['success'] = $stmt ? true : false;
    } else {
      $response['error'] = 'ID não enviado';
    }
    break;

  case 'alterar':
    $id = $_POST['id'] ?? null;
    if ($id) {
      $sql = "UPDATE Fornecedor SET nome=?, cpf_cnpj=?, num_principal=?, num_secundario=?, email=?, situacao=? WHERE fornecedor_id=?";
      $params = [
        $_POST['nome'],
        preg_replace('/\D/', '', $_POST['cpf_cnpj']),
        $_POST['num_principal'],
        $_POST['num_secundario'],
        $_POST['email'],
        $_POST['situacao'],
        $id
      ];
      $stmt = sqlsrv_query($conn, $sql, $params);
      $response['success'] = $stmt ? true : false;
    } else {
      $response['error'] = 'ID não enviado';
    }
    break;
}

echo json_encode($response);
?>