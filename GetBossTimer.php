<?php

header("Access-Control-Allow-Origin: https://xxxxxxdot.github.io");
require('./Model/Database.php'); // 載入

// $db = Database::get();

if(isset($_GET['action'])){
  if ($_GET['action'] == "GetBossTimerReflush"){
    echo json_encode(GetBossTimerReflush()); 
  };
  if ($_GET['action'] == "UpdataBossTimer"){
    $SetID = $_GET['SetID'];
    $SetTimer = $_GET['SetTimer'];
    echo json_encode(UpdataBossTimer($SetID, $SetTimer)); 
  };
  if ($_GET['action'] == "GetBossTimer"){
    echo json_encode(GetBossTimer()); 
  };

}


/////////////////////////////想要查詢的話///////////////////////////////
function GetBossTimer(){
  $db = Database::get();
  $table = "boss"; // 設定你想查詢資料的資料表
  $fields = "id,timer";//想撈取的欄位
  $condition = "1"; //意指不設條件

  $result =  $db->query($table, $condition, $order_by = "1", $fields, $limit = "");
  Database::unlinkDAO();
  $db->fMysqli_Close();
  
  return $result;

};
/////////////////////////////////////////////////////////////////////////

/////////////////////////////Comet 字串比對///////////////////////////////
function GetBossTimerReflush(){

  $table = "boss"; // 設定你想查詢資料的資料表
  $fields = "id,timer";//想撈取的欄位

  $sourceData  = json_decode($_GET['JsonTransfer'],true);
  $queryList = "";
 
  foreach ($sourceData as $key => $value) {
    $queryList .= "'" .$sourceData[$key]['id']."'" .",";
  }
  
  $condition = "id IN (" . substr($queryList, 0, -1) . ")";
  // $breakCount = 0;

  while (true) {

    $db = Database::get();
    $LocalData =  $db->query($table, $condition, $order_by = "1", $fields, $limit = "");
 
    Database::unlinkDAO();
    $db->fMysqli_Close();

    for ($i=0; $i < count($LocalData) ; $i++) { 
  
      if ($sourceData[$i]['timer'] != $LocalData[$i]['timer']){
        break 2 ;
      };
      
    };
    
    sleep(8);
    // $db->fMysqli_Close();
  };

  return $LocalData;

};
///////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////那想修改資料呢？///////////////////////////////
  function UpdataBossTimer($SetID = null, $SetTimer = null){
    $table = "boss";
    $data_array['timer'] = $SetTimer;
    $key_column = "id"; //指定的key  
    // $id = $SetID + 1  ; // $key_column的值
    $id = substr($SetID, 2) + 1  ; // $key_column的值

    $db = Database::get();
    $db->update($table, $data_array, $key_column, $id);
    Database::unlinkDAO();
    $db->fMysqli_Close();
    $result = $db->getLastSql();// 想知道會轉換成什麼語法 可以印出來看看
    return $result;
    // echo $GLOBALS["db"]->getLastSql(); // 想知道會轉換成什麼語法 可以印出來看看
  };

?>