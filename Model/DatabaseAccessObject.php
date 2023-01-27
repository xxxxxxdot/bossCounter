<?php

class DatabaseAccessObject {
    private $mysql_address = "";
    private $mysql_username = "";
    private $mysql_password = "";
    private $mysql_database = "";
    private $link;
    private $last_sql = "";
    private $last_id = 0;
    private $last_num_rows = 0;
    private $error_message = "";

    /**
     * 這段是『建構式』會在物件被 new 時自動執行，裡面主要是建立跟資料庫的連接，並設定語系是萬國語言以支援中文
     */
    public function __construct($mysql_address, $mysql_username, $mysql_password, $mysql_database) {
        $this->mysql_address  = $mysql_address;
        $this->mysql_username = $mysql_username;
        $this->mysql_password = $mysql_password;
        $this->mysql_database = $mysql_database;
        $this->link = ($GLOBALS["___mysqli_ston"] = mysqli_connect($this->mysql_address, $this->mysql_username, $this->mysql_password,$this->mysql_database));
        
     //資料庫連線失敗
        if (mysqli_connect_errno())
        {
            $this->error_message = "Failed to connect to MySQL: " . mysqli_connect_error();
            echo $this->error_message;
            return false;
        }
        mysqli_query($GLOBALS["___mysqli_ston"], "SET NAMES utf8");
        mysqli_query($this->link, "SET NAMES utf8");
        mysqli_query($this->link, "SET CHARACTER_SET_database= utf8");
        mysqli_query($this->link, "SET CHARACTER_SET_CLIENT= utf8");
        mysqli_query($this->link, "SET CHARACTER_SET_RESULTS= utf8");

     //資料庫已連線 但 資料庫 mysql_database 不存在
        if(!(bool)mysqli_query($this->link, "USE ".$this->mysql_database))
        {
          $this->error_message = 'Database '.$this->mysql_database.' does not exist!';
          echo $this->error_message;
          return false;
        }

     }

     
     /**
     * 這段是『解構式』會在物件被 unset 時自動執行，裡面那行指令是切斷跟資料庫的連接
     */

    public function __destruct() {
        // mysqli_close($this->link);
    }
    public function fMysqli_Close() {
        mysqli_close($this->link);
    }

