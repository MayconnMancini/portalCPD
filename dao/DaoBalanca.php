<?php 
require_once(__DIR__ . '/../model/Balanca.php');
require_once(__DIR__ . '/../model/StatusBalanca.php');
require_once(__DIR__ . '/../db/Db.php');

// Classe para persistencia de balancas 
// DAO - Data Access Object
class DaoBalanca {
    
  private $connection;

  public function __construct(Db $connection) {
      $this->connection = $connection;
  }
  
  public function porId(int $id): Balanca {
    $sql = "SELECT bl.numBalanca, bl.pip, bl.setor,bl.numSerie,
                   bl.localAtual, bl.id_status_balanca, sb.descricao 
            FROM pc_balancas bl
            LEFT JOIN pc_status_balancas sb ON sb.idstatusBalanca = bl.id_status_balanca
            WHERE bl.idbalanca = ?";
    $stmt = $this->connection->prepare($sql);
    $balanca = null;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      if ($stmt->execute()) {
        $stmt->bind_result($numBalanca,$pip,$setor,$numSerie,$localAtual,$id_status_balanca,$descricao);
        $stmt->store_result();
        if ($stmt->num_rows == 1 && $stmt->fetch()) {
          $balanca = new Balanca($numBalanca, $pip, $setor, $numSerie,$localAtual,
            new StatusBalanca($descricao, $id_status_balanca), $id);
        }
      }
      $stmt->close();
    }
    return $balanca;
  }

  public function inserir(Balanca $balanca): bool {
    $sql = "INSERT INTO pc_balancas (numBalanca,pip,setor,numSerie,localAtual,id_status_balanca) VALUES(?,?,?,?,?,?)";
    $stmt = $this->connection->prepare($sql);
    $res = false;
    if ($stmt) {
      $numBalanca = $balanca->getNumBalanca();
      $pip = $balanca->getPip();
      $setor = $balanca->getSetor();
      $numSerie = $balanca->getNumSerie();
      $localAtual = $balanca->getLocalAtual();
      $statusAtual = $balanca->getStatusBalanca()->getId();
      $stmt->bind_param('iisisi', $numBalanca, $pip, $setor, $numSerie, $localAtual, $statusAtual);
      if ($stmt->execute()) {
          $id = $this->connection->getLastID();
          $balanca->setId($id);
          $res = true;
      }
      $stmt->close();
    }
    return $res;
  }

  public function remover(balanca $balanca): bool {
    $sql = "DELETE FROM pc_balancas where idbalanca=?";
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

  public function atualizar(balanca $balanca): bool {
    $sql = "UPDATE pc_balancas SET numBalanca=?, pip=?, setor=?, numSerie=?, localAtual=?, id_status_balanca=? WHERE idbalanca = ?";
    $stmt = $this->connection->prepare($sql);
    $ret = false;      
    if ($stmt) {
      $numero = $balanca->getNumBalanca();
      $pip = $balanca->getPip();
      $setor = $balanca->getSetor();
      $numSerie = $balanca->getNumSerie();
      $local = $balanca->getLocalAtual();
      $id_status_balanca = $balanca->getStatusBalanca()->getId();      
      $idbalanca   = $balanca->getId();
      $stmt->bind_param('iisisii', $numero, $pip, $setor, $numSerie, $local, $id_status_balanca, $idbalanca);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  
  public function todos(): array {
    $sql = "SELECT bl.idbalanca, bl.numBalanca, bl.pip, bl.setor, 
                   bl.numSerie, bl.localAtual, bl.id_status_balanca, sb.descricao 
            FROM pc_balancas bl
            LEFT JOIN pc_status_balancas sb ON sb.idstatusBalanca = bl.id_status_balanca";

    $stmt = $this->connection->prepare($sql);
    $balancas = [];
    if ($stmt) {
      if ($stmt->execute()) {
        $id = 0; $nome = '';
        $stmt->bind_result(
          $idbalanca, $numBalanca, $pip, $setor, $numSerie, $localAtual, $sb_idstatusBalanca, $sb_descricao
        );
        $stmt->store_result();
        while($stmt->fetch()) {
          // TODO: Criar uma unica instancia para cada marca
          //       de modo a otimizar a memoria.
          // Adotei a abordagem abaixo por ser mais rapido, 
          // mas nao eh eficiente
          $status = new StatusBalanca($sb_descricao, $sb_idstatusBalanca);
          $balancas[] = new Balanca($numBalanca, $pip, $setor, $numSerie, $localAtual, $status, $idbalanca);
        }
      }
      $stmt->close();
    }
    return $balancas;
  }

};

