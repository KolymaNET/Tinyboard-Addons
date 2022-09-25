<?php
    $theme = Array();
    
    // Theme name
    $theme['name'] = 'KolymaNET SpamDB Client';
    // Description (you can use Tinyboard markup here)
    $theme['description'] = 'Uses <a href="//spam.kolyma.org/spam.php">KolymaNET Anti-Spam Database</a> to automatically filter and block posts. An API key is required.';
    $theme['version'] = 'v0.1.5';
    
    // Theme configuration    
    $theme['config'] = Array();
    
    $theme['config'][] = Array(
        'title' => 'API Key',
        'name' => 'apikey',
        'type' => 'text'
    );
    
    $theme['config'][] = Array(
        'title' => 'Verified Only',
        'name' => 'verifonly',
        'type' => 'checkbox',
        'default' => true,
        'comment' => '(verified anti-spam entries only)'
    );
    
    $theme['config'][] = Array(
        'title' => 'Timeout',
        'name' => 'curl_timeout',
        'type' => 'text',
        'comment' => '(in seconds)',
        'default' => '5',
        'size' => '1'
    );
    
    // Unique function name for building everything
    $theme['build_function'] = 'spamdb_build';
    $theme['install_callback'] = 'spamdb_install';

    if (!function_exists('spamdb_install')) {
        function spamdb_install($settings) {
			
			// Validate Timeout
            if (!is_numeric($settings['curl_timeout']) || $settings['curl_timeout'] < 0)
                return Array(false, '<strong>' . utf8tohtml($settings['curl_timeout']) . '</strong> is not a non-negative integer.');
			
			// cURL installed?
			if (!function_exists('curl_version')){
				return Array(false, '<strong>cURL</strong> is not installed.');
			}
			
        }
    }
