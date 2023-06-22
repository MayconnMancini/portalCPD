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
require_once(__DIR__ . '/../../config/config.php');

require_once(__DIR__ . '/../../model/Atendimento.php');
require_once(__DIR__ . '/../../dao/DaoAtendimento.php');

require_once(__DIR__ . '/../../model/Reparo.php');
require_once(__DIR__ . '/../../dao/DaoReparo.php');

require_once(__DIR__ . '/../../model/Usuario.php');
require_once(__DIR__ . '/../../dao/DaoUsuario.php');
require_once(__DIR__ . '/../../model/PrestadorServico.php');
require_once(__DIR__ . '/../../dao/DaoPrestadorServico.php');

require_once(__DIR__ . '/../../model/Status.php');
require_once(__DIR__ . '/../../dao/DaoStatus.php');

require_once(__DIR__ . '/../../model/Balanca.php');
require_once(__DIR__ . '/../../dao/DaoBalanca.php');



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

$daoAtendimento = new DaoAtendimento($conn);
$atendimento = $daoAtendimento->porId($_GET['id']);

$daoReparo = new DaoReparo($conn);
$reparos = $daoReparo->reparosPorAtendimento($atendimento->getId());

$daoStatus = new DaoStatus($conn);
$status = $daoStatus->todos();

$daoUsuario = new DaoUsuario($conn);
$usuarios = $daoUsuario->todos();

$daoPrestador = new DaoPrestadorServico($conn);
$prestadores = $daoPrestador->todos();

$daoBalanca = new DaoBalanca($conn);
$balancas = $daoBalanca->todos();

