<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../common/utilities.php';
include_once '../config/db.php';
include_once '../objects/list.php';

// utilities
$utilities = new Utilities();

// instantiate database and list object
$database = new Database();
$db = $database->getConnection();

// initialize object
$list = new Reuse_List($db);

// query lists
$stmt = $list->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 list found
if($num>0){

  // lists array
  $lists_arr=array();
  $lists_arr["lists"]=array();
  $lists_arr["paging"]=array();

  // retrieve our table contents
  // fetch() is faster than fetchAll()
  // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    // extract row
    // this will make $row['name'] to
    // just $name only
    extract($row);

    $lists=array(
      "id" => $id,
      "name" => $name,
      "archived" => $archived,
    );

    array_push($lists_arr["lists"], $lists);
  }


  // include paging
  $total_rows=$list->count();
  $page_url="{$home_url}list/read_paging.php?";
  $paging=$utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
  $lists_arr["paging"]=$paging;

  echo json_encode($lists_arr);
}

else{
  echo json_encode(
    array("message" => "No lists found.")
  );
}
