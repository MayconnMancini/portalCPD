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
    header("Location: ../login.php"); exit;
}
require_once(__DIR__ . '/../../templates/template-html.php');

require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../model/Reparo.php');
require_once(__DIR__ . '/../../dao/DaoReparo.php');
require_once(__DIR__ . '/../../config/config.php');

$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

$daoReparo = new DaoReparo($conn);

// Se for confirmação, apago o registro e redireciono para o index.php
if (isset($_POST['id']) && isset($_POST['confirmacao'])) {
  $reparo = $daoReparo->porId( $_POST['id'] );
  // Apagar registros em balanca_departamento:
  $idatendimento = $reparo->getAtendimento()->getId();
  if ($daoReparo->remover( $reparo )) {
    $_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Reparo excluído com sucesso! </div>";
    //header("Location: ./editar.php?id=$idatendimento");
    header("Location: ../atendimentos/editar.php?id=$idatendimento");
  }
  else {
    $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao excluir reparo!</div>";
    header("Location: ../atendimentos/editar.php?id=$idatendimento");
   
  }
  exit;  // Termino a execucação desse script
}

?>

