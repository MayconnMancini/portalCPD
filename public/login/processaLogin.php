<?php
session_start();
require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../dao/DaoLogar.php');


$conn = Db::getInstance();

if (!$conn->connect()) {
    echo ("Erro ao conectar ao Banco de Dados");
    //die();
}

// Verifica se houve POST e se o usuário ou a senha é(são) vazio(s)
if (!empty($_POST) and (empty($_POST['login']) or empty($_POST['senha']))) {
    header("Location: login.php?login=vazio");
    exit;
}

$login = addslashes($_POST['login']);
$senha = addslashes($_POST['senha']);

$daoLogar = new DaoLogar($conn);

if ($daoLogar->validaLogin($login, md5($senha)) == true) {
    if (isset($_SESSION['iduser'])) {
        //$_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Usuario Autenticado com sucesso! </div>";
        header('Location: ../index.php?login=ok');
    } else {
        //$_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Não criou sessão!</div>";
        header('Location: ../login.php?login=errosessao');
    }
} else {
    //$_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Usuário ou senha inválidos!</div>";
    header('Location: ../login.php?login=erro');
}
?>