<!DOCTYPE html>

<html>

  <head>

    <title>Register</title>

    <meta http-equiv="X-UA-Compatible" name="viewport" charset="UTF-8" content="IE=11,width=device-width,initial-scale=1.0" />

    <link rel="icon" href="../favicon.ico">

    <link rel="stylesheet" type="text/css" href="css/modules/materialize.css" />

    <link href="css/input_file.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="icons/icons.css" />

    <link href="css/errors.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="css/register.css" />

    <!-- replace with the minimized version in production -->
    <script src="javascript/modules/jquery-3.2.1.js"></script>

    <!-- replace with the minimized version in production -->
    <script src="javascript/modules/angular.js"></script>

    <script src="javascript/newjavascript.js"></script>

    <script src="javascript/modules/materialize.js"></script>

    <link rel="stylesheet" type="text/css" href="javascript/modules/croppie/croppie.css">

    <script type="text/javascript">

      angular.module('registerApp', ['registerFormValidation']).controller('registerController', ['$scope', function($scope) {
          $scope.image = {};
          $scope.image.src = '';
      }]);
      
    </script>

  </head>

  <style type="text/css">

    *{
      color: red
    }
    
  </style>

  <body style="background-image: url('');background-repeat: no-repeat;background-size: cover">

    <div>

      &nbsp;

    </div>
    
    <h2 style="text-align: center;color: violet">New User</h2>

    <div ng-app="registerApp" ng-controller="registerController" class="row">

      <form ng-cloak novalidate role="form" name="myform" style="width: 40%;margin: auto;text-align: center" enctype="multipart/form-data" method="post" action="../backend/requests/users.php">

        <div class="input-field col s12">

          <label>First Name</label>

          <input type="text" name="firstName" ng-model="firstName" style="text-align: center" required />

          <span ng-show="myform.firstName.$touched && myform.firstName.$invalid">First Name is Required</span>

        </div>

        <div class="input-field col s12">

          <label>Last Name</label>

          <input type="text" name="lastName" ng-model="lastName" style="text-align: center" required />

          <span ng-show="myform.lastName.$touched && myform.lastName.$invalid">Last Name is Required</span>

        </div>

        <div class="input-field col s12">

          <label>E-mail</label>

          <input check type="email" name="email" ng-model="email" style="text-align: center" required />

          <div class="errorMes" ng-show="(myform.email.$touched || myform.$submitted) && myform.email.$error.emailEmpty">

            Email is Required

          </div>

          <div class="errorMes" ng-show="(myform.email.$touched || myform.$submitted) && !myform.email.$error.emailEmpty && !myform.email.$error.emailExisted && myform.email.$invalid">

            Email is invalid

          </div>

          <div class="errorMes" ng-show="(myform.email.$touched || myform.$submitted) && myform.email.$error.emailExisted">

            Email already existed

          </div>

        </div>

        <div class="input-field col s12">

          <label>Password</label>

          <input check type="password" name="password" ng-model="password" style="text-align: center" required />

          <div class="errorMes" ng-show="(myform.password.$touched || myform.$submitted) && myform.password.$error.passEmpty">

            Password is Required

          </div>

          <div class="errorMes" ng-show="(myform.password.$touched || myform.$submitted) && myform.password.$error.checkPassword && !myform.password.$error.passEmpty">

            Password is Invalid. It must contain at least one lowercase letter, one uppercase letter and one digit

          </div>

        </div>

        <div class="input-field col s12">
          
          <label>Confirm Password</label>

          <input check class="inputField" check type="password" class="form-control" ng-model="confirmPassword" name="confirmPassword" required />

          <div class="errorMes" ng-show="(myform.confirmPassword.$touched || myform.$submitted) && myform.confirmPassword.$error.confirmPassEmpty">

            Confirm Password is Required

          </div>

          <div class="errorMes" ng-show="(myform.confirmPassword.$touched || myform.$submitted) && !myform.confirmPassword.$error.checkConfirmPassword && !myform.confirmPassword.$error.confirmPassEmpty && password != confirmPassword">

            Confirm Password is not as the password

          </div>

          <div class="errorMes" ng-show="(myform.confirmPassword.$touched || myform.$submitted) && myform.confirmPassword.$error.checkConfirmPassword && !myform.confirmPassword.$error.confirmPassEmpty">
            
            Confirm Password is Invalid. It must contain at least one lowercase letter, one uppercase letter and one digit

          </div>

        </div>

        <div class="input-field col s12">

          <label>Phone Number</label>

          <input check type="text" name="phone" ng-model="phone" style="text-align: center" required />

          <div class="errorMes" ng-show="(myform.phone.$touched || myform.$submitted) && myform.phone.$error.checkPhoneNumber && !myform.phone.$error.phoneEmpty">

            Phone Number is Invalid. It must has 11 digits starting with 01

          </div>

          <div class="errorMes" ng-show="(myform.phone.$touched || myform.$submitted) && myform.phone.$error.phoneEmpty">

            Phone Number is Required

          </div>

        </div>

        <div class="input-field col s12">

          <input check name="DOB" type="date" ng-model="DOB" class="datepicker" required />

          <label>Date of Birth</label>

          <div class="errorMes" ng-show="(myform.DOB.$touched || myform.$submitted) && myform.DOB.$error.birthEmpty">

            Date of Birth is Required

          </div>

          <div class="errorMes" ng-show="(myform.DOB.$touched || myform.$submitted) && !myform.DOB.$error.birthEmpty && myform.DOB.$error.checkBirth">

            Date of Birth is Invalid

          </div>

          <br><br>

        </div>

        <label class="labels" style="font-size: 16px">Gender</label>

        <br><br>

        <input class="with-gap" name="genderId" ng-model="genderId" type="radio" value="1" id="male" required />

        <label for="male">Male</label>

        <br>

        <input class="with-gap" name="genderId" ng-model="genderId" type="radio" value="2" id="female" required />

        <label for="female" style="margin-right: -17px">Female</label>

        <span ng-show="myform.genderId.$touched && myform.genderId.$invalid">Gender is Required</span>

        <br><br><br>

        <button class="btn btn-info" onclick="event.preventDefault();$('#file').trigger('click')">Choose Image</button> 

        <br><br>

        <!-- let user decide whether to crop or not -->
        <!-- <button onclick="event.preventDefault();crop();">Crop Image</button> -->

        <input type="file" id="file" name="image" class="inputfile" onchange="readURL(this)">

        <div id="container">

          <div id="parent"></div>

          <br><br><br>

          <div id="cont">

            <img id="inner" />

          </div>

        </div>

        <br>

        <input type="hidden" name="x1" value="" />
        <input type="hidden" name="y1" value="" />
        <input type="hidden" name="w" value="" />
        <input type="hidden" name="h" value="" />

        <input ng-if="myform.$invalid" type="button" name="submit" style="margin-left: -50px" class="btn btn-primary" value="Next" />
        
        <input ng-if="myform.$valid" type="submit" name="submit" style="margin-left: -50px" class="btn btn-primary" value="Next" />
        
      </form>

    </div>

    <br>

    <style type="text/css">
      .errorMes {
        color: red;
      }
    </style>

  </body>

</html>

<script type="text/javascript" src="javascript/modules/croppie/croppie.js"></script>

<script src="javascript/modules/crop.js"></script>

<script type="text/javascript" src="javascript/modules/register_form_validation.js"></script>