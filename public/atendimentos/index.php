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
    header("Location: ../login.php");
    exit;
}
require_once(__DIR__ . '/../../templates/template-html.php');
require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../model/Atendimento.php');
require_once(__DIR__ . '/../../dao/DaoAtendimento.php');
require_once(__DIR__ . '/../../config/config.php');

$conn = Db::getInstance();

if (!$conn->connect()) {
    die();
}

$daoAtendimento = new DaoAtendimento($conn);
$atendimentos = $daoAtendimento->todos();

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
        <h2>Atendimentos</h2>
    </div>
    <div class="row mb-2">
        <div class="col-md-6">
            <a href="novo.php" class="btn btn-primary active" role="button" aria-pressed="true">Novo Atendimento</a>
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

    <?php
    if (count($atendimentos) > 0) {
    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive border rounded-lg p-1 bg-white shadow">
                    <table class="table table-striped table-hover table-sm tables_datatable" id="table_atendimentos">
                        <thead class="pb-2">
                            <tr class="">
                                <th class="pt-3 pb-3" scope="col">Nº Atendimento</th>
                                <th class="pt-3 pb-3" scope="col">Status</th>
                                <th class="pt-3 pb-3" scope="col">Data Abertura</th>
                                <th class="pt-3 pb-3" scope="col">Data Fechamento</th>
                                <th class="pt-3 pb-3" scope="col">QTD balanças</th>
                                <th class="pt-3 pb-3 text-center" scope="col">OS</th>
                                <th class="pt-3 pb-3 text-center" scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($atendimentos as $b) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo  $b->getId(); ?></th>
                                    <?php if ($b->getStatus()->getDescricao() == "ABERTO") { ?>
                                        <td width=150 class="text-info"><strong><?php echo $b->getStatus()->getDescricao() ?></strong></td>

                                    <?php }
                                    if ($b->getStatus()->getDescricao() == "EM ATENDIMENTO") { ?>
                                        <td width=150 class="text-primary"><strong><?php echo $b->getStatus()->getDescricao() ?></strong></td>

                                    <?php }
                                    if ($b->getStatus()->getDescricao() == "CONCLUIDO") { ?>
                                        <td width=150 class="text-success"><strong><?php echo $b->getStatus()->getDescricao() ?></strong></td>

                                    <?php } ?>
                                    <td><?php echo inverteData($b->getDataInicioAtendimento()); ?></td>
                                    <td><?php echo inverteData($b->getDataFimAtendimento()); ?></td>
                                    <td>--</td>
                                    <td>--</td>

                                    <td>

                                        <button type="button" class="btn btn-secondary btn_view_detalhes_atendimento btn-sm" id="<?php echo $b->getId(); ?>" 
                                            data-toggle="tooltip" data-placement="top" title="Detalhes">
                                            <i class="fa fa-eye"></i>
                                        </button>

                                        <a class="btn btn-info btn-sm active" href="editar.php?id=<?php echo $b->getId(); ?>"
                                            data-toggle="tooltip" data-placement="top" title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm active" href="apagar.php?id=<?php echo $b->getId(); ?>"
                                            data-toggle="tooltip" data-placement="top" title="Apagar">
                                            <i class="fa fa-trash"></i>
                                        </a>
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

        <!-- bloco responsavel por exibir o modal com os detalhes do atendimento. Modal é carregado via JavaScript -->
        <div id="visual_usuario">

        </div>


    <?php

    }  // if 
    ?>
</div>


<?php

$content = ob_get_clean();
echo html($content);

?>