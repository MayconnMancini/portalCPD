<?php 
require_once(__DIR__ . '/../model/OrdemServico.php');
require_once(__DIR__ . '/../db/Db.php');

// Classe para persistencia de OrdemServico 
// DAO - Data Access Object
class DaoOrdemServico {
    
  private $connection;

  public function __construct(Db $connection) {
      $this->connection = $connection;
  }
  
  public function porId(int $id): OrdemServico {
    $sql = "SELECT numOrdemServico, valor FROM pc_ordens_servico where idordemServico = ?";
    $stmt = $this->connection->prepare($sql);
    $status = null;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      if ($stmt->execute()) {
        $descricao = '';
        $stmt->bind_result($numOrdemServico,$valor);
        $stmt->store_result();
        if ($stmt->num_rows == 1 && $stmt->fetch()) {
          $os = new OrdemServico($numOrdemServico, $valor, $id);
        }
      }
      $stmt->close();
    }
    return $os;
  }

  public function inserir(Status $status): bool {
    $sql = "INSERT INTO pc_status (descricao) VALUES(?)";
    $stmt = $this->connection->prepare($sql);
    $res = false;
    if ($stmt) {
      $descricao = $status->getDescricao();
      $stmt->bind_param('s', $descricao);
      if ($stmt->execute()) {
          $id = $this->connection->getLastID();
          $status->setId($id);
          $res = true;
      }
      $stmt->close();
    }
    return $res;
  }

  public function remover(Status $status): bool {
    $sql = "DELETE FROM pc_status where id=?";
    $id = $status->getId(); 
    $stmt = $this->connection->prepare($sql);
    $ret = false;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  public function atualizar(Status $status): bool {
    $sql = "UPDATE pc_status SET descricao=? WHERE id = ?";
    $stmt = $this->connection->prepare($sql);
    $ret = false;      
    if ($stmt) {
      $descricao = $status->getDescricao();
      $id   = $status->getId();
      $stmt->bind_param('si', $descricao, $id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  
  public function todos(): array {
    $sql = "SELECT idstatus, descricao from pc_status";
    $stmt = $this->connection->prepare($sql);
    $status = [];
    if ($stmt) {
      if ($stmt->execute()) {
        $id = 0; $descricao = '';
        $stmt->bind_result($id, $descricao);
        $stmt->store_result();
        while($stmt->fetch()) {
          $status[] = new Status($descricao, $id);
        }
      }
      $stmt->close();
    }
    return $status;
  }

};

