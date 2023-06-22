<?php
// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION)) session_start();

$perfil_necessario = 1;

// Verifica se não há a variável da sessão que identifica o usuário
if (!isset($_SESSION['iduser']) OR ($_SESSION['id_perfil'] != $perfil_necessario)
    OR (empty($_SESSION['iduser']))) {
    // Destrói a sessão por segurança
    session_destroy();
    // Redireciona o visitante de volta pro login
    header("Location: ../login.php?login=errosessao"); exit;
}

require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../model/Usuario.php');
require_once(__DIR__ . '/../../dao/DaoUsuario.php');
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../model/Perfil.php');
require_once(__DIR__ . '/../../dao/DaoPerfil.php');

require_once(__DIR__ . '/../../password_compat-master/lib/password.php');

$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

$daoPerfil = new DaoPerfil($conn);
$daoUsuario = new DaoUsuario($conn);

$perfil = $daoPerfil->porId( $_POST['perfil'] );

$login = addslashes($_POST['login']);
$senha = addslashes($_POST['senha']);

//$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

//if (password_verify($_POST['senha'], $senhaHash)) {

    $novoUsuario = new Usuario($_POST['matricula'], $_POST['nome'], $login, md5($senha), $perfil);

    if ($daoUsuario->inserir( $novoUsuario) ) {
    
        $_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Usuario salvo com sucesso! </div>";
        header('Location: ./index.php');
    }
    else {
        $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao salvar Usuario!</div>";
        header('Location: ./index.php');
    }

//} else {
//    $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao criar hash senha  Usuario!</div>";
//        header('Location: ./index.php');
//}



    
?>


