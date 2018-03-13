<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/db.php';
include_once '../objects/item.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare item object
$item = new Item($db);

// set ID property of item to be edited
$list_id = isset($_GET['list_id']) ? $_GET['list_id'] : die();

// query items
$stmt = $item->readAllInList($list_id);;
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

  // items array
  $items_arr=array();
  $items_arr["status"] = 1;
  $items_arr["msg"] = "Fetch all items of " . $list_id . "succeeded.";
  $items_arr["items"]=array();

  // retrieve our table contents
  // fetch() is faster than fetchAll()
  // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // extract row
    // this will make $row['name'] to
    // just $name only
    extract($row);

    $item=array(
      "id" => $id,
      "name" => $name,
      "checked" => $checked,
    );

    array_push($items_arr["items"], $item);
  }
  print_r(json_encode($items_arr));
} else {
    $data = array(
        "status" => 0,
        "msg" => "No items found."
    );
    print_r(json_encode($data));
}
