<?php

class Reuse_List{

  // database connection and table name
  private $conn;
  private $table_name = "lists";

  // object properties
  public $id;
  public $name;
  public $archived;
  public $created;

  // constructor with $db as database connection
  public function __construct($db){
    $this->conn = $db;
  }

  // read lists
  function read(){

    // select all query
    $query = "SELECT
                id, name, archived, created
              FROM
                  " . $this->table_name . "
              ORDER BY
                  created DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    return $stmt;
  }

  // create list
  function create(){

    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
              SET
                name=:name,
                archived=:archived,
                created=:created";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->archived=htmlspecialchars(strip_tags($this->archived));
    $this->created=htmlspecialchars(strip_tags($this->created));

    // bind values
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":archived", $this->archived);
    $stmt->bindParam(":created", $this->created);

    // execute query
    if($stmt->execute()){
      return true;
    }

    return false;
  }

  // used when filling up the update list form
  function readOne(){

    // query to read single record
    $query = "SELECT
                id, name, archived, created
              FROM
                  " . $this->table_name . "
              WHERE
                  id = ?
              LIMIT
                  0,1";

    // prepare query statement
    $stmt = $this->conn->prepare( $query );

    // bind id of list to be updated
    $stmt->bindParam(1, $this->id);

    // execute query
    $stmt->execute();

    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // set values to object properties
    $this->name = $row['name'];
    $this->archived = $row['archived'];
  }

  // update the list
  function update(){

    // update query
    $query = "UPDATE
                " . $this->table_name . "
              SET
                  name = :name
              WHERE
                  id = :id";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->id=htmlspecialchars(strip_tags($this->id));

    // bind new values
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':id', $this->id);

    // execute the query
    if($stmt->execute()){
      return true;
    }

    return false;
  }

  // delete the list
  function delete(){

    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));

    // bind id of record to delete
    $stmt->bindParam(1, $this->id);

    // execute query
    if($stmt->execute()){
      return true;
    }

    return false;
  }

  // search lists
  function search($keywords){

    // select all query
    $query = "SELECT
                id, name, archived, created
              FROM
                  " . $this->table_name . "
              WHERE
                  name LIKE ?
              ORDER BY
                  created DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // bind
    $stmt->bindParam(1, $keywords);

    // execute query
    $stmt->execute();

    return $stmt;
  }

  // archive/restore a list
  function archive() {
    // update query
    $query = "UPDATE
                " . $this->table_name . "
              SET
                  archived = :archived
              WHERE
                  id = :id";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->archived=htmlspecialchars(strip_tags($this->archived));
    $this->id=htmlspecialchars(strip_tags($this->id));

    // bind new values
    $stmt->bindParam(':archived', $this->archived);
    $stmt->bindParam(':id', $this->id);

    // execute the query
    if($stmt->execute()){
      return true;
    }
    return false;
  }

  // read lists with pagination
  public function readPaging($from_record_num, $records_per_page){

    // select query
    $query = "SELECT
                id, name, archived,created
              FROM
                  " . $this->table_name . "
              ORDER BY created DESC
              LIMIT ?, ?";

    // prepare query statement
    $stmt = $this->conn->prepare( $query );

    // bind variable values
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

    // execute query
    $stmt->execute();

    // return values from database
    return $stmt;
  }

  // used for paging lists
  public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name;

    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['total_rows'];
  }
}
