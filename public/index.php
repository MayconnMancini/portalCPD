<?php
// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION)) session_start();

$perfil_necessario = 1;

// Verifica se não há a variável da sessão que identifica o usuário
if (
  !isset($_SESSION['iduser']) or ($_SESSION['id_perfil'] != $perfil_necessario)
  or (empty($_SESSION['iduser']))
) {
  // Destrói a sessão por segurança
  session_destroy();
  // Redireciona o visitante de volta pro login
  header("Location: login.php");
  exit;
}

require_once(__DIR__ . '/../templates/template-html.php');
require_once(__DIR__ . '/../db/Db.php');
require_once(__DIR__ . '/../config/config.php');

$conn = Db::getInstance();

if (!$conn->connect()) {
  echo ("Erro ao conectar ao Banco de Dados");
  die();
}



$quantidade = [10, 15, 8, 5, 6, 9, 10, 7, 8, 11, 4, 5];


ob_start();

?>
<div class="container-fluid">
  <div class="py-2 text-center">
    <h2>Dashboard</h2>
  </div>
  <div class="container">
    <div class="row">
      <?php
      if (isset($_GET['login'])) {
        if ($_GET['login'] == 'ok') {
          echo "<div class='alert alert-success alert-dismissible fade show col-md-12' role='alert'>
                      Bem vindo ao sistema  <strong>" . $_SESSION['nome'] . "</strong>
                      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                      <span aria-hidden='true'>&times;</span> </button>
                      </div>";
        }
      }
      ?>
    </div>

  </div>


  <div class="row mt-3" id="conteudo">

    

  </div>
</div>
<!--
<script>
  var ctx = document.getElementsByClassName("line-chart");
  // Type, Data e options
  var chartGraph = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
      datasets: [{
          label: "TAXA DE CLIQUES - 2017",
          data: [5, 10, 5, 14, 20, 15, 6, 14, 8, 12, 15, 5],
          borderWidht: 6,
          borderColor: 'rgba(77,166,253,0.85)',
          backgroudColor: 'transparent'
        },
        {
          label: "TAXA DE CLIQUES - 2018",
          data: <?php echo json_encode($quantidade) ?>,
          borderWidht: 6,
          borderColor: 'rgba(77,204,6,0.85)',
          backgroudColor: 'transparent'
        },
      ]
    },
    options: {
      title: {
        display: true,
        fontSize: 20,
        text: "RELATÓRIO DE CTR ANUAL"
      },
      labels: {
        fontStyle: "bold"
      }
    }
  });

  var ctx2 = document.getElementsByClassName("line-chart2");
  // Type, Data e options
  var chartGraph = new Chart(ctx2, {
    type: 'line',
    data: {
      labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun"],
      datasets: [{
          label: "TAXA DE CLIQUES - 2017",
          data: [5, 10, 5, 14, 20, 15, 6, 14, 8, 12, 15, 5],
          borderWidht: 6,
          borderColor: 'rgba(77,166,253,0.85)',
          backgroudColor: 'trasnparent'
        },
        {
          label: "TAXA DE CLIQUES - 2018",
          data: <?php echo json_encode($quantidade) ?>,
          borderWidht: 6,
          borderColor: 'rgba(77,204,6,0.85)',
          backgroudColor: 'trasnparent'
        },
      ]
    },
    options: {
      title: {
        display: true,
        fontSize: 20,
        text: "RELATÓRIO DE CTR SEMESTRAL"
      },
      labels: {
        fontStyle: "bold"
      }
    }
  });
</script>
-->

<script>
  $(document).ready(function() {
    $('#conteudo').load("teste.php");
  });
</script>


<?php

$content = ob_get_clean();
echo html($content, "./");

?>