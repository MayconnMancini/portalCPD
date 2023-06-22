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
require_once(__DIR__ . '/../../config/config.php');

$conn = Db::getInstance();

if (!$conn->connect()) {
    die();
}

$daoBalanca = new DaoBalanca($conn);
$balancas = $daoBalanca->todos();

ob_start();

?>
<!-- Content Header (Page header) -->
<div class="content-header pl-0 pr-0">
    <div class="container-fluid pl-0 pr-0">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Balanças</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Balança</a></li>
                    <li class="breadcrumb-item active">Balanças</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!--<div class="container">-->
    <div class="row mb-2">
        <div class="col-md-6">
            <a href="novo.php" class="btn btn-primary active" role="button" aria-pressed="true">Nova Balança</a>

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
    if (count($balancas) > 0) {
    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive border rounded-lg bg-white">
                    <table class="table table-striped table-hover table-sm tables_datatable">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nº Balança</th>
                                <th scope="col">Nº PIP</th>
                                <th scope="col">Setor</th>
                                <th scope="col">Nº Série</th>
                                <th scope="col">Local Atual</th>
                                <th scope="col">Status</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($balancas as $b) {
                            ?>
                                <tr>
                                    <th scope="row"><?php echo  $b->getId(); ?></th>
                                    <td><?php echo $b->getNumBalanca(); ?></td>
                                    <td><?php echo $b->getPip(); ?></td>
                                    <td><?php echo $b->getSetor(); ?></td>
                                    <td><?php echo $b->getNumSerie(); ?></td>
                                    <td><?php echo $b->getLocalAtual(); ?></td>
                                    <td><?php echo $b->getStatusBalanca()->getDescricao(); ?></td>
                                    <td>

                                        <a class="btn btn-outline-primary btn-sm active" href="historico.php?id=<?php echo $b->getId(); ?>">
                                            Histórico
                                        </a>
                                        <a class="btn btn-secondary btn-sm active" href="editar.php?id=<?php echo $b->getId(); ?>">
                                            Editar
                                        </a>
                                        <a class="btn btn-danger btn-sm active" href="apagar.php?id=<?php echo $b->getId(); ?>">
                                            Apagar
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
    <?php

    }  // if 
    ?>
<!--</div>-->
<?php

$content = ob_get_clean();
echo html($content);

?>