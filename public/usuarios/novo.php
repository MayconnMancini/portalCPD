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
require_once(__DIR__ . '/../../model/Perfil.php');
require_once(__DIR__ . '/../../dao/DaoPerfil.php');
require_once(__DIR__ . '/../../db/Db.php');
require_once(__DIR__ . '/../../config/config.php');
 
 $conn = Db::getInstance();
 
 if (! $conn->connect()) {
     die();
 }
 
 $daoPerfil = new DaoPerfil($conn);
 $perfil = $daoPerfil->todos();

ob_start();

?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Cadastro de usuários</h2>
        </div>
        <div class="row">
            <div class="col-md-12" >

                <form action="salvar.php" method="POST">

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="numBalanca"
                            name="nome" placeholder="Nome" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="matricula">Matrícula</label>
                            <input type="number" class="form-control" id="matricula" 
                                name="matricula" placeholder="Matricula" required>
                        </div>                            
                        <div class="form-group col-md-6">
                        <label for="perfil">Perfil</label>
                        <select class="form-control" id="perfil" name="perfil" required>
<?php foreach($perfil as $pf) { ?>
                            <option value="<?php echo $pf->getId() ?>">
                                <?php echo $pf->getDescricao() ?>
                            </option>
<?php } ?>
                        </select>                        
                        </div>  
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="login">Login</label>
                            <input type="text" class="form-control" id="login"
                            name="login" placeholder="Login" required>
                        </div>                            
    
                        <div class="form-group col-md-6">
                            <label for="senha">Senha</label>
                            <input type="password" class="form-control" id="senha"
                            name="senha" placeholder="Senha" required>
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


