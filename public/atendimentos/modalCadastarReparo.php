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



$daoAtendimento = new DaoAtendimento($conn);
$atendimento = $daoAtendimento->porId($_POST['user_id']);

$daoStatus = new DaoStatus($conn);
$status = $daoStatus->todos();

$daoBalanca = new DaoBalanca($conn);
$balancas = $daoBalanca->todos();

$daoStatusBalanca = new DaoStatusBalanca($conn);
$statusBalanca = $daoStatusBalanca->todos();



?>


<?php if ($atendimento->getStatus()->getId() == 3) { ?>

    <div id="cadastrarReparoModal" class="modal fade modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabrl"> Erro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="container">

                        <div class="row">
                            <div class="col-md-12">
                                <h5>ERRO!</h5>
                                <h6>Atendimento com status CONCLUÍDO! Para adicionar outro reparo, favor mudar o status para EM ATENDIMENTO!</h6>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
                </div>

            </div>
        </div>
    </div>

<?php } else { ?>


    <!-- modal cadastrar reparo -->

    <div class="modal fade" id="cadastrarReparoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="cadastrarReparoModal">Cadastrar Reparo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="salvar-reparo.php" method="POST">
                    <div class="modal-body">

                        <!-- inicio 2 row -->

                        <input type="hidden" name="atendimento" value="<?php echo $atendimento->getId(); ?>">


                        <div class=" ">
                            <div class="form-row" id="formDadosReparo">

                                <div class="form-group col-lg-2">
                                    <label for="balanca" class="font-weight-bold">Balança</label>
                                    <select class="form-control" id="idbalanca" name="idbalanca" required>
                                        <option value="" disabled selected>Selecione</option>>
                                        <?php foreach ($balancas as $b) { ?>
                                            <option value="<?php echo $b->getId() ?>">
                                                <?php echo $b->getNumBalanca() ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!--
                                        <div class="form-group col-md-4">
                                            <label for="dataInicioReparo">Data do início do reparo</label>
                                            <input type="date" class="form-control" id="dataInicioReparo" name="dataInicioReparo" placeholder="Número" required>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="dataFimReparo">Data do fim do reparo</label>
                                            <input type="date" class="form-control" id="dataFimReparo" name="dataFimReparo" placeholder="Número">
                                        </div>  
                                        -->

                                <div class="form-group col-lg-3">
                                    <label for="usuario" class="font-weight-bold">Status do Reparo</label>
                                    <select class="form-control" id="statusReparoCad" name="status" required>
                                        <?php foreach ($status as $st) { ?>
                                            <option value="<?php echo $st->getId() ?>">
                                                <?php echo $st->getDescricao() ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="dataInicioReparo" class="font-weight-bold">Data do início do Reparo</label>
                                    <div class="input-group form_datetime">
                                        <span class="input-group-btn">
                                            <button tabindex="-1" type="button" class="btn btn-default border">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        </span>
                                        <input type="text" class="form-control datas" id="dataInicioReparo" name="dataInicioReparo" placeholder="dd/mm/aaaa" required>
                                    </div>
                                </div>

                                <input type="hidden" id="dataTempInicioAtendimento" disabled value="<?php echo inverteData($atendimento->getDataInicioAtendimento()) ?>">

                                <?php if ($atendimento->getDataFimAtendimento()) { ?>
                                    <div class="form-group col-lg-3" id="div_dtFimRepCad">
                                        <label for="dataFimReparo" class="font-weight-bold">Data do Fim do atendimento</label>
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button tabindex="-1" type="button" class="btn btn-default border">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                            <input type="text" class="form-control datas" id="dataFimReparo" name="dataFimReparo" placeholder="dd/mm/aaaa">
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>

                            <div class="form-row">

                                <div class="form-group col-lg-8 form-floating">
                                    <label for="descDefeito" class="font-weight-bold">Descrição do defeito</label>
                                    <textarea class="form-control" id="descDefeito" name="descDefeito" required></textarea>
                                </div>

                                <div class="form-group col-lg-4 form-floating">
                                    <label for="observacao" class="font-weight-bold">Observação</label>
                                    <textarea class="form-control" id="observacao" name="observacao"></textarea>
                                </div>

                            </div>

                        </div>


                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <input type="submit" name="cadReparo" id="cadReparo" value="Salvar" class="btn btn-success" onclick="return validarNovoReparo()">
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<script>
    function validarNovoReparo() {
        var balanca = document.getElementById("idbalanca");

        if (balanca.value == 0) {
            alert("ERRO! Selecione uma BALANÇA");
            // Deixa o input com o focus
            balanca.focus();
            // retorna a função e não olha as outras linhas
            return false;
        }
        if (balanca.value > 0) {
            //form-novoatendimento.submit();
            return true;
        }
        return false;
    }
</script>