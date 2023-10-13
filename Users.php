<?php 
class Users {

	public function __construct(private $users) {
		
	}

	public function getAllUsers() {
		return $this->users;
	}
}

?>
