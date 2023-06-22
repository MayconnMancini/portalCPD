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
require_once(__DIR__ . '/../../model/Balanca.php');
require_once(__DIR__ . '/../../dao/DaoBalanca.php');
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../dao/DaoStatusBalanca.php');

$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

$daoBalanca = new DaoBalanca($conn);
$balanca = $daoBalanca->porId( $_GET['id'] );

$daoStatusBalanca = new DaoStatusBalanca($conn);
$status = $daoStatusBalanca->todos();
    
if (! $balanca )
    header('Location: ./index.php');

else {  
    ob_start();

?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Editar balança</h2>
        </div>
        <div class="row">
            <div class="col-md-12 border rounded-lg bg-white p-3" >

              <form action="atualizar.php" method="POST">

                      <input type="hidden" name="id" 
                          value="<?php echo $balanca->getId(); ?>">

                    <div class="form-row ">
                        <div class="form-group col-md-6">
                            <label for="nome">Nº interno Balança</label>
                            <input type="number" class="form-control" id="numBalanca"
                                value="<?php echo $balanca->getNumBalanca(); ?>"
                                name="numBalanca" placeholder="Número" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pip">Nº do PIP</label>
                            <input type="number" class="form-control" id="pip"
                                value="<?php echo $balanca->getPip(); ?>"
                                name="pip" placeholder="PIP">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="setor">Setor</label>
                            <input type="text" class="form-control" id="setor"
                                value="<?php echo $balanca->getSetor(); ?>"
                                name="setor" placeholder="Setor" required>
                        </div>                            
                        <div class="form-group col-md-6">
                            <label for="numSerie">Nº de Série</label>
                            <input type="number" class="form-control" id="numSerie"
                                value="<?php echo $balanca->getNumSerie(); ?>"
                                name="numSerie" placeholder="Nº de Série" required>
                                
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="localAtual">Local atual</label>
                            <input type="text" class="form-control" id="localAtual"
                            value="<?php echo $balanca->getLocalAtual(); ?>"
                            name="localAtual" placeholder="Local" required>
                        </div>                            
                       <!-- <div class="form-group col-md-6">
                            <label for="dataNascimento">Perfil</label>
                            <input type="text" class="form-control" id="dataNascimento"
                            name="dataNascimento" placeholder="xx/xx/xxxx" required>
                        </div> -->
                        <div class="form-group col-md-6">
                        <label for="statusBalanca">Status</label>
                        <select class="form-control" id="statusBalanca" name="statusBalanca" required>
<?php foreach($status as $st) { ?>
                            <option value="<?php echo $st->getId() ?>"
                                <?php 
                                    if ($st->getId() == $balanca->getStatusBalanca()->getId()) 
                                        echo 'selected'; 
                                ?>
                            >
                                <?php echo $st->getDescricao() ?>
                            </option>
<?php } ?>
                        </select>                          
                    </div>    
                            

                    </div>

                    <button type="submit" class="btn btn-primary">Atualizar</button>
                    <a href="index.php" class="btn btn-secondary ml-1" role="button" aria-pressed="true">Cancelar</a>
                  </div>

              </form>
              

            </div>
        </div>
    </div>
<?php

    $content = ob_get_clean();
    echo html( $content );
} // else-if

?>
