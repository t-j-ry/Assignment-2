<?php 
session_start();

require 'includes/functions.php';


// If the session flag for the logged in user is not set, redirect to index.php
if(!isset($_SESSION['login']))
{
    header('Location: index.php');
}

$user = $_SESSION['username'];

$id = $_GET['id'];

$found = false;

$results = selectQuery('profiles', 'id', $id);

if (mysqli_num_rows($results) > 0) {
    while( $row = mysqli_fetch_array($results)  )
    {
        if ($row['id'] == $id) {
            if ($row['username'] == $user) {
                $found = true;
            }
        }
    }
}

disconnectDb();

if (!$found) {
    // Sets error_profile cookie if trying to delete a different profile 
    setcookie("error_profile", errorProfile(), time()+ 60);
    header('Location: profiles.php');
    exit();
} else {
    deleteQuery($id);
    header('Location: profiles.php');
    exit();
}


