<?php
class database
{
    function opencon()
    {
        return new PDO('mysql:host=localhost;dbname=phpoop_221','root','');
    }
 
    function check($username, $password){
        $con = $this->opencon();
        $query = "SELECT user from users WHERE user='".$username. "'&&pass='".$password."'";
        return $con->query($query)->fetch();
    }
    function signup($username, $firstname, $lastname, $sex, $birthday, $password, $confirm){
        $con = $this->opencon();
 
    $query = $con->prepare("SELECT user FROM users WHERE user= ?");
    $query->execute([$username]);
    $existingUser = $query->fetch();
 
    if ($existingUser){
        return false;
    }
   
    return $con->prepare("INSERT INTO users (firstname, lastname, sex, user, birthday, pass) VALUES (?,?,?,?,?,?)")
                ->execute([$username,$firstname, $lastname, $birthday, $sex, $password, $confirm]);  
}

function signupUser($firstname, $lastname, $birthday, $sex, $username,$password){
    $con = $this->opencon();

$query = $con->prepare("SELECT user FROM users WHERE user= ?");
$query->execute([$username]);
$existingUser = $query->fetch();

if ($existingUser){
    return false;
}

 $con->prepare("INSERT INTO users (firstname, lastname, birthday, sex, user, pass) VALUES (?,?,?,?,?,?)")
            ->execute([$firstname, $lastname, $birthday, $sex, $username, $password]);
            return $con -> lastInsertId();   
}

function insertAddress($user_id, $street, $barangay, $city, $province){
    $con = $this->opencon();

    return $con -> prepare ("INSERT INTO user_address  ( user_id, user_street, user_barangay, user_city, user_province) VALUES (?,?,?,?,?)")
    ->execute([$user_id, $street, $barangay, $city, $province]);
}
function view(){
    $con = $this->opencon();
    return $con->query("SELECT users.user_id,users.firstname,users.lastname,users.birthday,users.sex,users.user, CONCAT(user_address.user_street,' ',user_address. user_barangay,' ',user_address.user_city,' ',user_address.user_province) AS Address FROM user_address INNER JOIN users ON user_address.user_id = users.user_id")->fetchAll();
}
function Delete($id)
{
    try{
$con = $this->opencon();
$con->beginTransaction();
//Delete user address
$query = $con->prepare("DELETE FROM user_address WHERE user_id = ?");
$query->execute([$id]);
//Delete user address
$query2 = $con->prepare("DELETE FROM users WHERE user_id = ?");
$query2->execute([$id]);
$con->commit();
return true; //Deletion succesful
    } catch(PDOException $e) {
        $con->rollBack();
        return false;
  }
}

function viewdata($id){
    try{
    $con= $this-> opencon();
    $query= $con->prepare("SELECT users.user_id,users.firstname,users.lastname,users.birthday,users.sex,users.user,users.pass, user_address.user_street, user_address. user_barangay, user_address.user_city, user_address.user_province FROM user_address INNER JOIN users ON user_address.user_id = users.user_id WHERE users.user_id=?");
    $query->execute ([$id]);
    return $query->fetch();
    
} catch (PDOException $e){
    return[];
}
}
function UpdateUser($id,$firstname,$lastname,$birthday,$sex,$username,$password){
    try{
        $con = $this->opencon();
        $query= $con->prepare("UPDATE users SET firstname=?, lastname=?, birthday=?, sex=?, user=?,pass=? WHERE user_id=?");
        return $query->execute([$firstname,$lastname,$birthday,$sex,$username,$password, $id]);
    }catch(PDOException $e){
        return false;
}
}

function UpdateUserAdd($id, $barangay, $street, $city, $province){
    try{
        $con = $this->opencon();
        $query= $con->prepare("UPDATE user_address SET user_barangay=?, user_street=?, user_city=?, user_province=? WHERE user_id=?");
        return $query->execute([$barangay, $street, $city, $province, $id]);
    }catch(PDOException $e){
        return false;
}
}
} 