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
require_once(__DIR__ . '/../../model/Atendimento.php');
require_once(__DIR__ . '/../../dao/DaoAtendimento.php');
require_once(__DIR__ . '/../../model/PrestadorServico.php');
require_once(__DIR__ . '/../../dao/DaoPrestadorServico.php');
require_once(__DIR__ . '/../../model/Usuario.php');
require_once(__DIR__ . '/../../dao/DaoUsuario.php');
require_once(__DIR__ . '/../../dao/DaoStatus.php');
require_once(__DIR__ . '/../../model/Status.php');
require_once(__DIR__ . '/../../model/Reparo.php');
require_once(__DIR__ . '/../../dao/DaoReparo.php');
require_once(__DIR__ . '/../../config/config.php');

$conn = Db::getInstance();

if (!$conn->connect()) {
  die();
}

$daoAtendimento = new DaoAtendimento($conn);
$atendimento = $daoAtendimento->porId($_POST['id']);

$daoPrestador = new DaoPrestadorServico($conn);
$prestador = $daoPrestador->porId($_POST['prestadorServico']);

$daoUsuario = new DaoUsuario($conn);
$usuario = $daoUsuario->porId($_POST['usuario']);

$daoStatus = new DaoStatus($conn);
$status = $daoStatus->porId($_POST['status']);

$daoReparo = new DaoReparo($conn);
$reparos = $daoReparo->reparosPorAtendimento($atendimento->getId());

function inverteData($data)
{
  if (count(explode("/", $data)) > 1) {
    return implode("-", array_reverse(explode("/", $data)));
  } elseif (count(explode("-", $data)) > 1) {
    return implode("/", array_reverse(explode("-", $data)));
  }
}

if ($atendimento) {
  $id = $atendimento->getId();

  if (isset($_POST['dataFimAtendimento'])) {
    $dataFimAtendimento = inverteData($_POST['dataFimAtendimento']);
  } else {
    $dataFimAtendimento = null;
  }

  if ($status->getId() == 3 && $dataFimAtendimento == null) {
    $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show col-md-12' role='alert'>
    <strong>". 'ERRO!!'."</strong> para finalizar um atendimento é preciso informar a data do fim do atendimento!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span> </button>
    </div>";
    header("Location: ./editar.php?id=$id");
    exit;  
  }

  $dataInicioAtendimento = inverteData($_POST['dataInicioAtendimento']);

  if ($dataInicioAtendimento > $dataFimAtendimento && $dataFimAtendimento != null) {
    //$_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Data do inicio do atentimento maior que a data do fim do atendimento! </div>";
    $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show col-md-12' role='alert'>
    <strong>". 'ERRO!!'."</strong> Data do inicio do atentimento maior que a data do fim do atendimento!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span> </button>
    </div>";
    header("Location: ./editar.php?id=$id");
    exit;
  }

  if (count($reparos) > 0 && $status->getId() == 3) {
    foreach ($reparos as $r) {
      if ($r->getStatus()->getId() != 3) {
        //$_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> ERRO ao finalizar atendimento! Ainda existem reparos pendentes !!! </div>";
        $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show col-md-12' role='alert'>
                            <strong>". 'ERRO ao finalizar atendimento!'."</strong> Ainda existem reparos pendentes!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span> </button>
                            </div>";
        header("Location: ./editar.php?id=$id");
        exit;
      }
    }
  } elseif ($status->getId() == 3) {
    //$_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'><strong> ERRO ao finalizar atendimento!!</strong> Não existe nenhum reparo associado ao atendimento !!! </div>";
    $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show col-md-12' role='alert'>
    <strong>". 'ERRO ao finalizar atendimento!!'."</strong> Não existe nenhum reparo associado ao atendimento!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span> </button>
    </div>";
    header("Location: ./editar.php?id=$id");
    exit;
  }

  $atendimento->setDataInicioAtendimento($dataInicioAtendimento);
  $atendimento->setDataFimAtendimento($dataFimAtendimento);
  $atendimento->setPrestadorServico($prestador);
  $atendimento->setUsuario($usuario);
  $atendimento->setStatus($status);


  if ($daoAtendimento->atualizar($atendimento)) {

    //$_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Atendimento atualizado com sucesso! </div>";
    $_SESSION['msg'] = "<div class='alert alert-success alert-dismissible fade show col-md-12' role='alert'>
    Atendimento atualizado com sucesso!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span> </button>
    </div>";
    header("Location: ./editar.php?id=$id");
  } else {
    $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao atualizar atendimento!</div>";
    $_SESSION['msg'] = "<div class='alert alert-danger alert-dismissible fade show col-md-12' role='alert'>
    Erro ao atualizar atendimento!
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span> </button>
    </div>";
    header("Location: ./editar.php?id=$id");
  }
}
?>

//header('Location: ./index.php');