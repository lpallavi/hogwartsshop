<?php

    // configuration
    require("../includes/config.php"); 

    // Query database and get latest data for displaying history , FOR CURRENT USER AS A BUYER
    $rows = query("SELECT * FROM history WHERE id = ?", $_SESSION["id"]);

    if ($rows === false)
    {
        // apologize
        apologize("Unable to find history. Please try again.");
    }

    $buy_transactions = [];
	foreach($rows as $row)
	{
	    // Store details of history in buyers transactions array
	    $price = $row["price"];
        list($g,$s,$k) = conv_knuts_to_galleons($price);
        $price = conv_knuts_to_text($g,$s,$k);

        $buy_transactions[] = [
	         "itemname"     => $row["itemname"],
	         "price"        => $price,
	         "quantity"     => $row["quantity"],
	         "state"        => $row["state"],
	         "category"     => $row["category"],
	         "seller"       => $row["seller"],
	         "timestamp"    => $row["timestamp"]
        ];
    }

    // Query database and get latest data for displaying history , FOR CURRENT USER AS A SELLER
    $rows = query("SELECT * FROM history WHERE seller_id = ?", $_SESSION["id"]);

    if ($rows === false)
    {
        // apologize
        apologize("Unable to find history. Please try again.");
    }

    $sell_transactions = [];
	foreach($rows as $row)
	{
	    // Store details of history in sellers transactions array
        
        /*
        $buyerrows = query("SELECT * FROM wizards WHERE id = ?", $row["id"]);
        $buyerrow = $buyerrows[0];
        $buyer = $buyerrow["fullname"]; */

	    $price = $row["price"];
        list($g,$s,$k) = conv_knuts_to_galleons($price);
        $price = conv_knuts_to_text($g,$s,$k);

        $sell_transactions[] = [
	         "itemname"     => $row["itemname"],
	         "price"        => $price,
	         "quantity"     => $row["quantity"],
	         "state"        => $row["state"],
	         "category"     => $row["category"],
	         "buyer"        => $row["fullname"],
	         "timestamp"    => $row["timestamp"]
        ];
    }


    // render history
    render("show_history.php", ["buy_transactions" => $buy_transactions, "sell_transactions" => $sell_transactions,"title" => "History"]);

?>
