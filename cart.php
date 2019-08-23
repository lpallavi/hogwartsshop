<?php

    // configuration
    require("../includes/config.php"); 
    require_once("PHPMailer/class.phpmailer.php");

    // if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        if($_POST['buy'] == 'buy') 
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
	            $fullname = $row["fullname"];
	            $knuts = $row["knuts"];
	            $email = $row["email"];
	            $email_message = "\t\tItem name\t\tQuantity\t\tCost";
	            
	            // $items_bought = [];
	            // $items_num = [];
	            // $items_price = [];
	
	            // Check items in items list
	            foreach($_SESSION["cart"] as $itemno => $itemquantity)
	            {
	                $itemrows = query("SELECT * FROM items WHERE itemno = ?", $itemno);
	
	                // first (and only) row
	                $itemrow = $itemrows[0];
	                $price = $itemrow["price"];
	                $itemname = $itemrow["itemname"];
	                $quantity = $itemrow["quantity"];
		            $state = $itemrow["state"];
		            $category = $itemrow["category"];
	                $seller_id = $itemrow["id"]; // this is seller's id, need to pay him cash
	                $seller = $itemrow["seller"]; // this is seller's name
	
	                $current_amount= $price * $itemquantity;
	
	                if ($current_amount <= $knuts)
	                {
	                    // Buy this item 
	
	                    // Update buyer's cash
	                    $knuts = $knuts - $current_amount;
	
	                    $qsays = query("UPDATE wizards SET knuts = ? WHERE id = ?", $knuts, $_SESSION["id"]);
				        if ($qsays === false)
				        {
				            // apologize
				            apologize("Unable to update cash for buyer. Please try again.");
				        }
	
	
	                    // Update seller's cash
	
	                    $qsays = query("UPDATE wizards SET knuts = knuts + ? WHERE id = ?", $current_amount, $seller_id);
				        if ($qsays === false)
				        {
				            // apologize
				            apologize("Unable to update cash for seller. Please try again.");
				        }
	
	                    // Update quantity
	                    $qsays = query("UPDATE items SET quantity = quantity - ? WHERE itemno = ?", $itemquantity, $itemno);
	
				        if ($qsays === false)
				        {
				            // apologize
				            apologize("Unable to update quantity in items table. Please try again.");
				        }
	
	                    // Delete if quantity is zero
	                    $rows = query("SELECT * FROM items quantity WHERE itemno = ?", $itemno);
	                    $row = $rows[0];
	                    if ($row["quantity"] == 0)
	                    {
	                        $qsays = query("DELETE FROM items WHERE itemno = ?", $itemno);
	                    }
	
	
                        // Add to buyers history
                        $qsays = query("INSERT INTO history (id, fullname, itemno, itemname, price, quantity, state, category,seller_id,seller,timestamp) VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)", $_SESSION["id"],$fullname, $itemno,$itemname,$price,$itemquantity,$state, $category,$seller_id, $seller);

                        // if insert fails
                        if ($qsays === false)
                        {
                            // apologize
                            apologize("Unable to update history.Please try again.");
                        }


	                    // $items_bought = $items_bought . "$itemname";
	                    // $items_num = $items_num . "$itemquantity";
	                    // $items_price = $items_price . "$current_amount";
	                    $email_message = $email_message . "\n" . "\t\t\t$itemname\t\t\t$itemquantity\t\t\t$current_amount";
	
	                }
	                else
	                {
	                    apologize("Not enough wizard money to buy all the items. Please go to Gringotts bank.");
	                }
	
	            } 
	
	            // send email to buyer 

                $mail = new PHPMailer();
                 
                // use your ISP's SMTP server (e.g., smtp.fas.harvard.edu if on campus or smtp.comcast.net if off campus and your ISP is Comcast)
                $mail->IsSMTP();

                $mail->SMTPAuth   = true;                  // enable SMTP authentication
                $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
                $mail->Host       = "smtp.gmail.com";
                $mail->Port       = 587;                   // set the SMTP port for the GMAIL
                $mail->Username   = "cs50.hogwarts@gmail.com";  // GMAIL username
                $mail->Password   = "gmoilnde";          // GMAIL password
                  
                // set From:
                $mail->SetFrom("CS50@cs50.net","CS50");
                   

                //This line isn't needed, except if you want to debug. Take it out for your final version
                //$mail->SMTPDebug = 2;

                 
                // set To:
                $mail->AddAddress("$email");
                    
                // set Subject:
                $mail->Subject = "Success! Magical items purchased";
                     
                // set body
                $mail->Body = "Your purchase details are as follows \n" . $email_message;

                // send mail
                if ($mail->Send() === false)
                {
                    apologize("{$mail->ErrorInfo} \n");
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
	                 $knuts = $row["knuts"];
                }
                list($g,$s,$k) = conv_knuts_to_galleons($knuts);
                $price = conv_knuts_to_text($g,$s,$k);

                $message = "<div class=\"heading\"><h3>SUCCESS</h3></div><div><p>Details of your purchase have been sent to your Email ID : "
                . $email . "</p><br/><div>Your Hogwarts account now has ". $price ."</div><p>Thanks for using Hogwarts Shop!</p></div>";

                render("show_message.php", ["message" => $message , "title" => "Success"]);

                //Clear cart !!
                unset($_SESSION["cart"]);

            }
        }    
        else if($_POST['clearcart'] == 'clearcart') 
        {

            //Clear cart !!
            unset($_SESSION["cart"]);

            // redirect to home
            redirect("cart.php");
        }
        else if($_POST['backtoshop'] == 'backtoshop') 
        {

            // redirect to home
            redirect("buy.php");
        }
        else if($_POST['addcash'] == 'addcash') 
        {

            // redirect to home
            redirect("addcash.php");
        }
    }
    else
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
	        $fullname = $row["fullname"];
	        $knuts = $row["knuts"];
	        $email = $row["email"];
	        $itemlist = [];
	        $total_knuts=0;
	
	        // Check items in items list
            if (isset($_SESSION["cart"]))
            {
		        foreach($_SESSION["cart"] as $itemno => $itemquantity)
		        {
		            $itemrows = query("SELECT * FROM items WHERE itemno = ?", $itemno);
		
		            // first (and only) row
		            $itemrow = $itemrows[0];
		            $price = $itemrow["price"];
		            $itemname = $itemrow["itemname"];
		            $quantity = $itemrow["quantity"];
		            $state = $itemrow["state"];
		            $category = $itemrow["category"];
		            $seller_id = $itemrow["id"]; // this is seller's id
		            $seller = $itemrow["seller"]; // this is seller's name
		
		            $current_amount= $price * $itemquantity;
	
		            $total_knuts= $total_knuts + $current_amount;

                    list($g,$s,$k) = conv_knuts_to_galleons($price);
                    $price = conv_knuts_to_text($g,$s,$k);

                    list($g,$s,$k) = conv_knuts_to_galleons($current_amount);
                    $current_amount = conv_knuts_to_text($g,$s,$k);
		
		            // Store details of items in itemlist array
		            $itemlist[] = [
		                 "itemno"   => $itemno,
		                 "itemname" => $itemname,
		                 "price"    => $price,
		                 "quantity" => $itemquantity,
		                 "state"    => $state,
		                 "category" => $category,
		                 "rowtotal" => $current_amount,
		                 "seller"   => $seller
		            ];
		
		        } 
            }
        }

        if ($total_knuts > $knuts)
        {
            $not_enough_cash = true;
        }
        else
        {
            $not_enough_cash = false;
        }
	
        list($g,$s,$k) = conv_knuts_to_galleons($total_knuts);
        $total_knuts = conv_knuts_to_text($g,$s,$k);
        list($g,$s,$k) = conv_knuts_to_galleons($knuts);
        $knuts = conv_knuts_to_text($g,$s,$k);

        //dump($itemlist);
        // render cart_form
        render("show_cart.php", ["itemlist" => $itemlist, "not_enough_cash" => $not_enough_cash, "total_knuts" => $total_knuts, "knuts" => $knuts, "fullname" => $fullname, "email" => $email, "title" => "Cart"]);

    }

?>
