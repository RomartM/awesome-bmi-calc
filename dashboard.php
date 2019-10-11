<?php

  function tabFileName($filename){
      return "includes/tab-".$filename.".php";
  }

  // Plugin Manifest
  $app = new stdClass();
    $app->icon        = "../images/abc.svg";
    $app->title       = "Awesome BMI";
    $app->description = "A Simple Body Max Index Widget that calculates and
    provide suggestions on how to improve someone's health by offering good
    lifestyle. This offering is also customizable means you can add any of your
    post that may fit for BMI standards.";

  // Tabs Menu Configuration
  // Add another array to create new tab
  $tabs_object = (object) [
    [
      'id'=>'customize',
      'isActive'=>true,
      'name'=>'Customize Result Suggestions',
      'content_file_name'=> tabFileName('customize')
    ]
  ];

  include('template/dashboard-form.php');
 ?>
