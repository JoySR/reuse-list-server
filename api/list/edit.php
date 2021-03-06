<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

ini_set('date.timezone','Asia/Shanghai');

// get database connection
include_once '../config/db.php';

include_once '../lib/JWT.php';

// instantiate item object
include_once '../objects/list.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$list = new Reuse_List($db);
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
      // set ID property of list to be edited
      $list->id = $data->id;
      // set list property values
      $list->name = $data->name;

      if($list->update()) {
        $data = array(
          "status" => 1,
          "msg" => "Edit list succeeded."
        );
        print_r(json_encode($data));
      }
      else {
        $data = array(
          "status" => 0,
          "msg" => "Edit list failed."
        );
        print_r(json_encode($data));
      }
    }
  }
}
