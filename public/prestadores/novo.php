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

$conn = Db::getInstance();

if (!$conn->connect()) {
    die();
}

ob_start();

?>
<div class="container">
    <div class="py-5 text-center">
        <h2>Cadastro de Prestadores</h2>
    </div>
    <div class="row">
        <div class="col-md-12  border rounded-lg bg-white p-3">

            <form action="salvar.php" method="POST">

                <div class="form-group">
                    <label for="razaoSocial">Razão Social</label>
                    <input type="text" class="form-control" id="razaoSocial" name="razaoSocial" placeholder="Razão Social" required>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cnpj">CNPJ</label>
                        <input type="number" class="form-control" id="cnpj" name="cnpj" placeholder="CNPJ" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="telefone">Telefone</label>
                        <input type="number" class="form-control telefone" id="telefone" name="telefone" placeholder="Telefone" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>




                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="index.php" class="btn btn-secondary ml-1" role="button" aria-pressed="true">Cancelar</a>




            </form>
        </div>
    </div>
</div>


<?php


$content = ob_get_clean();
echo html($content);

?>