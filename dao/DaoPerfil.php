<?php 
require_once(__DIR__ . '/../model/Perfil.php');
require_once(__DIR__ . '/../db/Db.php');

// Classe para persistencia de perfils 
// DAO - Data Access Object
class DaoPerfil {
    
  private $connection;

  public function __construct(Db $connection) {
      $this->connection = $connection;
  }
  
  public function porId(int $id): Perfil {
    $sql = "SELECT descricao FROM pc_perfil where idperfil = ?";
    $stmt = $this->connection->prepare($sql);
    $perfil = null;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      if ($stmt->execute()) {
        $descricao = '';
        $stmt->bind_result($descricao);
        $stmt->store_result();
        if ($stmt->num_rows == 1 && $stmt->fetch()) {
          $perfil = new Perfil($descricao, $id);
        }
      }
      $stmt->close();
    }
    return $perfil;
  }

  public function inserir(Perfil $perfil): bool {
    $sql = "INSERT INTO pc_perfil (descricao) VALUES(?)";
    $stmt = $this->connection->prepare($sql);
    $res = false;
    if ($stmt) {
      $descricao = $perfil->getDescricao();
      $stmt->bind_param('s', $descricao);
      if ($stmt->execute()) {
          $id = $this->connection->getLastID();
          $perfil->setId($id);
          $res = true;
      }
      $stmt->close();
    }
    return $res;
  }

  public function remover(Perfil $perfil): bool {
    $sql = "DELETE FROM pc_perfil where id=?";
    $id = $perfil->getId(); 
    $stmt = $this->connection->prepare($sql);
    $ret = false;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  public function atualizar(Perfil $perfil): bool {
    $sql = "UPDATE pc_perfil SET descricao=? WHERE id = ?";
    $stmt = $this->connection->prepare($sql);
    $ret = false;      
    if ($stmt) {
      $descricao = $perfil->getDescricao();
      $id   = $perfil->getId();
      $stmt->bind_param('si', $descricao, $id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  
  public function todos(): array {
    $sql = "SELECT idperfil, descricao from pc_perfil";
    $stmt = $this->connection->prepare($sql);
    $perfis = [];
    if ($stmt) {
      if ($stmt->execute()) {
        $id = 0; $descricao = '';
        $stmt->bind_result($id, $descricao);
        $stmt->store_result();
        while($stmt->fetch()) {
          $perfis[] = new Perfil($descricao, $id);
        }
      }
      $stmt->close();
    }
    return $perfis;
  }

};

