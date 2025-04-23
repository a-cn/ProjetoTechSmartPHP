<?php
require_once '../../Back/verifica_sessao.php'; //Garante que somente usuários logados possam acessar a página
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Importação do Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"> <!-- Icones do FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> <!-- Icones do Bootstrap -->
    <link rel="stylesheet" type="text/css" href="../CSS/cadastro-componente.css">
    <title>Tela de Cadastro de Componentes</title>
</head>
<body>
    <?php include 'sidebar-header.php'; ?> <!-- Inclui o cabeçalho e a barra de navegação -->
    <div class="container">
        <h1>CADASTRO DE COMPONENTES</h1>
        <div class="form-container">
            <form>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome">
                
                <label for="descricao">Descrição:</label>
                <input type="text" id="descricao" name="descricao">
                
               <!-- <label for="nivel-max">Nível Máx:</label> ***nivel máximo suprimido pois limita o crescimento***
                <input type="text" id="nivel-max" name="nivel-max">*/ -->
                
                <label for="nivel-min">Nível Mínimo:</label>
                <input type="text" id="nivel-min" name="nivel-min">
                
                <label for="fornecedor">Fornecedor:</label>
                <input type="text" id="fornecedor" name="fornecedor">
                
                <button type="submit">CADASTRAR</button>
            </form>
        </div>
        <div class="table-container">
            <h2> ÚLTIMOS COMPONENTES CADASTRADOS</h2> <!-- somente a título de exemplo, deverá ser substituido por planilha do DB -->
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Situação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> </td>
                        <td> </td>
                        <td>
                            <button>EDITAR</button>
                            <button>EXCLUIR</button>
                        </td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td> </td>
                        <td>
                            <button>EDITAR</button>
                            <button>EXCLUIR</button>
                        </td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td> </td>
                        <td>
                            <button>EDITAR</button>
                            <button>EXCLUIR</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button>OLHAR TODOS COMPONENTES</button>
        </div>
    </div>
</body>
<script src="../JavaScript/cadastro-componente.js"></script>
</html>