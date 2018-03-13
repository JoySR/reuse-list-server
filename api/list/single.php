<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/db.php';
include_once '../objects/list.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare list object
$list = new Reuse_List($db);

// set ID property of list to be edited
$list->id = isset($_GET['list_id']) ? $_GET['list_id'] : die();

// read the details of list to be edited
$list->readOne();

if ($list->name) {
  // create array
  $list_arr = array(
    "id" =>  $list->id,
    "name" => $list->name,
    "archived" => $list->archived
  );
  $data = array(
    "status" => 1,
    "msg" => "Fetch single list succeeded.",
    "list" => $list_arr
  );
  print_r(json_encode($data));
} else {
  $data = array(
    "status" => 0,
    "msg" => "Fetch single list failed."
  );
  print_r(json_encode($data));
}
