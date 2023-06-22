<?php 
require_once(__DIR__ . '/../db/Db.php');
require_once(__DIR__ . '/../model/Atendimento.php');
require_once(__DIR__ . '/../model/Balanca.php');
require_once(__DIR__ . '/../model/Reparo.php');
require_once(__DIR__ . '/../model/Status.php');
require_once(__DIR__ . '/../model/PrestadorServico.php');
require_once(__DIR__ . '/../model/Usuario.php');


// Classe para persistencia de atendimentos 
// DAO - Data Access Object

class DaoReparo {
    
  private $connection;

  public function __construct(Db $connection) {
      $this->connection = $connection;
  }
  
  public function porId(int $id): Reparo {

    $sql = "SELECT rp.descDefeito, rp.dataInicioReparo, rp.dataFimReparo, rp.observacao, rp.id_atendimento, rp.id_balanca, rp.id_ordem_servico, rp.id_status,
            bl.numBalanca, bl.setor, bl.numSerie,
            st.descricao
            FROM pc_reparos rp, pc_balancas bl, pc_status st
            WHERE rp.idreparo = ?
            and rp.id_balanca = bl.idbalanca
            and rp.id_status = st.idstatus";
    $stmt = $this->connection->prepare($sql);
    $atendimento = null;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      if ($stmt->execute()) {
        $stmt->bind_result($descDefeito,$dataInicioReparo,$dataFimReparo,$observacao,$id_atendimento,
                           $id_balanca,$id_ordem_servico,$id_status,$numBalanca,$setor,$numSerie,$descricao);
        $stmt->store_result();
        if ($stmt->num_rows == 1 && $stmt->fetch()) {

          $atendimento = new Atendimento(null,null,null,null,null,$id_atendimento);
          $balanca = new Balanca($numBalanca,0,$setor,$numSerie,"",null,$id_balanca);
          $status = new Status($descricao,$id_status);
          $os = new OrdemServico(0,0,$id_ordem_servico);

          $reparo = new Reparo($balanca,$atendimento,$descDefeito,$dataInicioReparo,$dataFimReparo,$status,$observacao,$os,$id);
        }
      }
      $stmt->close();
    }
    return $reparo;
  }

  public function inserir(Reparo $reparo): bool {
    $sql = "INSERT INTO pc_reparos (descDefeito, dataInicioReparo, dataFimReparo,
                                    observacao, id_atendimento, id_balanca, id_ordem_servico, id_status) VALUES(?,?,?,?,?,?,?,?)";
    $stmt = $this->connection->prepare($sql);
    $res = false;
    if ($stmt) {

      $descDefeito = $reparo->getDescricaoDefeito();
      $dataInicioReparo = $reparo->getDataInicioReparo();
      //$dataInicioAtendimento = $dataInicioAtendimento->format('Y-m-d');
      $dataFimReparo = $reparo->getDataFimReparo();
      $observacao = $reparo->getObservacao();
      $atendimento = $reparo->getAtendimento()->getId();
      $balanca = $reparo->getBalanca()->getId();
      $os = $reparo->getOrdemServico()->getId();
      $status = $reparo->getStatus()->getId();

      $stmt->bind_param('ssssiiii', $descDefeito, $dataInicioReparo, $dataFimReparo, $observacao, $atendimento, $balanca,$os, $status );
      if ($stmt->execute()) {
          $id = $this->connection->getLastID();
          $reparo->setId($id);
          $res = true;
      }
      $stmt->close();
    }
    return $res;
  }

  public function remover(Reparo $reparo): bool {
    $sql = "DELETE FROM pc_reparos where idreparo=?";
    $id = $reparo->getId(); 
    $stmt = $this->connection->prepare($sql);
    $ret = false;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  public function atualizar(Reparo $reparo): bool {
    $sql = "UPDATE pc_reparos SET descDefeito=?,dataInicioReparo=?,dataFimReparo=?,observacao=?,id_atendimento=?,id_balanca=?,id_ordem_servico=?,id_status=? WHERE idreparo = ?";
    $stmt = $this->connection->prepare($sql);
    $ret = false;      
    if ($stmt) {
      $descDefeito =$reparo->getDescricaoDefeito(); 
      $dataInicio = $reparo->getdataInicioReparo();
      $dataFim =    $reparo->getdataFimReparo();
      $observacao = $reparo->getObservacao();
      $id_atendimento =    $reparo->getAtendimento()->getId();
      $id_balanca = $reparo->getBalanca()->getId();
      $id_ordem_servico = $reparo->getOrdemServico()->getId();
      $id_status = $reparo->getStatus()->getId();    
      $idreparo   = $reparo->getId();

      $stmt->bind_param('ssssiiiii', $descDefeito, $dataInicio, $dataFim, $observacao, $id_atendimento, $id_balanca, $id_ordem_servico, $id_status, $idreparo);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  
  public function todos(): array {
    $sql = "SELECT rp.idreparo, rp.descDefeito, rp.dataInicioReparo, rp.dataFimReparo,
            rp.observacao, rp.id_atendimento, rp.id_balanca, rp.id_ordem_servico, rp.id_status,
            bl.numBalanca, bl.setor, st.descricao
            FROM pc_reparos rp, pc_balancas bl, pc_status st
            WHERE rp.id_balanca = bl.idbalanca
            AND rp.id_status = st.idstatus ";

    $stmt = $this->connection->prepare($sql);
    $reparos = [];
    if ($stmt) {
      if ($stmt->execute()) {
        //$id = 0; $nome = '';
        $stmt->bind_result(
          $idreparo, $descDefeito, $dataInicioReparo, $dataFimReparo, $observacao, $id_atendimento, $id_balanca, $id_ordem_servico,$id_status,
          $numBalanca, $setor, $descricao
        );
        $stmt->store_result();
        while($stmt->fetch()) {
          // TODO: Criar uma unica instancia para cada marca
          //       de modo a otimizar a memoria.
          // Adotei a abordagem abaixo por ser mais rapido, 
          // mas nao eh eficiente
          $balanca = new Balanca($numBalanca,0,$setor,0,0,null,$id_balanca);
          $atendimento = new Atendimento(null,null,null,null,null,$id_atendimento);
          $status = new Status($descricao, $id_status);
          $os = new OrdemServico(-1,-1,$id_ordem_servico);

          $reparos[] = new Reparo($balanca, $atendimento, $descDefeito, $dataInicioReparo, $dataFimReparo, $status, $observacao, $os, $idreparo);
        }
      }
      $stmt->close();
    }
    return $reparos;
  }

  public function reparosPorAtendimento(int $id): array {
    $sql = "SELECT rp.idreparo, rp.descDefeito, rp.dataInicioReparo, rp.dataFimReparo,
                  rp.observacao, rp.id_atendimento, rp.id_balanca, rp.id_ordem_servico, rp.id_status,
                  bl.numBalanca, bl.setor, st.descricao
            FROM pc_reparos rp, pc_balancas bl, pc_status st
            WHERE rp.id_atendimento = ?
            AND rp.id_balanca = bl.idbalanca
            AND rp.id_status = st.idstatus ";

    $stmt = $this->connection->prepare($sql);
    $reparos = [];
   
    if ($stmt) {
      $stmt->bind_param('i',$id);
      if ($stmt->execute()) {
        //$id = 0; $nome = '';
        $stmt->bind_result(
          $idreparo, $descDefeito, $dataInicioReparo, $dataFimReparo, $observacao, $id_atendimento, $id_balanca, $id_ordem_servico,$id_status,
          $numBalanca, $setor, $descricao
        );
        $stmt->store_result();
        while($stmt->fetch()) {
          // TODO: Criar uma unica instancia para cada marca
          //       de modo a otimizar a memoria.
          // Adotei a abordagem abaixo por ser mais rapido, 
          // mas nao eh eficiente

        
          $balanca = new Balanca($numBalanca,0,$setor,0,0,null,$id_balanca);
          $atendimento = new Atendimento(null,null,null,null,null,$id_atendimento);
          $status = new Status($descricao, $id_status);
          $os = new OrdemServico(-1,-1,$id_ordem_servico);

          $reparos[] = new Reparo($balanca, $atendimento, $descDefeito, $dataInicioReparo, $dataFimReparo, $status, $observacao, $os, $idreparo);
        }
      }
      $stmt->close();
    }
    return $reparos;
  }

  public function reparosPorBalanca(int $id): array {
    $sql = "SELECT rp.idreparo, rp.descDefeito, rp.dataInicioReparo, rp.dataFimReparo,
                  rp.observacao, rp.id_atendimento, rp.id_balanca, rp.id_ordem_servico, rp.id_status,
                  bl.numBalanca, bl.setor, st.descricao
            FROM pc_reparos rp, pc_balancas bl, pc_status st
            WHERE rp.id_balanca = ?
            AND rp.id_balanca = bl.idbalanca
            AND rp.id_status = st.idstatus ";

    $stmt = $this->connection->prepare($sql);
    $reparos = [];
   
    if ($stmt) {
      $stmt->bind_param('i',$id);
      if ($stmt->execute()) {
        //$id = 0; $nome = '';
        $stmt->bind_result(
          $idreparo, $descDefeito, $dataInicioReparo, $dataFimReparo, $observacao, $id_atendimento, $id_balanca, $id_ordem_servico,$id_status,
          $numBalanca, $setor, $descricao
        );
        $stmt->store_result();
        while($stmt->fetch()) {
          // TODO: Criar uma unica instancia para cada marca
          //       de modo a otimizar a memoria.
          // Adotei a abordagem abaixo por ser mais rapido, 
          // mas nao eh eficiente

        
          $balanca = new Balanca($numBalanca,0,$setor,0,0,null,$id_balanca);
          $atendimento = new Atendimento(null,null,null,null,null,$id_atendimento);
          $status = new Status($descricao, $id_status);
          $os = new OrdemServico(-1,-1,$id_ordem_servico);

          $reparos[] = new Reparo($balanca, $atendimento, $descDefeito, $dataInicioReparo, $dataFimReparo, $status, $observacao, $os, $idreparo);
        }
      }
      $stmt->close();
    }
    return $reparos;
  }

};

?>