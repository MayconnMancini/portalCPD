<?php 
require_once(__DIR__ . '/../model/Atendimento.php');
require_once(__DIR__ . '/../model/Status.php');
require_once(__DIR__ . '/../model/PrestadorServico.php');
require_once(__DIR__ . '/../model/Usuario.php');
require_once(__DIR__ . '/../db/Db.php');

// Classe para persistencia de atendimentos 
// DAO - Data Access Object
class DaoAtendimento {
    
  private $connection;

  public function __construct(Db $connection) {
      $this->connection = $connection;
  }
  
  public function porId(int $id): Atendimento {

    /*  
    "SELECT bl.dataInicioAtendimento, bl.setor,bl.numSerie,
                   bl.localAtual, bl.id_status_atendimento, sb.descricao 
            FROM pc_atendimentos bl
            LEFT JOIN pc_status_atendimentos sb ON sb.idstatusatendimento = bl.id_status_atendimento
            WHERE bl.idatendimento = ?";


    */

 /*   SELECT atd.idatendimento, atd.dataInicioAtendimento, atd.dataFimAtendimento, atd.id_prestador_servico, atd.id_colaborador, atd.id_status,
	    pres.razaoSocial, usr.nome, st.descricao
      FROM pc_atendimentos atd, pc_prestadores_servico pres, pc_usuarios usr, pc_status st
      WHERE atd.id_prestador_servico = pres.idprestador
      and atd.id_colaborador = usr.idusuario
      and atd.id_status = st.idstatus;
*/
    $sql = "SELECT atd.dataInicioAtendimento, atd.dataFimAtendimento, atd.id_prestador_servico, atd.id_usuario, atd.id_status,
            pres.razaoSocial, usr.nome, st.descricao
            FROM pc_atendimentos atd, pc_prestadores_servico pres, pc_usuarios usr, pc_status st
            WHERE atd.idatendimento = ?
            and atd.id_prestador_servico = pres.idprestador
            and atd.id_usuario = usr.idusuario
            and atd.id_status = st.idstatus";
    $stmt = $this->connection->prepare($sql);
    $atendimento = null;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      if ($stmt->execute()) {
        $stmt->bind_result($dataInicioAtendimento,$dataFimAtendimento,$id_prestador_servico,
                           $id_usuario,$id_status,$razaoSocial,$nome,$descricao);
        $stmt->store_result();
        if ($stmt->num_rows == 1 && $stmt->fetch()) {

          $prestador = new PrestadorServico("",$razaoSocial,"","",$id_prestador_servico);
          $usuario = new Usuario(0,$nome,"","",null,$id_usuario);
          $status = new Status($descricao,$id_status);

          $atendimento = new Atendimento($dataInicioAtendimento,$status,$usuario,$prestador,$dataFimAtendimento,$id);
        }
      }
      $stmt->close();
    }
    return $atendimento;
  }

  public function inserir(Atendimento $atendimento): bool {
    $sql = "INSERT INTO pc_atendimentos (dataInicioAtendimento,dataFimAtendimento,id_prestador_servico ,id_usuario ,id_status ) VALUES(?,?,?,?,?)";
    $stmt = $this->connection->prepare($sql);
    $res = false;
    if ($stmt) {

      $dataInicioAtendimento = $atendimento->getDataInicioAtendimento();
      //$dataInicioAtendimento = $dataInicioAtendimento->format('Y-m-d');

      $dataFimAtendimento = $atendimento->getDataFimAtendimento();

      $prestador = $atendimento->getPrestadorServico()->getId();
      $usuario = $atendimento->getUsuario()->getId();
      $status = $atendimento->getStatus()->getId();
      $stmt->bind_param('ssiii', $dataInicioAtendimento, $dataFimAtendimento, $prestador, $usuario, $status);
      if ($stmt->execute()) {
          $id = $this->connection->getLastID();
          $atendimento->setId($id);
          $res = true;
      }
      $stmt->close();
    }
    return $res;
  }

  public function remover(atendimento $atendimento): bool {
    $sql = "DELETE FROM pc_atendimentos where idatendimento=?";
    $id = $atendimento->getId(); 
    $stmt = $this->connection->prepare($sql);
    $ret = false;
    if ($stmt) {
      $stmt->bind_param('i',$id);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  public function atualizar(atendimento $atendimento): bool {
    $sql = "UPDATE pc_atendimentos SET dataInicioAtendimento=?,dataFimAtendimento=?, id_prestador_servico=? ,id_usuario=? ,id_status=? WHERE idatendimento = ?";
    $stmt = $this->connection->prepare($sql);
    $ret = false;      
    if ($stmt) {
      $dataInicio = $atendimento->getdataInicioAtendimento();
      $dataFim = $atendimento->getdataFimAtendimento();
      $prestador = $atendimento->getPrestadorServico()->getId();
      $usuario = $atendimento->getUsuario()->getId();
      $status = $atendimento->getStatus()->getId();     
      $idatendimento   = $atendimento->getId();

      $stmt->bind_param('ssiiii', $dataInicio, $dataFim, $prestador, $usuario, $status, $idatendimento);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  public function atualizarStatus(int $idatendimento, int $id_status): bool {
    $sql = "UPDATE pc_atendimentos SET id_status=? WHERE idatendimento = ?";
    $stmt = $this->connection->prepare($sql);
    $ret = false;      
    if ($stmt) {
      
      $id_status = $id_status;     
      $idatendimento   = $idatendimento;

      $stmt->bind_param('ii', $id_status, $idatendimento);
      $ret = $stmt->execute();
      $stmt->close();
    }
    return $ret;
  }

  
  public function todos(): array {
    $sql = "SELECT bl.idatendimento, bl.dataInicioAtendimento, bl.dataFimAtendimento,
                  bl.id_status, sb.descricao 
            FROM pc_atendimentos bl
            LEFT JOIN pc_status sb ON sb.idstatus = bl.id_status";

    $stmt = $this->connection->prepare($sql);
    $atendimentos = [];
    if ($stmt) {
      if ($stmt->execute()) {
        $id = 0; $nome = '';
        $stmt->bind_result(
          $idatendimento, $dataInicioAtendimento, $dataFimAtendimento, $sb_idstatus, $sb_descricao
        );
        $stmt->store_result();
        while($stmt->fetch()) {
          // TODO: Criar uma unica instancia para cada marca
          //       de modo a otimizar a memoria.
          // Adotei a abordagem abaixo por ser mais rapido, 
          // mas nao eh eficiente
          $status = new Status($sb_descricao, $sb_idstatus);
          $atendimentos[] = new Atendimento($dataInicioAtendimento, $status, null,null, $dataFimAtendimento, $idatendimento);
        }
      }
      $stmt->close();
    }
    return $atendimentos;
  }

};

