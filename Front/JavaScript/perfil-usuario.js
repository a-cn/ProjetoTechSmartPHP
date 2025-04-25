document.addEventListener('DOMContentLoaded', () => {
    fetch('../../Back/dados_usuario.php')
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                alert(data.erro);
                return;
            }

            document.getElementById('nome').textContent = data.nome;
            document.getElementById('tipo').textContent = data.tipo_usuario;
            document.getElementById('cpf-cnpj').textContent = data.cpf_cnpj;
            document.getElementById('nascimento').textContent = data.data_nascimento;

            document.getElementById('email').textContent = data.email;
            document.getElementById('num-principal').textContent = data.num_principal;
            document.getElementById('num-recado').textContent = data.num_recado;

            document.getElementById('cep').textContent = data.cep;
            document.getElementById('cidade-estado').textContent = `${data.cidade} - ${data.estado}`;
            document.getElementById('logradouro').textContent = data.logradouro;
            document.getElementById('numero').textContent = data.numero;
            document.getElementById('bairro').textContent = data.bairro;
            document.getElementById('complemento').textContent = data.complemento || '-';
        });
});