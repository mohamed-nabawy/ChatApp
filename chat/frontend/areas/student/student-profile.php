<?php 
	require(dirname(__DIR__, 3) . '/backend/functions.php');
	validatePageAccess([1]);
?>

<!DOCTYPE html>

<html>

	<head>

		<title>Profile</title>

		<!-- if run with ie 10 compatible -->
		<meta http-equiv="X-UA-Compatible" name="viewport" content="IE=11,width=device-width,initial-scale=1.0">

		<link rel="icon" href="/chat/favicon.ico">

		<link rel="stylesheet" type="text/css" href="/chat/frontend/css/modules/bootstrap.css">

		<link rel="stylesheet" type="text/css" href="/chat/frontend/css/student/student-layout.css">

		<link rel="stylesheet" type="text/css" href="/chat/frontend/css/modules/font-awesome.css">

		<link rel="stylesheet" type="text/css" href="/chat/frontend/css/student/student-profile.css" />
		
	</head>

	<body style="background-image: url('')" ng-app="student" ng-controller="studentProfile">

		<?php
			require(dirname(__DIR__) . '/header.php');
		?>

		<div style="position: fixed;bottom: 0">

			<div class="ChatmatesPanel" style="display: inline-block;">
				
				<h4 onclick="$(this).parent().toggleClass('hideShowContacts');" class="clickable">Contacts</h4>

				<div ng-repeat="u in myClassMatesAndTeachers" class="panel classmate-and-teacher">

					<label class="panel-body clickable" ng-bind="u.email" ng-click="addChatWindow(u)"></label>

				</div>

			</div>

			<div style="display: inline-block;" class="thechats">
				<?php
					readfile(dirname(__DIR__) . '/chats.php');
				?>
			</div>

		</div>

		<div id="myModal" class="modal fade">

		  <div class="modal-dialog">

		    <div class="modal-content">

		      <div class="modal-header">

		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

		        <h4 class="modal-title">Change Profile Photo</h4>

		      </div>

		      <div class="modal-body">

		        <form name="myform" method="post">
		          <div style="text-align: center"><button class="btn btn-info" onclick="event.preventDefault();$('#file').trigger('click')">Choose Another Image</button></div>

		          <input type="hidden" value="<?php echo $_SESSION['csrf_token']; ?>" name="csrf_token" id="csrf_token">

		          <img style="display: none" id="myimg" src="">

		          <input type="file" id="file" name="image" class="inputfile" value="" onchange="readURL(this)" />

		          <div id="y">
		            <div id="container">

		              <div id="parent"></div>

		              <br>

		              <div id="cont">

		                <img id="inner" />

		              </div>

		            </div>
		          </div>

		          <div id="x">

		            <div id="profPicture"></div>

		            <div><img style="display: none;" src="<?php echo $_SESSION['image']; ?>" id="myPic" /></div>

		            <div id="container1">

		              <div id="profPicture"></div>

		              <div style="text-align: center" id="cont1">

		                <img id="picInner" src="<?php echo $_SESSION['croppedImage']; ?>" />

		              </div>

		            </div>

		          </div>

		          <input type="hidden" name="x1" id="x1" value="" />
		          <input type="hidden" name="y1" id="y1" value="" />
		          <input type="hidden" name="w" id="w" value="" />
		          <input type="hidden" name="h" id="h" value="" />

		          <br><br>
		        <!-- ng-show="<?php echo $_SESSION['imgFlag'] ?> != 1" -->
		          <div style="text-align: center"><input type="submit" class="btn btn-primary" ng-click="updateImage()" /></div>
		        </form>        

		      </div>

		      <!-- <div class="modal-footer">

		      </div> -->

		    </div>

		  </div>

		</div>

		<style type="text/css">
		  input[type=file] {
		    opacity: 0;
		    /*position: absolute;*/
		    max-width: 10px;
		    top: 0;
		  }

		  #picInner {
		    width: 150px;
		    height: 150px
		  }

		  /* preview container */
		  #cont {
		    width: 150px;
		    height: 150px;
		    /*overflow: hidden;*/
		    /*float: right;*/
		    text-align: center;
		    margin: 0 auto;
		    /*position: relative*/
		  }

		  /* preview */
		  #inner {
		    /*margin: 0 auto;*/
		    /*min-height: 100%;
		    min-width: 100%*/
		    text-align: center;
		  }

		  /* original image */
		  #image {
		    margin: 0 auto;
		    /* Don't set max-height and max-width */
		    /*max-width: 100%;
		    max-height: 100%;*/
		    width: 400px;
		    height: 400px;
		  }
		</style>

		<script type="text/javascript" src="/chat/frontend/javascript/modules/jquery-3.2.1.js"></script>

		<script type="text/javascript" src="/chat/frontend/javascript/modules/bootstrap.js"></script>

		<script type="text/javascript" src="/chat/frontend/javascript/modules/angular.js"></script>

		<script type="text/javascript" src="/chat/frontend/javascript/student/student-layout.js"></script>

		<script type="text/javascript" src="/chat/frontend/javascript/student/student-profile.js"></script>

		<link rel="stylesheet" type="text/css" href="/chat/frontend/javascript/modules/croppie/croppie.css">
		
		<script type="text/javascript" src="/chat/frontend/javascript/modules/croppie/croppie.js"></script>

		<script type="text/javascript" src="/chat/frontend/javascript/crop.js"></script>

	</body>

</html>