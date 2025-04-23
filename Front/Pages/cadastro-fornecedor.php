<?php
require_once '../../Back/verifica_sessao.php'; //Garante que somente usuários logados possam acessar a página
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Importação do Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"> <!-- Icones do FontAwesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> <!-- Icones do Bootstrap -->
  <link rel="stylesheet" type="text/css" href="../CSS/cadastro-fornecedor.css">
  <title>TechSmart - Fornecedores</title>
</head>
<body>
  <?php include 'sidebar-header.php'; ?> <!-- Inclui o cabeçalho e a barra de navegação -->

  <div class="container">
    <h1 class="titulo-fornecedores">FORNECEDORES</h1>

    <!-- Formulário de Cadastro -->
    <div class="form-container">
      <h2>CADASTRAR FORNECEDOR</h2>
      <form id="formFornecedor">
        <label for="cnpj">CPF/CNPJ:</label>
        <input type="text" id="cnpj" name="cnpj" placeholder="000.000.000-00 ou 00.000.000/0000-00" required>

        <label for="nome">Nome/Razão Social:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>

        <label for="telefone">Telefone (com DDD):</label>
        <input type="text" id="telefone" name="telefone" placeholder="(00) 0000-0000">

        <label for="celular">Celular (com DDD):</label>
        <input type="text" id="celular" name="celular" placeholder="(00) 00000-0000">

        <label for="cep">CEP:</label>
        <input type="text" id="cep" name="cep" placeholder="00000-000" required>
        <button type="button" id="buscarCep">Buscar CEP</button>

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>

        <label for="numero">Número:</label>
        <input type="text" id="numero" name="numero" required>

        <label for="complemento">Complemento:</label>
        <input type="text" id="complemento" name="complemento">

        <label for="bairro">Bairro:</label>
        <input type="text" id="bairro" name="bairro" required>

        <label for="cidade">Cidade:</label>
        <input type="text" id="cidade" name="cidade" required>

        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required>

        <label for="situacao">Situação do Cadastro:</label>
        <select id="situacao" name="situacao">
          <option value="ATIVO">ATIVO</option>
          <option value="INATIVO">INATIVO</option>
          <option value="PENDENTE">PENDENTE</option>
        </select>

        <button type="submit" class="btn-incluir">Incluir</button>
      </form>
    </div>

    <!-- Tabela de Fornecedores Cadastrados -->
    <div class="table-container">
      <h2>FORNECEDORES CADASTRADOS</h2>
      <input type="text" id="pesquisar" placeholder="Pesquisar fornecedor...">
      <button class="btn-pesquisar" onclick="pesquisarFornecedor()">Pesquisar</button>
      <button class="btn-selecionar-todos" onclick="selecionarTodos()">Selecionar Todos</button>
      <table id="tabelaFornecedores">
        <thead>
          <tr>
            <th>Selecionar</th>
            <th>ID</th>
            <th>NOME</th>
            <th>ENDEREÇO</th>
            <th>SITUAÇÃO</th>
            <th>AÇÕES</th>
          </tr>
        </thead>
        <tbody>
          <!-- Dados serão inseridos dinamicamente via JavaScript -->
        </tbody>
      </table>
      <button class="btn-imprimir" onclick="imprimirSelecionados()">Imprimir Selecionados</button>
    </div>
  </div>
</body>
<script src="../JavaScript/cadastro-fornecedor.js"></script>
</html>