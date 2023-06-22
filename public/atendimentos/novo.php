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
require_once(__DIR__ . '/../../model/Usuario.php');
require_once(__DIR__ . '/../../dao/DaoUsuario.php');
require_once(__DIR__ . '/../../model/PrestadorServico.php');
require_once(__DIR__ . '/../../dao/DaoPrestadorServico.php');
require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../config/config.php');

$conn = Db::getInstance();

if (!$conn->connect()) {
    die();
}

$daoUsuario = new DaoUsuario($conn);
$usuarios = $daoUsuario->todos();

$daoPrestadorServico = new DaoPrestadorServico($conn);
$prestadores = $daoPrestadorServico->todos();

ob_start();

?>
<div class="container">
    <div class="py-5 text-center">
        <h2>Cadastro de Atendimentos</h2>
    </div>
    <div class="row">
        <div class="col-md-12">

            <form action="salvar.php" name="form_novo_atendimento" method="POST">

                <div class="form-row border rounded-lg bg-white p-3 mb-3" id="form-novoatendimento">

                    <div class="form-group col-md-4">
                        <label for="dataInicioAtendimento" class="font-weight-bold">Data do início do atendimento</label>
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button tabindex="-1" type="button" class="btn btn-default border">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>
                            <input type="text" class="form-control datas" id="c" name="dataInicioAtendimento" placeholder="dd/mm/aaaa" required>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="prestadorServico" class="font-weight-bold">Prestador de Serviço</label>
                        <select class="form-control" id="prestadorServico" name="prestadorServico" required>
                        <option value="" disabled selected>Selecione</option>
                            <?php foreach ($prestadores as $pres) { ?>
                                <option value="<?php echo $pres->getId() ?>">
                                    <?php echo $pres->getRazaoSocial() ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <input type="hidden" name="usuario" id="usuario" value="<?php echo $_SESSION['iduser'] ?>">
                        <label for="usuario" class="font-weight-bold">Usuário</label>
                        <input type="text" class="form-control" id="usuario" value="<?php echo $_SESSION['nome'] ?>" readonly>
                    </div>
                    <input type="submit" class="btn btn-primary" onclick="return validarNovo()" value="Salvar e continuar">
                    <a href="index.php" class="btn btn-secondary ml-1" role="button" aria-pressed="true">Cancelar</a>

                </div>

            </form>
        </div>
    </div>
</div>
<script>
    function validarNovo() {
        var prestador = document.getElementById("prestadorServico");
        
        if (prestador.value == 0) {
            alert("ERRO! Selecione um prestador");
            // Deixa o input com o focus
            prestador.focus();
            // retorna a função e não olha as outras linhas
            return false;
        }
        
        return true;
    }
</script>

<?php

$content = ob_get_clean();
echo html($content);

?>