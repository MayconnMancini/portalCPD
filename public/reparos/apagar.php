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
    header("Location: ../login.php?login=errosessao"); exit;
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

// Se for confirmação, apago o registro e redireciono para o index.php
if (isset($_POST['id']) && isset($_POST['confirmacao'])) {
  $reparo = $daoReparo->porId( $_POST['id'] );
  // Apagar registros em balanca_departamento:
  $idatendimento = $reparo->getAtendimento()->getId();
  if ($daoReparo->remover( $reparo )) {
    $_SESSION['msg'] = "<div class='alert alert-success m-0 p-2 text-center'> Reparo excluído com sucesso! </div>";
    //header("Location: ./editar.php?id=$idatendimento");
    header("Location: ../atendimentos/editar.php?id=$idatendimento");
  }
  else {
    $_SESSION['msg'] = "<div class='alert alert-danger m-0 p-2 text-center'> Erro ao excluir reparo!</div>";
    header("Location: ../atendimentos/editar.php?id=$idatendimento");
   
  }
  exit;  // Termino a execucação desse script
}

// Se não for confirmação, exibo a confirmação
$reparo = $daoReparo->porId( $_GET['id'] );
if (! $reparo )
    echo("ERRO NO REPARO");
    //header('Location: ./index.php');
else {  
    ob_start();
?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Apagar reparo</h2>
        </div>
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
                <div class="form-row">
                  <input type="submit" class="btn btn-danger ml-1" value="Apagar" name="confirmacao"/>
                  
                  <a href="../atendimentos/index.php" class="btn btn-secondary ml-1" role="button" aria-pressed="true">Cancelar</a>
                </div>
              </form>

            </div>
        </div>
    </div>
<?php
    $content = ob_get_clean();
    echo html( $content );
} // else-if
