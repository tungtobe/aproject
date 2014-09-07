<?php


class Video extends Eloquent  {
	protected $table = 'videos';
	
	public static function existTime($updated_time){
		$now = date('Y-m-d H:i:s');
        $now = strtotime($now);
        return $now - $updated_time;
	}

	public static function getCountDown($updated_time){
		$date = date('Y-m-d H:i:s');
        $date = strtotime($date);
        $existed_time = $date - $updated_time;
        $max_exist_time = Config::get('params.globalVar.MAX_EXIST_TIME');
        return $max_exist_time - $existed_time;
	}

	public static function isExpire($id){
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

	public static function validate($input) {
		$rules = array(
				'title' => 'required'
		);

		return Validator::make($input, $rules);
	}

    //Define relationship between Video-Comments
	public function comment(){
        return $this->hasMany('Comment','video_id');
    }

    //Define relationship between Video-User
	public function user(){
		return $this->belongsTo('User', 'created_by');
	}

	
}

?>