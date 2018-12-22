<?php
  require(dirname(__DIR__) . '/classes/users.php');

  class UserController {

    private $user = NULL;

    public function __construct(User $user) {
      $this->user = $user;
    }

    public function getUsers($conn) { // in the same class
      //$user = new User();
      
      return $this->user->getUsers($conn);
    }

    public function addChatUser($user) { // add user to open chats
      //$user1 = new User();
    
      return $this->user->addChatUser($user);
    }

    public function deleteChat($id) { // close the window
      //$user = new User();
    
      return $this->user->deleteChat($id);
    }

    public function getUserById($conn, $id) { // check validations on this
      //$user = new User();
    
      return $this->user->getUserById($conn, $id);
    }

    public function openOrCloseChat($chatId, $open) {
      //$user = new User();
    
      return $this->user->openOrCloseChat($chatId, $open);
    }

    public function getCurrentUser($conn) {
      //$user = new User();
    
      return $this->user->getCurrentUser($conn);
    }

    public function addUser($conn, $firstName, $lastName, $image, $email, $phoneNumber, $password, $dateOfBirth, $genderId, $roleId, $x1 = null, $y1 = null, $w = null, $h = null) {
      //$user = new User();
    
      return $this->user->addUser($conn, $firstName, $lastName, $image, $email, $phoneNumber, $password, $dateOfBirth, $genderId, $roleId, $x1 = null, $y1 = null, $w = null, $h = null);
    }

    public function editUser($conn, $userName, $firstName, $lastName, $email, $image, $phoneNumber, $roleId, $id) {
      //$user = new User();
    
      return $this->user->editUser($conn, $userName, $firstName, $lastName, $email, $image, $phoneNumber, $roleId, $id);
    }

    // public function updateUserPasswordByEmail($conn,$password,$email)
    // {
    //   if (!isset($password) || !isset($email)) 
    //   {
    //     //echo "User password is empty !";
    //     return;
    //   }
    //   else
    //   {
    //     $sql = "update User set PasswordHash = (?) where Email = (?)"; 
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param("ss",$Password,$Email);
    //     $Email = $email;
    //     $Password = $password;
    //     if ($stmt->execute() === TRUE)
    //     {
    //       return "User updated successfully";
    //     }
    //     else
    //     {
    //       echo "Error: ".$conn->error;
    //     }
    //   }
    // }

    // public function updateUserPasswordById($conn,$password,$id)
    // {
    //    if (!isset($password) || !isset($id)) 
    //   {
    //     //echo "User password is empty !";
    //     return;
    //   }
    //   else
    //   {
    //     $sql = "update User set PasswordHash = (?) where Id = (?)"; 
    //     $stmt = $conn->prepare($sql);
    //     $stmt->bind_param("si",$Password,$Id);
    //     $Id = $id;
    //     $Password = $password;
    //     if ($stmt->execute() === TRUE)
    //     {
    //       return "User updated successfully";
    //     }
    //     else
    //     {
    //       echo "Error: ".$conn->error;
    //     }
    //   }
    // }

    public function activateUser($conn, $id) { // check validations on this
      //$user = new User();
    
      return $this->user->activateUser($conn, $id);
    }

    public function checkExistingUserName($conn, $userName, $register_edit) {
      //$user = new User();
    
      return $this->user->checkExistingUserName($conn, $userName, $register_edit);
    }
      
    // need to know if he's entering the same email or not as the condition will differ
    public function checkExistingEmail($conn, $email) { // problem if he wants to edit his info cause' of his email
      //$user = new User();
    
      return $this->user->checkExistingEmail($conn, $email);
    }

    public function deleteUser($conn, $id) { // cascaded delete ??
      //$user = new User();
    
      return $this->user->deleteUser($conn, $id);
    }
  }
?>