<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/Users.php';
include $_SERVER['DOCUMENT_ROOT'] . '/User.php';

/* data url */
$data_url = 'https://jsonplaceholder.typicode.com/users';

try {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $data_url);  
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curl);

	if (empty($response)) {
		return 'The data stream contained no data';
	}

	/* the assumption here is for this test we are required to work with objects and not arrays, so not passing the 'true' argument to json_decode */
	$json_data = json_decode($response);

	/* users object to hold all users returned from the data source */
	$users = new Users($json_data);


	/* open file for writing */
	$report_file = fopen('report.csv', 'w');

	/* create row of the header columns for the csv file */
	$header_columns = ['firstname', 'lastname', 'companyname', 'email', 'phone', 'extension', 'city'];

	/* write row of header columns into file as the first row */
	fputcsv($report_file, $header_columns, ',');

	// loop through object for each user for processing
	foreach ($users->getAllUsers() as $key => $val) {

		$user = new User($val);

		$user->processPhoneNumber();
	
		$user->validateEmail();

		/* add row of data according to the heading. The first and last name assumption is that the names from the original JSON file is always going to be in the format of 'firstname' space 'lastname', and not have more than 2 words in this field. Also trimming any whitespaces from the phone and extension which are done in the getting methods */

		fputcsv($report_file, [
		 $user->getFirstName(),
		 $user->getLastName(),
		 $user->getCompanyName(),
		 $user->getEmail(),
		 $user->getPhoneNumber(),
		 $user->getExtension(),
		 $user->getAddressCity()
		]);
	}

	/* close file pointer */
	fclose($report_file);
	echo 'Your report has been generated and can be viewed in your current directory';
}
catch (Exception $e) {
	echo 'Error: ' . $e->getMessage();
}

?>
