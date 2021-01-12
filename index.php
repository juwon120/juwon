<?php

require_once './_func.php';

$Info = new WEBParser();

$id = 'sensanho1125';
$pw = '1125889ss';

$culture_id = 'sensanho1125';
$culture_pw = '1125889ss';

$aa = $Info->Cash($id, $pw, $culture_id, $culture_pw, $_GET['no1'], $_GET['no2'], $_GET['no3'], $_GET['no4']);

if(!$aa) {
      die('Error');
}

?>