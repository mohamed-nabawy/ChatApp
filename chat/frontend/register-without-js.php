
<?php
require('../backend/validation-functions.php');
require('../backend/Controllers/User.php');
require('../backend/test-request-input.php');

$upload_errors = array(UPLOAD_ERR_OK => "no errors",
				UPLOAD_ERR_INI_SIZE => "larger than upload_max_file_size",
				UPLOAD_ERR_FORM_SIZE => "larger than form max_file_size",
				UPLOAD_ERR_PARTIAL => "partial upload",
				UPLOAD_ERR_NO_FILE => "no file",
				UPLOAD_ERR_NO_TMP_DIR => "NO temporary directory",
				UPLOAD_ERR_CANT_WRITE => "can't write to disk",
				UPLOAD_ERR_EXTENSION => "file upload stopped by extension"
);


$username = false;
$password = false;
$firstName = false;
$lastName = false;
$gender = false; // need a span
$email = false;
$phone = false;
$dob = false;

$emailExists = false;
$usernameExists = false;


if ( isset($_POST['submit']) ) {
	if ( !normalizeString($conn, $_POST['userName']) )  {
		$username = true;
	}

	if ( !normalizeString($conn, $_POST['password']) ) {
		$password = true;
	}

	if ( !normalizeString($conn, $_POST['firstName']) )  {
		$firstName = true;
	}

	if ( !normalizeString($conn, $_POST['lastName']) )  {
		$lastName = true;
	}

	if ( !isset($_POST['gender']) || !is_int($_POST['gender']) ) {
		$gender = true; 
	}

	if ( !normalizeString($conn, $_POST['email']) ) {
		$email = true;
	}
	if ( !normalizeString($conn, $_POST['phone']) ) {
		$phone = true;
	}
	if ( !normalizeString($conn, $_POST['dob']) ) {
		$dob = true;
	}

	if (isset($_FILES['image']) && $_FILES['image']['error'] > 0 && $_FILES['image']['error'] !== 4) { // ???
		$uploadErrorMessage = $upload_errors[$_FILES['image']['error']];
	}

	if (!$username && !$password && !$firstName && !$lastName && !$gender && !$email && !$phone && !$dob) { // all constraints achieved	
		$emailExists = checkExistingEmail($conn, $_POST['email']);
    $usernameExists = checkExistingUserName($conn, $_POST['userName'], true);

 		if (!$emailExists && !$usernameExists) {//total success
    	//$fields_with_max_lengths = array($data->userName  => 100 , $data->firstName=>50 ,$data->lastName=>50,$data->phone=>13, $data->email=>100,$data->password=>100 );
    	//validate_max_lengths($fields_with_max_lengths);
       //test_date_of_birth($data->dob)

    	//handling image uploaded
    	$temp_name = $_FILES['image']['tmp_name'];
    	$target_file = basename($_FILES['image']['name']);
    	$upload_direc = "ChatApp/chat/backend/uploads/";
    	move_uploaded_file($temp_name, $upload_direc . $target_file);//return true on success
 	    $roleId = getRoleIdByName($conn, 'Customer');
		  $localeId = 1;
		  $user_id = addUser($conn, $_POST['userName'], $_POST['firstName'], $_POST['lastName'], $Image, $_POST['email'], $_POST['phone'], $_POST['password'], $roleId, $localeId);
		  $customer_id = addCustomer($conn, 0.0, $dob, $user_id, $_POST['gender']);
    }
	}
}
else { // if at first time load not submitting the form
}
?>

<!DOCTYPE html>

<html>

  <head>

    <title>Register Form</title>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="icon" href="images/icon.png"> -->
    <link rel="stylesheet" type="text/css" href="css/materialize.css"/>
    <link rel="stylesheet" type="text/css" href="icons/icons.css" />
    <link href="css/errors.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/register.css" />

    <!-- <link href='https://fonts.google.com/?category=Serif,Sans+Serif,Monospace&selection.family=Roboto+Slab' rel='stylesheet'> -->
    <!-- <link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet'> -->
    <script src="javascript/modules/jquery-3.2.1.js"></script>
    <script src="javascript/newjavascript.js"></script>
    <script src="javascript/modules/materialize.js"></script>
    <script src="javascript/modules/angular.js"></script>
    <script src="javascript/modules/angular-route.js"></script>
    <script src="javascript/modules/ui-bootstrap-2.5.0.js"></script>
    <script src="javascript/modules/ui-bootstrap-tpls-2.5.0.js"></script>
    <script src="javascript/modules/angular-modal-service.js"></script>
    <script src="javascript/modules/alertify.min.js"></script>
    <!-- <script src="javascript/register.js"></script> -->
  </head>

<style type="text/css">
  *{
    color: violet;
  }

