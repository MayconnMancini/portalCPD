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
$prestador = $daoPrestador->porId( $_GET['id'] );
    
if (! $prestador )
    header('Location: ./index.php');

else {  
    ob_start();

?>
    <div class="container">
        <div class="py-5 text-center">
            <h2>Editar Prestador</h2>
        </div>
        <div class="row">
            <div class="col-md-12 border rounded-lg bg-white p-3" >

              <form action="atualizar.php" method="POST">

                      <input type="hidden" name="id" 
                          value="<?php echo $prestador->getId(); ?>">

                    <div class="form-group">
                      <label for="razaoSocial">Razão Social</label>
                      <input type="text" placeholder="Razão Social" 
                          value="<?php echo $prestador->getRazaoSocial(); ?>"
                          class="form-control" name="razaoSocial" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="cnpj">CNPJ</label>
                            <input type="number" class="form-control"
                                 id="cnpj"
                                value="<?php echo $prestador->getCnpj(); ?>" 
                                name="cnpj" placeholder="Cnpj" required>
                        </div>                            
                        <div class="form-group col-md-6">
                            <label for="telefone">Telefone</label>
                            <input type="number" class="form-control" id="matricula" 
                                value="<?php echo $prestador->getTelefone(); ?>" 
                                name="telefone" placeholder="telefone" required>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="email">E-mail</label>
                      <input type="email" placeholder="Email" 
                          value="<?php echo $prestador->getEmail(); ?>"
                          class="form-control" name="email" required>
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
