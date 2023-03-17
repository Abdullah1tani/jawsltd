<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<?php
    function checkUser() {
        if(isset($_POST["Sign-in"])) {
            $form_username = $_POST["Username"];
            $form_password = $_POST["Password"];
            $position = null;
            if(($user_file = fopen("../csv-files/Users.csv", "r")) != FALSE) {
                while(($user_data = fgetcsv($user_file, 1000, ",")) != FALSE) {
                    $user_array[] = $user_data;
                }
                foreach($user_array as $data) {
                    if($form_username == trim($data[0]) and $form_password == trim($data[1])) {
                        $valid_user = TRUE;
                        $position = $data[2];
                        break;
                    }
                    else {
                        $valid_user = FALSE;
                    }
                }
                if($valid_user) {
                    $_SESSION['UserNameLoggedIn'] = $_POST["Username"];
                    $_SESSION['UserPositionLoggedIn'] = $position;
                    echo "<script type='text/javascript'> alert('You are now logged in!'); window.location.replace('../Back-end/Products.php'); </script>";
                }
                else {
                    echo "<script type='text/javascript'> alert('Your username or password is incorrect, please try again!'); </script>";
                }
            }
            fclose($user_file);
        }
    }
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/Admin-SignIn.css">
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <title>Sign in</title>
</head>

<body>
    <div class="top">
     <h1> Welcome to J.A.W.S. Procurement System! </h1>
    <p id="description">We believe happiness begins with food. Here at J.A.W.S Procurement, we connect you with hundreds <br> of local farmers  to bring the freshest fruits and veggies in the region. <br>All of our products are carefully selected and certified organic.    </p>
    </div>

    <div class="container AdminSignIn">
        <h2 class="row justify-content-sm-center mt-3">Sign In</h2>
        <?php
        checkUser()
        ?>
        <form action="../Back-end/SignIn.php" method="post">
            <br>
            <div class="row justify-content-sm-center">
                <div class="form-group col-sm-7">
                    <label for="Username">Username: </label>
                    <input class="form-control AdminLoginInput" id="Username" type="text" name="Username" placeholder="Username" required>
                </div>
            </div>
            <br>
            <div class="row justify-content-sm-center">
                <div class="form-group col-sm-7">
                    <label for="Password">Password: </label>
                    <input class="form-control AdminLoginInput" type="password" name="Password" id="Password" placeholder="Password" required>
                    <input type="checkbox" id="ShowPassword" onclick="showPassword()"></input>
                    <label for="ShowPassword">Show Password</label>
                </div>
            </div>
            <br>
            <div class="row justify-content-sm-center">
                <div class="form-group col-sm-6">
                    <button type="submit" name="Sign-in" class="btn btn-primary" id="AdminLoginButton">Submit</button>
                </div>
            </div>
        </form>
    </div>
</body>
<script type="text/javascript">
    function showPassword() {
    var x = document.getElementById("Password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
    }
</script>
</html>