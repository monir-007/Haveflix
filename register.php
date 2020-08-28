<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/Account.php");

    $account = new Account($con);

    if(isset($_POST["submitButton"])) {
        
        $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
        $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
        $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
        $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
        $email2 = FormSanitizer::sanitizeFormEmail($_POST["email2"]);
        $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
        $password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);

        $success = $account->register($firstName, $lastName, $username, $email, $email2, $password, $password2);

        if($success) {
            $_SESSION["userLoggedIn"]=$username;
            header("Location:index.php"); 
        }
    }
        function getInputValue($name){
        if(isset($_POST[$name])){
            echo $_POST[$name];
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to Haveflix</title>
        <link href="assets/logo/favicon.png" rel="icon">
        <link rel="stylesheet" type="text/css" href="assets/style/style.css" />
    </head>
    <body>
        
        <div class="signInContainer">

            <div class="column">

                <div class="header">
                    <img src="assets/logo/logo.png" title="Logo" alt="Site logo" />
                    <h3>Sign Up</h3>
                    <span>to continue to Haveflix</span>
                </div>

                <form method="POST">

                    <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                    <input type="text" name="firstName" placeholder="First name" value="<?php getInputValue("firstName") ?>" required>

                    <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                    <input type="text" name="lastName" placeholder="Last name" value="<?php getInputValue("lastName") ?>" required>

                    <?php echo $account->getError(Constants::$userNameCharacters); ?> 
                    <?php echo $account->getError(Constants::$userNameTaken); ?>   
                    <input type="text" name="username" placeholder="Username" value="<?php getInputValue("username") ?>" required>

                    
                    <?php echo $account->getError(Constants::$DidnotMatchEmail); ?>   
                    <?php echo $account->getError(Constants::$InvalidEmail); ?>   
                    <?php echo $account->getError(Constants::$userNameTaken); ?>   
                    <input type="email" name="email" placeholder="Email" value="<?php getInputValue("email") ?>" required>

                    <input type="email" name="email2" placeholder="Confirm email" value="<?php getInputValue("email2") ?>" required>

                    <?php echo $account->getError(Constants::$DidnotMatchPassword); ?>   
                    <?php echo $account->getError(Constants::$passwordLength); ?>   
                    <input type="password" name="password" placeholder="Password" required>

                    <input type="password" name="password2" placeholder="Confirm password" required>

                    <input type="submit" name="submitButton" value="SUBMIT">

                </form>

                <a href="login.php" class="signInMessage">Already have an account? <span class="signInSpan">Sign in here</span></a>

            </div>

        </div>

    </body>
</html>