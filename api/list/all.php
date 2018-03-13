<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/db.php';
include_once '../objects/list.php';

// instantiate database and list object
$database = new Database();
$db = $database->getConnection();

// initialize object
$list = new Reuse_List($db);

// query lists
$stmt = $list->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

  // lists array
  $lists_arr=array();
  $lists_arr["status"] = 1;
  $lists_arr["msg"] = "Fetch all lists succeeded.";
  $lists_arr["lists"]=array();

  // retrieve our table contents
  // fetch() is faster than fetchAll()
  // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // extract row
    // this will make $row['name'] to
    // just $name only
    extract($row);

    $list=array(
      "id" => $id,
      "name" => $name,
      "archived" => $archived
    );

    array_push($lists_arr["lists"], $list);
  }
  print_r(json_encode($lists_arr));
} else {
  $data = array(
    "status" => 0,
    "msg" => "No lists found."
  );
  print_r(json_encode($data));
}
