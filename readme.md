# Prerequisite
Download/Clone this repo and do ```composer install```

Always write code in try/catch block to avoid server errors.
```
try {
    // write some nice code here.
} catch ( Exception $e ) {
    // handle exceptions here
}
```

Use ```session_start()``` to avoid facebook sdk error: Cross-site request forgery validation failed. The "state" param from the URL and session do not match.

# Set configurations for facebook.
```
FacebookConfig::set( '{app-id}', '{app-secret}', 'v2.10' );
```

# Set valid callback url.
After login to facebook, app will be redirected to this url.
```
// setting callback url for facebook.
FacebookConfig::set_callback( '{valid-url}' );
```

# Set permissions.
You can provide valid set of permissions in array, or use default set of permissions.
```
// default permissions
FacebookConfig::set_permissions();
```
OR
```
FacebookConfig::set_permissions([
    'manage_pages',
    'publish_pages',
    'pages_show_list',
    'publish_to_groups',
]);
```
For more information: https://developers.facebook.com/docs/facebook-login/permissions/

# Get login control to get access token
```
$login_control = FBcontrols::get_login_control();

if ( is_array( $login_control ) && array_key_exists( 'login_url', $login_control ) ) {
    echo '<a href="' . $login_control[ 'login_url' ] . '">Log in with Facebook!</a>';
}
```

# Now add below code in your call back url
```
$token = FBtokens::get_long_live_access_token();

$_SESSION['fb_access_token'] = $token;

header('location: {redirect}');
```

# Setting default access token
```
$token = $_SESSION['fb_access_token'];

FBtokens::set_default_access_token( $token );
```

# Functions for performing facebook pages
1 => Create object and getting token to work with facebook page.
```
$page = FBpage::get( '{page-id}' );

$page_access_token = $page->get_page_access_token();
```

2 => Creating post on facebook page.
```
$post_id = $page->create_post( 'Hello world', 'http://www.google.com' );
```

3 => Getting post from facebook.
```
$post = $page->get_post( $post_id );
```

4 => Removing post from facebook.
```
$status = $page->remove_post($post_id);
```

# Functions for scraping and updating url in facebook
```
$token = $_SESSION['fb_access_token'];

$scrape = FBdebug::scrape_info( '{valid-url}', $token);
```

# Uninstall/Unauthorised app and remove access token
```
$token = $_SESSION['fb_access_token'];

FBtokens::destroy_user_access_token( $token );
```