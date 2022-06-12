<?php

$db_hostname = "localhost";
$db_username = "root";
$db_password = "1234";
$db_database = "wp";


function randomColorDistributer(){
  $color_array = array(
    "#f4f4f4", "#ebf0f5", "#f6eeed", "rgb(241, 241, 234)",
  );
  return $color_array[rand(0, 3)];
}

function colorDistributer($i){
  $color_array = array(
    "#f4f4f4", "#ebf0f5", "#f6eeed", "rgb(241, 241, 234)",
  );
  return $color_array[$i];
}