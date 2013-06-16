<?php
// Author: Fredrik Åhman
// Course: PHPMVC@BTH
// File: test_cform.php
// Desc: To test Roos's Lydiaframwork form stuff
// Display errors. If not on, turn it on. Default is off locally.

if (!ini_get('display_errors')) {
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
}
else {
		error_reporting(-1);
}

include('CForm.php');

class CFormContact extends CForm{
	// member variables
		
	
	// construct destruc
		// Create all form elements and validation rules in construct
	public function __construct(){
		parent::__construct();
	
	
	$this->AddElement(new CFormElementText('name', array('label'=>'Sets another label name', 'required'=>true)));
	$this	->AddElement(new CFormElementText('email', array('required'=>true)));
	$this	->AddElement(new CFormElementText('phone', array('required'=>true)));
	$this	->AddElement(new CFormElementSubmit('submit', array('callback'=>array($this, 'DoSubmit'))));
	$this->AddElement(new CFormElementSubmit('submit-fail', array('callback'=>array($this, 'DoSubmitFail'))));
	
	$this->SetValidation('name', array('not_empty'));
	$this->SetValidation('email', array('not_empty'));
	$this->SetValidation('phone', array('numeric'));
	
	}
	
	// methods

	protected function DoSubmit(){
		echo "<p><i>DoSubmit(): The form was submitted. Do stuff (save to database) and return success or failure.</i></p>";
		return true;
	}
	protected function DoSubmitFail(){
		echo "<p><i>DoSubmitFail(): The form was submitted, but I failed to process/save/validate the input.</i></p>";
    return false;
	}
}

// Use form and check status
session_name('cform_example');
session_start();
$form = new CFormContact();

$status=$form->Check(); // Checks if something was submitted
if($status === true){
	echo "<p><i>Form was submitted. Callback method returned true. Redirect the page to avoid issues with reloading posted form.</i></p>";
} elseif($status === false){
	echo "<p><i>The form was submitted. The callback method returned false. Redirect to a page to avoid issues with reloading posted form.</i></p>";
}

?>
<!DOCTYPE html>
<html lang='sv'>
<head>
	<meta charset='UTF-8'>
	<title>Example using Lydia CForm</title>
</head>
<body>
<h1>Example exercises using Lydia's CForm</h1>
<hr>
<?php echo $form->GetHTML(); ?>
<hr>
<p>Big foot goes here.</p>
</body>
</html>