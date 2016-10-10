<?php

    class Pusher
    {
        private $settings = [];

        public function __construct($auth_key, $secret, $app_id)
        {
            $this->settings['app_id'] = $app_id;
            $this->settings['auth_key'] = $auth_key;
            $this->settings['secret'] = $secret;
        }

        private function create_curl($url, $request_method = 'GET', $query_params = [])
        {
            $url = sprintf('/apps/%s/%s', $this->settings['app_id'], $url);

            $signed_query = self::build_auth_query_string(
                $this->settings['auth_key'],
                $this->settings['secret'],
                $request_method,
                $url,
                $query_params
            );

            $full_url = sprintf('http://api.pusherapp.com%s?%s', $url, $signed_query);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $full_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            return $ch;
        }

        private function exec_curl($ch)
        {
            $response = [];

            $response['body'] = curl_exec($ch);
            $response['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            return $response;
        }

        public static function build_auth_query_string($auth_key, $auth_secret, $request_method, $request_path, $query_params = [], $auth_version = '1.0', $auth_timestamp = null)
        {
            $params = [];
            $params['auth_key'] = $auth_key;
            $params['auth_timestamp'] = (is_null($auth_timestamp) ? time() : $auth_timestamp);
            $params['auth_version'] = $auth_version;

            $params = array_merge($params, $query_params);
            ksort($params);

            $string_to_sign = "$request_method\n".$request_path."\n".self::array_implode('=', '&', $params);

            $auth_signature = hash_hmac('sha256', $string_to_sign, $auth_secret, false);

            $params['auth_signature'] = $auth_signature;
            ksort($params);

            $auth_query_string = self::array_implode('=', '&', $params);

            return $auth_query_string;
        }

        public static function array_implode($glue, $separator, $array)
        {
            if (!is_array($array)) {
                return $array;
            }

            $string = [];

            foreach ($array as $key => $val) {
                if (is_array($val)) {
                    $val = implode(',', $val);
                }
                $string[] = "{$key}{$glue}{$val}";
            }

            return implode($separator, $string);
        }

        public function trigger($channels, $event, $data, $socket_id = null, $already_encoded = false)
        {
            if (is_string($channels) === true) {
                $channels = [$channels];
            }

            $query_params = [];

            $data_encoded = $already_encoded ? $data : json_encode($data);

            $post_params = [];
            $post_params['name'] = $event;
            $post_params['data'] = $data_encoded;
            $post_params['channels'] = $channels;

            if ($socket_id !== null) {
                $post_params['socket_id'] = $socket_id;
            }

            $post_value = json_encode($post_params);

            $query_params['body_md5'] = md5($post_value);

            $ch = $this->create_curl('events', 'POST', $query_params);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_value);

            $response = $this->exec_curl($ch);

            return ($response['status'] == 200) ? true : false;
        }

        public function get_channel_info($channel, $params = [])
        {
            $response = $this->get('channels/'.$channel, $params);

            return ($response['status'] == 200) ? json_decode($response['body'], true) : false;
        }

        public function get_channels($params = [])
        {
            $response = $this->get('channels', $params);

            if ($response['status'] == 200) {
                $response = json_decode($response['body'], true);

                return $response['channels'];
            } else {
                return false;
            }
        }

        public function get($path, $params = [])
        {
            $ch = $this->create_curl($path, 'GET', $params);

            return $this->exec_curl($ch);
        }

        public function socket_auth($channel, $socket_id, $custom_data = false)
        {
            if ($custom_data == true) {
                $signature = hash_hmac('sha256', $socket_id.':'.$channel.':'.$custom_data, $this->settings['secret'], false);
            } else {
                $signature = hash_hmac('sha256', $socket_id.':'.$channel, $this->settings['secret'], false);
            }

            $signature = ['auth' => $this->settings['auth_key'].':'.$signature];

            if ($custom_data) {
                $signature['channel_data'] = $custom_data;
            }

            return json_encode($signature);
        }

        public function presence_auth($channel, $socket_id, $user_id, $user_info = false)
        {
            $user_data = ['user_id' => $user_id];

            if ($user_info == true) {
                $user_data['user_info'] = $user_info;
            }

            return $this->socket_auth($channel, $socket_id, json_encode($user_data));
        }
    }
