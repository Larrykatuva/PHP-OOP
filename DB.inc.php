<?php

class database{
  private $Dbservername,
          $Dbusername,
          $Dbpassword,
          $Dbname;
  public $conn;
  public $errors = array();

  public function _construct(
    $dbservername = "localhost",
    $dbusername = "root",
    $dbpassword = "",
    $dbname = "ecommerce"
  ){
    $this->$Dbservername = $dbservername;
    $this->$Dbusername = $dbusername;
    $this->$Dbpassword = $dbpassword;
    $this->$Dbname = $dbname;
      //creating a database connection
    $this->conn = new mysqli($servername, $username, $password,$Dbname);
    // Check connection
    if ($this->conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
    }
    return $this;
  }
  //closing the database connection
  public function closeConnection(){
    $this->conn->close();
  }
  /*selecting data by pasing tablename, passing:
  $result = $databse->getData(tablename);
  while ($row = mysqli_fetch_assoc($result)){
  }
  */
  public function getData($tableName){
    $sql = "SELECT * FROM $this->$tableName";
    $results = $this->conn->query($sql);
    if(mysqli_num_rows($result) > 0){
            return $result;
        }
  }
  /*inserting data
  $databse->insertData('products',('name','price'),('tv','20000'))
  */
  public function insertData($tableName,$fields = array(),$values = array()){
    //$sCount array to hold the "s"
    $sCount = array();
    //$bindedValues array to hold the "?"
    $bindedValues = array();
    //getting the length of the $values array
    $arrlength = count($values);
    for($x = 0; $x < $arrlength; $x++) {
      array_push($sCount, "s");
      array_push($bindedValues, "?");
    }
    //the sql statement
    $sql = "INSERT INTO ".$tableName."(".implode(",",$fields).")
    VALUES (".implode(",",$bindedValues).")";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(".implode("",$sCount).", implode(",",$fields));
    if(!$stmt->execute()){
      array_push($errors, "Execusion error");
      return $this->errors;
    }
  }
  /*inserting data
  $databse->insertWhereData('products',('name','price'),('tv','20000'),('id','=','row['id']'))
  */
  public function insertWhereData($tableName,$fields = array(),$values = array(),$where = array()){
    //operators array
    if(count($where) == 3){
      $operators = array('=', '>', '<', '>=', '<=');
    }
    $searchField = $where[0];
    $operator = $where[1];
    $searchValue = $where[2];
    if(in_array($operator, $operators)){
      //$sCount array to hold the "s"
      $sCount = array();
      //$bindedValues array to hold the "?"
      $bindedValues = array();
      //getting the length of the $values array
      $arrlength = count($values);
      for($x = 0; $x < $arrlength; $x++) {
        array_push($sCount, "s");
        array_push($bindedValues, "?");
      }
      //the sql statement
      $sql = "INSERT INTO ".$tableName."(".implode(",",$fields).")
      VALUES (".implode(",",$bindedValues).")
      WHERE" .$searchField $operator $searchValue;
      $stmt = $this->conn->prepare($sql);
      $stmt->bind_param(".implode("",$sCount).", implode(",",$fields));
      if(!$stmt->execute()){
        array_push($errors, "Execusion error");
        return $this->errors;
      }
    }
  }
}

 ?>
