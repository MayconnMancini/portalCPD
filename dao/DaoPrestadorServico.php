<?php 
require_once(__DIR__ . '/../model/PrestadorServico.php');
require_once(__DIR__ . '/../db/Db.php');

// Classe para persistencia de prestadores 
// DAO - Data Access Object
class DaoPrestadorServico {
    
  private $connection;

  public function __construct(Db $connection) {
      $this->connection = $connection;
  }
  
  public function porId(int $id): PrestadorServico {
    $sql = "SELECT prestadores.cnpj, prestadores.razaoSocial, 
                   prestadores.telefone, prestadores.email
            FROM pc_prestadores_servico prestadores
            WHERE prestadores.idprestador = ?";
    $stmt = $this->connection->prepare($sql);
    $prod = null;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      if ($stmt->execute()) {
        $stmt->bind_result($cnpj,$razaoSocial,$telefone,$email);
        $stmt->store_result();
        if ($stmt->num_rows == 1 && $stmt->fetch()) {
          $prest = new PrestadorServico($cnpj,$razaoSocial,$telefone,$email, $id);
        }
      }
      $stmt->close();
    }
    return $prest;
  }

  public function inserir(PrestadorServico $prestador): bool {
    $sql = "INSERT INTO pc_prestadores_servico (cnpj,razaoSocial,telefone,email) VALUES(?,?,?,?)";
    $stmt = $this->connection->prepare($sql);
    $res = false;
    if ($stmt) {
      $cnpj = $prestador->getCnpj();
      $razaoSocial = $prestador->getRazaoSocial();
      $telefone = $prestador->getTelefone();
      $email = $prestador->getEmail();
      $stmt->bind_param('ssss', $cnpj, $razaoSocial, $telefone, $email);
      if ($stmt->execute()) {
          $id = $this->connection->getLastID();
          $prestador->setId($id);
          $res = true;
      }
      $stmt->close();
    }
    return $res;
  }

  public function remover(PrestadorServico $prestador): bool {
    $sql = "DELETE FROM pc_prestadores_servico where idprestador=?";
    $id = $prestador->getId(); 
    $stmt = $this->connection->prepare($sql);
    $ret = false;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  public function atualizar(PrestadorServico $prestador): bool {
    $sql = "UPDATE pc_prestadores_servico SET cnpj=?, razaoSocial=?, telefone=?, email=? WHERE idprestador = ?";
    $stmt = $this->connection->prepare($sql);
    $ret = false;      
    if ($stmt) {
      $cnpj = $prestador->getCnpj();
      $razaoSocial = $prestador->getRazaoSocial();
      $telefone = $prestador->getTelefone();
      $email = $prestador->getEmail();     
      $id   = $prestador->getId();
      $stmt->bind_param('ssssi', $cnpj, $razaoSocial, $telefone, $email, $id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  
  public function todos(): array {
    $sql = "SELECT prestadores.idprestador, prestadores.cnpj, prestadores.razaoSocial, 
                   prestadores.telefone, prestadores.email
            FROM pc_prestadores_servico prestadores";
    $stmt = $this->connection->prepare($sql);
    $prestadores = [];
    if ($stmt) {
      if ($stmt->execute()) {
        $id = 0; $nome = '';
        $stmt->bind_result(
          $id, $cnpj, $razaoSocial, $telefone, $email
        );
        $stmt->store_result();
        while($stmt->fetch()) {
          $prestadores[] = new PrestadorServico($cnpj, $razaoSocial, $telefone, $email, $id);
        }
      }
      $stmt->close();
    }
    return $prestadores;
  }

};

