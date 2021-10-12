<?php
// the class order defines the structure of what every order object will look like. ie. each order will have an id, title, description etc...
// NOTE : For handiness I have the very same spelling as the database attributes
class order {
  public $id;
  public $product_name;
  public $price;
  public $delivery_address;
  public $date_ordered;
  public $image_id;

  public function __construct() {
    $this->id = null;
  }

  public function save() {
    throw new Exception("Not yet implemented");
  }

  public function delete() {
    throw new Exception("Not yet implemented");
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
          $order = new order();
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
    throw new Exception("Not yet implemented");
  }
}
?>
