<?php

    //KolymaNET Kaptcha Client
    //For use with https://sys.kolyma.org/kaptcha/
    
    require 'info.php';
    
    function kaptcha_build($action, $settings, $board) {
        // Possible values for $action:
        //    - all (rebuild everything, initialization)
        //    - news (news has been updated)
        //    - boards (board list changed)
        //    - post (a reply has been made)
        //    - post-thread (a thread has been made)
        
        error_log($action);
	if ($action == 'bans') {
	       	Kaptcha::build($action, $settings);
        }
    }
        
    // Wrap functions in a class so they don't interfere with normal Tinyboard operations
    class Kaptcha {
        public static function build($action, $settings) {
            global $config;
           
            // Moderator
            if (isset($_POST['mod']) && $_POST['mod']) return;
            if (isset($_POST['thread']) && $_POST['thread']) return;

            // Setup
            $apikey = $settings['apikey'];
            $timeout = $settings['curl_timeout'];
            $key = $_POST["_KAPTCHA_KEY"]??"";
                
            // Final Step
            if (!self::kaptcha_validate($key, $apikey, $_SERVER["REMOTE_ADDR"])){
                error('You appear to have mistyped the kaptcha! '.$_POST["_KAPTCHA"]);
            }
         
        }

        public static function kaptcha_validate($key, $apikey, $ip) {
		$url = "https://sys.kolyma.org/kaptcha/kaptcha.php";

		$check = file_get_contents('https://vipcode.kolyma.org/login/vip.php?key='.$apikey.'&addr='.$ip);
		if ($ip == $check) {
			return true;
		}

		if (isset($_REQUEST["_KAPTCHA_NOJS"])) {
			$k = $_REQUEST["_KAPTCHA_KEY"]??false;
			if (!$k) return false;
			if (stristr(file_get_contents($url."?finalguess&nojscheck&key=&_KAPTCHA=".$k), "CHECK correct")) {
				return true;
			}
			return false;
		}

		$k = $_REQUEST["_KAPTCHA"]??false;
		if (!$k) return false;
		if (stristr(file_get_contents($url."?finalguess&_KAPTCHA=".$k."&key=".$key), "CHECK correct")) {
			return true;
		}
		return false;
        }

    };
    
?>
