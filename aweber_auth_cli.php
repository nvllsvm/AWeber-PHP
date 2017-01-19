<?php

require_once('aweber_auth.php');


function main($argv) {
    parse_str(implode('&', array_slice($argv, 1)), $args);

    if (array_key_exists('app_id', $args)) {
        if (array_key_exists('callback_url', $args)) {
            $auth_url = get_auth_url($args['app_id'], $args['callback_url']);
        }
        else {
            $auth_url = get_auth_url($args['app_id']);
        }
        print $auth_url . PHP_EOL;
    } else if (array_key_exists('redirect_url', $args)) { 
        $auth_code = parse_redirect_url($args['redirect_url']);
        $oauth_creds = get_oauth_creds($auth_code);
        display_oauth_creds($oauth_creds);
    } else if (array_key_exists('auth_code', $args)) { 
        $oauth_creds = get_oauth_creds($args['auth_code']);
        display_oauth_creds($oauth_creds);
    } else {
        print 'You passed nothing I can work with :(' . PHP_EOL;
    }
}


function display_oauth_creds($oauth_creds) {
    function print_cred($oauth_creds, $key) {
        print '$' . $key . ' = "' . $oauth_creds[$key] . '";' . PHP_EOL;
    }

    print_cred($oauth_creds, 'consumer_key');
    print_cred($oauth_creds, 'consumer_secret');
    print_cred($oauth_creds, 'access_key');
    print_cred($oauth_creds, 'access_secret');
}


main($argv);
