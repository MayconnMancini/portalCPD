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
require_once(__DIR__ . "/../../templates/template-html.php");
require_once(__DIR__ . "/../../db/Db.php");
require_once(__DIR__ . "/../../model/Reparo.php");
require_once(__DIR__ . "/../../model/Balanca.php");
require_once(__DIR__ . '/../../dao/DaoReparo.php');
require_once(__DIR__ . "/../../config/config.php");

$conn = Db::getInstance();

if (!$conn->connect()) {
    echo ("ERRO CONEXAO COM O BANCO");
    die();
}

$daoReparo = new DaoReparo($conn);
$reparos = $daoReparo->todos();

function inverteData($data)
{
    if (count(explode("/", $data)) > 1) {
        return implode("-", array_reverse(explode("/", $data)));
    } elseif (count(explode("-", $data)) > 1) {
        return implode("/", array_reverse(explode("-", $data)));
    }
}

ob_start();

?>
<div class="container ">
    <div class="py-5 text-center">
        <h2>Reparos</h2>
    </div>
    <div class="row mb-2">

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
    <div class="mt-4">
        <h3>Lista de todos reparos</h3>
    </div>

    <?php
    if (count($reparos) > 0) {
    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive border rounded-lg bg-white p-2 mt-2">
                    <table class="table table-striped table-hover table-sm tables_datatable " id="table_reparos">
                        <thead>
                            <tr>
                                <th class="pt-3 pb-3" scope="col">Id</th>
                                <th class="pt-3 pb-3" scope="col">Status</th>
                                <th class="pt-3 pb-3" scope="col">Nº Balança</th>
                                <th class="pt-3 pb-3" scope="col">Setor</th>
                                <th class="pt-3 pb-3" scope="col">Defeito</th>
                                <th class="pt-3 pb-3" scope="col">Dt. inicio reparo</th>
                                <th class="pt-3 pb-3" scope="col">Dt. fim reparo</th>

                                <th class="pt-3 pb-3" scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($reparos as $r) {
                            ?>

                                <tr class="">
                                    <th scope="row"><?php echo  $r->getId(); ?></th>
                                    <?php if ($r->getStatus()->getDescricao() == "ABERTO") { ?>
                                        <td class="text-info"><strong><?php echo $r->getStatus()->getDescricao() ?></strong></td>


                                    <?php }
                                    if ($r->getStatus()->getDescricao() == "EM ATENDIMENTO") { ?>
                                        <td class="text-primary"><strong><?php echo $r->getStatus()->getDescricao() ?></strong></td>


                                    <?php }
                                    if ($r->getStatus()->getDescricao() == "CONCLUIDO") { ?>
                                        <td class="text-success"><strong><?php echo $r->getStatus()->getDescricao() ?></strong></td>

                                    <?php } ?>
                                    <td><?php echo $r->getBalanca()->getNumBalanca(); ?></td>
                                    <td><?php echo $r->getBalanca()->getSetor(); ?></td>
                                    <td width="260"><?php echo $r->getDescricaoDefeito(); ?></td>

                                    <td><?php echo inverteData($r->getDataInicioReparo()) ?></td>
                                    <td><?php echo inverteData($r->getDataFimReparo()) ?></td>


                                    <td>
                                        <div class="">
                                            <button type="button" title="Detalhes" class="btn btn-outline-primary view_detalhes_reparo btn-sm" id="<?php echo $r->getId(); ?>">
                                                <span><span class="fa fa-info-circle"></span></span>
                                            </button>
                                            <!--
                            <a class="btn btn-secondary btn-sm active" 
                                href="../atendimentos/editar.php?id=<?php echo $r->getAtendimento()->getId() ?>">
                                Editar
                            </a>
       -->
                                            <a href="../atendimentos/editar.php?id=<?php echo $r->getAtendimento()->getId() ?>" title="Editar" class="btn btn-outline-secondary btn-sm">
                                                <span><span class="fa fa-edit"></span></span>
                                            </a>
                                        </div>



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
    
</script>





<?php

$content = ob_get_clean();
echo html($content);

?>