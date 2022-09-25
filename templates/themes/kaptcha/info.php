<?php
    $theme = Array();
    
    // Theme name
    $theme['name'] = 'KolymaNET Kaptcha Client';
    // Description (you can use Tinyboard markup here)
    $theme['description'] = 'Uses KolymaNET\'s captcha service. An API key is required.';
    $theme['version'] = 'v0.2';
    
    // Theme configuration    
    $theme['config'] = Array();

	$theme['config'][] = Array(
        'title' => 'Reply Kaptcha',
        'name' => 'reply_enabled',
        'type' => 'checkbox',
        'default' => false,
        'comment' => '(enables kaptcha for replies)'
    );
    
    $theme['config'][] = Array(
        'title' => 'VIP API Key',
        'name' => 'apikey',
        'type' => 'text',
		'comment' => '(ignored if empty)'
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
            global $config;
            
            // Validate Timeout
            if (!is_numeric($settings['curl_timeout']) || $settings['curl_timeout'] < 0)
                return Array(false, '<strong>' . utf8tohtml($settings['curl_timeout']) . '</strong> is not a non-negative integer.');
            
            // cURL installed?
            if (!function_exists('curl_version')){
                return Array(false, '<strong>cURL</strong> is not installed.');
            }
            
            // Are the POST inputs added in config?
            if (!in_array("_KAPTCHA", $config['spam']['valid_inputs']) && !in_array("_KAPTCHA_KEY", $config['spam']['valid_inputs'])){
                return Array(false, 'Both "<strong>_KAPTCHA</strong>" & "<strong>_KAPTCHA_KEY</strong>" have not been added to <strong>$config[\'spam\'][\'valid_inputs\']</strong>.
                <br><br>\\Please include this in your <strong>instance-config.php</strong>.');
            }
            
        }

    }

