<?php

	//KolymaNET SpamDB
	//For use with https://spam.kolyma.org/
	
	require 'info.php';
	require 'inc/bans.php';
	
	function spamdb_build($action, $settings, $board) {
		// Possible values for $action:
		//	- all (rebuild everything, initialization)
		//	- news (news has been updated)
		//	- boards (board list changed)
		
		SpamDB::build($action, $settings);
	}
	
	// Wrap functions in a class so they don't interfere with normal Tinyboard operations
	class SpamDB {
		public static function build($action, $settings) {
			global $config;
			
			$spamlist = file_get_contents("https://spam.kolyma.org/spam.php?key=" . $settings['apikey'] . "&spam=" . $settings['category'] . "&verifonly=on");
			$banreason = "'''SpamDB: '''". $settings['category'];
			
			$endofline = "\r\n";
			$line = strtok($spamlist, $endofline);
			
			if (strpos($spamlist, "<html>") == false)
			{
				while ($line !== false)
				{

					//Bans::new_ban($line, $banreason, $settings['ban_length']);
					echo $line . "<br>";
					$line = strtok($endofline);
				}
				unset($spamlist);
				strtok("", "");
				//For some reason doing both cleans the file from memory.
		
			}
			else
			{
				echo("ERROR: Bad Key");
			}

		}
	};
	
?>
