<?php
class Account {

    private $con;
    private $errorArray = array();

    public function __construct($con) {
        $this->con = $con;
    }

        //UPDATE DETAILS
    public function updateDetails($fn, $ln, $em, $un){
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateNewEmail($em, $un); 

        if(empty($this->errorArray)){
            //UPDATE DATA
            $query = $this->con->prepare("UPDATE users SET firstName=:fn, lastName=:ln, email=:em
                                        WHERE username=:un");
            
            $query->bindValue(":fn", $fn);
            $query->bindValue(":ln", $ln);
            $query->bindValue(":em", $em);
            $query->bindValue(":un", $un);

            return $query->execute();
        }
        return false;
    }

    public function register($fn, $ln, $un, $em, $em2, $pw, $pw2){
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateUserName($un);
        $this->validateEmail($em, $em2);
        $this->validatePassword($pw, $pw2);

        if(empty($this->errorArray)){
            return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
        }
        return false;
    }

    public function login($un, $pw){
        $pw = hash("sha512", $pw);

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
        $query->bindValue(":un", $un);
        $query->bindValue(":pw", $pw);
        
        $query->execute();

        if($query->rowCount()==1){
            return true;
        }
        array_push($this->errorArray, Constants::$loginFailed);
        return false;
    }

    private function insertUserDetails($fn, $ln, $un, $em, $pw){
        $pw = hash("sha512", $pw);

        $query = $this->con->prepare("INSERT INTO users (firstName, lastName, username, email, password)
                                    VALUES(:fn, :ln, :un, :em, :pw)");
        $query->bindValue(":fn", $fn);
        $query->bindValue(":ln", $ln);
        $query->bindValue(":un", $un);
        $query->bindValue(":em", $em);
        $query->bindValue(":pw", $pw);


        return $query->execute();
    }

    private function validateFirstName($fn) {
        if(strlen($fn) < 2 || strlen($fn) > 25) {
            array_push($this->errorArray, Constants::$firstNameCharacters);
        }
    }
    private function validateLastName($ln) {
        if(strlen($ln) < 2 || strlen($ln) > 25) {
            array_push($this->errorArray, Constants::$lastNameCharacters);
        }
    }
    private function validateUserName($un) {
        if(strlen($un) < 4 || strlen($un) > 15) {
            array_push($this->errorArray, Constants::$userNameCharacters);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un");
        $query->bindValue(":un", $un);

        $query->execute();
        if($query->rowCount()!=0){
            array_push($this->errorArray, Constants::$userNameTaken);
        }
    }

    private function validateEmail($em, $em2){
        if($em != $em2){
            array_push($this->errorArray, Constants::$DidnotMatchEmail);
            return;
        }
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray, Constants::$InvalidEmail);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE email=:em");
        $query->bindValue(":em", $em);

        $query->execute();
        if($query->rowCount()!=0){
            array_push($this->errorArray, Constants::$userNameTaken);
        }
    }

    //UPDATE EMAIL INFO
    private function validateNewEmail($em, $un){

        if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray, Constants::$InvalidEmail);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users 
                                    WHERE email=:em
                                    AND username != :un");
        $query->bindValue(":em", $em);
        $query->bindValue(":un", $un);

        $query->execute();
        if($query->rowCount()!=0){
            array_push($this->errorArray, Constants::$userNameTaken);
        }
    }
    private function validatePassword($pw, $pw2){
        if($pw != $pw2){
            array_push($this->errorArray, Constants::$DidnotMatchPassword);
            return;
        }
        // Validate password strength
        $uppercase = preg_match('@[A-Z]@', $pw);
        $lowercase = preg_match('@[a-z]@', $pw);
        $number    = preg_match('@[0-9]@', $pw);
        $specialChars = preg_match('@[^\w]@', $pw);

        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($pw) <= 8 || strlen($pw) > 18) {
            array_push($this->errorArray, Constants::$passwordLength);
        }
    }


    public function getError($error) {
        if(in_array($error, $this->errorArray)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }

    public function getFirstError(){
        if(!empty($this->errorArray)){
            return $this->errorArray[0];
        }
    }

    public function updatePassword($oldPw, $pw, $pw2, $un){
        $this->validateOldPassword($oldPw, $un);
        $this->validatePassword($pw, $pw2);

        if(empty($this->errorArray)){
            //UPDATE DATA
            $query = $this->con->prepare("UPDATE users 
                                        SET password=:pw
                                        WHERE username=:un");
            $pw = hash("sha512", $pw);
            $query->bindValue(":pw", $pw);
            $query->bindValue(":un", $un);

            return $query->execute();
        }
        return false;

    }

    public function validateOldPassword($oldPw, $un){
        $pw = hash("sha512", $oldPw);

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
        $query->bindValue(":un", $un);
        $query->bindValue(":pw", $pw);
        
        $query->execute();
        
        if($query->rowCount() ==0){
            array_push($this->errorArray,Constants::$passwordIncorrect );

        }
    }

}
?>