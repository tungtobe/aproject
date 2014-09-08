<!-- Main hero unit for a primary marketing message or call to action -->
     <style>
      #form-upload { padding: 10px; background: #A5CCFF; border-radius: 5px;}
      #progress { border: 1px solid #ccc; height: 20px; margin-top: 10px;text-align: center;position: relative;}
      #bar { background: #F39A3A; height: 20px; width: 0px;}
      #percent { position: absolute; left: 50%; top: 0px;}
    </style>
    <div id="fb-root"></div>
 

      <div class="hero-unit">
        <h1>Enjoy!! 5 sec video! during only 30 min!</h1>

        @if(Auth::check())
        <!-- upload form -->
        <div class="row">
          <form id="form-upload" action="<?php echo URL::action('VideoController@upload'); ?>" method="post" enctype="multipart/form-data"> 
            Title <input type="text" id="video_title" name="video_title"> <br>
            <input type="file" id="upload_video" name="uploadedfile"><br>  
            <input type="submit" id="upload_submit" disabled value="Upload File">  
          </form>  
          <div id="progress">  
             <div id="bar"></div >  
             <div id="percent">0%</div >  
          </div>  
          <div id="status"></div> 
         
        </div>
        
      @else
      <div class="row">
        <h2>Login for upload video</h2>
        <p>Sign in with {{ HTML::linkAction('AuthenController@loginWithFacebook','Facebook') }}OR {{ HTML::linkAction('AuthenController@getLoginwithTwitter','Twitter') }}</p>
      </div>
      @endif

      

      <!-- Example row of columns -->
      <div class="row">
        <div class="span4">
          <h2>only 3 step!</h2>
          <ol>
              <li>login with Twitter,Facebook</li>
              <li>upload video file</li>
              <li>And... share the movie to your friends!</li>
          </ol>
        </div>
        <div class="span4">
          <h2>publish the video. during only 30 min!</h2>
          <p>publishing the video only 30 min! after uploaded.</p>
          <p>you speak ill of the boss in the video. even so your boss can't find it in only 30 min...</p>
       </div>
        <div class="span4">
          <h2>it can be said anything as long 5 sec!</h2>
          <p>for example speak ill of the boss,  tacky joke, or own up to...</p>
        </div>
      </div>
      

<?php echo exec("cd videoupload ; /usr/local/bin/ffmpeg -i sample.mpg -vcodec h264 -acodec aac -strict -2 testconvert.mp4"); ?>

@section('javascript')
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>  
 <script src="http://malsup.github.com/jquery.form.js"></script>  
 <script>  
 (function() {  
 var bar = $('.bar');  
 var percent = $('.percent');  
 var status = $('#status');  

    $('#upload_video').change(function(){
      var maxsize = 50000000; // 50MB
      var filetype = ["png", "mpg", "webm"];

       // get the file name, possibly with path (depends on browser)
       var file = this.files[0],
       filename = file.name,
       filesize = file.size;
       // console.log(file);

        // Use a regular expression to trim everything before final dot
        var extension = filename.replace(/^.*\./, '');

        // Iff there is no dot anywhere in filename, we would have extension == filename,
        // so we account for this possibility now
        if (extension == filename) {
            extension = '';
        } else {
            // if there is an extension, we convert to lower case
            // (N.B. this conversion will not effect the value of the extension
            // on the file upload.)
            extension = extension.toLowerCase();
        }

        if(filetype.indexOf(extension) != -1  && filesize < maxsize)
        {  
          $("#upload_submit").removeAttr('disabled');
        }
        else
        {
          $("#upload_submit").attr('disabled','disabled');
          alert("Invalid video file, your video must be under 50mb and ... ");
        }
    });

    
    $("#upload_submit").click(function(){
      if ($("#video_title").val() == "") {
        event.preventDefault();
        $("#video_title").css('border-color','red');
      }else{
        $(this).submit();
      }
    });

   $('#form-upload').ajaxForm({  
     beforeSend: function() {  
       status.empty();  
       var percentVal = '0%';  
       bar.width(percentVal)  
       percent.html(percentVal);  
     },  
     uploadProgress: function(event, position, total, percentComplete) {  
       var percentVal = percentComplete + '%';  
       bar.width(percentVal)  
       percent.html(percentVal);  
     },  
     complete: function(xhr) {  
       bar.width("100%");  
       percent.html("100%");
       mes = JSON.parse(xhr.responseText);
       if (mes.status == "SUCCESS") {
        window.location.replace(mes.link);
       }else{
        $('#status').html(mes.error_mess);
       }       
     }  
   });   
 })();      
 </script>  
 @stop