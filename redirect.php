<?php

session_start();

require 'includes/functions.php';

if(count($_POST) > 0)
{
    if($_GET['from'] == 'login')
    {
        $found = false; // assume not found

        $user = trim($_POST['username']);
        $pass = trim($_POST['password']);

        if(checkUsername($user))
        {
            $found = findUser($user, $pass);

            if($found)
            {   
                $_SESSION['username'] = $user;
                $_SESSION['login'] = true;

                if (searchAdmin()) {
                    $_SESSION['admin'] = true;
                }
                
                header('Location: thankyou.php?from=login&username='.filterUserName($user));
                exit();
            }
        }

        // Sets error_message cookie if login fails 
        setcookie("error_message", errorMessage(), time()+ 60);

        header('Location: login.php');
        exit();
    }
    elseif($_GET['from'] == 'signup')
    {
        if(checkSignUp($_POST) && saveUser($_POST))
        {
            $_SESSION['username'] = trim($_POST['username']);
            $_SESSION['login'] = true;
            header('Location: thankyou.php?from=signup&username='.filterUserName(trim($_POST['username'])));
            exit();
        }

        // Sets error_message cookie if login fails 
        setcookie("error_message", errorMessage(), time()+ 60);
        
        header('Location: signup.php');
        exit();
    }
}

header('Location: index.php');
exit();
