document.addEventListener('DOMContentLoaded', function () {
  carregarFornecedores();

  document.getElementById('formFornecedor').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const idHidden = document.getElementById('id_fornecedor');
    const isEditing = idHidden && idHidden.value;

    formData.append('action', isEditing ? 'alterar' : 'cadastrar');
    if (isEditing) formData.append('id', idHidden.value);

    fetch('../../Back/' + (isEditing ? 'controlador_fornecedor.php' : 'cadastro_fornecedor.php'), {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(isEditing ? 'Fornecedor alterado com sucesso!' : 'Fornecedor cadastrado com sucesso!');
        this.reset();
        document.getElementById('id_fornecedor')?.remove();
        carregarFornecedores();
      } else {
        alert(data.error || 'Erro desconhecido.');
      }
    });
  });
});

// Função chamada pelo botão Selecionar Todos
function selecionarTodos() {
  document.querySelectorAll('#tabelaFornecedores tbody .selecionar-fornecedor').forEach(cb => cb.checked = true);
}

// Função chamada pelo botão Pesquisar
function pesquisarFornecedor() {
  const termo = document.getElementById('pesquisar').value.toLowerCase();
  const linhas = document.querySelectorAll('#tabelaFornecedores tbody tr');
  linhas.forEach(linha => {
    const texto = linha.textContent.toLowerCase();
    linha.style.display = texto.includes(termo) ? '' : 'none';
  });
}

// Função chamada pelo botão Buscar CEP
document.getElementById('buscarCep')?.addEventListener('click', function () {
  const cep = document.getElementById('cep').value.replace(/\D/g, '');
  if (cep.length !== 8) {
    alert('CEP inválido!');
    return;
  }

  fetch(`https://viacep.com.br/ws/${cep}/json/`)
    .then(res => res.json())
    .then(data => {
      if (data.erro) {
        alert('CEP não encontrado!');
        return;
      }
      document.getElementById('logradouro').value = data.logradouro || '';
      document.getElementById('bairro').value = data.bairro || '';
      document.getElementById('cidade').value = data.localidade || '';
      document.getElementById('estado').value = data.uf || '';
    })
    .catch(() => alert('Erro ao buscar o CEP.'));
});

function carregarFornecedores() {
  fetch('../../Back/controlador_fornecedor.php', {
    method: 'POST',
    body: new URLSearchParams({ action: 'read' })
  })
  .then(res => res.json())
  .then(fornecedores => {
    const tbody = document.querySelector('#tabelaFornecedores tbody');
    tbody.innerHTML = '';

    fornecedores.forEach(f => {
      const enderecoCompleto = [
        f.logradouro,
        f.numero,
        f.bairro,
        f.cidade ? f.cidade + ' - ' : '',
        f.estado
      ].filter(x => x).join(', ');

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="checkbox" class="selecionar-fornecedor" data-id="${f.fornecedor_id}"></td>
        <td>${f.cpf_cnpj}</td>
        <td>${f.nome}</td>
        <td>${enderecoCompleto}</td>
        <td>${f.email}<br>${f.num_principal}${f.num_secundario ? '<br>' + f.num_secundario : ''}</td>
        <td>${f.situacao}</td>
        <td class="acoes">
          <button class="editar" onclick="editarFornecedor(${f.fornecedor_id})">Alterar</button>
          <button class="arquivar" onclick="arquivarFornecedor(${f.fornecedor_id})">Arquivar</button>
          <button class="excluir" onclick="excluirFornecedor(${f.fornecedor_id})">Excluir</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  });
}

function editarFornecedor(id) {
  fetch('../../Back/controlador_fornecedor.php', {
    method: 'POST',
    body: new URLSearchParams({ action: 'read' })
  }).then(res => res.json()).then(fornecedores => {
    const f = fornecedores.find(f => f.fornecedor_id == id);
    if (!f) return;

    document.getElementById('cpf_cnpj').value = f.cpf_cnpj || '';
    document.getElementById('nome').value = f.nome || '';
    document.getElementById('email').value = f.email || '';
    document.getElementById('telefone').value = f.num_principal || '';
    document.getElementById('celular').value = f.num_secundario || '';
    document.getElementById('cep').value = f.cep || '';
    document.getElementById('logradouro').value = f.logradouro || '';
    document.getElementById('numero').value = f.numero || '';
    document.getElementById('complemento').value = f.complemento || '';
    document.getElementById('bairro').value = f.bairro || '';
    document.getElementById('cidade').value = f.cidade || '';
    document.getElementById('estado').value = f.estado || '';
    document.getElementById('situacao').value = f.situacao || '';

    let hidden = document.getElementById('id_fornecedor');
    if (!hidden) {
      hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'id_fornecedor';
      hidden.id = 'id_fornecedor';
      document.getElementById('formFornecedor').appendChild(hidden);
    }
    hidden.value = f.fornecedor_id;
  });
}

function imprimirSelecionados() {
  const checkboxes = document.querySelectorAll('#tabelaFornecedores tbody input[type=checkbox]:checked');
  const novaJanela = window.open('', '', 'width=800,height=600');
  novaJanela.document.write('<html><head><title>Impressão</title><style>table{width:100%;border-collapse:collapse;}th,td{border:1px solid #000;padding:6px;text-align:left;}</style></head><body>');
  novaJanela.document.write('<table><thead><tr><th>CPF/CNPJ</th><th>Nome</th><th>Endereço</th><th>Contato</th><th>Situação</th></tr></thead><tbody>');
  checkboxes.forEach(cb => {
    const tds = cb.closest('tr').querySelectorAll('td');
    novaJanela.document.write('<tr>');
    for (let i = 1; i <= 5; i++) {
      novaJanela.document.write('<td>' + tds[i].innerHTML + '</td>');
    }
    novaJanela.document.write('</tr>');
  });
  novaJanela.document.write('</tbody></table></body></html>');
  novaJanela.document.close();
  novaJanela.print();
}