<?php
// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION)) session_start();

$perfil_necessario = 1;

// Verifica se não há a variável da sessão que identifica o usuário
if (
    !isset($_SESSION['iduser']) or ($_SESSION['id_perfil'] != $perfil_necessario)
    or (empty($_SESSION['iduser']))
) {
    // Destrói a sessão por segurança
    session_destroy();
    // Redireciona o visitante de volta pro login
    header("Location: ../login.php?login=errosessao");
    exit;
}
require_once(__DIR__ . '/../../templates/template-html.php');
require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../model/Balanca.php');
require_once(__DIR__ . '/../../dao/DaoBalanca.php');
require_once(__DIR__ . '/../../model/Reparo.php');
require_once(__DIR__ . '/../../dao/DaoReparo.php');
require_once(__DIR__ . '/../../config/config.php');

$conn = Db::getInstance();

if (!$conn->connect()) {
    die();
}

function inverteData($data)
{
    if (count(explode("/", $data)) > 1) {
        return implode("-", array_reverse(explode("/", $data)));
    } elseif (count(explode("-", $data)) > 1) {
        return implode("/", array_reverse(explode("-", $data)));
    }
}

$daoBalanca = new DaoBalanca($conn);
$balanca = $daoBalanca->porId($_GET['id']);

$daoReparo = new DaoReparo($conn);
$reparos = $daoReparo->reparosPorBalanca($balanca->getId());

ob_start();

?>

<div class="container">
    <div class="py-5 text-center">
        <h2>Histórico de Reparos por Balança</h2>
    </div>
    <div class="form-row border rounded-lg bg-white  p-2 mb-3">
        <div class="form-group col-md-2 ">
            <label for="setor">Nº Balança</label>
            <input type="number" class="form-control" id="numAtendimento" value="<?php echo $balanca->getNumBalanca(); ?>" name="numAtendimento" placeholder="numAtendimento" readonly>
        </div>
        <div class="form-group col-md-2 ">
            <label for="setor">Setor</label>
            <input type="text" class="form-control" id="setor" value="<?php echo $balanca->getSetor(); ?>" name="setor" readonly>
        </div>
        <div class="col-md-6">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>

    </div>
    <!-- bloco responsavel por exibir o modal com os detalhes do atendimento. Modal é carregado via JavaScript -->
    <div id="visual_detalhes_reparo">

    </div>
    <div class=" mb-3 mt-3 ">
        <a href="index.php" class="btn btn-secondary ml-1" role="button" aria-pressed="true">Voltar</a>
    </div>

    <h4>Histórico</h4>

    <?php
    if (count($reparos) > 0) {
    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive border rounded-lg bg-white">
                    <table class="table table-striped table-hover table-sm tables_datatable">
                        <thead>
                            <tr>
                                <th class="pt-3 pb-3" scope="col">Id</th>

                                <th class="pt-3 pb-3" scope="col">Setor</th>
                                <th class="pt-3 pb-3" scope="col">Defeito/reparo</th>
                                <th class="pt-3 pb-3" scope="col">Observaçao</th>
                                <th class="pt-3 pb-3" scope="col">Dt. inicio reparo</th>
                                <th class="pt-3 pb-3" scope="col">Dt. fim reparo</th>
                                <th class="pt-3 pb-3" scope="col">Status</th>
                                <th class="pt-3 pb-3" scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($reparos as $r) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo  $r->getId(); ?></th>

                                    <td><?php echo $r->getBalanca()->getSetor(); ?></td>
                                    <td width=260><?php echo $r->getDescricaoDefeito(); ?></td>
                                    <td class="text-center">
                                        <div class="dica">
                                        <span class="fa fa-info-circle fa-lg text-info"></span>
                                        
                                            <div class="dicaTexto">
                                                <?php echo $r->getObservacao(); ?>
                                            </div>
                                        </div>

                                    </td>
                                    <td><?php echo inverteData($r->getDataInicioReparo()) ?></td>
                                    <td><?php echo inverteData($r->getDataFimReparo()) ?></td>
                                    <?php if ($r->getStatus()->getDescricao() == "ABERTO") { ?>
                                        <td width=150 class="text-info"><strong><?php echo $r->getStatus()->getDescricao() ?></strong></td>


                                    <?php }
                                    if ($r->getStatus()->getDescricao() == "EM ATENDIMENTO") { ?>
                                        <td width=150 class="text-primary"><strong><?php echo $r->getStatus()->getDescricao() ?></strong></td>


                                    <?php }
                                    if ($r->getStatus()->getDescricao() == "CONCLUIDO") { ?>
                                        <td width=150 class="text-success"><strong><?php echo $r->getStatus()->getDescricao() ?></strong></td>

                                    <?php } ?>


                                    <td>

                                        <button type="button" class="btn btn-outline-primary view_detalhes_reparo btn-sm" id="<?php echo $r->getId(); ?>">
                                            Detalhes
                                        </button>

                                    </td>
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
</div>


<script>
    /*
$(document).ready(function() {

  $(document).on('click','.view_detalhes_reparo', function() {
    var user_id = $(this).attr("id");
    //alert(user_id);
    //Verificar se há valor na variável "user_id" 
    
    if(user_id !== '') {
      var dados = {
        user_id: user_id
      };
      $.post('../atendimentos/modalDetalhesReparo.php', dados, function(retorna) {
        //Carregar o conteúdo para o usuário
        //alert(retorna);
        $("#visual_detalhes_reparo").html(retorna);
        $('#visualReparoModal').modal('show');

      });
    } 
  });


});
*/
</script>

<?php
$content = ob_get_clean();
echo html($content);
?>