<?php
session_start();
require_once(__DIR__ . '/../model/Perfil.php');
require_once(__DIR__ . '/../model/Usuario.php');
require_once(__DIR__ . '/../db/Db.php');

// Classe para persistencia de perfils 
// DAO - Data Access Object
class DaoLogar
{

  private $connection;

  public function __construct(Db $connection)
  {
    $this->connection = $connection;
  }


  public function validaLogin(string $login, string $senha)
  {
    $sql = "SELECT idusuario, nome, login, id_perfil FROM pc_usuarios 
            WHERE login = ?
            AND senha = ? ";

    $stmt = $this->connection->prepare($sql);


    if ($stmt) {
      echo ("fiz o stmt");
      $stmt->bind_param('ss', $login, $senha);
      if ($stmt->execute()) {
        echo ("fiz o execute ");
        $stmt->bind_result($idusuario, $nome, $login, $id_perfil);
        $stmt->store_result();
        if ($stmt->num_rows() == 1 && $stmt->fetch()) {

          $_SESSION['iduser'] = $idusuario;
          $_SESSION['id_perfil'] = $id_perfil;
          $_SESSION['nome'] = $nome;
          $_SESSION['login'] = $login;
          $stmt->close();

          return true;
        } else {

          $stmt->close();

          return false;
        }
      }
    }
  }
}
