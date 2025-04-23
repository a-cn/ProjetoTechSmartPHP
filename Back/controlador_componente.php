<?php
include 'conexao_sqlserver.php';

header('Content-Type: application/json');

$acao = $_GET['acao'] ?? '';

try {
    switch ($acao) {
        case 'listar':
            $sql = "SELECT componente_id as id, nome 
                    FROM Componente 
                    WHERE ativo = 1 
                    ORDER BY nome";
            
            $stmt = sqlsrv_query($conn, $sql);
            if (!$stmt) {
                throw new Exception("Erro ao listar componentes");
            }
            
            $componentes = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $componentes[] = $row;
            }
            
            echo json_encode($componentes);
            break;
            
        default:
            throw new Exception("Ação não reconhecida");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}