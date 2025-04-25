<?php
require_once '../../Back/conexao_sqlserver.php';
require_once '../../Back/verifica_sessao.php';

$usuario_id = $_SESSION['usuario_id'];

// Busca as perguntas de segurança
$perguntas = [];
$sqlPerguntas = "SELECT pergunta_seguranca_id, pergunta FROM Pergunta_Seguranca";
$result = sqlsrv_query($conn, $sqlPerguntas);

if ($result) {
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $perguntas[] = $row;
    }
}

// Busca os dados do usuário e do endereço
$sqlUsuario = "SELECT u.*, e.* FROM Usuario u
              JOIN Endereco e ON u.fk_endereco = e.endereco_id
              WHERE u.usuario_id = ?";
$stmt = sqlsrv_query($conn, $sqlUsuario, [$usuario_id]);
$usuario = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Meus Dados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../CSS/cadastro-usuario.css">
</head>
<body>
<?php include 'sidebar-header.php'; ?>
<div class="header">
    <h1>Atualizar Meus Dados</h1>
</div>
<div class="form-container">
    <form class="form-content" action="../../Back/atualizar_usuario.php" method="POST">
        <input type="hidden" name="usuario_id" value="<?= $usuario['usuario_id'] ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= e($usuario['nome']) ?>" maxlength="100" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="cpf_cnpj">CPF/CNPJ:</label>
                <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="<?= e($usuario['cpf_cnpj']) ?>" maxlength="14" required>
            </div>
            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?= e($usuario['data_nascimento']->format('Y-m-d')) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= e($usuario['email']) ?>" maxlength="50" required>
            </div>
            <div class="form-group">
                <label for="num_principal">Telefone Principal:</label>
                <input type="text" id="num_principal" name="num_principal" value="<?= e($usuario['num_principal']) ?>" maxlength="15" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="num_recado">Telefone Recado:</label>
                <input type="text" id="num_recado" name="num_recado" value="<?= e($usuario['num_recado']) ?>" maxlength="15">
            </div>
        </div>

        <hr>
        <h4>Endereço</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" id="cep" name="cep" value="<?= e($usuario['cep']) ?>" maxlength="8" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" id="estado" name="estado" value="<?= e($usuario['estado']) ?>" maxlength="50" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="cidade">Cidade:</label>
                <input type="text" id="cidade" name="cidade" value="<?= e($usuario['cidade']) ?>" maxlength="50" required>
            </div>
            <div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" id="bairro" name="bairro" value="<?= e($usuario['bairro']) ?>" maxlength="50" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="logradouro">Logradouro:</label>
                <input type="text" id="logradouro" name="logradouro" value="<?= e($usuario['logradouro']) ?>" maxlength="150" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero" value="<?= e($usuario['numero']) ?>" required>
            </div>
            <div class="form-group">
                <label for="complemento">Complemento:</label>
                <input type="text" id="complemento" name="complemento" value="<?= e($usuario['complemento']) ?>" maxlength="100">
            </div>
        </div>

        <hr>
        <h4>Segurança</h4>
        <div class="form-row">
            <div class="form-group">
                <label for="senha">Nova Senha:</label>
                <input type="password" id="senha" name="senha" maxlength="15" placeholder="Alteração opcional">
            </div>
            <div class="form-group">
                <label for="confirmSenha">Confirmar Senha:</label>
                <input type="password" id="confirmSenha" name="confirmSenha" maxlength="15" placeholder="Alteração opcional">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="securityQuestion">Pergunta de Segurança:</label>
                <select id="securityQuestion" name="securityQuestion" class="form-control">
                    <option value="">Selecione uma pergunta</option>
                    <?php foreach ($perguntas as $p): ?>
                        <option value="<?= $p['pergunta_seguranca_id'] ?>" <?= $p['pergunta_seguranca_id'] == $usuario['fk_pergunta_seguranca'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['pergunta']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="securityAnswer">Resposta de Segurança:</label>
                <input type="text" id="securityAnswer" name="securityAnswer" maxlength="100" placeholder="Alteração opcional">
            </div>
        </div>

        <div class="form-footer">
            <button type="submit">Salvar Alterações</button>
        </div>
    </form>
</div>
</body>
</html>