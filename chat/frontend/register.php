<!DOCTYPE html>

<html>

  <head>

    <title>Register Form</title>

    <meta name="viewport" charset="UTF-8" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" type="text/css" href="/chat/frontend/css/materialize.css" />

    <link href="/chat/frontend/css/input_file.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="/chat/frontend/icons/icons.css" />

    <link href="/chat/frontend/css/errors.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="/chat/frontend/css/register.css" />

    <script src="/chat/frontend/javascript/jquery-3.2.1.js"></script>

    <script src="/chat/frontend/javascript/newjavascript.js"></script>

    <script src="/chat/frontend/javascript/materialize.js"></script>

  </head>

  <style type="text/css">

    *{
      color: violet
    }
    
  </style>

  <body style="background-image:url('/chat/frontend/images/chat background register.jpg');background-repeat:no-repeat;background-size:cover">

    <div>

      &nbsp;

    </div>
    
    <h2 style="text-align:center">New User</h2>

    <div class="row">  

      <form role="form" name="myform" style="width:40%;margin:auto;text-align:center" method="post" enctype="multipart/form-data" action="/chat/backend/requests/user.php">

        <div class="input-field col s12">

          <label>First Name</label>

          <input type="text" name="firstName" style="text-align:center" required />

          <span class="error" name="firstName">First Name is Required</span>

        </div>

        <div class="input-field col s12">

          <label>Last Name</label>

          <input type="text" name="lastName" style="text-align:center" required />

          <span class="error" name="lastName">Last Name is Required</span>

        </div>

        <div class="input-field col s12">

          <label>E-mail</label>

          <input type="text" name="email" style="text-align:center" required />

          <span class="error" name="email">Email is Required</span>

        </div>

        <div class="input-field col s12">

          <label>Password</label>

          <input type="password" name="password" style="text-align:center" required />

          <span class="error" name="password">Password is Required</span>

        </div>

        <div class="input-field col s12">

          <label>Phone Number</label>

          <input type="text" name="phone" style="text-align:center" required />

          <span class="error" name="phone">Phone Number is Required</span>

        </div>

        <div class="input-field col s12">

          <input name="DOB" type="date" class="datepicker" required />

          <label>Date of Birth</label>

          <span class="error" name="DOB">Date of Birth is Required</span>

        </div>

        <div><br><br></div>

        <label class="labels" style="font-size:16px">Gender</label>

        <br><br>

        <input class="with-gap" name="gender" type="radio" value="1" id="male" required />

        <label for="male">Male</label>

        <br>

        <input class="with-gap" name="gender" type="radio" value="2" id="female" required />

        <label for="female" style="margin-right:-150px">Female</label>

        <span class="error" name="gender">Gender is Required</span>

        <br>

        <div>
        
          <input type="file" name="file" />

          <!-- <img style="margin-left:-50px;width:200px;height:200px" /> -->

          <!-- div><br></div>

          <button class="btn btn-primary" style="margin-left:-50px" onclick="mylabel.click()">Choose Image</button> -->

          <!-- <label for="file">Choose Image</label> -->

          <div><br><br><br></div>

        </div>

        <input type="submit" name="submit" style="margin-left:-50px" class="btn btn-primary" value="Next" />
        
      </form>

    </div>

    <br>

  </body>

  <script type="text/javascript" src="/chat/frontend/javascript/user.js"></script>

</html>