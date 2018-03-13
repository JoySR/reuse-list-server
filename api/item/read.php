<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/db.php';
include_once '../objects/item.php';

// instantiate database and item object
$database = new Database();
$db = $database->getConnection();

// initialize object
$item = new Item($db);

// query items
$stmt = $item->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

  // items array
  $items_arr=array();
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
      "list_id" => $list_id,
      "list_name" => $list_name
    );

    array_push($items_arr["items"], $item);
  }

  echo json_encode($items_arr);
}

else{
  echo json_encode(
    array("message" => "No items found.")
  );
}
