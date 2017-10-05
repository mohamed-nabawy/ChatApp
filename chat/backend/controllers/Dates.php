<?php

function getDates($conn)
{
  $sql = "select * from `Dates`";
  $result = $conn->query($sql);
  if ($result)
  {
    $dates = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
      return $dates;
   
  }
  else
  {
    echo "Error retrieving Dates: " . $conn->error;
  }
}

function getDateById($conn ,$id)
{
  if (!isset($id))
  {
    //echo "Error: Date id is not set";
    return;
  }
  else
  {
    $sql = "select Date from `Dates` where Id=".$id." LIMIT 1";
    $result = $conn->query($sql);
    if ($result)
    {
      $Id = mysqli_fetch_assoc($result);
      mysqli_free_result($result);
        return $Id;
      
    }
    else
    {
      echo "Error retrieving Date: " . $conn->error;
    }
  }
}

function getDateIdByDate($conn ,$value)
{
  if (!isset($value))
  {
    //echo "Error: Date value  is not set";
    return;
  }
  else
  {
    $sql = "select Id from `Dates` where `Date` ='{$value}'";
    $result = $conn->query($sql);
    if ($result)
    {
      $dateId = mysqli_fetch_assoc($result);
      mysqli_free_result($result);
        return $dateId["Id"];
     
    }
    else
    {
      echo "Error retrieving Dates: " . $conn->error;
    }
  }
}


function getCurrentDateId($conn) //CURDATE() mysql
{ 
  $today = date("Y-m-d");
  $sql = "select Id from `Dates` where `Date` = STR_TO_DATE('{$today}', '%Y-%m-%d')";
  $result = $conn->query($sql);
  if ($result)
  {
    $date = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    if (isset($date["Id"]))
    {
      return $date["Id"];
    }
    else
    {
      return false;
    }
  }
  else
  {
    echo "Error retrieving Date Id: " . $conn->error;
  }
}

function addDate($conn,$date) // check format of the input
{ 
  if (!isset($date))
  {
    //echo "Error: Date is not set";
    return;
  }
  else
  {
    $sql = "insert into `Dates` (`Date`) values (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$Date);
    $Date = $date;
    if ($stmt->execute()===TRUE)
    {
      return "Date Added successfully";
    }
    else
    {
      echo "Error: ".$conn->error;
    }
  }
}

function addTodayDate($conn) // check format of the input  // ************************************************
 //echo date("Y-m-d");;
{ 
  $today=date("Y-m-d");
  $sql = "insert into `mydb`.`Dates` (`Date`) values (?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s",$Date);
  $Date = $today ;
  if ($stmt->execute()===TRUE)
  {
      return true;
  }
  else
  {
    echo "Error: ".$conn->error;
  }
}

function editDate($conn,$date,$id)
{
  if (!isset($id))
  {
   // echo "Error: Id is not set";
    return;
  }
  elseif (!isset($date))
  {
    //echo "Error: Date is not set";
    return;
  }
  else
  {
    $sql = "update Dates set Date = (?) where Id = (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",$Date,$Id);
    $Date = $date;
    $Id = $id;
    if ($stmt->execute()===TRUE)
    {
      return "Date updated successfully";
    }
    else
    {
      echo "Error: ".$conn->error;
    }
  }
}

function deleteDate($conn,$id)
{
  if (!isset($id))
  {
    //echo "Error: Id is not set";
    return;
  }
  else
  {
    //$conn->query("set foreign_key_checks=0");
    $sql = "delete from Dates where Id = ".$id. " LIMIT 1";
    if ($conn->query($sql)===TRUE)
    {
      return "Date deleted successfully";
    }
    else
    {
      echo "Error: ".$conn->error;
    }
  }
}

?>
