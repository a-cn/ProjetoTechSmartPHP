const urlParams = new URLSearchParams(window.location.search);
const erro = urlParams.get("erro");
const timeout = urlParams.get("timeout");

//Mensagem de erro para login inválido
if (erro === "1") {
    const divErro = document.getElementById("mensagemErro");
    if (divErro) {
        divErro.textContent = "Email ou senha inválidos!";
        divErro.style.display = "block";
        //Esconde a mensagem após 5 segundos (5000 milissegundos)
        setTimeout(() => {
            divErro.style.display = "none";
        }, 5000);
    }
//Alerta de sessão expirada
} else if (timeout === "1") {
    alert("Sua sessão expirou por inatividade. Por favor, faça login novamente.");
}