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
$item->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of item to be edited
$item->readOne();

// create array
$item_arr = array(
  "id" =>  $item->id,
  "name" => $item->name,
  "checked" => $item->checked,
  "list_id" => $item->list_id,
  "list_name" => $item->list_name

);

// make it json format
print_r(json_encode($item_arr));