if (!$atendimento) {
    echo ("ID do atemdimento não encontrado no Banco de dados");
    header('Location: ../index.php');
} else {
    ob_start();

?>
    <style>
        /*
    .datepicker { 
 
     z-index: 999999 !important; /* has to be larger than 1050 
        
    } */
        .datepicker {
            z-index: 999999 !important;
        }
    </style>
    <div class="container">
        <div class="py-4 text-center">
            <h2>Edição de Atendimentos</h2>
        </div>
        <div class="row">
            <div class="col-md-12">

                <!-- ########## Esse bloco é responsavel pelos dados do atendimento ################## -->

                <form action="atualizar.php" method="POST" id="form-atendimento" name="form-atendimento">
                    <!-- inicio form principal -->

                    <input type="hidden" name="id" value="<?php echo $atendimento->getId(); ?>">

                    <div class=" border rounded-lg bg-white p-3 mb-3 ">
                        <div class="form-row" id="formDadosAtendimento">
                            <div class="form-group col-md-2">
                                <label for="setor" class="font-weight-bold">Nº Atendimento</label>
                                <input type="number" class="form-control" id="numAtendimento" value="<?php echo $atendimento->getId(); ?>" name="numAtendimento" placeholder="numAtendimento" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="usuario" class="font-weight-bold">STATUS</label>
                                <select class="form-control" id="statusAtendimento" name="status" required>
                                    <?php foreach ($status as $st) { ?>
                                        <option value="<?php echo $st->getId() ?>" <?php
                                                                                    if ($st->getId() == $atendimento->getStatus()->getId())
                                                                                        echo 'selected';
                                                                                    ?>>
                                            <?php echo $st->getDescricao() ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <!--
                            <div class="form-group col-md-3">
                                <label for="dataInicioAtendimento">Data do início do atendimento</label>
                                <input type="date" class="form-control" id="dataInicioAtendimento" value="<?php echo $atendimento->getDataInicioAtendimento(); ?>" name="dataInicioAtendimento" placeholder="Número" required>
                            </div> 
                            -->
                            <div class="form-group col-md-3">
                                <label for="dataInicioAtendimento" class="font-weight-bold">Data do início do atendimento</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button tabindex="-1" type="button" class="btn btn-default border">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                    <input type="text" class="form-control datas" id="dataInicioAtendimento" value="<?php echo inverteData($atendimento->getDataInicioAtendimento()) ?>" name="dataInicioAtendimento" placeholder="dd/mm/aaaa" required>

                                </div>
                            </div>


                            <input type="hidden" id="dataTemp" value="<?php echo inverteData($atendimento->getDataFimAtendimento()) ?>">

                            <?php if ($atendimento->getDataFimAtendimento()) { ?>
                                <div class="form-group col-md-3" id="div_dtFimAtd">
                                    <label for="dataFimAtendimento" class="font-weight-bold">Data do Fim do atendimento</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button tabindex="-1" type="button" class="btn btn-default border">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>

                                        <input type="text" class="form-control datas" id="dataFimAtendimento" name="dataFimAtendimento" value="<?php echo inverteData($atendimento->getDataFimAtendimento()) ?>" placeholder="dd/mm/aaaa">
                                    </div>

                                </div>

                            <?php } ?>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['iduser'] ?>">
                                <label for="usuario" class="font-weight-bold">Usuário</label>
                                <input type="text" class="form-control" id="usuarioNome" value="<?php echo $_SESSION['nome'] ?>" readonly>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="prestadorServico" class="font-weight-bold">Prestador de Serviço</label>
                                <select class="form-control" id="prestadorServico" name="prestadorServico" required>
                                    <?php foreach ($prestadores as $pres) { ?>
                                        <option value="<?php echo $pres->getId() ?>" <?php
                                                                                        if ($pres->getId() == $atendimento->getPrestadorServico()->getId())
                                                                                            echo 'selected';
                                                                                        ?>>
                                            <?php echo $pres->getRazaoSocial() ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">

                                <input type="submit" name="" class="btn btn-primary" onclick="return validar()" value="Salvar">
                                <a href="index.php" class="btn btn-secondary ml-1" role="button" aria-pressed="true">Sair</a>
                            </div>
                        </div>


                    </div>
                    <!--fim primeiro row, dados do atendimento -->


                </form><!-- fim form principal -->

                <!-- ########## FIM do bloco responsavel pelos dados do atendimento ################## -->



                <!-- ########## INICIO do bloco responsavel pelo modal de cadastro de reparos ################## -->

                <!-- Botão para acionar modal -->

                <div class="form-row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-success btn_view_cadastrar_reparo " id="<?php echo $atendimento->getId(); ?>">
                            <i class="fa fa-plus"> </i> Cadastrar reparo
                        </button>
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

                <!-- ########## FIM do bloco responsavel pelo modal de cadastro de reparos ################## -->

                <!-- bloco responsavel por exibir o modal cadastr de reparo. Modal é carregado via JavaScript -->
                <div id="visual_cad_reparo">

                </div>

                <!-- bloco responsavel por exibir o modal com os detalhes do atendimento. Modal é carregado via JavaScript -->
                <div id="visual_edt_reparo">

                </div>
                <!-- bloco responsavel por exibir o modal com os detalhes do atendimento. Modal é carregado via JavaScript -->
                <div id="visual_excl_reparo">

                </div>
                <!-- bloco responsavel por exibir o modal com os detalhes do atendimento. Modal é carregado via JavaScript -->
                <div id="visual_detalhes_reparo">

                </div>

                <div class="mt-4">
                    <h3>Lista de balanças para reparo</h3>
                </div>

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
                                            <th class="pt-3 pb-3" scope="col">Nº Balança</th>
                                            <th class="pt-3 pb-3" scope="col">Setor</th>
                                            <th class="pt-3 pb-3" scope="col">Defeito</th>
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

                                            <tr class="">
                                                <th scope="row"><?php echo  $r->getId(); ?></th>
                                                <td><?php echo $r->getBalanca()->getNumBalanca(); ?></td>
                                                <td><?php echo $r->getBalanca()->getSetor(); ?></td>
                                                <td width=260 class="text-break"><?php echo $r->getDescricaoDefeito(); ?></td>

                                                <td><?php echo inverteData($r->getDataInicioReparo()) ?></td>
                                                <td><?php echo inverteData($r->getDataFimReparo()) ?></td>
                                                <?php if ($r->getStatus()->getDescricao() == "ABERTO") { ?>
                                                    <td width="150" class="text-info"><strong><?php echo $r->getStatus()->getDescricao() ?></strong></td>


                                                <?php }
                                                if ($r->getStatus()->getDescricao() == "EM ATENDIMENTO") { ?>
                                                    <td width="150" class="text-primary"><strong><?php echo $r->getStatus()->getDescricao() ?></strong></td>


                                                <?php }
                                                if ($r->getStatus()->getDescricao() == "CONCLUIDO") { ?>
                                                    <td width="150" class="text-success"><strong><?php echo $r->getStatus()->getDescricao() ?></strong></td>

                                                <?php } ?>


                                                <td width="200">

                                                    <button type="button" class="btn btn-outline-primary view_detalhes_reparo btn-sm" id="<?php echo $r->getId(); ?>">
                                                        Detalhes
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary view_editar_reparo btn-sm" id="<?php echo $r->getId(); ?>">
                                                        Editar
                                                    </button>
                                                    <button type="button" class="btn btn-danger view_excluir_reparo btn-sm" id="<?php echo $r->getId(); ?>">
                                                        Apagar
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
        </div>
    </div>

    <script>

    </script>



<?php

    $content = ob_get_clean();
    echo html($content);
} // else-if

?>