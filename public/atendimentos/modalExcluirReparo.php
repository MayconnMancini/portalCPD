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
require_once(__DIR__ . '/../../model/Reparo.php');
require_once(__DIR__ . '/../../dao/DaoReparo.php');
require_once(__DIR__ . '/../../config/config.php');

$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

$daoReparo = new DaoReparo($conn);

// Se não for confirmação, exibo a confirmação
$reparo = $daoReparo->porId( $_POST['user_id'] );
if (! $reparo )
    echo("ERRO NO REPARO");
    //header('Location: ./index.php');
?>

<!-- modal editar reparo -->

<div id="excluirReparoModal" class="modal fade"  tabindex="-1" role="dialog"
   aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="exampleModalLabrl"> Excluir reparo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="apagar-reparo.php" method="POST">
            <div class="modal-body">
            <div class="container">
                
                <div class="row">
                    <div class="col-md-12" >
                      <form action="apagar.php" class="card p-2 my-4" method="POST">
                        <input type="hidden" name="id" 
                          value="<?php echo $reparo->getId(); ?>"
                        >
                        <div class="form-group">
                          <label for="atendimento">Deseja realmente apagar o reparo abaixo?</label>
                          <input type="text" class="form-control" id="atendimento" aria-describedby="help" 
                            value="<?php echo $reparo->getId();?>" 
                            readonly
                          >
                          <small id="help" class="form-text text-muted">Esta operação não poderá ser desfeita.</small>
                        </div>
                    
                    </div>
                </div>
            </div>
                   
            </div>
                
            <div class="modal-footer">
                <input type="submit" class="btn btn-danger ml-1" value="Apagar" name="confirmacao"/>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
            </div>
            </form> 
        </div>
    </div>
</div>
