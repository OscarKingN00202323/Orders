<?php require_once 'config.php'; ?>
<?php 
$orders = order::findAll();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Orders</title>

    <link href="<?= APP_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= APP_URL ?>/assets/css/template.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <?php require 'include/header.php'; ?>
      <?php require 'include/navbar.php'; ?>
      <main role="main">
        <div>
          <h1>Our orders</h1>
          <div class="row">
          <?php foreach ($orders as $order) { ?>
            <div class="col mb-4">
              <div class="card" style="width:15rem;">
              <?php
                  // Use the image ID in festival, go to the Image table and get the image file name which includes the file location 
                  $order_image = Image::findById($order->image_id);
                  if ($order_image !== null) {
                  ?>
                    <!-- use the filename/location to display the correct image-->
                    <img src="<?= APP_URL . "/" . $order_image->filename ?>" class="card-img-top" alt="...">
                  <?php
                  }
                  ?>
                
                <div class="card-body">
                  <h5 class="card-title"><?= $order->product_name ?></h5>
                  <p class="card-text">Price: <?= get_words($order->price, 20) ?></p>
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item">Delivery Address: <?= $order->delivery_address ?></li>
                  <li class="list-group-item">Date Ordered: <?= $order->date_ordered ?></li>
                </ul>
              </div>
            </div>
          <?php } ?>
          </div>
        </div>
      </main>
      <?php require 'include/footer.php'; ?>
    </div>
    <script src="<?= APP_URL ?>/assets/js/jquery-3.5.1.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
