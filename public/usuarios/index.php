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

 $conn = Db::getInstance();
 
 if (! $conn->connect()) {
     die();
 }
 
 $daoUsuario = new DaoUsuario($conn);
 $usuarios = $daoUsuario->todos();

ob_start();

?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Usuários</h2>
        </div>
        <div class="row mb-2">
            <div class="col-md-6" >
                <a href="novo.php" class="btn btn-primary active" role="button" aria-pressed="true">Novo Usuário</a>
            </div>
            <div class="col-md-6" >
                <?php
                    if(isset($_SESSION['msg'])){
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }
                ?>
            </div>
        </div>

<?php 
    if (count($usuarios) >0) 
    {
?>
        <div class="row">
            <div class="col-md-12" >
            <div class="table-responsive border rounded-lg bg-white">
                <table class="table table-striped table-hover table-sm tables_datatable">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Matricula</th>
                        <th scope="col">Login</th>
                        <th scope="col">Perfil</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
<?php 
        foreach($usuarios as $b) 
       {
?>                    
                   <tr>
                        <th scope="row"><?php echo  $b->getId(); ?></th>
                        <td><?php echo $b->getNome(); ?></td>
                        <td><?php echo $b->getMatricula(); ?></td>
                        
                        <td><?php echo $b->getLogin(); ?></td>
                        <td><?php echo $b->getPerfil()->getDescricao(); ?></td>
                        <td>
                            
                        
                            <a class="btn btn-secondary btn-sm active" 
                                href="editar.php?id=<?php echo $b->getId();?>">
                                Editar
                            </a>
                            <a class="btn btn-danger btn-sm active" 
                                href="apagar.php?id=<?php echo $b->getId();?>">
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
    </div>
<?php

$content = ob_get_clean();
echo html( $content );
    
?>


