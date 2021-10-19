<?php require_once 'config.php';

try {
  // order_id is valid of it is present, is an integer with a minimum value of 1
  $rules = [
    'order_id' => 'present|integer|min:1'
  ];

  // you can have a look at the validate() function in HttpRequest.php (line 415)
  $request->validate($rules);
  if (!$request->is_valid()) {
    throw new Exception("Illegal request");
  }
  //the order_id was passed in from order-index.php
  // now you extract it from the request object and assign the value to the variable $order_id  
  $order_id = $request->input('order_id');


  /*Retrieving the correct Order object ($order) from the Database*/
  //Call findById(id) function to check if this order exists in the Database
  $order = Order::findById($order_id);
  // if order does not exist display error message 
  if ($order === null) {
    throw new Exception("No order exists or Illegal request parameter");
  }

  // $order is an object - created from the Order class
  // calling $order->delete() calls the delete function in the Order class
  $order->delete();

  // Display redirect to the list of orders and display the correct message - Deleted or Not Deleted
  $request->session()->set("flash_message", "The order was successfully deleted from the database");
  $request->session()->set("flash_message_class", "alert-info");
  $request->redirect("/order-index.php");
} catch (Exception $ex) {
  /*If something goes wrong, catch the message and store it as a flash message.*/
  $request->session()->set("flash_message", $ex->getMessage());
  $request->session()->set("flash_message_class", "alert-warning");

  $request->redirect("/order-index.php");
}