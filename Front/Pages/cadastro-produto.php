<?php
require_once '../../Back/conexao_sqlserver.php'; //Chama o arquivo de conexão com o banco de dados
require_once '../../Back/verifica_sessao.php'; //Garante que somente usuários logados possam acessar a página

// Função para conectar ao banco de dados SQL Server TechSmartDB
// function conecta_database($serverName = "SQLSERVER\\SQLEXPRESS", $database = "TechSmartDB", $username = "techsmart_user", $password = "@Techsmart@") {
/*
    $connectionInfo = array( "Database"=>"TechSmartDB", "UID"=>"techsmart_user", "PWD"=>"@Techsmart@", "CharacterSet" => "UTF-8", "ReturnDatesAsStrings" => true, "MultipleActiveResultSets" => true, "Encrypt" => false, "TrustServerCertificate" => true);
    $conn = sqlsrv_connect( "SQLSERVER\\SQLEXPRESS", $connectionInfo);
    if( !$conn ) {
	    echo "Connection could not be established.<br />";
	    die( print_r( sqlsrv_errors(), false));
    }  
*/

?>   

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" type="text/css" href="../CSS/cadastro-produto.css">  <!-- Link para o arquivo CSS -->
</head>
<body>
    <div class="container">
        <div class="cadastro-produto">
            <h2>Cadastro de Produto</h2>
            <form id="cadastroForm" action="../../Back/cadastrar_produto.php" method="POST">
                <div class="form-group">
                    <label for="id">ID:</label>
                    <input type="text" id="id" name="id" readonly>
                </div>
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" id="quantidade" name="quantidade" required>
                </div>
                <div class="form-group">
                    <label for="valor_venda">Valor:</label>
                    <input type="text" id="valor_venda" name="valor_venda" step="0.01" required>
                </div>
                <input type="submit" class="btn-cadastrar" value="Salvar">
            </form>
        </div>
        
<?php
    $sql = "SELECT [produtofinal_id],[descricao],[nome],[quantidade],[valor_venda],[nivel_minimo],[nivel_maximo] FROM [dbo].[ProdutoFinal]";
    $stmt = sqlsrv_query($conn, $sql, [], ["Scrollable" => 'static']);
    if ($stmt == false){
        die( print_r( sqlsrv_errors(), false));
    }   
?>

        <div class="produtos-cadastrados">
            <h2>Produtos Cadastrados</h2>
            <div class="pesquisa">
                <input type="text" id="pesquisarProduto" placeholder="Pesquisar produto...">
                <button class="btn-pesquisar">Pesquisar</button>
            </div>
            <table id="tabelaProdutos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descricao</th>
                        <th>Quantidade</th>
                        <th>Valor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Exemplo de dados estáticos, você pode substituir por dados dinâmicos do banco de dados -->
                    <tr>
                    <?php      
  while( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
        print "<td contenteditable='true'>";
        print($row['produtofinal_id']);
        print "</td>";         
        print "<td contenteditable='true'>";
        print($row['nome']);
        print "</td>";        
        print "<td contenteditable='true'>";
        print($row['descricao']);
        print "</td>";        
        print "<td contenteditable='true'>";
        print($row['quantidade']);
        print "</td>";
        print "<td contenteditable='true'>";
        print($row['valor_venda']);
        print "</td>";
/*        print "<td>";
        print($row['nivel_minimo']);
        print "</td>";
        print "<td>";
        print($row['nivel_maximo']);
        print "</td>";*/
        print "<td><button class='btn-editar'>Editar</button></td>";
        print "</tr>";
    }; 

    
//    var_dump($row);

    sqlsrv_free_stmt($stmt);
?>

                    <!-- Dados da tabela serão preenchidos com JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
    <!--script src="../JavaScript/cadastro-produto.js"></script>  < !-- Link para o arquivo JavaScript -->
</body>
</html>