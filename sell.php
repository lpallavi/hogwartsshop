<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // query database for user
        $rows = query("SELECT * FROM wizards WHERE id = ?", $_SESSION["id"]);

        // if query fails
        if (count($rows) != 1)
        {
            // apologize
            apologize("Unable to retrieve data. Please try again.");
        }
        else
        {
            // first (and only) row
            $row = $rows[0];
            $sellername = $row["fullname"];
            // $state = ucfirst(strtolower($_POST["state"]));
            
            // $category = ucfirst(strtolower($_POST["category"]));
            //dump($_POST["category"]);
            //if ($_POST["galleons"] == NULL || $_POST["galleons"] == "")
            if (!isset($_POST["galleons"]))
            {
                $galleons = 0;
            }
            else
            {
                $galleons = $_POST["galleons"];
            }

            if (!isset($_POST["sickles"]))
            {
                $sickles = 0;
            }
            else
            {
                $sickles = $_POST["sickles"];
            }

            if (!isset($_POST["knuts"]))
            {
                $knuts = 0;
            }
            else
            {
                $knuts = $_POST["knuts"];
            }

            // Conversion
            // Gold 1 Galleons : 17 sickles, 1 Silver Sickles : 29 Knuts, Bronze Knuts
            $sickles_total = ($galleons * 17) + $sickles;
            $knuts_total = ($sickles_total * 29) + $knuts;

            // Insert into items table
            $qsays = query("INSERT INTO items (id, itemname, price, quantity, category, state, seller) VALUES( ?, ?, ?, ?, ?, ?, ?)", $_SESSION["id"], $_POST["itemname"], $knuts_total,$_POST["quantity"],$_POST["category"],$_POST["state"],$sellername);
            

     		if ($qsays === false)
     		{
     		    // apologize
     		    apologize("Unable to update item to be sold. Please try again.");
            } 
        
            // redirect to home 
            redirect("/");
        }
    }
    else
    {
        // else render form

        render("sell_form.php", ["title" => "Sell"]);
    }

?>
