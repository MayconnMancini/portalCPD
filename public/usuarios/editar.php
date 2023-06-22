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
require_once(__DIR__ . '/../../model/Usuario.php');
require_once(__DIR__ . '/../../dao/DaoUsuario.php');
require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../../dao/DaoPerfil.php');

$conn = Db::getInstance();

if (! $conn->connect()) {
    die();
}

$daoUsuario = new DaoUsuario($conn);
$usuario = $daoUsuario->porId( $_GET['id'] );

$daoPerfil = new DaoPerfil($conn);
$perfil = $daoPerfil->todos();
    
if (! $usuario )
    header('Location: ./index.php');

else {  
    ob_start();

?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Editar de Usuario</h2>
        </div>
        <div class="row">
            <div class="col-md-12 border rounded-lg bg-white p-3" >

                <form action="atualizar.php" method="POST">

                    <input type="hidden" name="id" 
                    value="<?php echo $usuario->getId(); ?>">

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome"
                            value="<?php echo $usuario->getNome(); ?>"
                            name="nome" placeholder="nome" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="matricula">Matrícula</label>
                            <input type="number" class="form-control" id="matricula"
                                value="<?php echo $usuario->getMatricula(); ?>"
                                name="matricula" placeholder="matricula" required>
                        </div> 

                        <div class="form-group col-md-6">
                            <label for="perfil">Perfil</label>
                            <select class="form-control" id="perfil" name="perfil" required>
                            <?php foreach($perfil as $st) { ?>
                                <option value="<?php echo $st->getId() ?>"
                                    <?php 
                                        if ($st->getId() == $usuario->getPerfil()->getId()) 
                                            echo 'selected'; 
                                    ?>
                                >
                                    <?php echo $st->getDescricao() ?>
                                </option>
                            <?php } ?>
                            </select>                          
                        </div>
                        
                    </div>
                    <?php if($_SESSION['iduser'] == $usuario->getId() OR $_SESSION['login'] == 'admin')   { ?>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="login">Login</label>
                            <input type="text" class="form-control" id="login"
                            value="<?php echo $usuario->getLogin(); ?>"
                            name="login" placeholder="login" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="senha">Senha</label>
                            <input type="password" class="form-control" id="senha"
                            name="senha" placeholder="senha">
                        </div>                            
                    </div>
                    <?php } ?>
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
