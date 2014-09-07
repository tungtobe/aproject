<?php

class VideoController extends BaseController {


	private function existTime($updated_time){
		$date = date('Y-m-d H:i:s');
        $date = strtotime($date);
        return $date - $updated_time;
	}

	private function getCountDown($updated_time){
		$date = date('Y-m-d H:i:s');
        $date = strtotime($date);
        $existed_time = $date - $updated_time;
        $max_exist_time = Config::get('params.globalVar.MAX_EXIST_TIME');
        return $max_exist_time - $existed_time;
	}

	private function isExpire($id){
		$video = Video::find($id);

		// check if video has deleted or already deactived
		if ($video==null || $video->status== "deactive" ) {
			return true;
		}

		// check if video has expired
		$updated_time = strtotime($video->updated_at);		
        $exist_time = $this->existTime($updated_time);
        $max_exist_time = Config::get('params.globalVar.MAX_EXIST_TIME');
        if($exist_time > $max_exist_time) // expired
        {
        	$video->status = "deactive";
        	$video->save();
            return true; 
        }
        else
        {
        	return false ; // not expire
        }
	}

	public function showVideo($id)
	{
		if ($this->isExpire($id)) {
			return $this->layout->content = View::make('video.expired');
		}

		$video = Video::find($id);
		// get count down time
		$updated_time = strtotime($video->updated_at);	
		$count_down = $this->getCountDown($updated_time);

		
		$comments = $video->comment()->orderBy('id', 'DESC')->get();
		foreach ($comments as $comment) {
			$comment['comment_username']= $comment->user->username;
			$comment['comment_userid']= $comment->user->id;
		}



		$this->layout->content = View::make('video.detail')->with(array(
			'video'=> $video,
			'comments'=>$comments,
			'count_down' => $count_down
		));
	}

	public function upload(){
		$upload_dir = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF'])."/videoupload";
	 	$upload_url = '/';  
	      $temp_name = $_FILES['uploadedfile']['tmp_name'];  
	      $file_name = $_FILES['uploadedfile']['name']; 
	      $file_name1 = md5(uniqid(rand(), TRUE)) .".mp4"; //random file name 
	      $file_path = $upload_dir.$upload_url.$file_name;  
	      if(move_uploaded_file($temp_name, $file_path))  
	      {  
	      	$command = "cd videoupload; /usr/local/bin/ffmpeg -i ".$file_name." -vcodec h264 -acodec aac -strict -2 testconvert.mp4";
	        exec($command);
	        	      } 
	}

	

}
