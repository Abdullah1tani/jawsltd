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
      <link rel="stylesheet" href="../assets/AddUsers.css">
      <link rel="stylesheet" href="../assets/BackNavBar.css">
      <link rel="preconnect" href="https://fonts.googleapis.com/" />
      <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
      <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
      />    
      <title>Add User</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bgCol">
      <div class="container-fluid">
        <h2 class="navbar-brand" id="brandCol">J.A.W.S - <?php echo $_SESSION['UserPositionLoggedIn']; ?></h2>
        </button>
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
    <div class="container">
      <h2 class="row justify-content-md-center mt-5" >Add User</h2>
        <form action="../Back-end/users.php" method="post">
          <div class="row UserRow">
              <div class="form-group col-6">
                <label for="AddUsername">Username:</label>
                <input type="text" class="form-control" name="AddUsername" id="AddUsername" placeholder="Username" required>
              </div>
              <div class="form-group col-6">
              <label for="AddPosition">Position:</label><br>
              <input type="radio" id="employee" name="AddPosition" value="Employee">
              <label for="employee">Employee</label>
              <input type="radio" id="supervisor" name="AddPosition" value="Supervisor">
              <label for="supervisor">Supervisor</label><br>
            </div>
          </div>
          <div class="row UserRow">
            <div class="form-group col-6">
              <label for="AddPassword">Password:</label>
              <input type="password" class="form-control" name="AddPassword" id="AddPassword" placeholder="Password" required>
              <input type="checkbox" id="ShowAddPassword" onclick="showAddPassword()"></input>
              <label for="ShowAddPassword">Show Password</label>
            </div>
          </div>
          <br>
          <button type="submit" name="AddUser" class="btn btn-primary" id="submitButton">Submit</button>   
        </form>
    </div>
  </body>

  <script>
    function showAddPassword() 
    {
        var x = document.getElementById("AddPassword");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
</html>