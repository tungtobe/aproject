
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Flush Movies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    {{ HTML::style('css/bootstrap.min.css'); }}
    {{ HTML::style('css/bootstrap-responsive.css'); }}
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>
  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">

    
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="/">Flush Videos</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="/">Home</a></li>
              <li><a href="#about">About</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
            </ul>
            @if (Auth::check())
                <form id="socialLogin" class="navbar-form pull-right">
                  <p>Hello <b>{{Auth::user()->username}}</b>    {{ HTML::linkAction('AuthenController@getLogout','Logout') }}  </p>
                </form>            
            @else
                <form id="socialLogin" class="navbar-form pull-right">
                  <p>Sign in with {{ HTML::linkAction('AuthenController@loginWithFacebook','Facebook') }}OR {{ HTML::linkAction('AuthenController@getLoginwithTwitter','Twitter') }}</p>
                </form>
            @endif
            
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
      {{$content}}
      
    </div> <!-- /container -->
    
     <hr>
    <footer>
        <p>&copy; Company 2013</p>
      </footer>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    {{ HTML::script('js/bootstrap.js'); }} 
    {{ HTML::script('js/jquery-1.11.1.min.js'); }} 
    {{ HTML::script('js/bootstrap.min.js'); }}
    {{ HTML::script('js/jquery.runner-min.js'); }}

    <!-- facebook javascript sdk -->
    <script>
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=1474130469505961&version=v2.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- twitter share script -->
    <script>!function(d,s,id){
      var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
      if(!d.getElementById(id)){
        js=d.createElement(s);js.id=id;
        js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);
      }}(document, 'script', 'twitter-wjs');
    </script>
    
    @section('javascript') 
    @show
    <style>
        video {
            margin:50px 0;
            width:100%;
        }
        #socialLogin p{ color:#FFF }
        #socialLogin a.Twitter:after {content: url("../assets/img/social/Twitter.png")}
        #socialLogin a.Facebook:after{content: url("../assets/img/social/Facebook.png")}
    </style>
  </body>
</html>

