<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="../assets/bootstrap.min.css">
      <link rel="Stylesheet" href="../assets/AddProduct.css">
      <link rel="Stylesheet" href="../assets/BackNavBar.css">
      <link rel="preconnect" href="https://fonts.googleapis.com/" />
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
      <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
      />    
      <title>Add Porduct</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bgCol">
      <div class="container-fluid">
        <h2 class="navbar-brand" id="brandCol">J.A.W.S - <?php echo $_SESSION['UserPositionLoggedIn']; ?></h2>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <?php
              if($_SESSION['UserPositionLoggedIn'] == 'Supervisor')
              {
                echo '
                <li class="nav-item">
                  <a class="nav-link" href="../Back-end/users.php">Users</a>
                </li>';
              }
            ?>
            <li class="nav-item">
              <a class="nav-link" href="../Back-end/Products.php">Products</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Back-end/orders.php">Orders</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Back-end/logout.php">Log Out</a>
            </li>
          </ul>
        </div>
      </div>
    </nav> 
    <div class="container">
      <h2 class="row justify-content-md-center mt-5"> Add Product</h2>
      <form action="../Back-end/Products.php" method="post">
        <div class="row ProductRow">
          <div class="form-group col-md-6">
            <label for="ProductName">Product:</label>
            <input type="text" class="form-control" name="AddProduct" placeholder="Product" required>
          </div>
          <div class="form-group col-md-6">
            <label for="Supplier">Supplier:</label>
            <input type="text" class="form-control" name="AddSupplier" placeholder="Supplier" required>
          </div>
        </div>
        <div class="row ProductRow">
          <div class="form-group col-md-6">
            <label for="Price">Price: </label>
            <input type="text" class="form-control" name="AddPrice" placeholder="Price" required>
        </div>
            <button type="submit" name="submit" class="btn btn-primary" id="submitButton">Submit</button>   
        </form>
    </div>
  </body>
</html>