     /**
     * 這段可以新增資料庫中的資料，並把最後一筆的 ID 存到變數中，可以用 getLastId() 取出
     */
    public function insert($table = null, $data_array = array()){

     if($table===null)return false;

     if(count($data_array) == 0) return false;

     $tmp_col = array();
     $tmp_dat = array();

     foreach ($data_array as $key => $value) {
         $value = mysqli_real_escape_string($this->link, $value);
         $tmp_col[] = $key;
         $tmp_dat[] = "'$value'";
     }

     $columns = join(",", $tmp_col);
     $data = join(",", $tmp_dat);

     $this->last_sql = "INSERT INTO " . $table . "(" . $columns . ")VALUES(" . $data . ")";

     mysqli_query($this->link, $this->last_sql);

     if (((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))) {
         echo "MySQL Update Error: " . ((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
     } else {
         $this->last_id = mysqli_insert_id($this->link);
         return $this->last_id;
     }
    }

    /**
     * 這段用來讀取資料庫中的資料，回傳的是陣列資料
     */
    public function query($table = null, $condition = "1", $order_by = "1", $fields = "*", $limit = ""){
        $sql = "SELECT {$fields} FROM {$table} WHERE {$condition} ORDER BY {$order_by} {$limit}";
        return $this->execute($sql);
    }

    /**
     * 這段用來執行 MYSQL 資料庫的語法，可以靈活使用
     */
    public function execute($sql = null) {
      
        if ($sql===null) return false;
       
        $this->last_sql = str_ireplace("DROP","",$sql);
        
        //$result_set = array();

        //$result = mysqli_query($this->link, $this->last_sql);
    

        if (((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))) {
            $this->error_message = "MySQL ERROR: " . ((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
        } else {

            $DeleteResult = $this->last_sql;//記憶 "DELETE"呈述式
           
            $this->last_sql = str_ireplace("DELETE","SELECT *",$this->last_sql); //更改"DELETE"呈述式 -> "SELECT *"
            
            $result = mysqli_query($this->link, $this->last_sql);
            
            $this->last_num_rows = @mysqli_num_rows($result);
      
            for ($xx = 0; $xx < @mysqli_num_rows($result); $xx++) {
                $result_set[$xx] = mysqli_fetch_assoc($result);
            }
           
            if(isset($result_set)) {
                
                $this->last_sql = $DeleteResult; //取回 "DELETE"呈述式 
                
                mysqli_query($this->link, $this->last_sql); //執行 "DELETE"呈述式 

                return $result_set;

            }else{
                
                $this->error_message = "result: zero";
                //echo $this->error_message;
            }
        }
    }

    /**
     * 這段可以更新資料庫中的資料
     */
    public function update($table = null, $data_array = null, $key_column = null, $id = null) {
        if($table == null){
            echo "table is null";
            return false;
        }
        if($id == null) return false;
        if($key_column == null) return false;
        if(count($data_array) == 0) return false;

        $id = mysqli_real_escape_string($this->link, $id);

        $setting_list = "";
        $Cdata_array = count($data_array);  //計算下方 foreach 到最後一次時 字串候不加 ","
        
         foreach($data_array as  $key => $value){
         
          $value = mysqli_real_escape_string($this->link, $value);
          $setting_list .=  $key . "=" . "\"" . $value . "\"";
           
           if ($Cdata_array-- != 1){
             $setting_list .= ",";
           }
          
         }

        $this->last_sql = "UPDATE " . $table . " SET " . $setting_list . " WHERE " . $key_column . " = " . "\"" . $id . "\"";
        $result = mysqli_query($this->link, $this->last_sql);

        if (((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))) {
            echo "MySQL Update Error: " . ((is_object($this->link)) ? mysqli_error($this->link) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
        } else {
            return $result;
        }
    }

    /**
     * 這段可以刪除資料庫中的資料
     */
    public function delete($table = null, $key_column = null, $id = null) {
        if ($table===null) return false;
        if($id===null) return false;
        if($key_column===null) return false;

        $returnExecute = $this->execute("DELETE FROM $table WHERE " . $key_column . " = " . "\"" . $id . "\"");
        if (is_array($returnExecute)){
            echo "已刪除";
         }
        return $returnExecute;
        
       
    }

    /*
     * @return int
     * 主要功能是把新增的 ID 傳到物件外面
     * #1 可以讀取出 $hero_id 
     */
    public function getLastId() {
        return $this->last_id;
    }

    /*
     * @return string
     * 這段會把最後執行的語法回傳給你
     */
    public function getLastSql() {
        return $this->last_sql;
    }

    /*
     * @param int $last_id
     * 把這個 $last_id 存到物件內的變數
     */
    private function setLastId($last_id) {
        $this->last_id = $last_id;
    }

    /**
     * @return int
     */
    public function getLastNumRows() {
        return $this->last_num_rows;
    }

    /**
     * @param int $last_num_rows
     */
    private function setLastNumRows($last_num_rows) {
        $this->last_num_rows = $last_num_rows;
    }

    /*
     * @return string
     * 取出物件內的錯誤訊息
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }
    /*
     * @param string $error_message
     * 記下錯誤訊息到物件變數內
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }

}


// $mysql_address = "localhost"; // 通常是連接同一台機器，如果是遠端就設 IP
// $mysql_username = "root";     // 設定連接資料庫用戶帳號
// $mysql_password = ""; // 設定連接資料庫用戶的密碼
// $mysql_database = "id18342356_xxxxxxdotsql";     // 設成你在 mysql 創的資料庫
// $DAO = new DatabaseAccessObject($mysql_address, $mysql_username, $mysql_password, $mysql_database);


//設定你想新增資料的資料表
// $table = "hero"; 
// $data_array['hero_name'] = "倉庫";
// $data_array['hero_hp'] = 50;
// $data_array['hero_mp'] = 120;
// $DAO->insert($table, $data_array);
// $hero_id = $DAO->getLastId(); // #1可以利用 getLastId()取出 此筆插入的 id
// echo $DAO->getLastId() . "<br>";



//想要查詢的話
// $table = "hero"; // 設定你想查詢資料的資料表
// $condition = "hero_name = '依蘇'";
// $hero = $DAO->query($table, $condition, $order_by = "1", $fields = "*", $limit = "");
// print_r($hero);
// echo $DAO->getLastSql(); // 想知道會轉換成什麼語法 可以印出來看看

//那想修改資料呢？
// $table = "hero";
// $data_array['hero_name'] = "凡恩"; // 想改他的名字
// $data_array['hero_hp'] = "80";
// $data_array['hero_mp'] = "70";
// $key_column = "hero_id"; //
// $id = 6; // 根據我們剛剛上面拿到的 hero ID
// $DAO->update($table, $data_array, $key_column, $id);
// echo $DAO->getLastSql(); // 想知道會轉換成什麼語法 可以印出來看看

//最後的刪除也不難，告訴他條件就可以了
// $table = "hero";
// $key_column = "hero_id";
// $id = 9; // 我們假設要刪除 hero_id = 1 的英雄

// print_r ($DAO->delete($table, $key_column, $id)); 
// echo $DAO->getErrorMessage();
// echo $DAO->getLastSql(); // 想知道會轉換成什麼語法 可以印出來看看


?>