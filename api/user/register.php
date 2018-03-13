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

include_once '../config/core.php';

// instantiate user object
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set user property values
$user->username = $data->username;
$user->email = $data->email;
$user->password = $data->password;
$user->created = date('Y-m-d H:i:s');
$user->salt = $salt;

if ($user->check_exists_user()) {
  $data = array(
    "status" => 0,
    "msg" => "Username or email address exists, please use another one."
  );
  print_r(json_encode($data));
} else {
// create the user
  if($user->register()){
    $data = array(
      "status" => 1,
      "msg" => "Register succeeded."
    );
    print_r(json_encode($data));
  }
  else{
    $data = array(
      "status" => 0,
      "msg" => "Register failed."
    );
    print_r(json_encode($data));
  }
}
