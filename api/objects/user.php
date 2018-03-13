<?php
class User{

  // database connection and table name
  private $conn;
  private $table_name = "users";

  // object properties
  public $id;
  public $username;
  public $password;
  public $email;
  public $timestamp;
  public $created;
  public $salt;

  // constructor with $db as database connection
  public function __construct($db){
    $this->conn = $db;
  }

  function check_exists_user() {
    // select all query
    $query = "SELECT
                username, email
              FROM
                  " . $this->table_name . "
              WHERE
                  username = ? OR email = ?
              LIMIT
                  0,1";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->email=htmlspecialchars(strip_tags($this->email));

    // bind
    $stmt->bindParam(1, $this->username);
    $stmt->bindParam(2, $this->email);

    // execute query
    $stmt->execute();

    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // set values to object properties
    $username = $row['username'];

    echo $username;

    if ($username) {
      return true;
    }
    return false;
  }

  function register(){

    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
              SET
                  username=:username,
                  password=:password,
                  email=:email,
                  created=:created";

    // prepare query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->password=htmlspecialchars(strip_tags($this->password));
    // crypt
    $this->password = crypt($this->password, $this->salt);
    $this->email=htmlspecialchars(strip_tags($this->email));
    $this->created=htmlspecialchars(strip_tags($this->created));

    // bind values
    $stmt->bindParam(":username", $this->username);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":created", $this->created);

    // execute query
    if($stmt->execute()){
      return true;
    }
    return $stmt->errorInfo();
  }

  function login() {
    // select all query
    $query = "SELECT
                username, password
              FROM
                  " . $this->table_name . "
              WHERE
                  username = ?
              LIMIT
                  0,1";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->password=htmlspecialchars(strip_tags($this->password));

    // bind
    $stmt->bindParam(1, $this->username);

    // execute query
    $stmt->execute();

    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // set values to object properties
    $db_password = $row['password'];

    if ($db_password === crypt($this->password, $this->salt)) {
      return true;
    }
    return false;
  }

  function logout() {
    $query = "UPDATE
                " . $this->table_name . "
              SET
                  timestamp = :timestamp
              WHERE
                  username = :username";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->timestamp=NULL;
    $this->username=htmlspecialchars(strip_tags($this->username));

    // bind new values
    $stmt->bindParam(':timestamp', $this->timestamp);
    $stmt->bindParam(':username', $this->username);

    // execute the query
    if($stmt->execute()){
      return true;
    }
    return false;
  }

  function generate_token() {
    // header
    $header = '{
            "typ":"JWT",
            "alg":"HS256"
        }';

    // payload
    $issuer = "sun";
    $this->timestamp = $_SERVER['REQUEST_TIME'];
    $expired = 14 * 24 * 60 * 60;
    $expired_time_stamp = $this->timestamp + $expired;

    $payload = "{
      \"iss\":\"$issuer\",
      \"username\": \"$this->username\",
      \"iat\":$this->timestamp,
      \"exp\":$expired_time_stamp,
      \"code\":\"el psy congroo\"
    }";

    // signature
    // TODO: extract key to config
    $key = '221a4f3ea068d7f08b3fc60328c2161db13a1d18';

    // generate token
    $JWT = new JWT;
    $token = $JWT->encode($header, $payload, $key);

    // save current_time_stamp in db for checking token
    $query = "UPDATE
                " . $this->table_name . "
              SET
                  timestamp = :timestamp
              WHERE
                  username = :username";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->timestamp=htmlspecialchars(strip_tags($this->timestamp));
    $this->username=htmlspecialchars(strip_tags($this->username));

    // bind new values
    $stmt->bindParam(':timestamp', $this->timestamp);
    $stmt->bindParam(':username', $this->username);

    // execute the query
    if($stmt->execute()){
      return $token;
    }
    return false;
  }

  function check_token($token) {

    $key = '221a4f3ea068d7f08b3fc60328c2161db13a1d18';

    $JWT = new JWT;
    $json = $JWT->decode($token, $key);

    // json_decode: string => object
    // (array): object => array
    $json_array = (array) json_decode($json);
    $this->username = $json_array['username'];
    $iat = $json_array['iat'];

    // check if $iat equals $timestamp in db
    $query = "SELECT
                username, timestamp
              FROM
                  " . $this->table_name . "
              WHERE
                  username = ?
              LIMIT
                  0,1";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // bind
    $stmt->bindParam(1, $this->username);

    // execute query
    $stmt->execute();

    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // set values to object properties
    $this->timestamp = $row['timestamp'];

    if ($this->timestamp == $iat) {
      return true;
    }
    return false;
  }
}
