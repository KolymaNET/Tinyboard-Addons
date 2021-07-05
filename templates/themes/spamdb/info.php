<?php
	$theme = Array();
	
	// Theme name
	$theme['name'] = 'KolymaNET SpamDB';
	// Description (you can use Tinyboard markup here)
	$theme['description'] = 'Uses <a href="//spam.kolyma.org/spam.php">Kolyma Network Anti-Spam Database</a> and automatically bans the listed verified IP\'s at a custom length. An API key is required to use the service.';
	$theme['version'] = 'v0.0.1';
	
	// Theme configuration	
	$theme['config'] = Array();
	
	//Key
	$theme['config'][] = Array(
		'title' => 'API Key',
		'name' => 'apikey',
		'type' => 'text'
	);
	//Categories
	$theme['config'][] = Array(
		'title' => 'Category',
		'name' => 'category',
		'type' => 'text',
		'comment' => '(eg. "PROXY_IP")'
		//'size' => 3,
	);
	
	$theme['config'][] = Array(
		'title' => 'Ban Length',
		'name' => 'ban_length',
		'type' => 'text',
		//'default' => $config['file_index'],
		'comment' => '(eg. "2d1h30m" or "2 days")'
	);
	
	// Unique function name for building everything
	$theme['build_function'] = 'spamdb_build';
	$theme['install_callback'] = 'spamdb_install';

	if (!function_exists('spamdb_install')) {
		function spamdb_install($settings) {
		/*	if (!is_numeric($settings['ban_length']) || $settings['ban_length'] < 0)
				return Array(false, '<strong>' . utf8tohtml($settings['ban_length']) . '</strong> is not a non-negative integer.');
		}*/
			if (!is_numeric($settings['category']) == $settings['category'] < 0)
				return Array(false, '<strong>' . utf8tohtml($settings['ban_length']) . '</strong> is not a non-negative integer.');
		}
	}

