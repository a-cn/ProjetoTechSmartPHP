<?php
require_once '../../Back/conexao_sqlserver.php'; //Inclui o arquivo de conexão com o banco de dados
require_once '../../Back/verifica_sessao.php'; //Garante que somente usuários logados possam acessar a página

// Inicia a sessão --> Já ocorre em "verifica_sessao.php"
// session_start();

// Inicializa variáveis
$mensagem = '';
$mensagem_tipo = '';
$fornecedores = [];

// Verifica mensagens da sessão
if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    $mensagem_tipo = $_SESSION['mensagem_tipo'];
    unset($_SESSION['mensagem']);
    unset($_SESSION['mensagem_tipo']);
}

// Busca os fornecedores ativos do banco de dados
try {
    // Verifica se a tabela Fornecedor existe
    $check_table = $conn->query("
        SELECT COUNT(*) AS table_exists 
        FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_NAME = 'Fornecedor'
    ");
    
    if ($check_table === false) {
        throw new Exception("Erro ao verificar a existência da tabela Fornecedor");
    }
    
    $table_exists = $check_table->fetch(PDO::FETCH_ASSOC)['table_exists'];
    
    if (!$table_exists) {
        throw new Exception("A tabela de fornecedores não está disponível no banco de dados.");
    }

    // Busca fornecedores ativos
    $query_fornecedores = "SELECT fornecedor_id, nome FROM Fornecedor WHERE ativo = 1 ORDER BY nome";
    $stmt_fornecedores = $conn->query($query_fornecedores);
    
    if ($stmt_fornecedores === false) {
        throw new Exception("Erro ao buscar fornecedores");
    }
    
    $fornecedores = $stmt_fornecedores->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($fornecedores)) {
        $mensagem = "Nenhum fornecedor cadastrado no sistema.";
        $mensagem_tipo = "aviso";
    }
} catch (PDOException $e) {
    $mensagem = "Erro ao acessar o banco de dados: " . $e->getMessage();
    $mensagem_tipo = "erro";
    $fornecedores = [];
} catch (Exception $e) {
    $mensagem = $e->getMessage();
    $mensagem_tipo = "erro";
    $fornecedores = [];
}

// Processa o formulário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['incluir'])) {
    $transactionStarted = false;
    try {
        // Validação dos campos obrigatórios
        $required = ['nome', 'nivel-min', 'nivel-max', 'fornecedor_id'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("O campo " . ucfirst(str_replace('-', ' ', $field)) . " é obrigatório!");
            }
        }

        // Sanitização dos dados
        $nome = htmlspecialchars($_POST['nome']);
        $descricao = htmlspecialchars($_POST['descricao'] ?? '');
        $nivel_min = intval($_POST['nivel-min']);
        $nivel_max = intval($_POST['nivel-max']);
        $fornecedor_id = intval($_POST['fornecedor_id']);

        // Validação adicional
        if ($nivel_max <= $nivel_min) {
            throw new Exception("O Nível Máximo deve ser maior que o Nível Mínimo!");
        }

        // Verifica se o fornecedor existe e está ativo
        $fornecedor_valido = false;
        foreach ($fornecedores as $fornecedor) {
            if ($fornecedor['fornecedor_id'] == $fornecedor_id) {
                $fornecedor_valido = true;
                break;
            }
        }
        
        if (!$fornecedor_valido) {
            throw new Exception("Fornecedor selecionado não é válido!");
        }

        // Inicia a transação
        $conn->beginTransaction();
        $transactionStarted = true;
        
        // Insere o componente
        $sql_componente = "INSERT INTO Componente (nome, especificacao, quantidade, nivel_minimo, nivel_maximo, ativo) 
                          VALUES (?, ?, 0, ?, ?, 1)";
        $stmt = $conn->prepare($sql_componente);
        $stmt->execute([$nome, $descricao, $nivel_min, $nivel_max]);
        
        $componente_id = $conn->lastInsertId();
        
        // Inserção do relacionamento com fornecedor
        $sql_relacionamento = "INSERT INTO Fornecedor_Componente (fk_fornecedor, fk_componente) 
                             VALUES (?, ?)";
        $stmt_rel = $conn->prepare($sql_relacionamento);
        $stmt_rel->execute([$fornecedor_id, $componente_id]);
        
        $conn->commit();
        
        $_SESSION['mensagem'] = "Componente cadastrado com sucesso!";
        $_SESSION['mensagem_tipo'] = "sucesso";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
        
    } catch (Exception $e) {
        if ($transactionStarted && $conn->inTransaction()) {
            $conn->rollBack();
        }
        $mensagem = $e->getMessage();
        $mensagem_tipo = "erro";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Componentes</title>
    <link rel="stylesheet" type="text/css" href="../CSS/cadastro-componente.css">
</head>
<body>
    <?php include 'sidebar-header.php'; ?> <!-- Inclui o cabeçalho e a barra de navegação -->
    <div class="container">
        <h1>CADASTRO DE COMPONENTES</h1>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?= $mensagem_tipo ?>"><?= $mensagem ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" value="<?= isset($_POST['descricao']) ? htmlspecialchars($_POST['descricao']) : '' ?>">
                </div>
                
                <div class="nivel-group">
                    <div class="nivel-item">
                        <label for="nivel-min">Nível Mínimo:</label>
                        <input type="number" id="nivel-min" name="nivel-min" min="0" value="<?= isset($_POST['nivel-min']) ? htmlspecialchars($_POST['nivel-min']) : '' ?>" required>
                    </div>
                    
                    <div class="nivel-item">
                        <label for="nivel-max">Nível Máximo:</label>
                        <input type="number" id="nivel-max" name="nivel-max" min="0" value="<?= isset($_POST['nivel-max']) ? htmlspecialchars($_POST['nivel-max']) : '' ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="fornecedor_id">Fornecedor:</label>
                    <select id="fornecedor_id" name="fornecedor_id" required>
                        <option value="">Selecione um fornecedor</option>
                        <?php if (!empty($fornecedores)): ?>
                            <?php foreach ($fornecedores as $fornecedor): ?>
                                <option value="<?= $fornecedor['fornecedor_id'] ?>"
                                    <?= (isset($_POST['fornecedor_id']) && $_POST['fornecedor_id'] == $fornecedor['fornecedor_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($fornecedor['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Nenhum fornecedor disponível</option>
                        <?php endif; ?>
                    </select>
                    <?php if (empty($fornecedores)): ?>
                        <p class="error-message">Não foi possível carregar a lista de fornecedores.</p>
                    <?php endif; ?>
                </div>

                <div class="button-group">
                    <button type="submit" name="incluir" class="btn-primary">INCLUIR</button>
                    
                    <div class="action-buttons">
                        <button type="button" onclick="window.location.href='localizar.php'" class="btn-secondary">LOCALIZAR</button>
                        <button type="button" onclick="mostrarMensagemImpressao()" class="btn-secondary">RELATÓRIO</button>
						<script>
							function mostrarMensagemImpressao() {
								// Cria a mensagem se não existir
								let mensagem = document.getElementById('mensagem-impressao');
    
								if (!mensagem) {
                                    mensagem = document.createElement('div');
                                    mensagem.id = 'mensagem-impressao';
                                    mensagem.className = 'mensagem aviso';
                                    mensagem.textContent = 'Função de Impressão de Relatórios não habilitada no momento.';
                                    document.querySelector('.container').appendChild(mensagem);
                                }   
								mensagem.style.display = 'block';
    							setTimeout(() => {
								mensagem.style.display = 'none';
								}, 10000);
                            }
                        </script>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>