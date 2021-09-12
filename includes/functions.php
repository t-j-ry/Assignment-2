<?php

define('SALT', 'a_very_random_salt_for_this_app');

/**
 * Look up the user & password pair from the text file.
 *
 * Passwords are simple md5 hashed.
 *
 * Remember, md5() is just for demonstration purposes.
 * Do not do this in production for passwords.
 *
 * @param $user string The username to look up
 * @param $pass string The password to look up
 * @return bool true if found, false if not
 */
function findUser($user, $pass) 
{
    
    $found = false;

    $hash   = md5($pass . SALT);

    $results = selectQuery('users', 'username', $user);

    if (mysqli_num_rows($results) > 0) {
        while( $row = mysqli_fetch_array($results)  )
        {
            if ($row['password'] == $hash) {
                $found = true;
            }
        }
    }

    return $found;
}

/**
 * Remember, md5() is just for demonstration purposes.
 * Do not do this in production for passwords.
 *
 * @param $data
 * @return bool returns false if fopen() or fwrite() fails
 */
function saveUser($data)
{
    $success = false;

    $username   = trim($data['username']);
    $password   = trim($data['password']);
    $hash       = md5($password . SALT);

    $success = insertQuery('users', $username, $hash);

    return $success;
}

function checkUsername($username)
{
    return preg_match('/^([a-z]|[0-9]){8,15}$/i', $username);
}

/**
 * @param $data
 * @return bool
 */
function checkSignUp($data)
{
    $valid = true;

    // if any of the fields are missing
    if( trim($data['username'])        == '' ||
        trim($data['password'])        == '' ||
        trim($data['verify_password']) == '')
    {
        $valid = false;
    }
    elseif(!checkUsername(trim($data['username'])))
    {
        $valid = false;
    }
    elseif(!preg_match('/((?=.*[a-z])(?=.*[0-9])(?=.*[!?|@])){8}/', trim($data['password'])))
    {
        $valid = false;
    }
    elseif($data['password'] != $data['verify_password'])
    {
        $valid = false;
    }

    return $valid;
}

function filterUserName($name)
{
    // if it's not alphanumeric, replace it with an empty string
    return preg_replace("/[^a-z0-9]/i", '', $name);
}

function errorMessage() {
    return 'You made an "mistake", please try again...';
}

function errorProfile() {
    return 'You can only delete your own profile...';
}

function styles() {
    return '<div style="background-color:#feb9b9;color:gray;text-align:center;height:3rem;padding:.5rem;margin:0 .5rem;font-weight:bold;">';
}

function profileCard($profileUsername, $profileID, $profilePicture) {

    $isUser = isUser();

    $profileCard = "";
    return $profileCard .=     
    '<div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <span>'.$profileUsername.'</span>
                <span class="pull-right text-muted">
                    <a class="" href="delete.php?id='.$profileID.'">
                        <i class="fa fa-trash"></i> Delete
                    </a>
                </span>
            </div>
            <div class="panel-body">
                <p class="text-muted">
                </p>
                <p style="text-align: center;" >
                    <img src="profiles/'.$profilePicture.'" alt="Profile picture of" style="width: 300px;" />
                </p>
            </div>
            <div class="panel-footer">
                <p>'.$isUser.'</p>
            </div>
        </div>
    </div>';
}

function isUser() {

    $user = true;

    if ($user) { 
        return '<button class="btn btn-default" data-toggle="modal" data-target="#changePassword">Change Password</button>';
    }

    return '';

}

function connectToDb() {
    $dbusername = 'root';
    $dbpassword = '';
    $port     = '3306';
    $database = 'comp3015';
    $host     = 'localhost';

    return mysqli_connect($host, $dbusername, $dbpassword, $database, $port);
}

function disconnectDb() {
    $link = connectToDb();
    mysqli_close($link);
}

function displayPosts($table) {

    $link = connectToDb();

    if ($link != false) {
            
        $query = "SELECT * FROM $table order by username";
        
        return mysqli_query($link, $query);   
        
    }
    
}


function selectQuery($table, $whereClause = false, $value = false) {

    $link = connectToDb();

    if ($link != false) {
        
        if ($whereClause !== false && $value !== false) {
            
            $query = "SELECT * FROM $table WHERE $whereClause='".$value."'";
            
            return mysqli_query($link, $query);   
            
        } else if ($whereClause == false) {
            
            $query = "SELECT * FROM $table";
            
            return mysqli_query($link, $query);
        }
        
    }
    
}

function deleteQuery($value) {

    $link = connectToDb();

    if ($link != false) {
           
        $query = "DELETE FROM profiles WHERE id='".$value."'";
        
        mysqli_query($link, $query);
    }

    mysqli_close($link);
}

function insertQuery($table, $username, $param2) {

    $link = connectToDb();

    if ($link != false) {

        if ($table != 'users') {

            $query = "INSERT INTO $table (username, picture)
                    VALUES ('".$username."', '".$param2."')";
        
            $queryResults = mysqli_query($link, $query);
            
        } else {

            $query = "INSERT INTO $table (username, password)
                        VALUES ('".$username."', '".$param2."')";
            
            $queryResults = mysqli_query($link, $query);
        
        }

        if($queryResults)

        {
            mysqli_close($link);
            $success = true;
        }
        
    }

    return $success;
}

function searchAdmin() {
    return true;
}
