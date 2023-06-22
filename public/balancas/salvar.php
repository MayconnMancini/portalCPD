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
require_once(__DIR__ . '/../../model/Balanca.php');
require_once(__DIR__ . '/../../dao/DaoBalanca.php');
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../model/StatusBalanca.php');
require_once(__DIR__ . '/../../dao/DaoStatusBalanca.php');

$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

$daoStatusBalanca = new DaoStatusBalanca($conn);
$daoBalanca = new DaoBalanca($conn);

echo ($_POST['statusBalanca']);

$status = $daoStatusBalanca->porId( $_POST['statusBalanca'] );

$novaBalanca = new Balanca($_POST['numBalanca'], $_POST['pip'], $_POST['setor'], $_POST['numSerie'], $_POST['localAtual'], $status);

if ($daoBalanca->inserir( $novaBalanca) ) {
    //$daoProduto->sincronizarDepartamentos($novoProduto, $_POST['departamentos']);
   
    $_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Balança salva com sucesso! </div>";
    header('Location: ./index.php');

   
}
else {
    $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao salvar balança!</div>";
    header('Location: ./index.php');
   
}
    


?>


