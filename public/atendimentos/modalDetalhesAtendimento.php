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

$daoAtendimento = new DaoAtendimento($conn);
$atendimento = $daoAtendimento->porId( $_POST['user_id']);

$daoReparo = new DaoReparo($conn);
$reparos = $daoReparo->reparosPorAtendimento( $atendimento->getId() );

?>

<!-- modal detalhes atendimento -->

<div id="visualUsuarioModal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
   aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="exampleModalLabrl"> Detalhes do atendimento </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            <div class="form-row "><!-- inicio primeiro row, dados do atendimento -->
                <div class="form-group col-md-2 ">
                    <label for="numAtendimento" class="font-weight-bold">Nº Atendimento</label>
                    <input type="number" class="form-control" id="numAtendimento"
                        value="<?php echo $atendimento->getId(); ?>"
                        name="numAtendimento" placeholder="numAtendimento" readonly>
                </div>
                <div class="form-group col-md-3">
                    <label for="statusBalanca" class="font-weight-bold">STATUS</label>
                    <input class="form-control" id="statusBalanca" name="statusBalanca" 
                    value="<?php echo $atendimento->getStatus()->getDescricao(); ?>" readonly>                        
                </div>
                <div class="form-group col-md-3">
                    <label for="dataInicioAtendimento" class="font-weight-bold" >Data do início do atendimento</label>
                    <input type="date" class="form-control" id="dataInicioAtendimento"
                    value="<?php echo $atendimento->getDataInicioAtendimento(); ?>"
                    name="dataInicioAtendimento" readonly>
                </div>
                <div class="form-group col-md-3">
                    <label for="dataFimAtendimento" class="font-weight-bold" >Data do Fim do atendimento</label>
                    <input type="date" class="form-control" id="dataFimAtendimento"
                    value="<?php echo $atendimento->getDataFimAtendimento(); ?>"
                    name="dataFimAtendimento" readonly>
                </div>
                <div class="form-group col-md-2">
                    <label for="usuario" class="font-weight-bold">Usuário</label>
                    <input class="form-control" id="usuario" name="usuario" 
                    value="<?php echo $atendimento->getUsuario()->getNome() ?>" readonly>                  
                </div>
                    
                <div class="form-group col-md-3">
                    <label for="prestadorServico" class="font-weight-bold">Prestador de Serviço</label>
                    <input class="form-control" id="prestadorServico" name="prestadorServico" 
                    value="<?php echo $atendimento->getPrestadorServico()->getRazaoSocial() ?>" readonly>                     
                </div>
            </div>
                
            <h5>Reparos</h5>

                <?php 
    if (count($reparos) >0) 
    {
?>
        <div class="row">
            <div class="col-md-12" >
            <div class="table-responsive border rounded">
                <table class="table table-hover table-sm ">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nº Balança</th>
                        <th scope="col">Setor</th>
                        <th scope="col">Defeito</th>
                        <th scope="col">Observacao</th>
                        <th scope="col">Data inicio reparo</th>
                        <th scope="col">Data fim reparo</th>
                        <th scope="col">Status Reparo</th>
                        
                    </tr>
                </thead>
                <tbody>
<?php 
        foreach($reparos as $r) 
       {
?>                    
                   <tr>
                        <th scope="row"><?php echo  $r->getId(); ?></th>
                        <td><?php echo $r->getBalanca()->getNumBalanca() ; ?></td>
                        <td><?php echo $r->getBalanca()->getSetor(); ?></td>
                        <td><?php echo $r->getDescricaoDefeito(); ?></td>
                        <td><?php echo $r->getObservacao(); ?></td>
                        <td><?php echo inverteData($r->getDataInicioReparo()) ?></td>
                        <td><?php echo inverteData($r->getDataFimReparo()) ?></td>
                        <td><?php echo $r->getStatus()->getDescricao(); ?></td>
                        
                    </tr>
                   
                    
                    
<?php
        } // foreach
?>                    
                </tbody>
                </table>
            </div>
            </div>
        </div>
<?php 
    
    }  // if 
?>                    

                
                
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        
        </div>
    
    </div>

</div>
