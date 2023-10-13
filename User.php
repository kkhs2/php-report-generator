<?php 

/* class to hold the collection of users */
class User {

	/* php8 constructor property promotion */
	public function __construct(private $user) {

	}

	
	/* My assumption here of an extension is the number after the 'x', and that each number should only contain 1 extension. Also any non digit character is to be stripped and replaced with a blank space in its place */
	private function processingPhoneNumber($phone) {

		$phone_number = preg_match_all('/x\d*/', $phone, $extension);
		if (!empty($extension[0])) {
			$this->user->extension = implode('', $extension[0]);
		} else {
			$this->user->extension = 'n/a';
		}

		/*  replace the x followed by digits first otherwise it will remove the x and there is no condition to look for in removing the extension, and then replace all non numerical characters with a white space */
		$this->user->phone = preg_replace('/x\d*/', '', $this->user->phone);
		$this->user->phone = preg_replace('/\D/', ' ', $this->user->phone);



	}


	private function validatingEmail($email) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->user->email_valid = 'false';
		} else {
			$this->user->email_valid = 'true';
		}
	}



	/* have two public functions here for phone number and email for the main class to call, in order to call the private processPhoneNumber() and validateEmail() methods in order for better protection */
	public function processPhoneNumber() {
		$this->processingPhoneNumber($this->user->phone);
	}

	public function validateEmail() {
		$this->validatingEmail($this->user->email);
	}


	/* getters for the required data of the report */

	public function getEmail() {
		return $this->user->email;
	}

	public function getFirstName() {
		return explode(' ', $this->user->name)[0];
	}

	public function getLastName() {
		return explode(' ', $this->user->name)[1];
	}

	public function getCompanyName() {
		return $this->user->company->name;
	}

	public function getAddressCity() {
		return $this->user->address->city;
	}

	public function getPhoneNumber() {
		return trim($this->user->phone);
	}

	public function getExtension() {
		return trim($this->user->extension);
	}

}

?>
