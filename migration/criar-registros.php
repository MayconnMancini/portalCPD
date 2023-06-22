<?php
require_once(__DIR__ . "/../db/Db.php");
//require_once(__DIR__ . "/../model/Departamento.php");
//require_once(__DIR__ . "/../model/Marca.php");
//require_once(__DIR__ . "/../model/Produto.php");
//require_once(__DIR__ . "/../model/Vendedor.php");
//require_once(__DIR__ . "/../dao/DaoDepartamento.php");
//require_once(__DIR__ . "/../dao/DaoMarca.php");
//require_once(__DIR__ . "/../dao/DaoProduto.php");
//require_once(__DIR__ . "/../dao/DaoVendedor.php");
//require_once(__DIR__ . "/../model/Reparo.php");
//require_once(__DIR__ . "/../dao/DaoReparo.php");

require_once(__DIR__ . '/./../model/Reparo.php');
require_once(__DIR__ . '/./../dao/DaoReparo.php');
require_once(__DIR__ . '/./../model/Atendimento.php');
require_once(__DIR__ . '/./../dao/DaoAtendimento.php');
require_once(__DIR__ . '/./../model/Balanca.php');
require_once(__DIR__ . '/./../dao/DaoBalanca.php');
require_once(__DIR__ . '/./../model/Status.php');
require_once(__DIR__ . '/./../dao/DaoStatus.php');
require_once(__DIR__ . '/./../model/OrdemServico.php');
require_once(__DIR__ . '/./../dao/DaoOrdemServico.php');

$db = Db::getInstance();
if ($db->connect()) {
  /*
  $daoMarca = new DaoMarca($db);
  $daoProd = new DaoProduto($db);
  $daoDep  = new DaoDepartamento($db);
  $daoVen  = new DaoVendedor($db);
  */


  $daoReparo = new DaoReparo($db);
  $daoAtendimento = new DaoAtendimento($db);
  $daoBalanca = new DaoBalanca($db);
  $daoOrdemServico = new DaoOrdemServico($db);
  $daoStatus = new DaoStatus($db);

  $atendimento = $daoAtendimento->porId(6);
  $balanca = $daoBalanca->porId(1);
  $os = $daoOrdemServico->porId(1);
  $status = $daoStatus->porId(3);

  $novoReparo = new Reparo(
    $balanca,
    $atendimento,
    "Inserindo dados ficticios",
    '2021-01-05',
    '2021-01-10',
    $status,
    "Teste de performance",
    $os
  );

  for ($i = 0; $i < 10000; $i++) {

    $daoReparo->inserir($novoReparo);
    
  }

  /*
  $vendedores[]   = new Vendedor("João da Silva", "324.534.232-12", 342134, 1000, "12/10/1989");
  $vendedores[]   = new Vendedor("Maria Aparecida", "123.456.789-11", 112233, 1800, "09/05/2000");
  $vendedores[]   = new Vendedor("Ana Beatriz", "321.654.987-09", 557788, 2000, "04/01/1980");
  $vendedores[]   = new Vendedor("José de Alencar", "324.534.232-12", 928374, 2100, "12/12/1988");
  $vendedores[]   = new Vendedor("Mauricio de Souza", "673.231.876-99", 562343, 4000, "22/02/1977");
  foreach ($vendedores as $ven) $daoVen->inserir($ven);

  $deps[]   = new Departamento("Eletrônicos");
  $deps[]   = new Departamento("Roupas");
  $deps[]   = new Departamento("Informática");
  $deps[]   = new Departamento("Som e imagem");
  $deps[]   = new Departamento("Gamers");
  $deps[]   = new Departamento("Alimentos");
  $deps[]   = new Departamento("Bebidas");
  $deps[]   = new Departamento("Acessórios automotivos");
  foreach ($deps as $dep) $daoDep->inserir($dep);

  $marcas[] = new Marca("Sony");
  $marcas[] = new Marca("LG");
  $marcas[] = new Marca("Samsung");
  $marcas[] = new Marca("Asus");
  $marcas[] = new Marca("Acer");
  $marcas[] = new Marca("AOC");
  $marcas[] = new Marca("Xiaomi");
  $marcas[] = new Marca("Multilaser");
  foreach ($marcas as $marca) $daoMarca->inserir($marca);

  $prods[] = new Produto("Monitor AOC 21.5", 500.55, 5, $marcas[5]);
  $prods[] = new Produto("Notebook Acer Aspire", 5000, 2, $marcas[4]);
  $prods[] = new Produto("Tablet Multilaser 10p", 200, 20, $marcas[7]);
  foreach ($prods as $prod) $daoProd->inserir($prod);

  $daoProd->sincronizarDepartamentos($prods[0], [
    $deps[0]->getId(), $deps[2]->getId(), $deps[3]->getId()
  ]);
  $daoProd->sincronizarDepartamentos($prods[1], [
    $deps[0]->getId(), $deps[2]->getId(), $deps[3]->getId(), $deps[4]->getId()
  ]);
  $daoProd->sincronizarDepartamentos($prods[2], [
    $deps[0]->getId(), $deps[3]->getId()
  ]);
  */
}


exit;
