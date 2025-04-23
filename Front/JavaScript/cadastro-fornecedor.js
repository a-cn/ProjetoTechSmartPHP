let fornecedores = [];
let idCounter = 1;

document.getElementById('formFornecedor').addEventListener('submit', function (e) {
  e.preventDefault();

  const nome = document.getElementById('nome').value;
  const email = document.getElementById('email').value;
  const cnpj = document.getElementById('cnpj').value;
  const telefone = document.getElementById('telefone').value;
  const celular = document.getElementById('celular').value;
  const cep = document.getElementById('cep').value;
  const endereco = document.getElementById('endereco').value;
  const numero = document.getElementById('numero').value;
  const complemento = document.getElementById('complemento').value;
  const bairro = document.getElementById('bairro').value;
  const cidade = document.getElementById('cidade').value;
  const estado = document.getElementById('estado').value;
  const situacao = document.getElementById('situacao').value;

  const fornecedor = {
    id: idCounter++,
    nome,
    email,
    cnpj,
    telefone,
    celular,
    cep,
    endereco,
    numero,
    complemento,
    bairro,
    cidade,
    estado,
    situacao,
    selecionado: false,
  };

  fornecedores.push(fornecedor);
  atualizarTabela();
  limparFormulario();
});

function atualizarTabela() {
  const tbody = document.querySelector('#tabelaFornecedores tbody');
  tbody.innerHTML = '';

  fornecedores.forEach(fornecedor => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td><input type="checkbox" ${fornecedor.selecionado ? 'checked' : ''} onchange="selecionarFornecedor(${fornecedor.id})"></td>
      <td>${fornecedor.id}</td>
      <td>${fornecedor.nome}</td>
      <td>${fornecedor.endereco}, ${fornecedor.numero}, ${fornecedor.bairro}, ${fornecedor.cidade}-${fornecedor.estado}</td>
      <td>${fornecedor.situacao}</td>
      <td class="acoes">
        <button class="editar" onclick="editarFornecedor(${fornecedor.id})">Editar</button>
        <button class="excluir" onclick="excluirFornecedor(${fornecedor.id})">Excluir</button>
      </td>
    `;
    tbody.appendChild(row);
  });
}

function limparFormulario() {
  document.getElementById('formFornecedor').reset();
}

function editarFornecedor(id) {
  const fornecedor = fornecedores.find(f => f.id === id);
  if (fornecedor) {
    document.getElementById('nome').value = fornecedor.nome;
    document.getElementById('email').value = fornecedor.email;
    document.getElementById('cnpj').value = fornecedor.cnpj;
    document.getElementById('telefone').value = fornecedor.telefone;
    document.getElementById('celular').value = fornecedor.celular;
    document.getElementById('cep').value = fornecedor.cep;
    document.getElementById('endereco').value = fornecedor.endereco;
    document.getElementById('numero').value = fornecedor.numero;
    document.getElementById('complemento').value = fornecedor.complemento;
    document.getElementById('bairro').value = fornecedor.bairro;
    document.getElementById('cidade').value = fornecedor.cidade;
    document.getElementById('estado').value = fornecedor.estado;
    document.getElementById('situacao').value = fornecedor.situacao;

    fornecedores = fornecedores.filter(f => f.id !== id);
    atualizarTabela();
  }
}

function excluirFornecedor(id) {
  fornecedores = fornecedores.filter(f => f.id !== id);
  atualizarTabela();
}

function selecionarFornecedor(id) {
  const fornecedor = fornecedores.find(f => f.id === id);
  if (fornecedor) {
    fornecedor.selecionado = !fornecedor.selecionado;
  }
}

function selecionarTodos() {
  const todosSelecionados = fornecedores.every(f => f.selecionado);
  fornecedores.forEach(f => f.selecionado = !todosSelecionados);
  atualizarTabela();
}

function pesquisarFornecedor() {
  const termo = document.getElementById('pesquisar').value.toLowerCase();
  const resultados = fornecedores.filter(fornecedor =>
    fornecedor.nome.toLowerCase().includes(termo)
  );

  if (resultados.length === 0) {
    alert("Fornecedor não localizado.");
  } else {
    atualizarTabela(resultados);
  }
}

function imprimirSelecionados() {
  const selecionados = fornecedores.filter(f => f.selecionado);
  if (selecionados.length === 0) {
    alert("Nenhum fornecedor selecionado para impressão.");
    return;
  }

  const printWindow = window.open('', '', 'width=800,height=600');
  printWindow.document.write(`
    <html>
      <head>
        <title>Imprimir Fornecedores</title>
        <style>
          table { width: 100%; border-collapse: collapse; }
          th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        </style>
      </head>
      <body>
        <h1>Fornecedores Selecionados</h1>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>NOME</th>
              <th>E-MAIL</th>
              <th>CPF/CNPJ</th>
              <th>TELEFONE</th>
              <th>CELULAR</th>
              <th>CEP</th>
              <th>ENDEREÇO</th>
              <th>NÚMERO</th>
              <th>COMPLEMENTO</th>
              <th>BAIRRO</th>
              <th>CIDADE</th>
              <th>ESTADO</th>
              <th>SITUAÇÃO</th>
            </tr>
          </thead>
          <tbody>
            ${selecionados.map(f => `
              <tr>
                <td>${f.id}</td>
                <td>${f.nome}</td>
                <td>${f.email}</td>
                <td>${f.cnpj}</td>
                <td>${f.telefone}</td>
                <td>${f.celular}</td>
                <td>${f.cep}</td>
                <td>${f.endereco}</td>
                <td>${f.numero}</td>
                <td>${f.complemento}</td>
                <td>${f.bairro}</td>
                <td>${f.cidade}</td>
                <td>${f.estado}</td>
                <td>${f.situacao}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.print();
}

document.getElementById('buscarCep').addEventListener('click', function () {
  const cep = document.getElementById('cep').value.replace(/\D/g, '');
  if (cep.length !== 8) {
    alert("CEP inválido.");
    return;
  }

  fetch(`https://viacep.com.br/ws/${cep}/json/`)
    .then(response => response.json())
    .then(data => {
      if (data.erro) {
        alert("CEP não encontrado.");
      } else {
        document.getElementById('endereco').value = data.logradouro;
        document.getElementById('bairro').value = data.bairro;
        document.getElementById('cidade').value = data.localidade;
        document.getElementById('estado').value = data.uf;
      }
    })
    .catch(() => alert("Erro ao buscar CEP."));
});

// Auto-formatação dos campos
document.getElementById('cnpj').addEventListener('input', function (e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length > 14) value = value.slice(0, 14);
  value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
  e.target.value = value;
});

document.getElementById('telefone').addEventListener('input', function (e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length > 11) value = value.slice(0, 11);
  value = value.replace(/^(\d{2})(\d{4,5})(\d{4})$/, '($1) $2-$3');
  e.target.value = value;
});

document.getElementById('celular').addEventListener('input', function (e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length > 11) value = value.slice(0, 11);
  value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
  e.target.value = value;
});

document.getElementById('cep').addEventListener('input', function (e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value.length > 8) value = value.slice(0, 8);
  value = value.replace(/^(\d{5})(\d{3})$/, '$1-$2');
  e.target.value = value;
});