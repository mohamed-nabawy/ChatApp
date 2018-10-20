<?php

  require dirname(__DIR__) . '/classes/chat-messages.php';

  class ChatMessageController {

    public function getMessagesBetweenUsersIdsInClass($conn, $firstUserId, $secondUserId, $classId, $offset) {
      $chatMessage = new ChatMessage(); 
      
      return $chatMessage->getMessagesBetweenUsersIdsInClass($conn, $firstUserId, $secondUserId, $classId, $offset);
    }

    public function checkMessageExistence($conn, $messageId) {
      $chatMessage = new ChatMessage();

      return $chatMessage->checkMessageExistence($conn, $messageId);
    }

    // select last message for every chat between current user and other users
    public function getLastCurrentUserMessages($conn, $offset) {
      $chatMessage = new ChatMessage();

      return $chatMessage->getLastCurrentUserMessages($conn, $offset);
    }

    public function recieveNewMessageForUserIdInClass($conn, $UserId, $classId) {
      $chatMessage = new ChatMessage();

      return $chatMessage->recieveNewMessageForUserIdInClass($conn, $UserId, $classId);
    }

    public function checkNewMessageForUserIdInClass($conn, $UserId, $classId) {
      $chatMessage = new ChatMessage();

      return $chatMessage->checkNewMessageForUserIdInClass($conn, $UserId, $classId);
    }

    public function getMessageById($conn, $id) { // check validations on this
      $chatMessage = new ChatMessage();

      return $chatMessage->getMessageById($conn, $id);
    }

    public function sendMessage($conn, $content, $sentFrom, $sentTo, $classId) {
      $chatMessage = new ChatMessage();

      return $chatMessage->sendMessage($conn, $content, $sentFrom, $sentTo, $classId);
    }

    public function addOrUpdateMessageNotification($conn, $sentFrom, $sentTo) {
      $chatMessage = new ChatMessage();
      
      return $chatMessage->addOrUpdateMessageNotification($conn, $sentFrom, $sentTo);
    }

    public function getMessageNotifications($conn) {
      $chatMessage = new ChatMessage();
      
      return $chatMessage->getMessageNotifications($conn);
    }

    public function markAllMessageNotificationsAsRead($conn) {
      $chatMessage = new ChatMessage(); 
      
      return $chatMessage->markAllMessageNotificationsAsRead($conn);
    }

  public function markMessageAsReadFromSomeUser($conn, $userId) { // cascaded delete ??
      $chatMessage = new ChatMessage(); 
      
      return $chatMessage->markMessageAsReadFromSomeUser($conn, $userId);
    }

    public function markMessageAsRead($conn, $id) { // cascaded delete ??
      $chatMessage = new ChatMessage();

      return $chatMessage->markMessageAsRead($conn, $id);
    }

    public function deleteMessage($conn, $id) { // cascaded delete ?? 
      $chatMessage = new ChatMessage();

      return $chatMessage->deleteMessage($conn, $id);
    }
  }
?>