<?php require_once 'config.php';

try {
  $rules = [
    'order_id' => 'present|integer|min:1'
  ];
  $request->validate($rules);
  if (!$request->is_valid()) {
    throw new Exception("Illegal request");
  }
  $order_id = $request->input('order_id');
  /*Retrieving a customer object*/
  $order = Order::findById($order_id);
  if ($order === null) {
    throw new Exception("Illegal request parameter");
  }
} catch (Exception $ex) {
  $request->session()->set("flash_message", $ex->getMessage());
  $request->session()->set("flash_message_class", "alert-warning");

  $request->redirect("/order-index.php");
}

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Edit Order</title>

  <link href="<?= APP_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?= APP_URL ?>/assets/css/template.css" rel="stylesheet">
  <link href="<?= APP_URL ?>/assets/css/style.css" rel="stylesheet">
  <link href="<?= APP_URL ?>/assets/css/form.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&display=swap" rel="stylesheet">


</head>

<body>
  <div class="container-fluid p-0">
    <?php require 'include/navbar.php'; ?>
    <main role="main">
      <div>
        <div class="row d-flex justify-content-center">
          <h1 class="t-peta engie-head pt-5 pb-5">Edit Order</h1>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-8">
            <?php require "include/flash.php"; ?>
          </div>
        </div>

        <div class="row justify-content-center pt-4">
          <div class="col-lg-10">
            <form method="post" action="<?= APP_URL ?>/order-update.php" enctype="multipart/form-data">

              <!--This is how we pass the ID-->
              <input type="hidden" name="order_id" value="<?= $order->id ?>" />


              <div class="form-group">
                <label class="labelHidden" for="productName">Product Name</label>
                <input placeholder="Product Name" name="product_name" type="text" id="productName" class="form-control" value="<?= old('product_name', $order->product_name) ?>" />
                <span class="error"><?= error("product_name") ?></span>
              </div>

              <!--textarea does not have a 'value' attribute, so in this case we have to put our php for filling in the old form data INSIDE the textarea tag.-->
              <div class="form-group">
                <label class="labelHidden" for="date">Price</label>
                <textarea placeholder="Price" name="price" rows="3" id="price" class="form-control"><?= old('price', $order->price) ?></textarea>
                <span class="error"><?= error("price") ?></span>
              </div>

              <div class="form-group">
                <label class="labelHidden" for="deliveryAddress">Delivery Address</label>
                <input placeholder="Delivery Address" type="text" name="delivery_address" class="form-control" id="deliveryAddress" value="<?= old("delivery_address", $order->delivery_address) ?>" />
                <span class="error"><?= error("delivery_address") ?></span>
              </div>

              <div class="form-group">
                <label class="labelHidden" for="dateOrdered">Date Ordered</label>
                <input placeholder="Date Ordered" type="date" name="date_ordered" class="dateInput form-control" id="dateOrdered" value="<?= old("date_ordered", $order->date_ordered) ?>" />
                <span class="error"><?= error("date_ordered") ?></span>
              </div>

              <div class="form-group">
                <label>Order image:</label>
                <?php
                $image = Image::findById($order->image_id);
                if ($image != null) {
                ?>
                  <img src="<?= APP_URL . "/" . $image->filename ?>" width="150px" />
                <?php
                }
                ?>
                <input type="file" name="profile" id="profile" />
                <span class="error"><?= error("profile") ?></span>
              </div>

              <div class="form-group">
                <a class="btn btn-default" href="<?= APP_URL ?>/order-index.php">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
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