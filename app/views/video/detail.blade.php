<!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <h1>This video exist during  <span id="runner"></span> !</h1>
        
        <h3><h3>
        <video id="myVideo" controls poster="video.jpg" width="600" height="400" >
           <source src="sample.mp4" type="video/mp4" />
           
        </video>

        

        <!-- add new comment -->
        <div>
          <input name="video_id" id="video_id" type="hidden" value="{{ $video->id}}">
          <textarea row ="10" col = "20" name="content" id="content" placeholder="コメート" ></textarea><br/>
          <button class="btn btn-primary" type="button" id="submitButton" name="Submit">Submit</button>
        </div>


        <!-- new comment -->
        <div id="new-comment">
        </div>

        <!-- error -->
        <div id="error">
        </div>

        <!-- show comment -->
        <div>
          @foreach ($comments as $comment)
          {{ HTML::linkAction('UserController@getShow', $comment->comment_username , array($comment->comment_userid), array('class' => '')) }}
          <p>{{$comment->content}}</p>
          @endforeach
        </div>
          
        
      </div>
   
        <button class="btn btn-primary" type="button" id="fbShare" name="Submit">Share via FB</button>
          
      

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-share-button" data-href="https://developers.facebook.com/docs/plugins/"></div>
      
@section('javascript')
<script type="text/javascript">
$(function() {
  //Ajax
  $("#submitButton").click(function(e){
    e.preventDefault();
    var commentContent = $("#content").val();
    var videoID = $("#video_id").val();
    var myUrl = "{{URL::action('CommentController@postStore')}}";   
    $.ajax({
        url: myUrl,
        type: 'POST',
        data:{
          content: commentContent,
          video_id: videoID
        },
        dataType: 'json',
        success: function (data) {
          if(data.msg=="SUCCESS"){
            $("#new-comment").prepend("<p>"+data.content+"</p>");
            
            console.log(data);        
          }
          else {          
            $("#new-comment").html('');
            var content = '<ul>';
               jQuery.each(data.content, function(i, v){
                  content += "<li class = 'error'>" + v + "</li>";
               });
               content += '</ul>'; 
            $("#error").html(content); 
          }
        },
        error: function(data) {
            console.log(data);
        }
    })
  });
  $('#runner').runner({
    autostart: true,
    countdown: true,
    milliseconds: false,
    startAt: {{ $count_down * 1000 }}, // alternatively you could just write: 60*1000
    stopAt:0
  }).on('runnerFinish', function(eventObject, info) {
    alert('Video has expired !!! ');
    location.reload();
  });
});

</script>
@stop