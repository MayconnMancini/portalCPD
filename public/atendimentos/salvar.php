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
require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../config/config.php');

require_once(__DIR__ . '/../../model/Atendimento.php');
require_once(__DIR__ . '/../../dao/DaoAtendimento.php');

require_once(__DIR__ . '/../../model/Usuario.php');
require_once(__DIR__ . '/../../dao/DaoUsuario.php');

require_once(__DIR__ . '/../../model/Status.php');
require_once(__DIR__ . '/../../dao/DaoStatus.php');

require_once(__DIR__ . '/../../model/PrestadorServico.php');
require_once(__DIR__ . '/../../dao/DaoPrestadorServico.php');



$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

$daoAtendimento = new DaoAtendimento($conn);
$daoUsuario = new DaoUsuario($conn);
$daoPrestadorServico = new DaoPrestadorServico($conn);
$daoStatus = new DaoStatus($conn);


$usuario = $daoUsuario->porId( $_POST['usuario'] );
$prestador = $daoPrestadorServico->porId( $_POST['prestadorServico'] );
$status = $daoStatus->porId( 1 ); // id 1 = status "ABERTO"

function inverteData($data)
{
  if (count(explode("/", $data)) > 1) {
    return implode("-", array_reverse(explode("/", $data)));
  } elseif (count(explode("-", $data)) > 1) {
    return implode("/", array_reverse(explode("-", $data)));
  }
}

$dataInicioAtendimento = inverteData($_POST['dataInicioAtendimento']);

$novoAtendimento = new Atendimento($dataInicioAtendimento, $status,$usuario, $prestador,null);

if ($daoAtendimento->inserir( $novoAtendimento) ) {
    $id = $novoAtendimento->getId();
    $_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Atendimento salvo com sucesso! </div>";
    echo('OKKKK');
    header("Location: ./editar.php?id=$id");
}
else {
    $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao salvar Atendimento!</div>";
    header('Location: ./index.php');
}
    
//header('Location: ./index.php');

?>


