<?php 
require_once(__DIR__ . '/../model/StatusBalanca.php');
require_once(__DIR__ . '/../db/Db.php');

// Classe para persistencia de balancas 
// DAO - Data Access Object
class DaoStatusBalanca {
    
  private $connection;

  public function __construct(Db $connection) {
      $this->connection = $connection;
  }
  
  public function porId(int $id): StatusBalanca {
    $sql = "SELECT descricao FROM pc_status_balancas where idstatusBalanca = ?";
    $stmt = $this->connection->prepare($sql);
    $status = null;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      if ($stmt->execute()) {
        $descricao = '';
        $stmt->bind_result($descricao);
        $stmt->store_result();
        if ($stmt->num_rows == 1 && $stmt->fetch()) {
          $status = new StatusBalanca($descricao, $id);
        }
      }
      $stmt->close();
    }
    return $status;
  }

  public function inserir(StatusBalanca $balanca): bool {
    $sql = "INSERT INTO pc_status_balancas (descricao) VALUES(?)";
    $stmt = $this->connection->prepare($sql);
    $res = false;
    if ($stmt) {
      $descricao = $balanca->getDescricao();
      $stmt->bind_param('s', $descricao);
      if ($stmt->execute()) {
          $id = $this->connection->getLastID();
          $balanca->setId($id);
          $res = true;
      }
      $stmt->close();
    }
    return $res;
  }

  public function remover(StatusBalanca $balanca): bool {
    $sql = "DELETE FROM pc_status_balancas where id=?";
    $id = $balanca->getId(); 
    $stmt = $this->connection->prepare($sql);
    $ret = false;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  public function atualizar(Balanca $balanca): bool {
    $sql = "UPDATE pc_status_balancas SET descricao=? WHERE id = ?";
    $stmt = $this->connection->prepare($sql);
    $ret = false;      
    if ($stmt) {
      $descricao = $balanca->getStatusBalanca()->getDescricao();
      $id   = $balanca->getId();
      $stmt->bind_param('si', $descricao, $id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  
  public function todos(): array {
    $sql = "SELECT idstatusBalanca, descricao from pc_status_balancas";
    $stmt = $this->connection->prepare($sql);
    $status = [];
    if ($stmt) {
      if ($stmt->execute()) {
        $id = 0; $descricao = '';
        $stmt->bind_result($id, $descricao);
        $stmt->store_result();
        while($stmt->fetch()) {
          $status[] = new StatusBalanca($descricao, $id);
        }
      }
      $stmt->close();
    }
    return $status;
  }

};

