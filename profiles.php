<?php 
session_start();

require 'includes/functions.php';

// If the session flag for the logged in user is not set, redirect to index.php
if(!isset($_SESSION['login']))
{
    header('Location: index.php');
}

if(isset($_COOKIE["error_profile"])) {
    echo styles(); 
        echo $_COOKIE["error_profile"]; 
    echo '</div>';
    setcookie('error_profile', null, time() - 3600);
}

define("MAX_FILESIZE", 4000000);
define("FILE_TYPE", "image/jpeg");

if(isset($_POST['submit'])){

    if($_FILES['picture']['type'] == FILE_TYPE && $_FILES['picture']['size'] <= MAX_FILESIZE)
    {
        $pictureName = md5(time().$_FILES['picture']['name']) . '.jpg';

        $picture    = 'profiles/' . $pictureName;
        
        move_uploaded_file($_FILES['picture']['tmp_name'], $picture);

    }    
    
    insertQuery('profiles', $_SESSION['username'], $pictureName);

}

if(isset($_POST['submit-password'])){

    // $_POST['username'] = $_SESSION['username'];

    if (findUser($_POST['username'], $_POST['curent-password'])) {
        
        // var_dump($_POST);
        
        if (checkSignUp($_POST) && saveUser($_POST)) {
            echo 'it worked';
        }
    } else {
        echo 'wrong password';
    }
    
    // echo $_POST['curent-password'];
    // echo $_POST['new-password'];
    // echo $_POST['verify-password'];

}


?>

<!DOCTYPE html>
<html>
<head>
    <title>COMP 3015</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div id="wrapper">

    <div class="container">

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <h1 class="login-panel text-center text-muted">
                    COMP 3015 Assignment 2
                </h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <hr/>
                <button class="btn btn-default" data-toggle="modal" data-target="#newPost"><i class="fa fa-comment"></i> New Profile</button>
                <a href="logout.php" class="btn btn-default pull-right"><i class="fa fa-sign-out"> </i> Logout</a>
                <hr/>
            </div>
        </div>
<?php

// $results = selectQuery("profiles");

$results = displayPosts("profiles");

if (mysqli_num_rows($results) > 0) {
    echo '<div class="row">';
    while( $row = mysqli_fetch_array($results)  )
    {
            echo profileCard($row['username'],$row['id'],$row['picture']);
    }
    echo '</div>';
}

disconnectDb();

?>

<!-- Wrapper closing tag -->
</div>

<div id="newPost" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="profiles.php" enctype="multipart/form-data">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">New Profile</h4>
        </div>
        <div class="modal-body">
                <div class="form-group">
                    <label>Username</label>
                    <input class="form-control disabled" disabled value=<?php echo $_SESSION['username'] ?>>
                </div>
                <div class="form-group">
                    <label>Profile Picture</label>
                    <input class="form-control" type="file" name="picture">
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Submit!" name="submit" />
        </div>
    </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Password change model -->
<div id="changePassword" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <form role="form" method="post" action="profiles.php">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Change Password</h4>
        </div>
        <div class="modal-body">
                <div class="form-group">
                    <label>Current Password</label>
                    <input class="form-control" type="text" name="curent-password">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input class="form-control" type="text" name="password">
                </div>
                <div class="form-group">
                    <label>Verify Password</label>
                    <input class="form-control" type="text" name="verify_password">
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-primary" value="Submit!" name="submit-password" />
        </div>
    </div><!-- /.modal-content -->
    </form>
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


</body>

<script src="js/index.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>
