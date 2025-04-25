<?php
include 'conexao_sqlserver.php'; //Chama o arquivo de conexão com o banco de dados
require_once 'verifica_sessao.php'; //Colocado em todos os arquivos de processamento e recebimento de dados, exceto arquivos públicos ou em que a sessão não é necessária

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id=$_POST["id"];
    $nome=$_POST["nome"]; 
    $descricao=$_POST["descricao"]; 
    $quantidade=$_POST["quantidade"];
    $valor_venda=$_POST["valor_venda"];
    //var_dump($_POST,$id);

    $sql="INSERT INTO [dbo].[ProdutoFinal]
            ([fk_producao]
            ,[nome]
            ,[descricao]
            ,[valor_venda]
            ,[quantidade]
            ,[nivel_minimo]
            ,[nivel_maximo])
        VALUES
            (1
            ,'$nome'
            ,'$descricao'
            ,$valor_venda
            ,$quantidade
            ,0
            ,0)";
    //var_dump($sql);
    /*
    $connectionInfo = array( "Database"=>"TechSmartDB", "UID"=>"techsmart_user", "PWD"=>"Teste123!", "CharacterSet" => "UTF-8", "ReturnDatesAsStrings" => true, "MultipleActiveResultSets" => true, "Encrypt" => false, "TrustServerCertificate" => true);
    $conn = sqlsrv_connect( "SQLSERVER\\SQLEXPRESS", $connectionInfo);
    if( !$conn ) {
	    echo "Connection could not be established.<br />";
	    die( print_r( sqlsrv_errors(), false));
    }  */

    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt == false){
        die( print_r( sqlsrv_errors(), false));
    } else {
        echo "<script>alert('Produto cadastrado com sucesso!');</script>";
        header("Location: index.php"); // Redireciona para a página inicial após o cadastro
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);  
}