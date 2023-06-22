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
require_once(__DIR__ . '/../../model/Usuario.php');
require_once(__DIR__ . '/../../dao/DaoUsuario.php');
require_once(__DIR__ . '/../../dao/DaoPerfil.php');
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../password_compat-master/lib/password.php');

$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

$daoUsuario = new DaoUsuario($conn);
$usuario = $daoUsuario->porId( $_POST['id'] );

$daoPerfil = new DaoPerfil($conn);
$perfil = $daoPerfil->porId( $_POST['perfil'] );


if ( $usuario )
{  
  $usuario->setMatricula( $_POST['matricula'] );
  $usuario->setNome( $_POST['nome'] );
  $usuario->setLogin( $_POST['login'] );

  if( (isset($_POST['senha']) && ($_POST['senha']) != null) ) {

    $senha = addslashes($_POST['senha']);

    $usuario->setSenha( md5($senha) );
  }

  $usuario->setPerfil( $perfil );

  if ($daoUsuario->atualizar( $usuario ) ) {

    $_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Usuario atualizado com sucesso! </div>";
    header('Location: ./index.php');
  }
  else {
      $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao atualizar Usuario!</div>";
      header('Location: ./index.php');
  }
}
