<?php
//CÓDIGO PARA VERIFICAR SE EXISTE SESSÃO ATIVA (SE ESTÁ LOGADO)

session_start(); //Inicia ou continua a sessão existente, permitindo suporte a sessões no PHP para poder ler ou gravar dados na $_SESSION

//Tempo máximo de inatividade (em segundos)
$tempoInatividade = 30 * 60; //30 minutos

//Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    //Se não houver usuário logado, redireciona para a tela de login
    header("Location: ../../Front/Pages/index.html");
    exit;
}

//Verifica o tempo de inatividade
if (isset($_SESSION['ultimo_acesso'])) {
    $tempoDesdeUltimoAcesso = time() - $_SESSION['ultimo_acesso'];
    if ($tempoDesdeUltimoAcesso > $tempoInatividade) {
        //Encerra a sessão e redireciona
        session_unset();
        session_destroy();
        header("Location: ../../Front/Pages/index.html?timeout=1");
        exit;
    }
}

//Atualiza o tempo do último acesso
$_SESSION['ultimo_acesso'] = time();
?>