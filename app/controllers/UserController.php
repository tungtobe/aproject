<?php

class UserController extends BaseController {
	private function existTime($updated_time){
		$now = date('Y-m-d H:i:s');
        $now = strtotime($now);
        return $now - $updated_time;
	}

	private function getCountDown($updated_time){
		$date = date('Y-m-d H:i:s');
        $date = strtotime($date);
        $existed_time = $date - $updated_time;
        $max_exist_time = Config::get('params.globalVar.MAX_EXIST_TIME');
        return $max_exist_time - $existed_time;
	}

	public function checkExpire($userid){
		$videos = Video::where('created_by',$userid)->get();

		if ($videos->count() != 0) {
			foreach ($videos as $video) {
				// check if video has expired
				$updated_time = strtotime($video->updated_at);		
		        $exist_time = $this->existTime($updated_time);
		        $max_exist_time = Config::get('params.globalVar.MAX_EXIST_TIME');
		        if($exist_time > $max_exist_time) // expired
		        {
		        	$video->status = "deactive";
		        	$video->save();
		        }
			}
		}		
	}


	private function getRebornRequest($videoid){
		return RebornRequest::where('video_id',$videoid)->count();
	}

	private function getActiveVideo($userid){
		$check = $this->checkExpire($userid);
		 return $videos = Video::where('created_by', $userid)->where('status',"active")->get();
		 

	}
	private function getDeactiveVideo($userid){
		$videos = Video::where('created_by', $userid)->where('status',"deactive")->get();
		if ($videos->count() != 0) {
			foreach ($videos as $video) {
				$video['request_number']= $this->getRebornRequest($video->id);
			}
		}
		return $videos;
	}

	public function getShow($unique_id)
	{
		$users = User::where('unique_id', $unique_id)->get();
		$user = $users[0];

		// check existed user
		if (is_null($user)) {
			return $this->layout->content = View::make('notfound');
		}

		// check if user go to own page 
		if (Auth::user()->unique_id == $unique_id) {
			// get active video 
			$active_videos = $this->getActiveVideo($user->id);
			$deactive_videos = $this->getDeactiveVideo($user->id);
			$this->layout->content = View::make('user.own_profile')->with(array(
																			'user'=> $user,
																			'active_videos'=> $active_videos,
																			'deactive_videos'=>$deactive_videos
																		));

		}else{
			$active_videos = $this->getActiveVideo($user->id);
			$this->layout->content = View::make('user.profile')->with(array(
																		'user'=> $user,
																		'active_videos'=> $active_videos
																	));
		}
		
	}

	
}
