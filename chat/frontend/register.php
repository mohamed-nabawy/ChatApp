<!DOCTYPE html>

<html>

  <head>

    <title>Register</title>

    <meta name="viewport" charset="UTF-8" content="width=device-width, initial-scale=1.0" />

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

    <script src="javascript/modules/image_module.js"></script>

    <script src="javascript/modules/phone_number_module.js"></script>

    <script type="text/javascript">

      angular.module('registerApp', ['phone_number', 'image']).controller('registerController', ['$scope', function($scope) {
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

      <form novalidate role="form" name="myform" style="width: 40%;margin: auto;text-align: center" enctype="multipart/form-data" method="post" action="../backend/requests/users.php">

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

          <input type="email" name="email" ng-model="email" style="text-align: center" required />

          <span ng-show="myform.email.$touched && myform.email.$invalid">Email is Required</span>

        </div>

        <div class="input-field col s12">

          <label>Password</label>

          <input type="password" name="password" ng-model="password" style="text-align: center" required />

          <span ng-show="myform.password.$touched && myform.password.$invalid">Password is Required</span>

        </div>

        <div class="input-field col s12">

          <label>Phone Number</label>

          <input type="text" name="phone" ng-model="phone" style="text-align: center" required />

          <span ng-show="myform.phone.$touched && myform.phone.$invalid">Phone Number is Required</span>

        </div>

        <div class="input-field col s12">

          <input name="DOB" type="date" ng-model="DOB" class="datepicker" required />

          <label>Date of Birth</label>

          <span ng-show="myform.DOB.$touched && myform.DOB.$invalid">Date of Birth is Required</span>

        </div>

        <div><br><br></div>

        <label class="labels" style="font-size: 16px">Gender</label>

        <br><br>

        <input class="with-gap" name="gender" ng-model="gender" type="radio" value="0" id="male" required />

        <label for="male">Male</label>

        <br>

        <input class="with-gap" name="gender" ng-model="gender" type="radio" value="1" id="female" required />

        <label for="female" style="margin-right: -17px">Female</label>

        <span ng-show="myform.gender.$touched && myform.gender.$invalid">Gender is Required</span>

        <br>

        <div class="image-button">

          <!-- <input type="file"  name="image" fileread="image.src" size="5" /> -->

          <label for="file" class="inside-image-label" onclick="event.preventDefault();$('#file').trigger('click')">Choose Image</label>

        </div>

        <img ng-src="{{image.src}}" />

        <div class="dropzone" file-dropzone="[image/png, image/jpeg]" file="image" file-name="imageFileName" data-max-file-size="3">

          <input type="file" id="file" fileread="uploadme.src" name="image" class="inputfile">

          <img ng-src="{{uploadme.src}}" style="width: 200px;height: 200px">

        </div>

        <!-- <div>
        
          <div class="dropzone" file-dropzone="[image/png, image/jpeg, image/gif]" file="image" file-name="imageFileName" data-max-file-size="3">

          </div>
        
          <input type="file" fileread="imageSrc" name="file" id="file" class="inputfile" />

          <img style="margin-left:-50px;width:200px;height:200px" ng-src="{{imageSrc}}" id="profileImage" />

          <div><br></div>

          <button class="btn btn-primary" style="margin-left:-50px" onclick="mylabel.click()">Choose Image</button>

          <label id="mylabel" for="file"></label>

          <div><br><br><br></div>

        </div> -->

        <input ng-if="myform.$invalid" type="button" name="submit" style="margin-left: -50px" class="btn btn-primary" value="Next" />
        
        <input ng-if="myform.$valid" type="submit" name="submit" style="margin-left: -50px" class="btn btn-primary" value="Next" />
        
      </form>

    </div>

    <br>

  </body>

</html>