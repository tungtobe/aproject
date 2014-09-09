<!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <h2><b>{{$video->title}}<b> </h2>
        <h4>exist during  <span id="runner"></span> !</h4>
        
        <!-- share div  -->
        <div data-type="button_count" class="fb-share-button" data-href="<?php echo URL::current(); ?>" data-width="600"></div>
        <a href="<?php echo URL::current(); ?>" class="twitter-share-button" data-text="Flush Video">Tweet</a>


        <!-- video div -->
        <h3><h3>
        <video id="myVideo" controls poster="video.jpg" width="600" height="400" >
           <source src="{{$video->link}}" type="video/mp4" />           
        </video>



        <!-- add new comment -->
        <div>
          <input name="video_id" id="video_id" type="hidden" value="{{ $video->id}}">
          <textarea row ="10" col = "20" name="content" id="content" placeholder="コメート" ></textarea>
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
            $("#content").val(" ");        
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
    startAt: {{ $count_down * 1000 }}, // count_down in milisecond
    stopAt:0
  }).on('runnerFinish', function(eventObject, info) {
    alert('Video has expired !!! ');
    location.reload();
  });
});

</script>
@stop