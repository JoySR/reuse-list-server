<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/db.php';
include_once '../objects/item.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare item object
$item = new Item($db);
$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
$token = null;
$headers = apache_request_headers();

if(isset($headers['Authorization'])){
  $matches = array();
  preg_match('/Bearer (.*)/', $headers['Authorization'], $matches);
  if(isset($matches[1])){
    $token = $matches[1];
    if ($user->check_token($token)) {
      // set ID property of item to be edited
      $item->list_id = $data->list_id;

      if($item->reuse()) {
        $data = array(
          "status" => 1,
          "msg" => "Reuse all items succeeded."
        );
        print_r(json_encode($data));
      }
      else {
        $data = array(
          "status" => 0,
          "msg" => "Reuse all items failed."
        );
        print_r(json_encode($data));
      }
    }
  }
}