[ng\:cloak], [ng-cloak], .ng-cloak {
  display: none !important;
}
</style>

  <body ng-app="register" >

    <div id="main">

      <div id="navigation">

        &nbsp;

      </div>

      <div id="page" class="row" style="align-content:center;text-align:center">

        <?php// echo message(); ?>

        <?php //echo form_errors($errors); ?>

      </div>
      
      <h2 style="text-align: center;"> New User </h2>

      <div class="row" ng-controller="Register">  

        <!-- enctype="multipart/form-data" -->

      <form novalidate method="post" name="myform" action="register-without-js.php" enctype="multipart/from-data" style="width:40%;margin:auto;text-align:center">
   
          <div class="input-field col s12">

            <label for="un">User Name</label>

            <input id="un" type="text" name="userName" style="text-align: center;" ng-maxlength="30" required />

            <span ng-cloak ng-show="'<?php echo $username; ?>'">User Name is required !</span>

            <span ng-cloak ng-show="<?php echo $usernameExists; ?>">User Name already exists !</span>

            <span ng-cloak ng-show="<?php echo false; ?>">User Name must be less than 30 character or numbers .</span>

          </div>

          <div class="input-field col s12">

            <label for="ps">Password</label>

            <input id="ps" type="password" name="password" style="text-align: center;" required/>

            <span ng-cloak ng-show="'<?php echo $password; ?>' && myform.password.$touched">Password is required.</span>

          </div>

          <div class="input-field col s12">

            <label for="fn">First Name</label>

            <input id="fn" type="text" name="firstName" style="text-align: center;" required/>

            <span ng-cloak ng-show="'<?php echo $firstName; ?>' && myform.firstName.$touched">First Name is required.</span>

          </div>

          <div class="input-field col s12">

            <label for="ln">Last Name</label>

            <input id="ln" type="text" name="lastName" style="text-align: center;" required/>

            <span ng-cloak ng-show="'<?php echo $lastName; ?>' && myform.lastName.$touched">Last Name is required.</span>

            <div><br><br></div>

          </div>


		<div>
          <label class="labels" style="font-size: 16px">Gender</label><br>

          <br>

          <input class="with-gap" name="gender" type="radio" id="male" value="1" required />

          <label for="male">Male</label>

          <br>

          <input class="with-gap" name="gender" type="radio" id="female" value="2" required/>

          <label for="female" style="margin-right: -16px">Female</label>

          <br>

          <input class="with-gap" name="gender" type="radio" id="other" value="3" required />

          <label for="other" style="margin-right: -5px">Other</label>
            <h3 ng-cloak ng-show="<?php echo false; ?>" >Gender is required.</h3>

          <br><br>
            <span ng-cloak ng-show="'<?php echo $gender; ?>' && myform.gender.$touched">Gender is required.</span>
			</div>


          <div class="input-field col s12">

            <label for="em">E-mail</label>

            <input id="em" type="text" name="email" style="text-align: center;" required/>

            <span id="emailConfirm" ng-cloak ng-show="'<?php echo $email; ?>' && myform.email.$touched">Email is required!</span>
            <span  ng-cloak ng-show="<?php echo $emailExists; ?>" >Email already exists !</span>

          </div>

          <div class="input-field col s12">

            <label for="pn">Phone Number</label>

            <input id="pn" type="text" name="phone"  style="text-align: center;" required />

            <span ng-cloak ng-show="'<?php echo $phone; ?>' && myform.phone.$touched">Phone Number is required.</span>

          </div>

          <div class="input-field col s12">

            <!-- <i class="material-icons prefix">today</i> -->

            <input id="DOB" name="dob" type="date" class="datepicker"   required />

            <span ng-cloak ng-show="'<?php echo $dob; ?>' && myform.dob.$touched">Date of Birth is required.</span>

            <label for="DOB">Date of Birth</label>

          </div>
          <input type="file" name="image">

          <!-- <div class="input-field col s12">
      
            <div class="dropzone" file-dropzone="[image/png, image/jpeg, image/gif]" file="image" file-name="imageFileName" data-max-file-size="3">
          
              <input type="file" fileread="uploadme.src" name="image" id="file" class="inputfile">

              <img ng-src="{{uploadme.src}}" width="200" height="200" id="profileImage">

              <div><br></div>

            </div>

            <div>

              <button class="btn btn-primary" onclick="mylabel.click()">Choose image</button>

              <label id="mylabel" for="file"></label>

              <div><br><br><br></div>

            </div>

          </div> -->
       
          <input type="submit" class="btn btn-primary" name="submit" value="Next" />

          <input type="submit" name="cancel" class="btn btn-primary" value="Reset" id="cancel" "/>
          
        </form>

      </div>

      <br>
      
    </div>

  </body>

</html>

<script type="text/javascript">
	
var registerApp = angular.module('register',[]);

registerApp.controller('Register',['$scope','$http',function($scope,$http) {
	}]);
</script>