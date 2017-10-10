<!DOCTYPE html>

<html>

  <head>

    <meta name="viewport" charset="utf-8" content="width=device-width,initial-scale=1.0">
  
    <link href="/ChatApp/chat/frontend/css/errors.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="/ChatApp/chat/frontend/css/materialize.css">

    <script src="/ChatApp/chat/frontend/javascript/modules/jquery-3.2.1.js"></script>

    <script src="/ChatApp/chat/frontend/javascript/modules/angular.js"></script>

    <script src="/ChatApp/chat/frontend/javascript/modules/materialize.js"></script>

    <script type="text/javascript">

      angular.module('loginApp',[]).controller('loginController',['$scope',function($scope){}]);
      
    </script>

  </head>

  <body style="background-image: url('');background-repeat: no-repeat;background-size: cover">

    <div>

      &nbsp;

    </div>

    <div id="page" style="align-content: center;text-align: center">
      
      <h1 style="font-style: italic">Login</h1>

      <form novalidate role="form" name="myform" ng-app="loginApp" ng-controller="loginController" action="/ChatApp/chat/backend/requests/login.php" method="post" class="login-box" style="width: 30%;margin: auto;text-align: center">

        <div class="input-field col s12">

          <label for="email" style="font-size: 25px">E-mail</label>

          <input type="email" name="email" ng-model="email" required />

          <span ng-show="myform.email.$touched && myform.email.$invalid" style="color: red">email is Required</span>

        </div>

        <div class="input-field col s12">

          <label for="password" style="font-size: 25px">Password</label>

          <input type="password" name="password" ng-model="password" required />

          <span ng-show="myform.password.$touched && myform.password.$invalid" style="color: red">password is Required</span>

        </div>

        <div class="input-field col s12" style="margin-left: 55px">

          <input type="checkbox" id="rememberme" name="remember" />

          <label for="rememberme" style="">Remeber me</label>

        </div>

        <br><br>

        <input ng-if="myform.$invalid" type="button" name="submit" style="margin-left: -50px" class="btn btn-primary" value="Next" />

        <input ng-if="myform.$valid" type="submit" name="submit" style="margin-left: -50px" class="btn btn-primary" value="Next" />

      </form>

      <br>

      <a href="<?php echo "/ChatApp/chat/index.php";?>">

        <button class="btn waves-effect waves-light btn" type="submit" name="action">Facebook Login

          <img src="/ChatApp/chat/frontend/icons/facebook.png" width="30px" height="30px" style="margin-top: 2px">

        </button>

      </a>

      <div><br></div>

      <div>

        <div>

          <a href="/ChatApp/chat/frontend/register.php" name="submit" />New User ! </a>

        </div>

        <a href="/ChatApp/chat/frontend/resetPassword.php" name="submit" />Forgot Password ! </a>

        <div><br></div>

      </div>

    </div>

  </body>

</html>

<div style="align-content: center;text-align: center;font-style: italic;color: white">&copy; 2010-<?php echo date("Y");?></div>