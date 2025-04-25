// Array para armazenar os produtos cadastrados
let produtos = [];
let idAutoIncremento = 1; // Para gerar ID único para cada produto

// Função para adicionar um novo produto ao array e atualizar a tabela
function cadastrarProduto(event) {
    event.preventDefault(); // Impede o envio do formulário e recarregamento da página
    
    // Coletando valores do formulário
    const tipo = document.getElementById('tipo').value.trim();
    const descricao = document.getElementById('descricao').value.trim();
    const valor = parseFloat(document.getElementById('valor').value);
    const quantidade = parseInt(document.getElementById('quantidade').value);
    const modelo = document.getElementById('modelo').value.trim();
    const unidadeMedida = document.getElementById('unidadeMedida') ? document.getElementById('unidadeMedida').value : '';
    const codigoBarras = document.getElementById('codigoBarras') ? document.getElementById('codigoBarras').value.trim() : '';
    const categoria = document.getElementById('categoria') ? document.getElementById('categoria').value : '';
    const marca = document.getElementById('marca') ? document.getElementById('marca').value.trim() : '';
    const fornecedor = document.getElementById('fornecedor') ? document.getElementById('fornecedor').value.trim() : '';
    const situacao = document.getElementById('situacao') ? document.getElementById('situacao').value : 'Ativo';

    // Validações básicas (ajuste conforme necessário)
    if (!tipo || !modelo || isNaN(valor) || isNaN(quantidade)) {
        alert('Por favor, preencha os campos Tipo, Modelo, Valor e Quantidade corretamente.');
        return;
    }

    // Criando objeto do produto
    const produto = {
        id: idAutoIncremento++,
        tipo,
        descricao,
        valor: valor.toFixed(2),
        quantidade,
        modelo,
        unidadeMedida,
        codigoBarras,
        categoria,
        marca,
        fornecedor,
        situacao
    };

    // Adiciona produto ao array
    produtos.push(produto);

    // Atualiza a tabela
    atualizarTabela(produtos);

    // Limpa o formulário
    document.getElementById('cadastroForm').reset();
}

// Função para atualizar a tabela com os produtos fornecidos
function atualizarTabela(listaProdutos) {
    const tbody = document.querySelector('#tabelaProdutos tbody');
    tbody.innerHTML = ''; // Limpa o conteúdo da tabela

    listaProdutos.forEach((prod) => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${prod.id}</td>
            <td>${prod.modelo}</td>
            <td>${prod.quantidade}</td>
            <td>${prod.situacao}</td>
            <td>${prod.tipo}</td>
            <td>R$ ${prod.valor}</td>
            <td>
                <button onclick="editarProduto(${prod.id})">Editar</button>
                <button onclick="excluirProduto(${prod.id})">Excluir</button>
            </td>
        `;

        tbody.appendChild(tr);
    });
}

// Função para pesquisar produtos pela descrição/modelo
function pesquisarProdutos() {
    const termo = document.getElementById('pesquisarProduto').value.trim().toLowerCase();
    const produtosFiltrados = produtos.filter(prod => 
        prod.modelo.toLowerCase().includes(termo) ||
        prod.tipo.toLowerCase().includes(termo) ||
        prod.descricao.toLowerCase().includes(termo)
    );

    atualizarTabela(produtosFiltrados);
}

// Função para excluir produto - confirma e remove do array
function excluirProduto(id) {
    if (confirm('Deseja realmente excluir este produto?')) {
        produtos = produtos.filter(prod => prod.id !== id);
        atualizarTabela(produtos);
    }
}

// Função para editar produto - preenche o formulário com os dados
function editarProduto(id) {
    const prod = produtos.find(p => p.id === id);
    if (!prod) return;

    // Preenche o formulário com os dados do produto
    document.getElementById('tipo').value = prod.tipo;
    document.getElementById('descricao').value = prod.descricao;
    document.getElementById('valor').value = prod.valor;
    document.getElementById('quantidade').value = prod.quantidade;
    document.getElementById('modelo').value = prod.modelo;

    if (document.getElementById('unidadeMedida')) document.getElementById('unidadeMedida').value = prod.unidadeMedida;
    if (document.getElementById('codigoBarras')) document.getElementById('codigoBarras').value = prod.codigoBarras;
    if (document.getElementById('categoria')) document.getElementById('categoria').value = prod.categoria;
    if (document.getElementById('marca')) document.getElementById('marca').value = prod.marca;
    if (document.getElementById('fornecedor')) document.getElementById('fornecedor').value = prod.fornecedor;
    if (document.getElementById('situacao')) document.getElementById('situacao').value = prod.situacao;

    // Remove o produto do array temporariamente para sobrescrever quando cadastrar
    produtos = produtos.filter(p => p.id !== id);
    atualizarTabela(produtos);
}

// Adiciona eventos
document.getElementById('cadastroForm').addEventListener('submit', cadastrarProduto);
document.querySelector('.btn-pesquisar').addEventListener('click', pesquisarProdutos);