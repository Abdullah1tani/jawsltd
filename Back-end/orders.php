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
    <link rel="Stylesheet" href="../assets/orders.css">
    <link rel="Stylesheet" href="../assets/BackNavBar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <title>Orders List</title>
    <style>
      #history{
        background-color: #a98467;
        border: none;
        color: white;
        border-radius: 40px;
        margin-left: 10px;
        padding-left: 15px;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 130px;
        }
        #history:hover{
          background-color:#E3242B;
        }
    </style>
  </head>

  <?php
    function makeItemTable($file_path, $table_id) 
    {
      echo "<table id='".$table_id."' class='table table-hover'>";
      echo "<thead id='categories'><tr><th>Name</th><th>Supplier</th><th>Price</th></tr></thead><tbody id='products'>";
      $item_file = fopen($file_path, "r+");
      while(($item_data = fgetcsv($item_file)) !== false) {
        
        echo "<tr>";
        foreach($item_data as $itemCell) {
          echo "<td>" . htmlspecialchars($itemCell) . "</td>";
        }
        echo "</tr>";
      }
      fclose($item_file);
      echo "</tbody></table>";
    }
    function orderStatus()
    {
      if(isset($_POST['noButton']))
      {
        $file_path = fopen('../csv-files/InQueue.csv','w+');
        fclose($file_path);
        echo "<script type='text/javascript'> alert('Order declined'); if (window.history.replaceState) {window.history.replaceState(null, null, window.location.href);} location.reload(); </script>";
      }
      else if(isset($_POST['yesButton']))
      {
        $queue= fopen("../csv-files/InQueue.csv", "r+");
        $ordered = fopen("../csv-files/Ordered.csv", "a+");
        {
          while (!feof($queue)) 
          {
            $item_arr = fgetcsv($queue);
            if (gettype($item_arr) != 'boolean')
            {
              fputcsv($ordered, $item_arr);
            }
          }
        }
        fclose($queue);
        fclose($ordered);

        $queue= fopen("../csv-files/InQueue.csv", "w+");
        fclose($queue);

        echo "<script type='text/javascript'> alert('Order approved'); if (window.history.replaceState) {window.history.replaceState(null, null, window.location.href);} location.reload(); </script>";
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
          <h2 class="subhead">Orders List</h2>
          <div class="container-fluid">
            <div class="row">
              <div class="form-group col-5">
                <h3>Items Ordered:</h3>
                <div class="row">
                  <div class="form-group col-7">
                    <input type="text" id="search2" onkeyup="filterOrderTable()" placeholder="Search for items">
                  </div>
                  <div class="form-group col-3">
                    <form action="../Back-end/orders.php" method="POST">
                    <?php 
                    if($_SESSION['UserPositionLoggedIn'] == 'Supervisor')
                      {
                        echo '<input type="submit" id="history" name="clearHistory" value="clear table">';
                      } 
                    ?>         
                    </form>
                  </div>
                </div>
                <?php
                  if($_SESSION['UserPositionLoggedIn'] == 'Supervisor')
                  {
                      if(isset($_POST['clearHistory']))
                    {
                      $file_path = fopen('../csv-files/Ordered.csv','w+');
                      fclose($file_path);
                      echo "<script type='text/javascript'> alert('table cleared'); if (window.history.replaceState) {window.history.replaceState(null, null, window.location.href);} location.reload(); </script>";
                    }
                  }
                  makeItemTable('../csv-files/Ordered.csv','orderTable');
                ?>
              </div>
              <div class="form-group col-5">
                <h3>Items Queued:</h3>
                <input type="text" id="search1" onkeyup="filterQueueTable()" placeholder="Search for items">
                <?php
                  makeItemTable('../csv-files/InQueue.csv','queueTable'); 
                ?>
              </div>
              <?php
                if($_SESSION['UserPositionLoggedIn'] == 'Supervisor')
                {
                  echo '
                  <div class="form-group approvalBox col-2">
                  <h6>Approve order?</h6>
                  <form action="../Back-end/orders.php" method="POST">
                  <input type="submit" id="yes" value="Yes" name="yesButton">
                  <input type="submit" id="no" value="No" name="noButton">
                  </form>
                  </div>';
                  orderStatus();
                }
              ?>
            </div>
          </div>
        </div>    
      </div>
    </div>
  </body>
  <script>
    function filterQueueTable() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search1");
    filter = input.value.toUpperCase();
    table = document.getElementById("queueTable");
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
  function filterOrderTable() 
  {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search2");
    filter = input.value.toUpperCase();
    table = document.getElementById("orderTable");
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

  if(window.history.replaceState) {window.history.replaceState(null, null, window.location.href);}
  </script>
</html>