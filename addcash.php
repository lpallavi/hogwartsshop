<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["galleons"]) && empty($_POST["sickles"]) && empty($_POST["knuts"]))
        {
            apologize("You must provide an amount.");
        }

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
            $fullname = $row["fullname"];
            $knuts = $row["knuts"];

            $total_amount = ($_POST["galleons"]*17*29) + ($_POST["sickles"]*29) + $_POST["knuts"];
            // Update cash
            $knuts = $knuts + $total_amount;

            $qsays = query("UPDATE wizards SET knuts = ? WHERE id = ?", $knuts, $_SESSION["id"]);
			if ($qsays === false)
			{
			    // apologize
			    apologize("Unable to update cash for user. Please try again.");
			}

            // write a function for this!!!
            /*
            $s_temp = (int) ($knuts / 29);
            $k = $knuts % 29;
            $g = (int) ($s_temp / 17);
            $s = $s_temp % 17; */

            list($g,$s,$k) = conv_knuts_to_galleons($knuts);

            $price = conv_knuts_to_text($g,$s,$k);
            $message = "<div class=\"heading\"><br/><h3>SUCCESS</h3></div><br/>
            <h3>Cash Withdrawal from Gringotts Bank is successful!</h3><br/>
            <h4>Your Hogwarts account now has $price </h4><br/>
            <div><p>Thanks for using Gringotts E-transaction!</p></div>";

            // render success page
            render("show_message.php", ["message" => $message, "title" => "Success"]);
        }
    }
    else
    {
        // else render form
        render("addcash_form.php", ["title" => "Add Cash from Gringotts Bank"]);
    }

?>
