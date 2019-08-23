<?php

    // configuration
    require("../includes/config.php"); 

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        for ($i=0,$max=count($_POST["itemno_array"]); $i<$max; $i++)
        {

            if ($_POST["num_array"][$i] != 0)
            { 
                $_SESSION["cart"][$_POST["itemno_array"][$i]] = $_POST["num_array"][$i];
            }

        } 

        //dump($_SESSION["cart"]);
        // redirect to view cart
        redirect("/cart.php");
    }
    else
    {
        // Query database and get latest data for displaying items 
        $rows = query("SELECT * FROM items");
        
        if ($rows === false)
        {
            // apologize
            apologize("Unable to find any items. Please try again.");
        }
        
        $itemlist = [];
        foreach($rows as $row)
        {

            $price = $row["price"];
            list($galleons, $sickles, $knuts) = conv_knuts_to_galleons($price);
            $price = conv_knuts_to_text($galleons,$sickles,$knuts);

            if (isset($_SESSION["cart"][$row["itemno"]]))
                $val = $_SESSION["cart"][$row["itemno"]];
            else
                $val = 0;

            // Store details of items in itemlist array
            $itemlist[] = [
                 "itemno"   => $row["itemno"],
                 "itemname" => $row["itemname"],
                 "price"    => $price,
                 "quantity" => $row["quantity"],
                 "category" => $row["category"],  
                 "state"    => $row["state"],
                 "val"      => $val,
                 "seller"   => $row["seller"]
            ];
        }
        
        // else render form
        render("buy_form.php", ["itemlist" => $itemlist, "title" => "Buy"]);

    }

?>
