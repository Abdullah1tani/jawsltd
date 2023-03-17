<?php
  session_start();
  if($_SESSION['UserPositionLoggedIn'] != 'Supervisor')
  {
    header('Location: ../Back-end/Products.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/users.css">
    <link rel="stylesheet" href="../assets/BackNavBar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />    
    <title>User list</title>
</head>

<?php
  if(isset($_POST["AddUsername"]))
  {
    $username = $_POST["AddUsername"];
    $password = $_POST["AddPassword"];
    $position = $_POST["AddPosition"];
    $user_file = fopen("../csv-files/Users.csv", "a+");
    $userDetails = array($username, $password, $position);
    fputcsv($user_file, $userDetails);
    fclose($user_file);   
    echo "<script type='text/javascript'> alert('User added'); if (window.history.replaceState) {window.history.replaceState(null, null, window.location.href);}</script>";
  }
  function makeUserTable() 
  {
    echo '<table id="itemTable" class="table table-hover"><thead id="categories"><tr><th>Username</th><th>Position</th></tr></thead><tbody>';
    if(($user_file = fopen("../csv-files/Users.csv", "r")) != FALSE) 
    {
      while(($user_data = fgetcsv($user_file, 1000, ",")) != FALSE) 
      {
        echo '<tr><td>'. $user_data[0] . '</td><td>' . $user_data[2] . '</td></tr>';
      }
      echo '</tbody></table>';
    }
    fclose($user_file);
  }

  function removeUserTable()
  {
    if(isset($_POST['removeUserButton']))
    {
      if($_POST['removeUser'] == $_SESSION['UserNameLoggedIn'])
      {
        echo "<script type='text/javascript'> alert('Cannot remove logged in username'); </script>";
      }
      else
      {
        $user_exist = false;
        $remove_user = $_POST['removeUser'];
        $user_read = fopen("../csv-files/Users.csv", "r+");
        $temp_write = fopen("../csv-files/UsersTemp.csv", "w+");
        while (!feof($user_read)) 
        {
          $user_arr = fgetcsv($user_read);
          if (gettype($user_arr) != 'boolean' && $remove_user != $user_arr[0]) 
          {
            fputcsv($temp_write, $user_arr);
          }
          else if(gettype($user_arr) != 'boolean' && $remove_user == $user_arr[0])
          {
            $user_exist = true;
          }
        }
        fclose($temp_write);
        fclose($user_read);

        if(!$user_exist)
        {
          echo "<script type='text/javascript'> alert('Username does not exist'); </script>";
        }
        else
        {
          $user_write = fopen("../csv-files/Users.csv", "w+");
          $temp_read = fopen("../csv-files/UsersTemp.csv", "r+");
          while (!feof($temp_read)) 
          {
            $user_arr = fgetcsv($temp_read);
            if(gettype($user_arr) != 'boolean')
            {
              fputcsv($user_write, $user_arr);
            }
          }
          fclose($temp_read);
          fclose($user_write);
          echo "<script type='text/javascript'> alert('Username removed'); if (window.history.replaceState) {window.history.replaceState(null, null, window.location.href);} location.reload(); </script>";
        }
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
          <li class="nav-item">
            <a class="nav-link" href="../Back-end/users.php">Users</a>
          </li>
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
        <h2 class="subhead">Users List</h2>
        <button type="button" class="btn btn-primary" id="addUser" onclick="window.location.href = '../Back-end/AddUser.php';">Add a User</button>
        <form action="../Back-end/users.php" method="POST">
          <input type="text" name="removeUser" id="search" onkeyup="filterTable()" placeholder="Search for user">
          <input type="submit" value="remove" id="removeButton" name="removeUserButton">
        </form>      
        <?php
          makeUserTable();
          removeUserTable();
        ?>
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

if (window.history.replaceState) {window.history.replaceState(null, null, window.location.href);}
</script>
</html>