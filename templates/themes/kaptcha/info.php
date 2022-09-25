<?php
    $theme = Array();
    
    // Theme name
    $theme['name'] = 'KolymaNET Kaptcha Client';
    // Description (you can use Tinyboard markup here)
    $theme['description'] = 'Uses the KolymaNet Kaptcha system to verify humanity. An API key is required.';
    $theme['version'] = 'v0.1.0';
    
    // Theme configuration    
    $theme['config'] = Array();
    
    $theme['config'][] = Array(
        'title' => 'VIP API Key',
        'name' => 'apikey',
        'type' => 'text'
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
    $theme['build_function'] = 'kaptcha_build';
    $theme['install_callback'] = 'kaptcha_install';

    if (!function_exists('kaptcha_install')) {
        function kaptcha_install($settings) {
			
			// Validate Timeout
            if (!is_numeric($settings['curl_timeout']) || $settings['curl_timeout'] < 0)
                return Array(false, '<strong>' . utf8tohtml($settings['curl_timeout']) . '</strong> is not a non-negative integer.');
			
			// cURL installed?
			if (!function_exists('curl_version')){
				return Array(false, '<strong>cURL</strong> is not installed.');
			}
			
        }
    }
