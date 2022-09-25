KolymaNET Kaptcha Client v0.2
========================================================

About
------------
This is KolymaNET's Kaptcha Client for Tinyboard-based imageboards.


Installation
-------------
1.	Paste the theme you would like to use in the root directory of your Tinyboard instance.

2.	Add `_KAPTCHA` and `_KAPTCHA_KEY` to `$config['spam']['valid_inputs']` in your instance-config.php.
3.	Add `$config['additional_javascript'][] = 'js/kaptcha.js';` to your instance-config.php
KolymaNET Kaptcha Client should now be installed.

