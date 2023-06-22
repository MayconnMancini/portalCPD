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
require_once(__DIR__ . '/../../model/StatusBalanca.php');
require_once(__DIR__ . '/../../dao/DaoStatusBalanca.php');
require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../config/config.php');
 
 $conn = Db::getInstance();
 
 if (! $conn->connect()) {
     die();
 }
 
 $daoStatusBalanca = new DaoStatusBalanca($conn);
 $statusBalancas = $daoStatusBalanca->todos();

ob_start();

?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Cadastro de Balanças</h2>
        </div>
        <div class="row">
            <div class="col-md-12" >

                <form action="salvar.php" method="POST">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nome">Nº interno Balança</label>
                            <input type="number" class="form-control" id="numBalanca"
                                name="numBalanca" placeholder="Número" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nome">Nº do PIP</label>
                            <input type="number" class="form-control" id="pip"
                                name="pip" placeholder="PIP" required>
                        </div>
                    </div>
                    

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="setor">Setor</label>
                            <input type="text" class="form-control" id="setor" 
                                name="setor" placeholder="Setor" required>
                        </div>                            
                        <div class="form-group col-md-6">
                            <label for="numSerie">Nº de Série</label>
                            <input type="number" class="form-control" id="numSerie" 
                                name="numSerie" placeholder="Nº de Série" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="localAtual">Local atual</label>
                            <input type="text" class="form-control" id="localAtual"
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
<?php foreach($statusBalancas as $status) { ?>
                            <option value="<?php echo $status->getId() ?>">
                                <?php echo $status->getDescricao() ?>
                            </option>
<?php } ?>
                        </select>                        
                    </div>    
                            

                    </div> 
                                                    
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="index.php" class="btn btn-secondary ml-1" role="button" aria-pressed="true">Cancelar</a>

                </form> 
            </div>
        </div>
    </div>
<?php

$content = ob_get_clean();
echo html( $content );
    
?>


