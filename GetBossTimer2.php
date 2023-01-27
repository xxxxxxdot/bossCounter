<?php

// use Symfony\Component\VarExporter\Internal\Values;
header("Access-Control-Allow-Origin: https://xxxxxxdot.github.io");
require('./Model/Database.php'); // 載入

$db = Database::get();

if(isset($_POST['action'])){
  if ($_POST['action'] == "GetBossTimer"){
    echo json_encode(GetBossTimer()); 
  };
  if ($_POST['action'] == "UpdataBossTimer"){
    $SetID = $_POST['SetID'];
    $SetTimer = $_POST['SetTimer'];
    echo json_encode(UpdataBossTimer($SetID, $SetTimer)); 
  };
  // if ($_POST['action'] == "function3") { func3(); }
  // if ($_POST['action'] == "function4") { func4(); }
}
/////////////////////////////想要查詢的話///////////////////////////////
  function GetBossTimer(){
    // $db = Database::get();
    $table = "boss"; // 設定你想查詢資料的資料表
    $fields = "name,timer";//想撈取的欄位
    $condition = "1"; //意指不設條件
    $result =  $GLOBALS["db"]->query($table, $condition, $order_by = "1", $fields, $limit = "");
    return $result;
  };
/////////////////////////////那想修改資料呢？///////////////////////////////
  function UpdataBossTimer($SetID = null, $SetTimer = null){
    $table = "boss";
    $data_array['timer'] = $SetTimer;
    $key_column = "id"; //指定的key  
    // $id = $SetID + 1  ; // $key_column的值
    $id = substr($SetID, 2) + 1  ; // $key_column的值
    $GLOBALS["db"]->update($table, $data_array, $key_column, $id);
    $result = $GLOBALS["db"]->getLastSql();// 想知道會轉換成什麼語法 可以印出來看看
    return $result;
    // echo $GLOBALS["db"]->getLastSql(); // 想知道會轉換成什麼語法 可以印出來看看
  };
  // $table = "boss";  // 設定你想更新資料的資料表
  // $key_column = "hero_id"; 
  // $id = 6; // 根據我們剛剛上面拿到的 hero ID
  // $DAO->update($table, $data_array, $key_column, $id);
  // echo $DAO->getLastSql(); // 想知道會轉換成什麼語法 可以印出來看看
//////////////////////////////新增 boss ///////////////////////////////
  // $Boss_Name = array("巴魯","克頓","黑鋼","哭臉", "亡命", "口水","魚人","蜘蛛","鎧甲","族長");
  //   for ($i=0; $i < count($Boss_Name) ; $i++) { 
  //     $table = "boss";
  //     $data_array['name'] = $Boss_Name[$i];
  //     $db->insert($table, $data_array);
  //     $getLastId = $db->getLastId();
  //     echo $getLastId . "<br>";
  //   }
//////////////////////////////新增 greenCard///////////////////////////////
// foreach (glob("../lineagew/greenCard/*.png") as $filePath) {
    
//     $filePath = substr($filePath,1);
//     $fileName = pathinfo($filePath,PATHINFO_FILENAME);

//     // 設定你想新增資料的資料表
//     $table = "card";
//     $data_array['color'] = "green";
//     $data_array['path'] = "$filePath";
//     $data_array['name'] = "$fileName";

//     $db->insert($table, $data_array);
//     $getLastId = $db->getLastId();
//     echo $getLastId . "<br>";
// }

//////////////////////////////新增 whiteCard///////////////////////////////
// foreach (glob("../lineagew/whiteCard/*.png") as $filePath) {
    
//     $filePath = substr($filePath,1);
//     $fileName = pathinfo($filePath,PATHINFO_FILENAME);

//     // 設定你想新增資料的資料表
//     $table = "card";
//     $data_array['color'] = "white";
//     $data_array['path'] = "$filePath";
//     $data_array['name'] = "$fileName";

//     $db->insert($table, $data_array);
//     $getLastId = $db->getLastId();
//     echo $getLastId . "<br>";
// }


////////////////////////////example/////////////////////////////
// # 列出所有 *.txt 文字檔案
// foreach (glob("*.txt") as $filename) {
//     echo "$filename 檔案大小：" . filesize($filename) . "\n";
//   }
// # 列出指定路徑下的文字檔案
// foreach (glob("/home/ubuntu/tmp/php/*.txt") as $filename) {
//     echo "$filename 檔案大小：" . filesize($filename) . "\n";
//   }
  # 列出 file2.txt、file3.txt 與 file4.txt 文字檔案
// foreach (glob("file[2-4].txt") as $filename) {
//     echo "$filename 檔案大小：" . filesize($filename) . "\n";
//   }
//   # 列出指定路徑下的所有檔案
// foreach (scandir(".") as $item) {
//     if (is_dir($item)) {
//       echo "目錄：$item\n";
//     } else {
//       echo "檔案：$item\n";
//     }
//   }
//   # 列出指定路徑下的所有檔案或子目錄
// foreach (scandir("/home/ubuntu/tmp/php") as $item) {
//     if (is_dir($item)) {
//       echo "目錄：$item\n";
//     } else {
//       echo "檔案：$item\n";
//     }
//   }
//   # 指定目錄路徑
// $directory = '/home/ubuntu/tmp/php';

// # 列出所有檔案或目錄，去除「.」與「..」目錄
// $items = array_diff(scandir($directory), array('..', '.'));

// # 輸出檔案或目錄
// foreach ($items as $item) {
//   if (is_dir($item)) {
//     echo "目錄：$item\n";
//   } else {
//     echo "檔案：$item\n";
//   }
// }
# 列出指定路徑下的文字檔案

// 定义和用法
// pathinfo() 函数以数组的形式返回关于文件路径的信息。

// 返回的数组元素如下：

// [dirname]: 目录路径
// [basename]: 文件名
// [extension]: 文件后缀名
// [filename]: 不包含后缀的文件名
// 语法
// pathinfo(path,options)

// 参数	描述
// path	必需。规定要检查的路径。
// options	可选。规定要返回的数组元素。默认是 all。
// 可能的值：

// PATHINFO_DIRNAME - 只返回 dirname
// PATHINFO_BASENAME - 只返回 basename
// PATHINFO_EXTENSION - 只返回 extension
// PATHINFO_FILENAME - 只返回 filename


?>