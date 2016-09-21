<?php

class Db {
    // The database connection
    protected static $connection;

    /**
     * Connect to the database
     * 
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
    public function connect() {    
        // Try and connect to the database
        if(!isset(self::$connection)) {
            // Load configuration as an array. Use the actual location of your configuration file
            $config = parse_ini_file('./config.ini'); 
            self::$connection = new mysqli('localhost',$config['username'],$config['password'],$config['dbname']);
        }

        // If connection was not successful, handle the error
        if(self::$connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }
        return self::$connection;
    }

    /**
     * Query the database
     *
     * @param $query The query string
     * @return mixed The result of the mysqli::query() function
     */
    public function query($query) {
        // Connect to the database
        $connection = $this -> connect();

        // Query the database
        $result = $connection -> query($query);

        return $result;
    }

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param $query The query string
     * @return bool False on failure / array Database rows on success
     */
    public function select($query) {
        $rows = array();
        $result = $this -> query($query);
        if($result === false) {
            return false;
        }
        while ($row = $result -> fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch the last error from the database
     * 
     * @return string Database error message
     */
    public function error() {
        $connection = $this -> connect();
        return $connection -> error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param string $value The value to be quoted and escaped
     * @return string The quoted and escaped string
     */
    public function quote($value) {
        $connection = $this -> connect();
        return "'" . $connection -> real_escape_string($value) . "'";
    }
}

    class User
    {
        public $prefix;
        public $firstName;
        public $lastName;
        public $suffix;



        function savePerson(){
            $db = new Db();

            // Quote and escape form submitted values
            $first_name = $db -> quote($this->firstName);
            $second_name = $db -> quote($this->lastName);

            // Insert the values into the database
                $result = $db -> query("INSERT INTO `users` (`first_name`,`second_name`) VALUES (" . $first_name . "," . $second_name . ")");

        }
        function get_person_by_last_name(){
                $db = new Db();
                $res = $db -> select("SELECT * FROM 'users' WHERE second_name = '".$this->lastName."'");

                while ($x = $res){
                    echo $x['first_name'].' '.$x['second_name'];
                }
        }

        function getUsers(){
            $db = new Db();
            $res = $db -> select("SELECT * FROM users");

            echo '<table>';
            foreach ($res as $x) {
                echo '<tr>';
                echo '<td>'.$x['first_name'].'</ td>';
                echo '<td>'.$x['second_name'].'</ td>';
                echo '</tr>';
            }
            echo '</table>';
        }
        public function GetProperties()
        {
            // $acceptsPets = 1;
            return [
                ['7439', 'Craster Reach', '1', 'Craster', 'no smoking', "pets $acceptsPets"],
                ['2105', 'Richard House', '5', 'chester', 'smoking', "pets $acceptsPets"]
            ];
        }
    }
    $user = new User();
    $user->firstName = "Peter";
    $user->lastName = "Johnson";
    echo($user->firstName);
    echo($user->lastName);
    $user->savePerson();
    $user->get_person_by_last_name();       // fixed error in Function name - $user->get_person_by_last_ name();
    $user->getUsers();
    $data = @$user->GetProperties(true);
    foreach ($data as $value):
        echo $value[1] . ", sleeps {$value[2]} <br />";
    endforeach;
?>