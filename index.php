<?php

    // configuration
    require("../includes/config.php"); 


    //$qsays = query("ALTER TABLE wizards AUTO_INCREMENT = 1");

    // Query database and get latest data for displaying home page 
    $userrows = query("SELECT * FROM wizards WHERE id = ?", $_SESSION["id"]);
    if (count($userrows) != 1)
    {
        // else render form
        render("login_form.php", ["title" => "Log In"]);
    }
    $userrow = $userrows[0];
    $fullname = $userrow["fullname"];
    $email = $userrow["email"];
    $house = $userrow["house"];
    $quidditch = $userrow["quidditch"];
    $profile_pic = $userrow["profile_pic"];
    $knuts = $userrow["knuts"];
    list($g,$s,$k) = conv_knuts_to_galleons($knuts);
    $price = conv_knuts_to_text($g,$s,$k); 

    if ($profile_pic == NULL || $profile_pic == "")
    {
        $profile_pic = "profiles/unknown.jpg"; 
    }

    if ($house == NULL || $house == "")
    {
        $house = "Not Sorted";
    }

    if ($quidditch == NULL || $quidditch == "")
    {
        $quidditch = "Afraid of flying";
    }

    $password = "Unknown";
    switch($house)
    {
        case "Gryffindor": 
            $password = "Mimbulus mimbletonia";
            break;
        case "Slytherin":
            $password = "Parselmouth";
            break;
        case "Ravenclaw":
            $password = "Which came first? Chicken or Egg?";
            break;
        case "Hufflepuff":
            $password = "Tap in the rhythm of Helga Hufflepuff";
            break;
        case "default": 
            $password="Unknown";
            break;
    }

    // render home page
    render("home.php", ["profile_pic" => $profile_pic, "fullname" => $fullname,"house" => $house, "password" => $password, "quidditch" => $quidditch, "email" => $email, "price" => $price, "title" => "Home"]);

?>
