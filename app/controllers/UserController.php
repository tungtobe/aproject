<?php

class UserController extends BaseController {


	public function getShow($id)
	{
		$user = User::find($id);

		// check existed user
		if (is_null($user)) {
			return $this->layout->content = View::make('notfound');
		}
		$this->layout->content = View::make('user.profile')->with('user',$user);
	}

}
