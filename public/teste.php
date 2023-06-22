<?php
require_once(__DIR__ . '/../db/Db.php');
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../dao/DaoRelatorio.php');


function reparosPorAnoAtual()
{

    $conn = Db::getInstance();

    if (!$conn->connect()) {
        echo ("Erro ao conectar ao Banco de Dados");
        die();
    }

    $daoRelatorio = new DaoRelatorio($conn);
    $agora = new DateTime();
    $mes_atual = $agora->format('m');

    //print_r($agora->format('d-m-Y'));
    //echo ('<br>');
    $reparos = [];
    $reparos = $daoRelatorio->reparosPorMes($agora->format('Y'));

    $reparosFinal = [];


    //print_r($mes_atual);
    //echo ('<br>');

    for ($i = 0; $i < $mes_atual; $i++) {
        $verif = false;
        foreach ($reparos as $rep) {

            if ($rep['mes'] == $i + 1) {
                $reparosFinal[$i] = $rep['total'];
                $verif = true;
                //print_r($rep['mes']);
                //echo ('<br>');
                //print_r($rep['total']);
                //echo ('<br>');
                //
                //print_r($reparosFinal[$i]);
                //echo ('<br>');
                //echo ('------');
                //echo ('<br>');
                $verif = true;
            }
        }
        if ($verif == false) {
            $reparosFinal[$i] = 0;
        }
    }

    //$reparosFinal = [12,18,6,5,9];
    return $reparosFinal;
}

function reparosPorAno(int $ano)
{

    $conn = Db::getInstance();

    if (!$conn->connect()) {
        echo ("Erro ao conectar ao Banco de Dados");
        die();
    }

    $daoRelatorio = new DaoRelatorio($conn);
    $reparos = [];
    $reparos = $daoRelatorio->reparosPorMes($ano);

    $reparosFinal = [];

    for ($i = 0; $i < 12; $i++) {
        $verif = false;
        foreach ($reparos as $rep) {

            if ($rep['mes'] == $i + 1) {

                $reparosFinal[$i] = $rep['total'];
                $verif = true;
            }
        }
        if ($verif == false) {
            $reparosFinal[$i] = 0;
        }
    }
    return $reparosFinal;
}



?>

<div class="col-xl-6 mt-3">
    <canvas class="line-chart"></canvas>
</div>
<div class="col-xl-6 mt-3">
    <canvas class="line-chart-balancas"></canvas>
</div>


<script>
    var ctx = document.getElementsByClassName("line-chart");
    // Type, Data e options
    var chartGraph = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            datasets: [{
                    label: "QUANTIDADE DE REPAROS - 2020",
                    data: <?php echo json_encode(reparosPorAno(2020)) ?>,
                    borderWidht: 6,
                    borderColor: 'rgba(77,166,253,0.85)',
                    backgroudColor: 'transparent'
                },
                {
                    label: "QUANTIDADE DE REPAROS - 2021",
                    data: <?php echo json_encode(reparosPorAnoAtual()) ?>,
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
                text: "RELATÓRIO DE REPAROS ANUAL"
            },
            labels: {
                fontStyle: "bold"
            }
        }
    });

    var ctx = document.getElementsByClassName("line-chart-balancas");
    // Type, Data e options
    var chartBar = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            datasets: [{
                label: "QUANTIDADE DE REPAROS - 2021",
                data: <?php echo json_encode(reparosPorAnoAtual()) ?>,
                borderWidht: 6,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                // Define a espessura da borda dos retângulos
                borderWidth: 1
            }, ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            title: {
                display: true,
                fontSize: 20,
                text: "RELATÓRIO DE REPAROS ANUAL - 2021"
            },
            labels: {
                fontStyle: "bold"
            }
        }
    });
</script>