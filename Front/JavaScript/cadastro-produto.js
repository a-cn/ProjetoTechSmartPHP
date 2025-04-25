console.debug('Leu');
function ocultaCadastroProdutos(){
    var elEdit = document.getElementById("divCadastroProduto");
    elEdit.classList.toggle("oculta");
    var elCons = document.getElementById("divConsultaProdutos");
    elCons.classList.toggle("oculta");

}

function CancelaCadastroProdutos(){
    document.getElementById("cadastroForm").reset();
    ocultaCadastroProdutos();
}

// Adiciona eventos
// document.getElementById('cadastroForm').addEventListener('submit', cadastrarProduto);
// document.querySelector('.btn-pesquisar').addEventListener('click', pesquisarProdutos);