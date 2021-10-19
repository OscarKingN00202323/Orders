<?php
// the class Order defines the structure of what every order object will look like. ie. each order will have an id, title, description etc...
// NOTE : For handiness I have the very same spelling as the database attributes
class Order {
  public $id;
  public $product_name;
  public $price;
  public $deliver_address;
  public $date_ordered;
  public $image_id;



  public function __construct() {
    $this->id = null;
  }

  public function save() {
    try {
      //Create the usual database connection - $conn
      $db = new DB();
      $db->open();
      $conn = $db->get_connection();

      $params = [
        ":product_name" => $this->product_name,
        ":price" => $this->price,
        ":delivery_address" => $this->delivery_address,
        ":date_ordered" => $this->date_ordered,
        ":image_id" => $this->image_id
      ];

      // We will uncomment this code when we get to do the Create 
      // If there is no ID yet, then it's a new order being created for the first time
      // if ($this->id === null) {
      //   $sql = "INSERT INTO orders (" .
      //     "title, description, location, start_date, end_date, contact_name, contact_email, contact_phone, image_id" .
      //     ") VALUES (" .
      //     ":title, :description, :location, :start_date, :end_date, :contact_name, :contact_email, :contact_phone, :image_id" .
      //     ")";
      // } else {
        // if there is an ID then it's an update for an existing order in the database. 
        $sql = "UPDATE orders SET " .
          "product_name = :product_name, " .
          "price = :price, " .
          "delivery_address = :delivery_address, " .
          "date_ordered = :date_ordered, " .
          "image_id = :image_id " .
          "WHERE id = :id";
        $params[":id"] = $this->id;
    //  }


      $stmt = $conn->prepare($sql);
      $status = $stmt->execute($params);

      if (!$status) {
        $error_info = $stmt->errorInfo();
        $message = "SQLSTATE error code = " . $error_info[0] . "; error message = " . $error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      if ($stmt->rowCount() !== 1) {
        throw new Exception("Failed to save order.");
      }

      //If the save() was a new order created it won't have an ID
      // so retrieve the ID assigned by the DB. - remember auto_increment in the Database for assigning primary keys
      // if ($this->id === null) {
      //   $this->id = $conn->lastInsertId();
      // }
    } finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }
  }

  public function delete() {
    try {
      /*Create connection.*/
      $db = new DB();
      $db->open();
      $conn = $db->get_connection();

      $sql = "DELETE FROM orders WHERE id = :id";
      $params = [
        ":id" => $this->id
      ];

      $stmt = $conn->prepare($sql);
      $status = $stmt->execute($params);

      if (!$status) {
        $error_info = $stmt->errorInfo();
        $message = "SQLSTATE error code = " . $error_info[0] . "; error message = " . $error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      if ($stmt->rowCount() !== 1) {
        throw new Exception("Failed to delete order.");
      }
    } finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }
  }

  public static function findAll() {
    $orders = array();

    try {
      // call DB() in DB.php to create a new database object - $db
      $db = new DB();
      $db->open();
      // $conn has a connection to the database
      $conn = $db->get_connection();
      

      // $select_sql is a variable containing the correct SQL that we want to pass to the database
      $select_sql = "SELECT * FROM orders";
      $select_stmt = $conn->prepare($select_sql);
      // $the SQL is sent to the database to be executed, and true or false is returned 
      $select_status = $select_stmt->execute();

      // if there's an error display something sensible to the screen. 
      if (!$select_status) {
        $error_info = $select_stmt->errorInfo();
        $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }
      // if we get here the select worked correctly, so now time to process the orders that were retrieved
      

      if ($select_stmt->rowCount() !== 0) {
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        while ($row !== FALSE) {
          // Create $order object, then put the id, title, description, location etc into $order
          $order = new Order();
          $order->id = $row['id'];
          $order->product_name = $row['product_name'];
          $order->price = $row['price'];
          $order->delivery_address = $row['delivery_address'];
          $order->date_ordered = $row['date_ordered'];
          $order->image_id = $row['image_id'];

          // $order now has all it's attributes assigned, so put it into the array $orders[] 
          $orders[] = $order;
          
          // get the next order from the list and return to the top of the loop
          $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        }
      }
    }
    finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }

    // return the array of $orders to the calling code - index.php (about line 6)
    return $orders;
  }

  public static function findById($id) {
    $order = null;

    try {
      $db = new DB();
      $db->open();
      $conn = $db->get_connection();

      $select_sql = "SELECT * FROM orders WHERE id = :id";
      $select_params = [
          ":id" => $id
      ];
      $select_stmt = $conn->prepare($select_sql);
      $select_status = $select_stmt->execute($select_params);

      if (!$select_status) {
        $error_info = $select_stmt->errorInfo();
        $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      if ($select_stmt->rowCount() !== 0) {
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
          
        $order = new Order();
        $order->id = $row['id'];
        $order->product_name = $row['product_name'];
        $order->price = $row['price'];
        $order->delivery_address = $row['delivery_address'];
        $order->date_ordered = $row['date_ordered'];
        $order->image_id = $row['image_id'];
      }
    }
    finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }

    return $order;
  }

  
}
?>
