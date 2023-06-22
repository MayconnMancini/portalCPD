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
require_once(__DIR__ . '/../../model/PrestadorServico.php');
require_once(__DIR__ . '/../../dao/DaoPrestadorServico.php');
require_once(__DIR__ . '/../../config/config.php');

 $conn = Db::getInstance();
 
 if (! $conn->connect()) {
     die();
 }
 
 $daoPrestador = new DaoPrestadorServico($conn);
 $prestadores = $daoPrestador->todos();

ob_start();

?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Prestadores de Serviço</h2>
        </div>
        <div class="row mb-2">
            <div class="col-md-6" >
                <a href="novo.php" class="btn btn-primary active" role="button" aria-pressed="true">Novo Prestador</a>
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
//    if (count($prestadores) >0) 
  //  {
?>
        <div class="row">
            <div class="col-md-12" >
            <div class="table-responsive border rounded-lg bg-white">
                <table class="table table-striped table-hover table-sm tables_datatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Razão Social</th>
                        <th scope="col">CNPJ</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
<?php 
        foreach($prestadores as $p) {
?>                    
                    <tr>
                        <th scope="row"><?php echo  $p->getId(); ?></th>
                        <td><?php echo $p->getRazaoSocial(); ?></td>
                        <td><?php echo $p->getCnpj(); ?></td>
                        <td><?php echo $p->getTelefone(); ?></td>
                        <td><?php echo $p->getEmail(); ?></td>
                        <td>
                            <a class="btn btn-secondary btn-sm active" 
                                href="editar.php?id=<?php echo $p->getId();?>">
                                Editar
                            </a> 
                            <a class="btn btn-danger btn-sm active" 
                                href="apagar.php?id=<?php echo $p->getId();?>">
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
    
 //   }  // if 
?>        
    </div>
<?php

$content = ob_get_clean();
echo html( $content );
    
?>


