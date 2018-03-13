<?php
// Reference: https://www.codeofaninja.com/2017/02/create-simple-rest-api-in-php.html

class Item{
  // database connection and table name
  private $conn;
  private $table_name = "items";

  // object properties
  public $id;
  public $name;
  public $checked;
  public $list_id;
  public $list_name;
  public $created;

  // constructor with $db as database connection
  public function __construct($db){
    $this->conn = $db;
  }

  // read items
  function read(){

    // select all query
    $query = "SELECT
                c.name as list_name, p.id, p.name, p.checked, p.list_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    lists c
                        ON p.list_id = c.id
            ORDER BY
                p.created DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    return $stmt;
  }

  // create item
  function create(){

    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, checked=:checked, list_id=:list_id, created=:created";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->checked=htmlspecialchars(strip_tags($this->checked));
    $this->list_id=htmlspecialchars(strip_tags($this->list_id));
    $this->created=htmlspecialchars(strip_tags($this->created));

    // bind values
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":checked", $this->checked);
    $stmt->bindParam(":list_id", $this->list_id);
    $stmt->bindParam(":created", $this->created);

    // execute query
    if($stmt->execute()){
      return true;
    }

    return false;
  }

  // used when filling up the update item form
  function readOne(){

    // query to read single record
    $query = "SELECT
                c.name as list_name, p.id, p.name, p.checked, p.list_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    lists c
                        ON p.list_id = c.id
            WHERE
                p.id = ?
            LIMIT
                0,1";

    // prepare query statement
    $stmt = $this->conn->prepare( $query );

    // bind id of item to be updated
    $stmt->bindParam(1, $this->id);

    // execute query
    $stmt->execute();

    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // set values to object properties
    $this->name = $row['name'];
    $this->checked = $row['checked'];
    $this->list_id = $row['list_id'];
    $this->list_name = $row['list_name'];
  }

  // used when list all items in a list
  function readAllInList($list_id){

    // query to read single record
    $query = "SELECT
                p.id, p.name, p.checked, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    lists c
                        ON p.list_id = c.id
            WHERE
                c.id = ?
            ORDER BY
                p.created DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // bind id of item to be updated
    $stmt->bindParam(1, $list_id);

    // execute query
    $stmt->execute();

    return $stmt;
  }

  // update the item
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

  // delete the item
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

  // search items
  function search($keywords){

    // select all query
    $query = "SELECT
                c.name as list_name, p.id, p.name, p.checked, p.list_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    lists c
                        ON p.list_id = c.id
            WHERE
                p.name LIKE ? OR c.name LIKE ?
            ORDER BY
                p.created DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);

    // execute query
    $stmt->execute();

    return $stmt;
  }

  // check / uncheck the item
  function check(){
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                checked = :checked
            WHERE
                id = :id";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->checked=htmlspecialchars(strip_tags($this->checked));
    $this->id=htmlspecialchars(strip_tags($this->id));

    // bind new values
    $stmt->bindParam(':checked', $this->checked);
    $stmt->bindParam(':id', $this->id);

    // execute the query
    if($stmt->execute()){
      return true;
    }
    return false;
  }

  // uncheck all items
  function reuse() {
    // select all query
    $query = "SELECT
                p.id, p.list_id
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    lists c
                        ON p.list_id = c.id";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // execute query
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $id = $row['id'];

      // update query
      $query2 = "UPDATE
                " . $this->table_name . "
                SET
                    checked = :checked
                WHERE
                    id = :id";

      // prepare query statement
      $stmt2 = $this->conn->prepare($query2);

      // bind new values
      $stmt2->bindParam(':checked', 0);
      $stmt2->bindParam(':id', $id);

      // execute the query
      if($stmt2->execute()){
        return true;
      }
      return false;
    }
  }

  // read items with pagination
  public function readPaging($from_record_num, $records_per_page){

    // select query
    $query = "SELECT
                c.name as list_name, p.id, p.name, p.checked, p.list_id, p.created
              FROM
                  " . $this->table_name . " p
                  LEFT JOIN
                      lists c
                          ON p.list_id = c.id
              ORDER BY p.created DESC
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

  // used for paging items
  public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name;

    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['total_rows'];
  }
}
