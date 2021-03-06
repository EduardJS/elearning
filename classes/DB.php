<?php
    class DB
    {
        public function __construct()
        {
            if (!self::$con) {
                self::$con = new MySQLi('localhost', 'root', 'password', 'elearning');
            }
        }

        public static $con;
        public static $config = [
            'cookie_name'       => 'elearning_session',
            'website_title'     => 'eLearning App',
            'pusher_app_id'     => '109308',
            'pusher_app_key'    => 'a906e3112e21c93de22b',
            'pusher_app_secret' => '090f410197850b16797e',
            'to_be_paid'        => 670,
        ];

        public static function query($statement)
        {
            $result = self::$con->query($statement);

            self::$con->error && printf('error[%s]', $statement);

            return $result;
        }

        public static function insertID()
        {
            return self::$con->insertID;
        }

        public static function random($length)
        {
            $result = '';
            $chr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';

            for ($i = 0; $i < $length; ++$i) {
                $result .= $chr[mt_rand(0, strlen($chr) - 1)];
            }

            return $result;
        }

        public static function reload($location = false)
        {
            // close connection if previously opened
            if (self::$con) {
                self::$con->close();
            }

            if (!$location) {
                $location = '/';
            }

            die(header('Location: '.$location));
        }
    }
