<?php
require_once '../../Back/verifica_sessao.php'; //Garante que somente usuários logados possam acessar a página
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Importação do Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"> <!-- Icones do FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> <!-- Icones do Bootstrap -->
    <link rel="stylesheet" type="text/css" href="../CSS/cadastro-produto.css">
    <title>Tela de Cadastro de Produtos - TechSmart</title>
</head>

<body>
    <?php include 'sidebar-header.php'; ?> <!-- Inclui o cabeçalho e a barra de navegação -->

    <div class="container">
        <h1>Tela de Cadastro de Produtos</h1>
        <div class="form-container">
            <div class="form-section">
                <h2>Cadastro de Produto</h2>
                <div class="form-item">
                    <label for="tipo">Tipo:</label>
                    <input type="text" id="tipo" name="tipo" placeholder="Digite o tipo do produto">
                </div>
                
                <div class="form-item">
                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Digite a descrição do produto">
                </div>
                
                <div class="form-item">
                    <label for="valor">Valor:</label>
                    <input type="number" id="valor" name="valor" placeholder="Digite o valor do produto">
                </div>
                
                <div class="form-item">
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" id="quantidade" name="quantidade" placeholder="Digite a quantidade">
                </div>
                
                <div class="form-item">
                    <label for="modelo">Modelo:</label>
                    <input type="text" id="modelo" name="modelo" placeholder="Digite o modelo">
                </div>

                <button id="btnCadastrar">Cadastrar</button>
            </div>

            <div class="list-section">
                <h2>Tela de Consulta de Produtos Cadastrados</h2>
                <table id="produtosTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Modelo</th>
                            <th>Quantidade</th>
                            <th>Situação</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Produtos serão inseridos aqui -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script src="../JavaScript/cadastro-produto.js"></script>
</html>