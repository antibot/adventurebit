<?php

  function message($text='', $type='') {
    echo json_encode(array($type => $text));  
  }

  try {
  
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      switch($_POST['type']) {
      
        case 'reg':
          $login = $_POST['login'];
          $email = $_POST['email'];
          $password = $_POST['password'];
          $repeat = $_POST['repeat'];
        break;
        
        case 'auth':
          $login = $_POST['login'];
          $password = $_POST['password'];
          $rememberme = $_POST['rememberme'];
        break; 
        
        case 'forgot':
          $email = $_POST['email'];
        break; 
        
        default: 
          message('Invalid request', 'error');  
      }
      return;
    } 
  
  } catch(Exception $e) {
    echo 'error';
  }

?>