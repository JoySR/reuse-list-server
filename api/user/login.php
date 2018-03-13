<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/db.php';

// get global variables;
include_once '../config/core.php';

// instantiate user object
include_once '../objects/user.php';

include_once '../lib/JWT.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set user property values
$user->username = $data->username;
$user->password = $data->password;
$user->salt = $salt;

// create the user
if($user->login()){
  $token = $user->generate_token();
  $data = array(
    "status" => 1,
    "msg" => "Login succeeded.",
    "token" => $token
  );
  print_r(json_encode($data));
}
else{
  $data = array(
    "status" => 0,
    "msg" => "Login failed."
  );
  print_r(json_encode($data));
}
