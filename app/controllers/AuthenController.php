<?php
define('CONSUMER_KEY', Config::get('params.twitter.clientID'));
define('CONSUMER_SECRET', Config::get('params.twitter.clientSecret'));
define('OAUTH_CALLBACK', Config::get('params.twitter.redirectUrl'));

require(__DIR__.'/../helpers/twitteroauth/twitteroauth.php');

class AuthenController extends BaseController {

    public function getLogin(){
        if (Auth::check()) {
            return Redirect::to(URL::action('HomeController@showWelcome'));        
        }
        $this->layout->content =  View::make('authen.login');
    }
        
        
    public function getLoginwithTwitter()
    {

        session_start();
        $twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
        // Requesting authentication tokens, the parameter is the URL we will be redirected to
        $request_token = $twitteroauth->getRequestToken(OAUTH_CALLBACK);

        $_SESSION['oauth_token'] = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

        if($twitteroauth->http_code==200){
            $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
            return Redirect::away ( $url );
        } else {
            Throw new CHttpException("200", "something was wrong");
        }
    }

    public function TwitterCallback()
    {
        session_start();
        /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

        /* Request access tokens from twitter */
        $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

        /* Save the access tokens. Normally these would be saved in a database for future use. */
        $_SESSION['access_token'] = $access_token;

        /* Remove no longer needed request tokens */
        unset($_SESSION['oauth_token']);
        unset($_SESSION['oauth_token_secret']);
        if (200 == $connection->http_code) {
            /* The user has been verified and the access tokens can be saved for future use */
            $_SESSION['status'] = 'verified';
            return Redirect::to(URL::action('AuthenController@showUserCredentials'));
        } else {
            /* Save HTTP status for error dialog on connnect page.*/
            Throw new CHttpException("200", "something was wrong");
        }
    }

    public function showUserCredentials()
    {
        session_start();
        $access_token = $_SESSION['access_token'];

        /* Create a TwitterOauth object with consumer/user tokens. */
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

        /* If method is set change API call made. Test is called by default. */
        $user  = $connection->get('account/verify_credentials');

        $newuser = User::where(array(
                                    'unique_id' => md5($user ->id),
                                    'type' => "tw",
                            ))->first();
            if(is_null($newuser)){
                $newuser = new User;
                $newuser->username = $user ->screen_name;
                $newuser->unique_id = md5($user ->id);
                $newuser->type = "tw";
                $newuser->save();
                Auth::login($newuser);
                return Redirect::intended(URL::action('HomeController@showWelcome'));
            }else{
                //update twitter username
                $newuser->username = $user ->screen_name;
                $newuser->save();
                Auth::login($newuser);
                
                return Redirect::intended(URL::action('HomeController@showWelcome'));
            }
    }



	public function loginWithFacebook(){
        $clientID = Config::get('params.facebook.clientID');
        $clientSecret = Config::get('params.facebook.clientSecret');
        $redirectURL = Config::get('params.facebook.redirectUrl');

    	if(session_id() == '') { //check session
    		session_start();
    	}
    	//get code and error which got from response of facebook.
    	$code = isset($_REQUEST['code']) ? $_REQUEST['code'] : null;
    	$error = array(
    			"error_reason"=>isset($_REQUEST["error_reason"])?$_REQUEST["error_reason"]:null,
    			"error"=>isset($_REQUEST["error"])?$_REQUEST["error"]:null,
    			"error_description"=>isset($_REQUEST["error_description"])?$_REQUEST["error_description"]:null
    	);
    	
    	
    	//Handling error or user revokes permission
    	if(!empty($error["error"]))
    	{
    		// If user decided to decline the permission
    		if ($error["error_reason"] === "user_denied")
    		{
    			// Redirect back to homepage page
    			Redirect::to ( URL::action ( 'HomeController@showWelcome' ) );
    		}
    		// In case unexpect error occur
    		else
    		{
    			// Redirect to error page
    			// Throw exception and display error_reason and error description
    			Throw new CHttpException("200",
    					$error["error_reason"] . ": " . $error["error_description"]);
    		}
    	}
    	
    	//If user is not login yet
    	//Redirect to login dialog
    	if (empty($code))
    	{
    		// Create an unique session state
    		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
    	
    		// Request permission by using scope
    		// Permission request : Basic Information, user birthday, user education history
    		$dialog_url = "https://www.facebook.com/dialog/oauth?" .
    				"client_id=" . $clientID.
    				"&redirect_uri=" . urlencode($redirectURL) . // TODO: change it later
    				"&state=" . $_SESSION['state'] .
    				"&scope=email";
    		return Redirect::to($dialog_url);
    	}
    	
    	//Check if state varibale matches or not
    	if(isset($_SESSION['state']) && ($_SESSION['state'] == $_REQUEST['state']))
    	{
    		// state variable matches
    		// Exchange the code for access token
    		$token_url = "https://graph.facebook.com/oauth/access_token?" .
    				"client_id=" . $clientID.
    				"&redirect_uri=" . urlencode($redirectURL) .
    				"&client_secret=". $clientSecret.
    				"&code=" . $code;
    	
    		// Handling on error when exchange access token
    		if(($response = @file_get_contents($token_url)) === FALSE)
    		{
    			// Throw exception
    			Throw new CHttpException("200",
    					"Something went wrong when we are logging you in");
    			session_destroy();
    		}
    		$params = null;
    		parse_str($response, $params);
    		// Save access token to session parameters
    		$_SESSION['access_token'] = $params['access_token'];
    	
    		// Query information that is requested
    		$graph_url = "https://graph.facebook.com/me?" .
    				"access_token=" . $params['access_token'];
    		if(($user = @json_decode(file_get_contents($graph_url))) === NULL)
    		{
    			// Throw exception
    			Throw new CHttpException("200",
    					"Something went wrong when we are logging you in");
    			session_destroy();
    		}
    		
            $newuser = User::where(array(
                        'unique_id' => md5($user->id),
                        'type' => "fb",
                ))->first();
    		if(is_null($newuser)){
    			$newuser = new User;
    			$newuser->username = $user->first_name . " " . $user->last_name;
    			$newuser->unique_id = md5($user->id);
    			$newuser->type = "fb";
                $newuser->access_token = $params['access_token'];
                $newuser->save();
    			Auth::login($newuser);
                return Redirect::intended(URL::action('HomeController@showWelcome'));
    		}else{
    			//update facebook_username
    			$newuser->username = $user->first_name . " " . $user->last_name;
                $newuser->access_token = $_SESSION['access_token'];
    			$newuser->save();
    			Auth::login($newuser);
    			
    			return Redirect::intended(URL::action('HomeController@showWelcome'));
    		}
    		
    	}
    	else
    	{
    		// Redirect to error page
    		// Throw exception
    		Throw new CHttpException("404", "Session error. You do not have a valid session");
    		session_destroy();
    	}
    	
    	// Destroy session on login success
    	session_destroy();
    }

    public function getLogout() {
        Auth::logout();
        return Redirect::to(URL::action('HomeController@showWelcome'));
    }

}

?>