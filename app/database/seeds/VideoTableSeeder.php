<?php
use Illuminate\Database\Seeder;


class VideoTableSeeder extends Seeder{
	
	public function run(){
		
			$video = new Video;
		
			$video->title = 'testvideo';
			$video->status = 'active';
			$video->created_by = "1";
			$video->link = "";
			$video->save();
		

		
	}
} 