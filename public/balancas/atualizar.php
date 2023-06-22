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
require_once(__DIR__ . '/../../templates/template-html.php');

require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../model/Balanca.php');
require_once(__DIR__ . '/../../dao/DaoBalanca.php');
require_once(__DIR__ . '/../../dao/DaoStatusBalanca.php');
require_once(__DIR__ . '/../../config/config.php');

$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

$daoBalanca = new DaoBalanca($conn);
$balanca = $daoBalanca->porId( $_POST['id'] );

$daoStatusBalanca = new DaoStatusBalanca($conn);
$status = $daoStatusBalanca->porId( $_POST['statusBalanca'] );


if ( $balanca ){  
  $balanca->setNumBalanca( $_POST['numBalanca'] );
  $balanca->setPip( $_POST['pip'] );
  $balanca->setSetor( $_POST['setor'] );
  $balanca->setNumSerie( $_POST['numSerie'] );
  $balanca->setLocalAtual( $_POST['localAtual'] );
  $balanca->setStatusBalanca( $status );

  if ($daoBalanca->atualizar( $balanca )) {
    $_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Balança salva com sucesso! </div>";
    header('Location: ./index.php');
  }
  else {
    $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao salvar balança!</div>";
    header('Location: ./index.php');
   
  }
}

//header('Location: ./index.php');