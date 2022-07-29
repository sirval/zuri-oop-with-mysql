<?php
error_reporting(0);
include_once 'Dbh.php';

class UserAuth extends Dbh{
    private static $db;

    public function __construct(){
        $this->db = new Dbh();
    }


    public function register($fullname, $email, $password, $confirmPassword, $country, $gender){
        $conn = $this->db->connect();
        if($this->confirmPasswordMatch($password, $confirmPassword)){
           if($this->checkEmailExist($email)){
            header("location: ./forms/register.php?message =error3");
            return ;
           }
            
            $sql = "INSERT INTO Students (`full_names`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')";
            if($conn->query($sql)){
               header("location: ./forms/login.php?message=success");
            } else {
                header("location: ./forms/register.php?message =error1");
            }
        }else {
            header("location: ./forms/register.php?message=error2");
        }
    }

    public function login($email, $password){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students WHERE email='$email' AND `password`='$password'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while ($get_username = $result->fetch_array()) {
                $username = $get_username['full_names'];
            }
            $_SESSION['email'] = $email;
            $_SESSION['username'] = $username;
            header("Location: ./dashboard.php");
        } else {
            header("location: ./forms/login.php?message=error1");
        }
    }

    public function getUser($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getAllUsers(){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students";
        $result = $conn->query($sql);
        echo"<html>
        <head>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
        </head>
        <body>
        <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
        <table class='table table-bordered' border='0.5' style='width: 80%; background-color: smoke; border-style: none'; >
        <tr style='height: 40px'>
            <thead class='thead-dark'> <th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th>
        </thead></tr>";
        if (isset($_GET['all']) =='success') {
            echo "<tr style='border: 0'><td colspan='6'><div class='alert alert-success'>User Deleted Successfully</td></tr><br></div>";
        }
        if (isset($_GET['all']) ) {
            }
        if($result->num_rows > 0){
            while($data = mysqli_fetch_assoc($result)){
                //show data
                echo "<tr style='height: 20px'>".
                    "<td style='width: 50px; background: gray'>" . $data['id'] . "</td>
                    <td style='width: 150px'>" . $data['full_names'] .
                    "</td> <td style='width: 150px'>" . $data['email'] .
                    "</td> <td style='width: 150px'>" . $data['gender'] . 
                    "</td> <td style='width: 150px'>" . $data['country'] . 
                    "</td>
                    <td style='width: 150px'> 
                    <form action='./action.php' method='post'>
                    <input type='hidden' name='id' value=" . $data['id'] . ">
                    <input type='hidden' name='all' value='all'>".
                    "<button class='btn btn-danger' type='submit' name='delete'> DELETE </button> </form> </td>".
                    "</tr>";
            }
            echo "</table></table></center></body></html>";
        }
    }

    public function deleteUser($id){
        $conn = $this->db->connect();
        $sql = "DELETE FROM Students WHERE id = '$id'";
        if($conn->query($sql) === TRUE){
            header("refresh:0.5; url=action.php?all=success");
        } else {
            header("refresh:0.5; url=action.php?all=?message=Error");
        }
    }

    public function updateUser($username, $password){
        $conn = $this->db->connect();
        $sql = "UPDATE users SET password = '$password' WHERE username = '$username'";
        if($conn->query($sql) === TRUE){
            header("Location: ../dashboard.php?update=success");
        } else {
            header("Location: forms/resetpassword.php?error=1");
        }
    }

    public function getUserByUsername($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function logout($email){
        if (isset($email)) {
            session_start();
            session_destroy();
            header('Location: ./index.php');
        }
    }

    public function confirmPasswordMatch($password, $confirmPassword){
        if($password === $confirmPassword){
            return true;
        } else {
            return false;
        }
    }

    public function checkEmailExist($email){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
           while ($get_user_email = $result->fetch_array()) {
            $existing_email = $get_user_email['email'];
            if ($existing_email === $email) {
                return true;
            }else {
                return false;
            }
           }
        }
    }
}