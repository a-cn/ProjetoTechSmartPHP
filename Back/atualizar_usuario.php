<?php
require_once 'conexao_sqlserver.php';
require_once 'verifica_sessao.php';

$usuario_id = $_SESSION['usuario_id'];

// Recebe os dados do formulário
$nome               = $_POST['nome'] ?? '';
$cpf_cnpj           = $_POST['cpf_cnpj'] ?? '';
$data_nascimento    = $_POST['data_nascimento'] ?? null;
$email              = $_POST['email'] ?? '';
$num_principal      = $_POST['num_principal'] ?? '';
$num_recado         = $_POST['num_recado'] ?? '';
$senha              = $_POST['senha'] ?? '';
$confirmSenha       = $_POST['confirmSenha'] ?? '';
$nova_pergunta      = $_POST['securityQuestion'] ?? ''; // ID da nova pergunta selecionada
$resposta           = $_POST['securityAnswer'] ?? '';

// Endereço
$cep        = $_POST['cep'] ?? '';
$estado     = $_POST['estado'] ?? '';
$cidade     = $_POST['cidade'] ?? '';
$bairro     = $_POST['bairro'] ?? '';
$logradouro = $_POST['logradouro'] ?? '';
$numero     = $_POST['numero'] ?? '';
$complemento= $_POST['complemento'] ?? '';

// Verifica se as senhas batem (caso o usuário queira alterar)
if (!empty($senha) && $senha !== $confirmSenha) {
    die("As senhas não coincidem.");
}

// Busca o fk_endereco atual e a pergunta de segurança atual
$sqlUsuario = "SELECT fk_endereco, fk_pergunta_seguranca FROM Usuario WHERE usuario_id = ?";
$stmtUser = sqlsrv_query($conn, $sqlUsuario, [$usuario_id]);
$rowUser = sqlsrv_fetch_array($stmtUser, SQLSRV_FETCH_ASSOC);

if (!$rowUser) {
    die("Usuário não encontrado.");
}
$endereco_id = $rowUser['fk_endereco'];
$pergunta_atual = $rowUser['fk_pergunta_seguranca'];

// Atualiza o endereço
$sqlUpdateEndereco = "UPDATE Endereco SET
    cep = ?, estado = ?, cidade = ?, bairro = ?, logradouro = ?, numero = ?, complemento = ?
WHERE endereco_id = ?";
$paramsEndereco = [$cep, $estado, $cidade, $bairro, $logradouro, $numero, $complemento, $endereco_id];
$stmtUpdateEnd = sqlsrv_query($conn, $sqlUpdateEndereco, $paramsEndereco);

if (!$stmtUpdateEnd) {
    die("Erro ao atualizar endereço: " . print_r(sqlsrv_errors(), true));
}

// Monta dinamicamente os campos da atualização do usuário
$setSenha = '';
$setResposta = '';
$setPergunta = '';
$paramsUsuario = [$nome, $cpf_cnpj, $data_nascimento, $email, $num_principal, $num_recado];

// Condicional para a senha
if (!empty($senha)) {
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $setSenha = ", senha = ?";
    $paramsUsuario[] = $senhaHash;
}

// Condicional para a resposta de segurança
if (!empty($resposta)) {
    $respostaHash = hash('sha256', $resposta);
    $setResposta = ", resposta_seguranca = ?";
    $paramsUsuario[] = $respostaHash;
}

// Condicional para a pergunta de segurança (se foi alterada)
if (!empty($nova_pergunta) && $nova_pergunta != $pergunta_atual) {
    $setPergunta = ", fk_pergunta_seguranca = ?";
    $paramsUsuario[] = $nova_pergunta;
}

// Finaliza parâmetros e monta a query
$paramsUsuario[] = $usuario_id;

// Query de atualização do usuário
$sqlUpdateUsuario = "UPDATE Usuario SET
    nome = ?, cpf_cnpj = ?, data_nascimento = ?, email = ?, num_principal = ?, num_recado = ?
    $setSenha
    $setResposta
    $setPergunta
WHERE usuario_id = ?";

$stmtUsuario = sqlsrv_prepare($conn, $sqlUpdateUsuario, $paramsUsuario);

if (!$stmtUsuario || !sqlsrv_execute($stmtUsuario)) {
    die("Erro ao atualizar usuário: " . print_r(sqlsrv_errors(), true));
}

// Redireciona após sucesso
echo "<script>alert('Dados alterados com sucesso!'); window.location.href = '../Front/Pages/perfil-usuario.php';</script>";
exit;
?>