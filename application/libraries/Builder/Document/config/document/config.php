<?php
/**
 * @author Nutdanai Tippanontakul
 */

$config['webgen_base_url_website'] = '';
$config['webgen_base_url_localhost'] = '';

$config['webgen_baseurl'] = ENVIRONMENT == 'localhost' ? $config['webgen_base_url_localhost'] : $config['webgen_base_url_website'];
