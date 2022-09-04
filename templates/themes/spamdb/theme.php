<?php

    //KolymaNET SpamDB
    //For use with https://spam.kolyma.org/spam.php
    
    require 'info.php';
    
    function spamdb_build($action, $settings, $board) {
        // Possible values for $action:
        //    - all (rebuild everything, initialization)
        //    - news (news has been updated)
        //    - boards (board list changed)
        
        SpamDB::build($action, $settings);
    }
        
    // Wrap functions in a class so they don't interfere with normal Tinyboard operations
    class SpamDB {
        
        public function build($action, $settings) {
            global $config;
           
            // TODO: Include VIPDEF
            if (!isset($_POST['mod']) || !$_POST['mod']) {
				
				// Setup
				$key = $settings['apikey'];
				$verifonly = $settings['verifonly'];
				$timeout = $settings['curl_timeout'];
				$body = '';
				if (isset($_POST["body"])){
					$body = $_POST["body"];
				}
                    
				// Final Step
                if (isset($_POST['body']) && self::spamcheck($key, $_SERVER['REMOTE_ADDR'], $body, 'md5', $verifonly, $timeout)){
                    error('Your post could not be submitted due to your post or your IP address being listed @ spam.kolyma.org');
                }

            } else {
                
				// Moderator
                return;
            }
                
        }

        public function _spamapi($key, $spam, $verifonly, $input, $timeout=5) {
            
            $url = "https://spam.kolyma.org/spam.php?key=".$key."&spam=".$spam."&verifonly=".($verifonly?'on':'');
            
            // Curl Setup
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Tinyboard');
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,"c=".$input);
            curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);

            // Output
            $resp = curl_exec($curl);

            if ($resp === false) error('SpamDB Error, Curl says: ' . curl_error($curl));
            curl_close($curl);
			
			// Valid key?
			if (strpos($resp, "ERROR: Bad Key")){
				error('SpamDB ERROR: Bad Key');
			}
         
            // Is the input in SpamDB?
            if (!empty($resp)){
                return true;
            } else {
                return false;
            }
            
        }

        public function spamcheck($key, $ip='', $txt='', $md5='', $verifonly=true) {
    
            $categories = array(
			'ADV_TXT',
			'ADV_IMG',
			'ADV_IP','CP_IP','PROXY_IP','MISC_IP',
            );
            
            foreach ($categories as $spam) {
                if (!empty($txt) && preg_match('/_TXT$/', $spam)) {
                    if (self::_spamapi($key,$spam,$verifonly,$txt))
                        return true;
                } elseif (!empty($md5) && preg_match('/_IMG$/', $spam)) {
                    if (self::_spamapi($key,$spam,$verifonly,$md5))
                        return true;
                } elseif (!empty($ip) && preg_match('/_IP$/', $spam)) {
                    if (self::_spamapi($key,$spam,$verifonly,$ip))
                        return true;
                }
            }
            return false;
        }

    };
    
?>
