<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (!strcasecmp($_POST["house"],"sortme"))
        {
            switch (rand(0,3))
            {
                case "0": $_POST["house"] = "Gryffindor";
                          break;
                case "1": $_POST["house"] = "Ravenclaw";
                          break;
                case "2": $_POST["house"] = "Hufflepuff";
                          break;
                case "3": $_POST["house"] = "Slytherin";
                          break;
                default : $_POST["house"] = "Gryffindor";
                          break;
            }
        }

        // if ($_POST["quidditch"] == 'yes')
        if (isset($_POST["quidditch"]))
        {
            $quidditch = "Yes";
        }
        else
        {
            $quidditch = "No";
        }

        // query database for wizards
        if ($_POST["firstname"] != NULL && $_POST["firstname"] != "" && $_POST["lastname"] != NULL && $_POST["lastname"] != "")
        {
            $fullname = $_POST["firstname"]." ".$_POST["lastname"];
            $qsays = query("UPDATE wizards SET fullname = ? WHERE id = ?", $fullname, $_SESSION["id"]);
            if ($qsays === false)
            {
                // apologize
                apologize("Unable to update full name in profile. Please try again.");
            }
        }

        if ($_POST["password"] != NULL && $_POST["password"] != "")
        {
            $qsays = query("UPDATE wizards SET hash = ? WHERE id = ?", crypt($_POST["password"]), $_SESSION["id"]);
            if ($qsays === false)
            {
                // apologize
                apologize("Unable to update password in profile. Please try again.");
            }
        }
        
        $qsays = query("UPDATE wizards SET house = ? WHERE id = ?", $_POST["house"], $_SESSION["id"]);
        if ($qsays === false)
        {
            // apologize
            apologize("Unable to update house in profile. Please try again.");
        }
        
        $qsays = query("UPDATE wizards SET quidditch = ? WHERE id = ?", $quidditch, $_SESSION["id"]);
        if ($qsays === false)
        {
            // apologize
            apologize("Unable to update quidditch in profile. Please try again.");
        }


        // Processing for profile image : taken from PHP and mysql : the missing manual by Brett McLaughlin
        $upload_dir = SITE_ROOT . "profile_img/";

        // $upload_dir = "profile_img/";
        //$upload_dir = "~/vhosts/localhost/html/profile_img/";

        //dump($_FILES);
		/*
		Array
		(
		[user_pic] => Array
			(
			[name] => harry.jpg
			[type] => image/jpeg
			[tmp_name] => /tmp/phpRJusoy
			[error] => 0
			[size] => 31770
			)
		)
		*/
        // $image_fieldname = $_POST["user_pic"]; -- will not work as file name is in $_FILES

	    $image_fieldname = $_FILES["user_pic"];
	    $image = $image_fieldname["name"];
	    $uploadedfile = $image_fieldname["tmp_name"];

        if ($image == NULL || $image == "")
        {
            // Do nothing
            // $upload_filename = $upload_dir . "unknown.jpg";
        }
        else
        {
	
	
	        //Potential PHP upload errors
	        $php_errors = array(1 => 'Maximum file size in php.ini exceeded',
	                            2 => 'Maximum file size in HTML form exceeded',
	                            3 => 'Only part of the file was uploaded',
	                            4 => 'No file was selected to upload');
	
	        // Make sure we didnt get an error uploading the image
	        if (!($image_fieldname['error'] == 0))
	        {
	            apologize("the server couldnt upload the image you selected.", $php_errors[$image_fieldname['error']]);
	        }
	        
	        // Is this file the result of a valid upload?
	        if( !is_uploaded_file($image_fieldname['tmp_name']))
	        {
	            apologize("you were trying to do something undesirable.");
	        }
	
	        // Is this actually an image?
	        if (!getimagesize($image_fieldname['tmp_name']))
	        {
	            apologize("You selected a file that is not an image.");
	        }
	
	        // Name the file uniquely
	        $now = time();
	        // while (file_exists($upload_filename = $upload_dir . $now .  $FILES[$image_fieldname]['name'])) 
	        while (file_exists($upload_filename = $upload_dir . $now .  $image_fieldname['name'])) 
	        {
	            $now++;
	        }
	
	        // Finally, move the file to its permanent location
	
	        //if (file_exists($image_fieldname['tmp_name']))
	
	        if (!move_uploaded_file($image_fieldname['tmp_name'],$upload_filename))
	        { 
	            apologize("We had a problem saving your image {$image_fieldname['tmp_name']} to {$upload_filename}");
	        } 
	        
	        //Change permissions of uploaded file
	        chmod($upload_filename,0644);

            // Update only if profile pic is loaded by user
	        $qsays = query("UPDATE wizards SET profile_pic = ? WHERE id = ?", $upload_filename, $_SESSION["id"]);
	        if ($qsays === false)
	        {
	            // apologize
	            apologize("Unable to update profile_pic in profile. Please try again.");
	        }
        }    
	
        // redirect to home page
        redirect("/");
    }
    else
    {
        // else render form
        render("edit_profile.php", ["title" => "Edit Profile"]);
    }

?>
