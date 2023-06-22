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
    header("Location: ../public/login.php");
    exit;
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

require_once(__DIR__ . '/../../dao/DaoStatusBalanca.php');

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

$daoReparo = new DaoReparo($conn);
$reparo = $daoReparo->porId($_POST['user_id']);

$daoAtend = new DaoAtendimento($conn);
$atendimento = $daoAtend->porId($_POST['numAtendimento']);

$daoStatus = new DaoStatus($conn);
$status = $daoStatus->todos();

$daoBalanca = new DaoBalanca($conn);
$balancas = $daoBalanca->todos();

$daoStatusBalanca = new DaoStatusBalanca($conn);
$statusBalanca = $daoStatusBalanca->todos();


?>

<!-- modal editar reparo -->

<div id="editarReparoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="exampleModalLabrl"> Editar reparo </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="atualizar-reparo.php" method="POST">
                <div class="modal-body">
                    <div class="form-row " id="form_edt_reparo">
                        <!-- inicio primeiro row, dados do atendimento -->
                        <div class="form-group col-md-2 ">
                            <label for="idreparo" class="font-weight-bold">Nº Reparo</label>
                            <input type="number" class="form-control" id="idreparo" value="<?php echo $reparo->getId(); ?>" name="idreparo" placeholder="idreparo" readonly>
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="idatendimento" class="font-weight-bold">Nº Atendimento</label>
                            <input type="number" class="form-control" id="idatendimento" value="<?php echo $reparo->getAtendimento()->getId(); ?>" name="idatendimento" placeholder="idatendimento" readonly>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="status" class="font-weight-bold">Status do Reparo</label>
                            <select class="form-control" id="statusReparo" name="status" required>
                                <?php foreach ($status as $st) { ?>
                                    <option value="<?php echo $st->getId() ?>" <?php
                                                                                if ($st->getId() == $reparo->getStatus()->getId())
                                                                                    echo 'selected';
                                                                                ?>>
                                        <?php echo $st->getDescricao() ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <!--
                        <div class="form-group col-md-3">
                            <label for="dataInicioReparo">Data do início do reparo</label>
                            <input type="date" class="form-control" id="dataInicioReparo" value="<?php echo $reparo->getDataInicioReparo(); ?>" name="dataInicioReparo" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dataFimReparo">Data do Fim do reparo</label>
                            <input type="date" class="form-control" id="dataFimReparo" value="<?php echo $reparo->getDataFimReparo(); ?>" name="dataFimReparo">
                        </div>
                        -->
                        <div class="form-group col-md-3">
                            <label for="dataInicioReparo" class="font-weight-bold">Data do início do atendimento</label>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button tabindex="-1" type="button" class="btn btn-default border">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                                <input type="text" class="form-control datas" id="dataInicioReparo" value="<?php echo inverteData($reparo->getDataInicioReparo()) ?>" name="dataInicioReparo" placeholder="dd/mm/aaaa" required>
                            </div>
                        </div>

                        <input type="hidden" id="dataTempReparo" disabled value="<?php echo inverteData($reparo->getDataFimReparo()) ?>">
                        <input type="hidden" id="dataTempInicioAtendimento" disabled value="<?php echo inverteData($atendimento->getDataInicioAtendimento()) ?>">
                        

                        <?php if ($reparo->getDataFimReparo()) { ?>
                            <div class="form-group col-md-3" id="div_dtFimRep">
                                <label for="dataFimReparo" class="font-weight-bold">Data do Fim do atendimento</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button tabindex="-1" type="button" class="btn btn-default border">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>

                                    <input type="text" class="form-control datas" id="dataFimReparo" required name="dataFimReparo" value="<?php echo inverteData($reparo->getDataFimReparo()) ?>" placeholder="dd/mm/aaaa">
                                </div>

                            </div>

                        <?php } ?>

                    </div><!-- Fim primeiro row, dados do atendimento -->

                    <div class="form-row">
                        <!-- Inicio Segundo row, dados do atendimento -->
                        <div class="form-group col-md-2">
                            <label for="idBalanca" class="font-weight-bold">Nº Balança</label>
                            <select class="form-control" id="idBalanca" name="idBalanca" required>
                                <?php foreach ($balancas as $b) { ?>
                                    <option value="<?php echo $b->getId() ?>" <?php
                                                                                if ($b->getNumBalanca() == $reparo->getBalanca()->getNumBalanca())
                                                                                    echo 'selected';
                                                                                ?>>
                                        <?php echo $b->getNumBalanca() ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6 form-floating">
                            <label for="descDefeito" class="font-weight-bold">Descrição do defeito</label>
                            <textarea class="form-control" id="descDefeito" name="descDefeito"><?php echo $reparo->getDescricaoDefeito() ?></textarea>
                        </div>
                        <div class="form-group col-md-4 form-floating">
                            <label for="observacao" class="font-weight-bold">Observação</label>
                            <textarea class="form-control" id="observacao" name="observacao"><?php echo $reparo->getObservacao() ?></textarea>
                        </div>
                    </div><!-- Fim segundo row, dados do atendimento -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
                    <input type="submit" name="edtReparo" id="edtReparo" value="Salvar" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</div>