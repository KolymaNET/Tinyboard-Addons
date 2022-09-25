<?php
    require 'info.php';
    
    function kaptcha_build($action, $settings, $board) {
        // Possible values for $action:
        //    - all (rebuild everything, initialization)
        //    - post (a post has been made)
        //    - post-thread (a thread has been made)
        //    - delet
        
        Kaptcha::build($action, $settings);
    }
    
    // Wrap functions in a class so they don't interfere with normal Tinyboard operations
    class Kaptcha {
        public function build($action, $settings) {
            global $config;
            
            // Add the Kaptcha Javascript
            if ($action == 'all')
                file_write('js/kaptcha.js', Kaptcha::config($settings));
            
            // Uninstall
            if ($action == 'delete')
                file_unlink('js/kaptcha.js');
            
            // Has a post been made?
            if ($action == 'post-thread' || $settings['reply_enabled'] && $action == 'post') {

                // Moderator
                if (isset($_POST['mod']) && $_POST['mod']) return;
                    
                // Setup
                $key        = $settings['apikey'];
                $ip         = $_SERVER['REMOTE_ADDR'];
                $timeout    = $settings['curl_timeout'];
                
                // VIP
                if (isset($settings['apikey']) && self::VIPDEF($key, $timeout, $ip)) return;

                // Final Step
                if (isset($_POST["_KAPTCHA"])) {
                    if (!self::kaptcha_validate($_POST["_KAPTCHA_KEY"], $timeout)){
                         error($config['error']['captcha']);
                    }
                }
                
            }

        }
        
        public function config($settings){
            global $config;
            
            return Element('themes/kaptcha/kaptcha.js', Array(
                'settings' => $settings,
            ));
        }
    
        public function kaptcha_validate($kaptcha_key, $timeout=5) {
            
            $k = $_REQUEST["_KAPTCHA"]??false;
            if (!$k) return false;
            
            $url = "https://sys.kolyma.org/kaptcha/kaptcha.php?_KAPTCHA=".$k."&key=".$kaptcha_key;
            
            // Curl Setup
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Tinyboard');
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);

            // Output
            $resp = curl_exec($curl);

            if ($resp === false) die('KAPTCHA Error, Curl says: ' . curl_error($curl));
            curl_close($curl);
         
            // Kaptcha Passed?
            if ($resp === 'CHECK correct') return true;
            
            return false;
        }
        
        public function VIPDEF($key, $timeout=5, $ip){

            $get = '?key='.$key.'&ip='.$ip;
            $url = 'https://vipcode.kolyma.org/login/vip.php'.$get;
            
            // Curl Setup
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Tinyboard');
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
            
            // Output
            $resp = curl_exec($curl);

            if ($resp === false) error('KAPTCHA Error, Curl says: ' . curl_error($curl));
            curl_close($curl);
            
            // Valid key?
            if (strpos($resp, "ERROR: Bad Key")){
                error('KAPTCHA Error: Bad Key');
            }
            
            // Does the user have VIP?
            if ($resp === $ip) return true;
            
            return false;
        }
    };
    
?>
