<?php

require_once './model/Database.php';
require_once './model/Validator.php';
require_once 'autoload.php';

class Controller {

    private $action;
    private $db;
    private $twig;

    /**
     * Instantiates a new controller
     */
    public function __construct() {
        $loader = new Twig\Loader\FilesystemLoader('./view');
        $this->twig = new Twig\Environment($loader);
        $this->setupConnection();
        $this->connectToDatabase();
        $this->twig->addGlobal('session', $_SESSION);
        $this->action = $this->getAction();
    }

    /**
     * Initiates the processing of the current action
     */
    public function invoke() {
        switch ($this->action) {
            case 'User_Profile':
                $this->processShowUserProfilePage();
                break;
            case 'Personal_Training':
                $this->processShowPersonalTrainingPage();
                break;
            case 'register_class':
                $this->processRegisterForClass();
                break;
            case 'Logout':
                $this->processLogout();
                break;
            case 'Register':
                $this->processShowRegisterPage();
                break;
            case 'Process_Registration':
                $this->processRegistration();
                break;
            case 'Login':
                $this->processShowLoginPage();
                break;
            case 'Home':
                $this->processShowHomePage();
                break;
            default:
                $this->processShowHomePage();
                break;
        }
    }

    /*     * **************************************************************
     * Process Request
     * ************************************************************* */

    /**
     * Show user profile page
     */
    private function processShowUserProfilePage() {
        $Customer_id = $_SESSION['customer_id'];
        $classes = $this->db->getClassesDetailsByCustomer($Customer_id);
        $username = $_SESSION['username'];
        
        $template = $this->twig->load('user_profile.twig');
        echo $template->render(['classes' => $classes, 'Customer_id' => $Customer_id, 'username' => $username]);
    }

    /**
     * Process show personal training page
     */
    private function processShowPersonalTrainingPage() {
        $classes = $this->db->getClasses();

        if ($_SESSION['is_valid_user'] == true) {
            $signedIn = true;
        } else {
            $signedIn = false;
        }
        
        $Customer_id = $_SESSION['customer_id'];

        $template = $this->twig->load('personal_training.twig');
        echo $template->render(['classes' => $classes, 'signed_in' => $signedIn, 'Customer_id' => $Customer_id]);
    }
    
    /**
     * Process register for a class
     */
    private function processRegisterForClass() {
        $Customer_id = filter_input(INPUT_POST, 'Customer_id');
        $Class_id = filter_input(INPUT_POST, 'Class_id');

        $this->db->registerClass($Customer_id, $Class_id);

        header("Location: .?action=User_Profile");
    }
    
    /**
     * Process Logout
     */
    private function processLogout() {
        $_SESSION = array();
        session_destroy();
        $this->twig->addGlobal('session', $_SESSION);
        $login_message = 'You have been logged out.';
        $template = $this->twig->load('login.twig');
        echo $template->render(['login_message' => $login_message, 'session']);
    }

    /**
     * Shows the Register page
     */
    private function processShowRegisterPage() {
        $error_username = '';
        $error_password = '';
        $template = $this->twig->load('register.twig');
        echo $template->render(['error_username' => $error_username, 'error_password' => $error_password]);
    }

    /**
     * Process Registration
     */
    private function processRegistration() {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password');
        $first_name = filter_input(INPUT_POST, 'firstName');
        $last_name = filter_input(INPUT_POST, 'lastName');
        $address = filter_input(INPUT_POST, 'address');
        $city = filter_input(INPUT_POST, 'city');
        $state = filter_input(INPUT_POST, 'state');
        $postal = filter_input(INPUT_POST, 'postal');
        $phone = filter_input(INPUT_POST, 'phone');
        $email = filter_input(INPUT_POST, 'email');

        $validator = new Validator($this->db);
        $error_username = $validator->validateUsername($username);
        $error_password = $validator->validatePassword($password);
        $error_firstName = $validator->validateValue($first_name);
        $error_lastname = $validator->validateValue($last_name);
        $error_address = $validator->validateAddress($address);
        $error_city = $validator->validateValue($city);
        $error_state = $validator->validateValue($state);
        $error_postal = $validator->validatePostal($postal);
        $error_email = $validator->validateValue($email);

        if (!empty($error_username) || !empty($error_password) || !empty($error_firstName) || !empty($error_lastname) || !empty($error_address) || !empty($error_city) || !empty($error_state) || !empty($error_postal) || !empty($error_email)) {
            $template = $this->twig->load('register.twig');
            echo $template->render(['error_username' => $error_username, 'error_password' => $error_password, 'error_firstName' => $error_firstName, 'error_lastname' => $error_lastname,
                'error_address' => $error_address, 'error_city' => $error_city, 'error_state' => $error_state,
                'error_postal' => $error_postal, 'error_email' => $error_email]);
        } else {
            $this->db->addCustomer($username, $password, $first_name, $last_name, $address, $city, $state, $postal, $phone, $email);
            $_SESSION['customer_id'] = $this->db->getCustomerIdByUsername($username);
            $_SESSION['is_valid_user'] = true;
            $_SESSION['username'] = $username;
            header("Location: .?action=User_Profile");
        }
    }

    /**
     * Shows the Login page
     */
    private function processShowLoginPage() {
        $template = $this->twig->load('login.twig');
        echo $template->render();
    }

    /**
     * Shows the home page
     */
    private function processShowHomePage() {
        $template = $this->twig->load('home.twig');
        echo $template->render();
    }

    /**
     * Gets the action from $_GET or $_POST array
     * 
     * @return string the action to be processed
     */
    private function getAction() {
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($action === NULL) {
            $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($action === NULL) {
                $action = '';
            }
        }
        return $action;
    }

    /**
     * Ensures a secure connection and start session
     */
    private function setupConnection() {
        $https = filter_input(INPUT_SERVER, 'HTTPS');
        if (!$https) {
            $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
            $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
            $url = 'https://' . $host . $uri;
            header("Location: " . $url);
            exit();
        }
        session_start();
    }

    /**
     * Connects to the database
     */
    private function connectToDatabase() {
        $this->db = new Database();
        if (!$this->db->isConnected()) {
            $error_message = $this->db->getErrorMessage();
            $template = $this->twig->load('database_error.twig');
            echo $template->render(['error_message' => $error_message]);
            exit();
        }
    }

}
