<!DOCTYPE HTML>
<html>

<head>
    <title>PC Tech</title>
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine&amp;v1" />
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>public/css/bootstrap.min.css" />
</head>

<body>
    <div id="main">
        <div id="header">
            <div id="logo">
                <h1><a href="<?=base_url()?>Blog/">PC Tech</a></h1>
                <div class="slogan">Computer Technology News and Updates Blog!</div>
            </div>
            <div id="menubar">
                <ul id="menu">
                  <!-- put class="current" in the li tag for the selected page - to highlight which page you're on -->
                    <li class="<?=$home_class;?>" ><a href="<?=base_url()?>Blog/">Home</a></li>
                    <?php 
					$validate_token = $this->session->userdata('user_token');
					$is_valid_token = $this->authorization_token->validateTokenPost($validate_token);
					if($is_valid_token['status'] === TRUE){ 
					?>
						<li class="<?=$profile_class;?>" ><a href="<?=base_url()?>Pages/myProfile">My Profile</a></li>
                        <li class="<?=$login_class;?>" ><a href="<?=base_url()?>Users/logout">(<?=$this->session->userdata['username']?>)Logout</a></li>
                    <?php
                    } 
                    else{ 
					?>
                        <li class="<?=$login_class;?>" ><a href="<?=base_url()?>Users/login">Login</a></li>
                    <?php } ?>
					<?php if($is_valid_token['status'] === FALSE){ ?>
						<li class="<?=$register_class;?>" ><a href="<?=base_url()?>Users/register/">Register</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>