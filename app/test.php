<pre>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/helpers/helperDb.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

//$nums = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19];

// foreach ($nums as $num) {
// 	//var_dump($num);
// 	var_dump(intdiv($num, 9));
// }

//var_dump(ceil(19 / 9));
// $postfilter = require_once $_SERVER['DOCUMENT_ROOT'] . '/data/validateFilt.php';
// $str = 'мама мыла раму 123 раза';
// var_dump($postfilter['product_name']['options']);
// var_dump(filter_var($str, $postfilter['product_name']['filter'], $postfilter['product_name']['options']));

// \helperDb\connectDb();
// var_dump(error_get_last());
$sql = 'select products';
$regExp = '#^update|insert|delete#i';
//(?i)(\W|^)(туфта|проклятие|убирайся|бред|черт\sвозьми|зараза)(\W|$)
preg_match($regExp, $sql, $matches);
$arr = ['UPDATE', 'INSERT', 'DELETE'];
var_dump(preg_match($regExp, $sql, $matches));
var_dump($matches);
//var_dump(in_array(strtoupper($matches[0]), $arr));

preg_match('#^update|insert|delete#i', $sql);
?>
</pre>


