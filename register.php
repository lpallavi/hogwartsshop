<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        $_POST["email"] = strtolower($_POST["email"]);

//        if (empty($_POST["email"]))
//        {
//            apologize("You must provide your email ID.");
//        }
//        // else if (!(preg_match("/^\w+@\w+\.[a-z][a-z][a-z]/",$_POST["email"])))
//        // {
//        //     apologize("You must provide a valid email ID.");
//        // }
//        else if (empty($_POST["password"]))
//        {
//            apologize("You must provide your password.");
//        }
//        else if (empty($_POST["confirmation"]))
//        {
//            apologize("Please retype your password.");
//        }
//        else if ($_POST["password"] != $_POST["confirmation"])
//        {
//            apologize("Passwords do not match.");
//        }

        $default_profile_pic = SITE_ROOT . "profile_img/unknown.jpg";
        // query database for wizards
        $qsays = query("INSERT INTO wizards (email, fullname, hash, knuts,profile_pic) VALUES( ?, ?, ?, 10000,?)", $_POST["email"], ($_POST["firstname"]." ".$_POST["lastname"]), crypt($_POST["password"]),$default_profile_pic);

        // if insert fails
        if ($qsays === false)
        {
            // apologize
            apologize("Unable to register.Please try again.");
        }
        else
        {
            // Get details of last transaction
            $rows = query("SELECT LAST_INSERT_ID() AS id");

            // Get id
            $id = $rows[0]["id"];

            // remember that user's now logged in by storing user's ID in session
            $_SESSION["id"] = $id;

            // redirect to home page
            redirect("/");
        }
    }
    else
    {
        // else render form
        render("register_form.php", ["title" => "Register"]);
    }

?>
