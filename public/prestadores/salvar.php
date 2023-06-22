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
require_once(__DIR__ . '/../../model/PrestadorServico.php');
require_once(__DIR__ . '/../../dao/DaoPrestadorServico.php');
require_once(__DIR__ . '/../../config/config.php');

$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

$daoPrestador = new DaoPrestadorServico($conn);

$novoPrestador = new PrestadorServico($_POST['cnpj'],$_POST['razaoSocial'], $_POST['telefone'], $_POST['email']);

if ($daoPrestador->inserir($novoPrestador) ) {

    $_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Prestador salvo com sucesso! </div>";
    header('Location: ./index.php');
}
else {
    $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao salvar prestador!</div>";
    header('Location: ./index.php');
   
}
    
?>


