<?php  
session_start();

# check if username & password submitted
if(isset($_POST['username']) && isset($_POST['password'])){

   # database connection file
   include '../db.conn.php';
   
   # get data from POST request and store them in var
   $password = $_POST['password'];
   $username = $_POST['username'];
   
   # simple form Validation
   if(empty($username)){
      # error message
      $em = "Username is required";
      header("Location: ../../index.php?error=$em");
      exit;
   } else if(empty($password)){
      # error message
      $em = "Password is required";
      header("Location: ../../index.php?error=$em");
      exit;
   } else {
      $sql  = "SELECT * FROM users WHERE username=?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$username]);

      # if the username exists
      if($stmt->rowCount() === 1){
         # fetching user data
         $user = $stmt->fetch();

         # verify password
         if (password_verify($password, $user['password'])) {
            # login success
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_id'] = $user['user_id'];

            header("Location: ../../home.php");
            exit;
         } else {
            # incorrect password
            $em = "Incorrect username or password";
            header("Location: ../../index.php?error=$em");
            exit;
         }
      } else {
         # username not found in database
         $em = "Username and password not registered. Please sign up.";
         header("Location: ../../index.php?error=$em");
         exit;
      }
   }
} else {
   header("Location: ../../index.php");
   exit;
}
