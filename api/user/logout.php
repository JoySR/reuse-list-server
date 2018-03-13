<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/db.php';

// instantiate user object
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set user property values
$user->username = $data->username;

// create the user
if($user->logout()){
  $data = array(
    "status" => 1,
    "msg" => "Logout succeeded."
  );
  print_r(json_encode($data));
}
else{
  $data = array(
    "status" => 0,
    "msg" => "Logout failed."
  );
  print_r(json_encode($data));
}
