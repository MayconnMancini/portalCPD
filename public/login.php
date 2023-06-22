<?php
session_start();
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="http://localhost/PortalCPD/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>

    <style>
.login-form {
    width: 390px;
    margin: 50px auto;
  	font-size: 15px;
}
.login-form form {
    margin-bottom: 15px;
    background: #f7f7f7;
    box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    padding: 30px;
}
.login-form h2 {
    margin: 0 0 15px;
}
.form-control, .btn {
    min-height: 38px;
    border-radius: 2px;
}
.btn {        
    font-size: 15px;
    font-weight: bold;
}
</style>

    <title>Portal CPD - Login</title>
  </head>
  <body>
    
  <div class="login-form">
    <form action="./login/processaLogin.php" method="post">
        <h2 class="text-center">Portal CPD</h2>       
        <div class="form-group">
            <input type="text" class="form-control" name="login" id="login" placeholder="Usuário" required="required">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha" required="required">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Logar</button>
        </div>
        <div  >
          <?php
            if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }

            if(isset($_GET['login'])) {
                if($_GET['login'] == 'erro') {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <strong>Erro! </strong>Usuário ou senha inválidos!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span> </button>
                            </div>";    
                }
            }
            if(isset($_GET['login'])) {
                if($_GET['login'] == 'errosessao') {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <strong>Erro! </strong>Não criou a sessão! Faça login!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span> </button>
                            </div>";    
                }
            }
            if(isset($_GET['login'])) {
                if($_GET['login'] == 'vazio') {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <strong>Erro! </strong>Campo usuário e senha vazios!
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span> </button>
                            </div>";    
                }
            }
            
          ?>
          
        </div>
    </form>
    
</div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="http://localhost/PortalCPD/bootstrap/js/bootstrap.min.js"></script>		


  </body>
</html>