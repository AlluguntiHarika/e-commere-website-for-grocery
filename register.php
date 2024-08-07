<?php
include 'config.php';

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = md5($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'user email already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert = $conn->prepare("INSERT INTO `users`(name, email, password, image) VALUES(?,?,?,?)");
         $insert->execute([$name, $email, $pass, $image]);

         if($insert){
            if($image_size > 2000000){
               $message[] = 'image size is too large!';
            }else{
               move_uploaded_file($image_tmp_name, $image_folder);
               $message[] = 'registered successfully!';
               header('location:login.php');
            }
         }

      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>
<body>

<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>
   
<section class="form-container">

   <form action="" enctype="multipart/form-data" method="POST">
      <h3>register now</h3>
      <input type="text" name="name" class="box" placeholder="enter your name" required>
      <input type="email" name="email" class="box" placeholder="enter your email" required>
      <input type="password" name="pass" class="box" placeholder="enter your password" required>
      <input type="password" name="cpass" class="box" placeholder="confirm your password" required>
      <input type="file" name="image" class="box"  accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="register now" class="btn" name="submit">
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>

</section>

<script>
	function signupValidation() {
	    var valid = true;

	    $("#name").removeClass("error-field");
	    $("#email").removeClass("error-field");
	    $("#pass").removeClass("error-field");
	    $("#cpass").removeClass("error-field");

	    var name = $("#name").val();
	    var email = $("#email").val();
	    var pass = $('#pass').val();
	    var cpass = $('#cpass').val();
       
       var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	    var passRegex = /^(?=.\d)(?=.[a-z])(?=.*[A-Z]).{8,}$/; // Corrected password regex

	    $("#name-info").html("").hide();
	    $("#email-info").html("").hide();

	    if (name.trim() == "") {
	        $("#name-info").html("required.").css("color", "#ee0000").show();
	        $("#name").addClass("error-field");
	        valid = false;
	    }
	    if (email == "") {
	        $("#email-info").html("required").css("color", "#ee0000").show();
	        $("#email").addClass("error-field");
	        valid = false;
	    } else if (!emailRegex.test(email)) {
	        $("#email-info").html("Invalid email address.").css("color", "#ee0000").show();
	        $("#email").addClass("error-field");
	        valid = false;
	    }
	    if (pass.trim() == "") {
	        $("#pass-info").html("required.").css("color", "#ee0000").show();
	        $("#pass").addClass("error-field");
	        valid = false;
	    } else if (!passRegex.test(pass)) {
	        $("#pass-info").html("Password must contain at least 8 characters with at least one uppercase letter, one lowercase letter, one digit, and one special character.").css("color", "#ee0000").show();
	        $("#pass").addClass("error-field");
	        valid = false;
	    }
	    if (cpass.trim() == "") {
	        $("#cpass-info").html("required.").css("color", "#ee0000").show();
	        $("#cpass").addClass("error-field");
	        valid = false;
	    } else if (pass != cpass) {
	        $("#cpass-info").html("Confirm password does not match").css("color", "#ee0000").show();
	        $("#cpass").addClass("error-field");
	        valid = false;
	    }

	    if (valid == false) {
	        $('.error-field').first().focus();
	        valid = false;
	    }
	    return valid;
	}
</script>





</body>
</html>