<?php
/**
 * @author Nutdanai Tippanontakul
 */

$config['webgen_base_url_website'] = 'https://webgen.23perspective.com/';
$config['webgen_base_url_localhost'] = 'http://webgen.localhost/';

$config['webgen_baseurl'] = ENVIRONMENT == 'localhost' ? $config['webgen_base_url_localhost'] : $config['webgen_base_url_website'];
