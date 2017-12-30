<?php
include('config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Access-Control-Allow-Origin: *');
startio($_GET);

function startio()
{
  if (isset($_GET['type'])) {
        switch ($_GET['type']) {
          case 'test':
          echo 'test';
          break;

        case 'submit_data':
        $apikey =   str_replace( ' ', '', $_GET['apikey'] );
        $strategy =  str_replace( ' ', '', $_GET['strategy'] );
        $real_amount =  str_replace( ' ', '', $_GET['real_amount'] );
        $good_amount =  str_replace( ' ', '', $_GET['good_amount'] );
          $sql = "INSERT INTO `stats` (`apikey`,`strategy`,`real_amount`,`good_amount`) VALUES ('$apikey','$strategy','$real_amount','$good_amount')";
$sql = preg_replace("/(?<=[A-Za-z0-9])(')(?=[A-Za-z0-9])/", "\'", $sql);
db_query($sql) or die(db_error());
          break;

          case 'get_data':
$sql = "select * from `stats` where timestamp BETWEEN SUBDATE(CURDATE(), INTERVAL 1 MONTH) AND NOW();";
$sql = preg_replace("/(?<=[A-Za-z0-9])(')(?=[A-Za-z0-9])/", "\'", $sql);
$result = db_query($sql);
if ($result === false) {return false;}
$emparray = array();
$aaData = array();

while ($row = mysqli_fetch_assoc($result)) {
  $theapikey = $row['apikey'];
  $theid = $row['id'];
  $thestrategy = $row['strategy'];
  $therealamount = $row['real_amount'];
  $thegoodamount = $row['good_amount'];
  $thetime = $row['timestamp'];
  $myIndex = $theid.':'.$theapikey;

//  if ($previousprice> 0) {
  if (!isset($aaData[$myIndex])) {
      $aaData[$myIndex][$theapikey]['strategy'] = $thestrategy;
      $aaData[$myIndex][$theapikey]['real_amount'] = $therealamount;
      $aaData[$myIndex][$theapikey]['good_amount'] = $thegoodamount;
      $aaData[$myIndex][$theapikey]['theadtimestampdress'] = $thetime;

  }


}

$aaData = array_values($aaData);
$response = array('data' => $aaData);
$merger = array_merge($response);
header('Content-Type: application/json');
echo json_encode($merger);
//echo '<pre>';print_r($merger);echo '</pre>';
db_close();


  echo 'ok';
            break;


}
}
else {
  echo "no T defined \n";
}

}

?>