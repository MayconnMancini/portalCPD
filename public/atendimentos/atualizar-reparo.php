<?php
// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION)) session_start();

$perfil_necessario = 1;

// Verifica se não há a variável da sessão que identifica o usuário
if (
  !isset($_SESSION['iduser']) or ($_SESSION['id_perfil'] != $perfil_necessario)
  or (empty($_SESSION['iduser']))
) {
  // Destrói a sessão por segurança
  session_destroy();
  // Redireciona o visitante de volta pro login
  header("Location: ../login.php");
  exit;
}
require_once(__DIR__ . '/../../templates/template-html.php');
require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../model/Reparo.php');
require_once(__DIR__ . '/../../dao/DaoReparo.php');
require_once(__DIR__ . '/../../model/Atendimento.php');
require_once(__DIR__ . '/../../dao/DaoAtendimento.php');
require_once(__DIR__ . '/../../model/Balanca.php');
require_once(__DIR__ . '/../../dao/DaoBalanca.php');
require_once(__DIR__ . '/../../model/OrdemServico.php');
require_once(__DIR__ . '/../../dao/DaoOrdemServico.php');
require_once(__DIR__ . '/../../dao/DaoStatus.php');
require_once(__DIR__ . '/../../config/config.php');

$conn = Db::getInstance();

if (!$conn->connect()) {
  die();
}

$daoReparo = new DaoReparo($conn);
$reparo = $daoReparo->porId($_POST['idreparo']);

$daoAtendimento = new DaoAtendimento($conn);
$atendimento = $daoAtendimento->porId($_POST['idatendimento']);

$daoBalanca = new DaoBalanca($conn);
$balanca = $daoBalanca->porId($_POST['idBalanca']);

$daoOs = new DaoOrdemServico($conn);
$os = $daoOs->porId(1);

$daoStatus = new DaoStatus($conn);
$status = $daoStatus->porId($_POST['status']);

function inverteData($data)
{
  if (count(explode("/", $data)) > 1) {
    return implode("-", array_reverse(explode("/", $data)));
  } elseif (count(explode("-", $data)) > 1) {
    return implode("/", array_reverse(explode("-", $data)));
  }
}

if ($reparo) {

  $id = $atendimento->getId();
  $dataInicioReparo = inverteData($_POST['dataInicioReparo']);

  $dataInicioAtendimento = $atendimento->getDataInicioAtendimento();

  if ($dataInicioReparo < $dataInicioAtendimento) {
    $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show col-md-12' role='alert'>
    <strong>" . 'ERRO!!' . "</strong> Data do inicio do reparo menor que a data de inicio do atendimento!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span> </button>
    </div>";
    header("Location: ./editar.php?id=$id");
    exit;
  }

  if (isset($_POST['dataFimReparo'])) {
    $dataFimReparo = inverteData($_POST['dataFimReparo']);
  } else {
    $dataFimReparo = null;
  }

  if ($status->getId() == 3 && $dataFimReparo == null) {
    $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show col-md-12' role='alert'>
    <strong>" . 'ERRO!!' . "</strong> para finalizar um reparo é preciso informar a data do fim do reparo!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span> </button>
    </div>";
    header("Location: ./editar.php?id=$id");
    exit;
  }

  

  if ($dataInicioReparo > $dataFimReparo && $dataFimReparo != null) {
    //$_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Data do inicio do atentimento maior que a data do fim do atendimento! </div>";
    $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show col-md-12' role='alert'>
    <strong>" . 'ERRO!!' . "</strong> Data do inicio do reparo maior que a data do fim do reparo!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span> </button>
    </div>";
    header("Location: ./editar.php?id=$id");
    exit;
  }


  $reparo->setDescricaoDefeito($_POST['descDefeito']);
  $reparo->setDataInicioReparo($dataInicioReparo);
  $reparo->setDataFimReparo($dataFimReparo);
  $reparo->setObservacao($_POST['observacao']);
  $reparo->setAtendimento($atendimento);
  $reparo->setBalanca($balanca);
  $reparo->setOrdemServico($os);
  $reparo->setStatus($status);


  if ($daoReparo->atualizar($reparo)) {
    $_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Reparo atualizado com sucesso! </div>";
    header("Location: ./editar.php?id=$id");
  } else {
    $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao atualizar Reparo!</div>";
    header("Location: ./editar.php?id=$id");
  }
}

//header('Location: ./index.php');