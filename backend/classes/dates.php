<?php


/**
 * 
 */
class Date
{
	// private $id = 0;
	// private $date = '';

	public function getDates($conn) {
		$sql = "select * from `dates`";
		$result = $conn->query($sql);

		if ($result) {
			$dates = mysqli_fetch_all($result, MYSQLI_ASSOC);
			mysqli_free_result($result);

			return $dates;
		}
		else {
			echo "Error retrieving Dates: " . $conn->error;
		}
	}

	public function getDateById($conn, $id) {
		$sql = "select `date` from `dates` where `id` = " . $id . " LIMIT 1";
		$result = $conn->query($sql);

		if ($result) {
			$Id = mysqli_fetch_assoc($result);
			mysqli_free_result($result);

			return $Id;
		}
		else {
			echo "Error retrieving Date: " . $conn->error;
		}
	}

	public function getDateIdByDate($conn, $value) {
	  $sql = "select `id` from `dates` where `date` = '{$value}'";
	  $result = $conn->query($sql);

	  if ($result) {
	    $dateId = mysqli_fetch_assoc($result);
	    mysqli_free_result($result);
	    return $dateId["id"];
	  }
	  else {
	    echo "Error retrieving Dates: " . $conn->error;
	  }
	}

	public function getCurrentDateId($conn) { // CURDATE() mysql 
	  $today = date("Y-m-d");
	  $sql = "select `id` from `dates` where `date` = STR_TO_DATE('{$today}', '%Y-%m-%d')";
	  $result = $conn->query($sql);

	  if ($result) {
	    $date = mysqli_fetch_assoc($result);
	    mysqli_free_result($result);
	    if ( isset($date["id"]) ) {
	      return $date["id"];
	    }
	    else {
	      return false;
	    }
	  }
	  else {
	    echo "Error retrieving Date Id: " . $conn->error;
	  }
	}

	public function addDate($conn, $date) { // check format of the input 
	  $sql = "insert into `dates` (`date`) values (?)";
	  $stmt = $conn->prepare($sql);
	  $stmt->bind_param("s", $date);

	  if ($stmt->execute() === TRUE) {
	    return "Date Added successfully";
	  }
	  else {
	    echo "Error: " . $conn->error;
	  }
	}

	public function addTodayDate($conn) { // check format of the input  // ************************************************
	  $today = date("Y-m-d");
	  $sql = "insert into `dates` (`date`) values (?)";
	  $stmt = $conn->prepare($sql);
	  $stmt->bind_param("s", $today);
	  
	  if ($stmt->execute() === TRUE) {
	    return true;
	  }
	  else {
	    echo "Error: " . $conn->error;
	  }
	}

	public function editDate($conn, $date, $id) {
	  $sql = "update `dates` set `date` = (?) where `id` = (?)";
	  $stmt = $conn->prepare($sql);
	  $stmt->bind_param("si", $date, $id);
	  
	  if ($stmt->execute() === TRUE) {
	    return "Date updated successfully";
	  }
	  else {
	    echo "Error: " . $conn->error;
	  }
	}

	public function deleteDate($conn, $id) {
	  //$conn->query("set foreign_key_checks=0");
	  $sql = "delete from `dates` where `id` = " . $id . " LIMIT 1";

	  if ($conn->query($sql) === TRUE) {
	    return "Date deleted successfully";
	  }
	  else {
	    echo "Error: " . $conn->error;
	  }
	}
	
	// function __construct(argument)
	// {
	// 	# code...
	// }
}