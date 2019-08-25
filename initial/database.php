<?php
  $query = 'CREATE TABLE IF NOT EXISTS user(
           id INT AUTO_INCREMENT PRIMARY KEY,
           username VARCHAR(60) NOT NULL,
           password VARCHAR(150) NOT NULL,
           name VARCHAR(60) NOT NULL
       )';
 if(!mysqli_query($con,$query)){
 	die('connection error');
 }

$query = 'CREATE TABLE IF NOT EXISTS search(
           id INT AUTO_INCREMENT PRIMARY KEY,
           user_id INT NOT NULL,
           query VARCHAR(50) NOT NULL,
           FOREIGN KEY (user_id) REFERENCES user(id)
         )';
  if(!mysqli_query($con,$query)){
  die('connection error');
 }       
?>