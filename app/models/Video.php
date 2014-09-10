<?php

class Video extends Eloquent {

    protected $table = 'videos';

    public static function validate($input) {
        $rules = array(
            'title' => 'required'
        );

        return Validator::make($input, $rules);
    }

    //Define relationship between Video-Comments
    public function comment() {
        return $this->hasMany('Comment', 'video_id');
    }

    //Define relationship between Video-User
    public function user() {
        return $this->belongsTo('User', 'created_by');
    }

}

?>