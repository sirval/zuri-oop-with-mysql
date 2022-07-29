<?php
include_once 'Dbh.php';

class FormController extends UserAuth{
    private static $db;
    private $fullname;
    private $email;
    private $password;
    private $confirmPassword;
    private $country;
    private $gender;
    private $id;

    public function __construct(){
        $this->db = new Dbh();
    }

    function handleForm()
    {
        switch(true) {
            case isset($_POST['register']):
                //unpack all data for registering
                $this->fullname = $_POST['fullnames'];
                $this->email = $_POST['email'];
                $this->password = $_POST['password'];
                $this->confirmPassword = $_POST['confirmPassword'];
                $this->gender = $_POST['gender'];
                $this->country = $_POST['country'];
                $this->register($this->fullname, $this->email, $this->password, $this->confirmPassword, $this->country, $this->gender);
                break;
            case isset($_POST['login']):
                //unpack all data for login
                $this->email = $_POST['email'];
                $this->password = $_POST['password'];
                $this->login($this->email, $this->password);
                break;
            case isset($_POST['logout']):
                //unpack all data for logout
                $this->email = $_POST['email'];
                $this->logout($this->email);
                break;
            case isset($_POST['delete']):
                //unpack all data for deleting
                $this->id = $_POST['id'];
                $this->deleteUser($this->id);
                break;
            case isset($_POST['reset']):
                //unpack all data for updating password
                $this->email = $_POST['email'];
                $this->password = $_POST['password'];
                $this->updateUser($this->email, $this->password);
                break;
            case isset($_GET['all']):
                //unpack all data for getting all users
                $this->getAllUsers();
                break;
            default:
                echo 'No form was submitted';
                break;
        }
    }
}


