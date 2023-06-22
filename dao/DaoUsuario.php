<?php 
require_once(__DIR__ . '/../model/Usuario.php');
require_once(__DIR__ . '/../model/Perfil.php');
require_once(__DIR__ . '/../db/Db.php');

// Classe para persistencia de usuarios 
// DAO - Data Access Object
class DaoUsuario {
    
  private $connection;

  public function __construct(Db $connection) {
      $this->connection = $connection;
  }
  
  public function porId(int $id): Usuario {
    $sql = "SELECT usr.matricula, usr.nome, usr.login,
                   usr.senha, usr.id_perfil, pf.descricao
            FROM pc_usuarios usr
            LEFT JOIN pc_perfil pf ON pf.idperfil = usr.id_perfil
            WHERE usr.idusuario = ?";
    $stmt = $this->connection->prepare($sql);
    $usuario = null;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      if ($stmt->execute()) {
        $stmt->bind_result($matricula,$nome,$login,$senha,$id_perfil,$descricao);
        $stmt->store_result();
        if ($stmt->num_rows == 1 && $stmt->fetch()) {
          $usuario = new Usuario($matricula, $nome, $login,$senha,
            new Perfil($descricao, $id_perfil), $id);
        }
      }
      $stmt->close();
    }
    return $usuario;
  }

  public function inserir(Usuario $usuario): bool {
    $sql = "INSERT INTO pc_usuarios (matricula,nome,login,senha,id_perfil) VALUES(?,?,?,?,?)";
    $stmt = $this->connection->prepare($sql);
    $res = false;
    if ($stmt) {
      $matricula = $usuario->getMatricula();
      $nome = $usuario->getNome();
      $login = $usuario->getLogin();
      $senha = $usuario->getSenha();
      $perfil = $usuario->getPerfil()->getId();
      $stmt->bind_param('isssi', $matricula, $nome, $login, $senha, $perfil);
      if ($stmt->execute()) {
          $id = $this->connection->getLastID();
          $usuario->setId($id);
          $res = true;
      }
      $stmt->close();
    }
    return $res;
  }

  public function remover(Usuario $usuario): bool {
    $sql = "DELETE FROM pc_usuarios where idusuario=?";
    $id = $usuario->getId(); 
    $stmt = $this->connection->prepare($sql);
    $ret = false;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  public function atualizar(Usuario $usuario): bool {
    $sql = "UPDATE pc_usuarios SET matricula=?, nome=?, login=?, senha=?, id_perfil=? WHERE idusuario = ?";
    $stmt = $this->connection->prepare($sql);
    $ret = false;      
    if ($stmt) {
      $matricula = $usuario->getMatricula();
      $nome = $usuario->getNome();
      $login = $usuario->getLogin();
      $senha = $usuario->getSenha();
      $id_perfil = $usuario->getPerfil()->getId();      
      $idusuario   = $usuario->getId();
      $stmt->bind_param('isssii', $matricula, $nome, $login, $senha, $id_perfil, $idusuario);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  
  public function todos(): array {
    $sql = "SELECT bl.idusuario, bl.matricula, bl.nome, 
                   bl.login, bl.senha, bl.id_perfil, sb.descricao 
            FROM pc_usuarios bl
            LEFT JOIN pc_perfil sb ON sb.idperfil = bl.id_perfil";

    $stmt = $this->connection->prepare($sql);
    $usuarios = [];
    if ($stmt) {
      if ($stmt->execute()) {
        $id = 0; $nome = '';
        $stmt->bind_result(
          $idusuario, $matricula, $nome, $login, $senha, $sb_idperfil, $sb_descricao
        );
        $stmt->store_result();
        while($stmt->fetch()) {
          // TODO: Criar uma unica instancia para cada marca
          //       de modo a otimizar a memoria.
          // Adotei a abordagem abaixo por ser mais rapido, 
          // mas nao eh eficiente
          $perfil = new Perfil($sb_descricao, $sb_idperfil);
          $usuarios[] = new Usuario($matricula, $nome, $login, $senha, $perfil, $idusuario);
        }
      }
      $stmt->close();
    }
    return $usuarios;
  }

};

