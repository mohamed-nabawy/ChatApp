<?php

function getTimes($conn)
{
  $sql = "select * from Times";
  $result = $conn->query($sql);
  if ($result)
  {
    $times = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
      return $times;
   
  }
  else
  {
    echo "Error retrieving Times: " . $conn->error;
  }
}

function getTimeById($conn,$id)
{
  if (!isset($id))
  {
    echo "Error: Time Id is not set";
    return;
  }
  else
  {
    $sql = "select * from Times where Id=".$id." LIMIT 1";
    $result = $conn->query($sql);
    if ($result)
    {
      $times = mysqli_fetch_assoc($result);
      mysqli_free_result($result);
        return $times;
      
    }
    else
    {
      echo "Error retrieving Time: " . $conn->error;
    }
  }
}

function getTimeIdByTime($conn,$time)
{
  $sql = "select Id from Times where Time='{$time}' LIMIT 1";
  $result = $conn->query($sql);
  if ($result)
  {
    $times = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $times["Id"];
  }
  else
  {
    echo "Error: ".$conn->error;
  }
}

function getCurrentTimeId($conn)
{
  $time = date("h:i:00");
  $sql = "select Id from Times where Time='{$time}' LIMIT 1";
  $result = $conn->query($sql);
  if ($result)
  {
    $times = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $times["Id"];
  }
  else
  {
    echo "Error: ".$conn->error;
  }
}

function deleteTime($conn,$id)
{
  if (!isset($id))
  {
    echo "Error: Id is not set";
    return;
  }
  else
  {
    //$conn->query("set foreign_key_checks=0");
    $sql = "delete from Times where Id = ".$id. " LIMIT 1";
    if ($conn->query($sql)===TRUE)
    {
      return "Time deleted successfully";
    }
    else
    {
      echo "Error: ".$conn->error;
    }
  }
}

?>