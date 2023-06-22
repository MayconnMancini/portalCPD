<?php
function html($content, $root_relative_path = '../')
{
  ob_start();
?>

  <!doctype html>
  <html lang="pt-br">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="http://progweb.local/PortalCPD/public/favicon-16x16.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="http://progweb.local/PortalCPD/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://progweb.local/PortalCPD/bootstrap/estilo.css">
    <link rel="stylesheet" href="http://progweb.local/PortalCPD/bootstrap/demo.css">
    <link rel="stylesheet" href="http://progweb.local/PortalCPD/bootstrap/footer-distributed-with-address-and-phones.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="http://progweb.local/PortalCPD/javascript/js-portal.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
    <!-- mascara dos inputs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

    <!-- datatable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>

    <!-- Datepicker CSS 
    <link rel="stylesheet" href="http://localhost/PortalCPD/datepicker-in-bootstrap-modal/css/datepicker.css">

     Datepicker JS
    <script src="http://localhost/PortalCPD/datepicker-in-bootstrap-modal/js/datepicker.js"></script>-->

    <title>Controle de Balanças</title>
  </head>

  <body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
      <div class="container ">
        <a class="navbar-brand" href="<?php echo $root_relative_path . 'index.php'; ?>">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $root_relative_path . 'balancas'; ?>">BALANÇAS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $root_relative_path . 'atendimentos'; ?>">ATENDIMENTOS</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $root_relative_path . 'reparos'; ?>">REPAROS</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $root_relative_path . 'prestadores'; ?>">PRESTADORES DE SERVIÇOS </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $root_relative_path . 'usuarios'; ?>">USUÁRIOS </a>
            </li>

          </ul>
          <?php
          if (!isset($_SESSION)) session_start();
          if (isset($_SESSION['login'])) {
          ?>
            <div class="mr-auto">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <span class="text-white">Usuário: <?php echo ($_SESSION['login']) ?></span>
                </li>
              </ul>
            </div>
          <?php
          }
          ?>
          <div class="my-2 my-lg-0">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo $root_relative_path . 'login/sair.php'; ?>">SAIR </a>
              </li>
            </ul>
          </div>

        </div>
    </nav>

    </div><!-- fim container -->


    <div class="">

      <?php echo $content; ?>

    </div>
    <br><br><br><br>

    <footer class="footer-distributed">
      <div class="container">
        <div class="row">
          <div class="col-md-4 footer-left">
            <h3>Portal <span>CPD</span></h3>

            <p class="footer-links">
              <a href="<?php echo $root_relative_path . 'balancas'; ?>">Balanças</a>
              ·
              <a href="<?php echo $root_relative_path . 'atendimentos'; ?>">Atendimentos</a>
              ·
              <a href="#">Reparos</a>
              ·
              <a href="<?php echo $root_relative_path . 'prestadores'; ?>">Prestadores</a>
              ·
              <a href="<?php echo $root_relative_path . 'usuarios'; ?>">Usuários</a>

            </p>

            <p class="footer-company-name">Atacadão Rondonópolis &copy; 2020<br>
              Desenvolvido por Mayconn Mancini.<br> Versão 1.0<br< /p>
          </div>

          <div class="col-md-4 footer-center">
            <div>
              <i class="fa fa-map-marker"></i>
              <p><span>Av. Bandeirante, 2432, Centro</span> Rondonópolis-MT, Brasil</p>
            </div>

            <div>
              <i class="fa fa-phone"></i>
              <p>(66) 3411-2415 ·</p>
              <p>(66) 3411-2416</p>
            </div>

            <div>
              <i class="fa fa-envelope"></i>
              <p><a href="/cdn-cgi/l/email-protection#285b5d5858475a5c684b474558494651064b4745"><span class="__cf_email__" data-cfemail="f88b8d8888978a8cb89b979588999681d69b9795">cpdrondonopolis@atacadao.com.br</span></a></p>
            </div>
          </div>

          <div class="col-md-4 footer-right">
            <p class="footer-company-about">
              <span>Sobre o Portal CPD</span>
              Este portal tem como objetivo gerenciar as manutenções das balanças da loja.
            </p>

            <div class="footer-icons">
              <!--<a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-github"></i></a>-->
            </div>

          </div>
        </div>
      </div>
    </footer>

    <!--
    <footer class="bg-dark">
      <div class="container rodape">
        <div class="row rodape">
          <div class="col-md-12 text-white text-center">
            <p>Desenvolvido por Mayconn Mancini<br> Versão 1.0</p>
          </div>
        </div>
      </div>
    </footer>
-->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="http://progweb.local/PortalCPD/bootstrap/js/bootstrap.min.js"></script>


  </body>

  </html>

<?php
  $html = ob_get_clean();
  return $html;
}
?>