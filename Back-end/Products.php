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
      <link rel="Stylesheet" href="../assets/Products.css">
      <link rel="Stylesheet" href="../assets/BackNavBar.css">
      <link rel="preconnect" href="https://fonts.googleapis.com/" />
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
      <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
      />
      <title>Product List</title>
  </head>

<?php
  if(isset($_POST["AddProduct"]))
  {
    $product = $_POST["AddProduct"];
    $supplier = $_POST["AddSupplier"];
    $price = $_POST["AddPrice"];
    $product_file = fopen("../csv-files/Items.csv", "a+");
    $productDetails = array($product, $supplier, $price);
    fputcsv($product_file, $productDetails);
    fclose($product_file);
    echo "<script type='text/javascript'> alert('Item added'); if (window.history.replaceState) {window.history.replaceState(null, null, window.location.href);}</script>";
  }
  function makeItemTable() 
  {
    echo "<table id='itemTable' class='form-group table table-hover'>\n\n";
    echo "<thead id='categories'><tr><th>Name</th><th>Supplier</th><th>Price</th></tr></thead><tbody id='products'>";
    $item_file = fopen("../csv-files/Items.csv", "r");
    while(($item_data = fgetcsv($item_file)) !== false) {
      echo "<tr>";
      foreach($item_data as $itemCell) {
        echo "<td>" . htmlspecialchars($itemCell) . "</td>";
      }
      echo "</tr>\n";
    }
    fclose($item_file);
    echo "\n</tbody></table>";
  }
  function orderItem() 
  {
    $price = 0;
    $item_file = fopen("../csv-files/Items.csv", "r");
    if(isset($_POST["submitOrder"])) {
      $itemOrder = $_POST["itemOrder"];
      while(($item_data = fgetcsv($item_file)) !== false) {
        $item_array[] = $item_data;
      }
      foreach($item_array as $data) {
        if($itemOrder == $data[0] and $price == 0) {
          $supplier = $data[1];
          $price = $data[2];
        }
        elseif($itemOrder == $data[0] and $data[2] < $price) {
          $supplier = $data[1];
          $price = $data[2];
        }
      }
      
      if($price == 0) {
        echo "<script type='text/javascript'> alert('Item not found'); </script>";
      }
      else {
        $orderDetails = array($itemOrder, $supplier, $price);
        $queue = fopen("../csv-files/InQueue.csv", "a+");
        $orderList = fopen("../csv-files/Ordered.csv", "a+");
        $itemQueue = fgetcsv($queue);
        if($price >= 5000) {
          fputcsv($queue, $orderDetails);
          echo "<script type='text/javascript'> alert('Item in queue'); </script>";
        }
        elseif($price < 5000) {
          fputcsv($orderList, $orderDetails);
          echo "<script type='text/javascript'> alert('Item ordered'); </script>";
        }  
        fclose($queue);
        fclose($orderList);
      }
      fclose($item_file);
    }
  }
  function removeItemTable()
  {
    if(isset($_POST['removeItemButton']))
    {
      $item_exist = false;
      $supplier_exist = false;
      $remove_item = $_POST['removeItem'];
      $remove_supplier = $_POST['removeSupplier'];
      $item_read = fopen("../csv-files/Items.csv", "r+");
      $temp_write = fopen("../csv-files/ItemsTemp.csv", "w+");
      while (!feof($item_read)) 
      {
        $item_arr = fgetcsv($item_read);
        if (gettype($item_arr) != 'boolean' && ($remove_item != $item_arr[0] || $remove_supplier != $item_arr[1])) 
        {
          fputcsv($temp_write, $item_arr);
        }
        else if(gettype($item_arr) != 'boolean' && $remove_item == $item_arr[0] && $remove_supplier == $item_arr[1])
        {
          $item_exist = true;
          $supplier_exist = true;
        }
        else if(gettype($item_arr) != 'boolean' && $remove_item == $item_arr[0] && $remove_supplier != $item_arr[1])
        {
          $item_exist = true;
          $supplier_exist = false;
        }
        else if(gettype($item_arr) != 'boolean' && $remove_item != $item_arr[0] && $remove_supplier == $item_arr[1])
        {
          $item_exist = false;
          $supplier_exist = true;
        }
      }
      fclose($temp_write);
      fclose($item_read);
      
      if(!$item_exist || !$supplier_exist)
      {
        echo "<script type='text/javascript'> alert('Item or supplier does not exist'); </script>";
      }
      else
      {
        $item_write = fopen("../csv-files/Items.csv", "w+");
        $temp_read = fopen("../csv-files/ItemsTemp.csv", "r+");
      while (!feof($temp_read)) 
      {
        $user_arr = fgetcsv($temp_read);
        if(gettype($user_arr) != 'boolean')
        {
          fputcsv($item_write, $user_arr);
        }
      }
      fclose($temp_read);
      fclose($item_write);
      echo "<script type='text/javascript'> alert('Item removed'); if (window.history.replaceState) {window.history.replaceState(null, null, window.location.href);} location.reload(); </script>";
      }
    }
  }
?>

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

    <div class="container-fluid main-page">
      <div class="row">
        <div>
          <h2 class="subhead">Products List</h2>
          <form action="../Back-end/Products.php" method="post">
            <div class="container-fluid">
              <div class="row">
                <div>
                  <button type="button" class="btn btn-primary" id="addProduct" onclick="window.location.href = '../Back-end/AddProduct.php';">Add a product</button><br>
                  <input type="text" name="itemOrder" id="search" onkeyup="filterTable()" placeholder="Search for products">
                  <input type="submit" value="order" id="orderButton" name="submitOrder">
                  <input type="text" name="removeItem" id="searchRemove" placeholder="item">
                  <input type="text" name="removeSupplier" id="searchRemove" placeholder="supplier">
                  <input type="submit" value="remove" id="removeButton" name="removeItemButton">
                </div>
                <?php
                  orderItem();
                  removeItemTable();
                  makeItemTable();
                ?>
                
              </div>
            </div>
          </form>          
        </div>    
      </div>
    </div>
  </body>

<script>
function filterTable() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("itemTable");
  tr = table.getElementsByTagName("tr");
  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
          } 
          else {
              tr[i].style.display = "none";
          }
      }
  }
}

if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}
</script>
</html>