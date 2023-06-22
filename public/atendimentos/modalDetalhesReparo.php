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
    header("Location: ../public/login.php"); exit;
}
require_once(__DIR__ . '/../../templates/template-html.php');
require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../config/config.php');

require_once(__DIR__ . '/../../model/Atendimento.php');
require_once(__DIR__ . '/../../dao/DaoAtendimento.php');

require_once(__DIR__ . '/../../model/Reparo.php');
require_once(__DIR__ . '/../../dao/DaoReparo.php');

require_once(__DIR__ . '/../../model/Status.php');
require_once(__DIR__ . '/../../dao/DaoStatus.php');

require_once(__DIR__ . '/../../model/Balanca.php');
require_once(__DIR__ . '/../../dao/DaoBalanca.php');

$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

function inverteData($data){
    if(count(explode("/",$data)) > 1){
        return implode("-",array_reverse(explode("/",$data)));
    }elseif(count(explode("-",$data)) > 1){
        return implode("/",array_reverse(explode("-",$data)));
    }
  }

$daoReparo = new DaoReparo($conn);
$reparo = $daoReparo->porId( $_POST['user_id']);


?>

<!-- modal detalhes atendimento -->

<div id="visualReparoModal" class="modal fade" tabindex="-1" role="dialog"
   aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="exampleModalLabrl"> Detalhes do reparo </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
                <div class="form-row "><!-- inicio primeiro row, dados do atendimento -->
                    <div class="form-group col-md-2 ">
                        <label for="setor" class="font-weight-bold">Nº Reparo</label>
                        <input type="number" class="form-control" id="numAtendimento"
                            value="<?php echo $reparo->getId(); ?>"
                            name="numAtendimento" placeholder="numAtendimento" readonly>
                    </div>
                    <div class="form-group col-md-2 ">
                        <label for="setor" class="font-weight-bold">Nº Atendimento</label>
                        <input type="number" class="form-control" id="numAtendimento"
                            value="<?php echo $reparo->getAtendimento()->getId() ?>"
                            name="numAtendimento" placeholder="numAtendimento" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="status" class="font-weight-bold">Status do Reparo</label>
                        <input class="form-control" id="status" name="status" 
                        value="<?php echo $reparo->getStatus()->getDescricao() ?>" readonly>           
                    </div>

                    <div class="form-group col-md-3">
                        <label for="dataInicioReparo" class="font-weight-bold" >Data do início do reparo</label>
                        <input type="date" class="form-control" id="dataInicioReparo"
                        value="<?php echo $reparo->getDataInicioReparo(); ?>"
                        name="dataInicioReparo" readonly>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="dataFimReparo" class="font-weight-bold" >Data do Fim do reparo</label>
                        <input type="date" class="form-control" id="dataFimReparo"
                        value="<?php echo $reparo->getDataFimReparo(); ?>"
                        name="dataFimReparo" readonly>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="idBalanca" class="font-weight-bold">Nº Balança</label>
                        <input class="form-control" id="idBalanca" name="idBalanca" 
                        value="<?php echo $reparo->getBalanca()->getNumBalanca() ?>" readonly>
                    </div>

                    <div class="form-group col-md-6 form-floating">
                        <label for="descDefeito" class="font-weight-bold">Descrição do defeito</label>
                        <textarea class="form-control" id="descDefeito" name="descDefeito"
                        readonly ><?php echo $reparo->getDescricaoDefeito() ?></textarea>
                    </div>
                    <div class="form-group col-md-4 form-floating">
                        <label for="observacao" class="font-weight-bold">Observação</label>
                        <textarea class="form-control" id="observacao" name="observacao"
                        readonly><?php echo $reparo->getObservacao() ?></textarea>
                    </div>
                </div>             
            </div>
                 
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        
        </div>
    
    </div>

</div>
