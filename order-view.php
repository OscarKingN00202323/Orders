<?php require_once 'config.php';

try {

  // The $rules array has 3 rules, order_id must be present, must be an integer and have a minimum value of 1.  
  // note order_id was passed in from order_index.php when you chose a order by clicking a radio button. 
  $rules = [
    'order_id' => 'present|integer|min:1'
  ];
  // $request->validate() is a function in HttpRequest(). You pass in the 3 rules above and it does it's magic. 
  $request->validate($rules);
  if (!$request->is_valid()) {
    throw new Exception("Illegal request");
  }

  // get the order_id out of the request (remember it was passed in from order_index.php)
  $order_id = $request->input('order_id');
 
  //Retrieve the order object from the database by calling findById($order_id) in the Order.php class
  $order = Order::findById($order_id);
  if ($order === null) {
    throw new Exception("Illegal request parameter");
  }
} catch (Exception $ex) {
  $request->session()->set("flash_message", $ex->getMessage());
  $request->session()->set("flash_message_class", "alert-warning");

  // some exception/error occured so re-direct to the index page
  $request->redirect("/home.php");
}

?>



<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>View Customer</title>

  <link href="<?= APP_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?= APP_URL ?>/assets/css/template.css" rel="stylesheet">
  <link href="<?= APP_URL ?>/assets/css/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&display=swap" rel="stylesheet">


</head>

<body>
  <div class="container-fluid p-0">
    <?php require 'include/navbar.php'; ?>
    <main role="main">
      <div>
        <div class="row d-flex justify-content-center">
          <h1 class="t-peta engie-head pt-5 pb-5">View Order</h1>
        </div>

        <div class="row justify-content-center pt-4">
          <div class="col-lg-10">
            <form method="get">
              <!--This is how we pass the ID-->
              <input type="hidden" name="order_id" value="<?= $order->id ?>" />

              <!--Disabled so the user can't intereact. This form is for viewing only.-->

              <div class="form-group">
                <label class="labelHidden" for="venueCapacity">Product Name</label>
                <input placeholder="Product Name" type="text" class="form-control" id="productName" value="<?= $order->product_name ?>" disabled />
              </div>

              <div class="form-group">
                <label class="labelHidden" for="venueCapacity">Price</label>
                <input placeholder="Price" type="text" class="form-control" id="price" value="<?= $order->price ?>" disabled />
              </div>

              <div class="form-group">
                <label class="labelHidden" for="venueDescription">Delivery Address</label>
                <input placeholder="Delivery Address" type="text" id="deliveryAddress" class="form-control" value="<?= $order->delivery_address ?>" disabled />
              </div>

              <div class="form-group">
                <label class="labelHidden" for="venueDescription">Date Ordered</label>
                <input placeholder="Date Ordered" type="date" id="dateOrdered" class="form-control" value="<?= $order->date_ordered ?>" disabled />
              </div>

              <div class="form-group">
                <label class="labelHidden" for="venueDescription">Image</label>
                <?php
                try {
                  $image = Image::findById($order->image_id);
                } catch (Exception $e) {
                }
                if ($image !== null) {
                ?>
                  <img src="<?= APP_URL . "/" . $image->filename ?>" width="205px" alt="image" class="mt-2 mb-2" />
                <?php
                }
                ?>
              </div>

              <div class="form-group">
                <a class="btn btn-default" href="<?= APP_URL ?>/order-index.php">Cancel</a>
                <button class="btn btn-warning" formaction="<?= APP_URL ?>/order-edit.php">Edit</button>
                <button class="btn btn-danger btn-order-delete" formaction="<?= APP_URL ?>/order-delete.php">Delete</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
    <?php require 'include/footer.php'; ?>
  </div>
  <script src="<?= APP_URL ?>/assets/js/jquery-3.5.1.min.js"></script>
  <script src="<?= APP_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= APP_URL ?>/assets/js/order.js"></script>

  <script src="https://kit.fontawesome.com/fca6ae4c3f.js" crossorigin="anonymous"></script>

</body>

</html>