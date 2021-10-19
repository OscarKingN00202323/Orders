<?php
require_once 'config.php';

try {
    // $request = new HttpRequest();


    $rules = [
        "order_id" => "present|integer|min:1",
        "product_name" => "present|minlength:2|maxlength:64",
        "price" => "present|minlength:2|maxlength:64",
        "delivery_address" => "present|minlength:2|maxlength:64",
        "date_ordered" => "present|minlength:10|maxlength:10",

    ];

    $request->validate($rules);
    if ($request->is_valid()) {
        $image = null;
        if (FileUpload::exists('profile')) {
            //If a file was uploded for profile,
            //create a FileUpload object
            $file = new FileUpload("profile");
            $filename = $file->get();
            //Create a new image object and save it.
            $image = new Image();
            $image->filename = $filename;

            // you must implement save() function in the Image.php class
            $image->save();
        }
        $order = Order::findById($request->input("order_id"));
        $order->product_name = $request->input("product_name");
        $order->price = $request->input("price");
        $order->delivery_address = $request->input("delivery_address");
        $order->date_ordered = $request->input("date_ordered");
        /*If not null, the user must have uploaded an image, so reset the image id to that of the one we've just uploaded.*/
        if ($image !== null) {
            $order->image_id = $image->id;
        }

        // you must implement the save() function in the Order class
        $order->save();

        $request->session()->set("flash_message", "The order was successfully updated in the database");
        $request->session()->set("flash_message_class", "alert-info");
        /*Forget any data that's already been stored in the session.*/
        $request->session()->forget("flash_data");
        $request->session()->forget("flash_errors");

        $request->redirect("/order-index.php");
    } else {
        $order_id = $request->input("order_id");
        /*Get all session data from the form and store under the key 'flash_data'.*/
        $request->session()->set("flash_data", $request->all());
        /*Do the same for errors.*/
        $request->session()->set("flash_errors", $request->errors());

        $request->redirect("/order-edit.php?order_id=" . $order_id);
    }
} catch (Exception $ex) {
    //redirect to the create page...
    $request->session()->set("flash_message", $ex->getMessage());
    $request->session()->set("flash_message_class", "alert-warning");
    $request->session()->set("flash_data", $request->all());
    $request->session()->set("flash_errors", $request->errors());

    // not yet implemented
    $request->redirect("/order-create.php");
}
