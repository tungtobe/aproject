<div class="hero-unit">
    <center>
        <div class="row"><h2>Login for upload video</h2></div>
        <br/>
        <div class="row">
            {{ HTML::linkAction('AuthenController@loginWithFacebook','Facebook', null, array('class' => '_btn')) }}
            or {{ HTML::linkAction('AuthenController@getLoginwithTwitter','Twitter', null, array('class' => '_btn')) }}
        </div>
    </center>
</div>