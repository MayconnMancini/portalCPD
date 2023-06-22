<?php

require_once(__DIR__ . "/../db/Db.php");
$sql = file_get_contents(__DIR__ . '"/../db/script-bancoportalcpd.sql');
$sqls = explode(';', $sql);

$db = Db::getInstance();
if ($db->connect()) {

  $res_all = true;
  foreach($sqls as $s) {
    $s = trim($s);
    if ($s != "") {
      if($res = $db->query($s))
        echo "$s \n[[[[ OK ]]]] <br> \n\n";
      else
        echo "$s \n[[[[ ERRO ]]]] <br>\n\n";
      $res_all = $res_all && $res;
    }
  }
  
  if ($res_all)
    echo "\nTabelas criadas. <br>\n\n";
  else  
    echo "\nErro na criação das tabelas. <br>\n\n ";


}

exit;
