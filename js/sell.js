/*
$(document).ready(function() {

    // check data is correct

    //$('#form_register input[name=email]').on('submit', function() {

    $('#form_register').on('submit', function() {

        //get values
        var email = $('#form_register input[name=email]').val();

        //if(email.length == 0)
        if(email == 'undefined')
        {
            alert("Please enter email");
        }
        else
        {
            alert("Email is okay");
        }

    });
    alert("Whats going on?");
    return false;
});
*/

function validateForm()
{
    var x = document.forms["form_sell"]["itemname"].value;
    if (x == null || x == "")
    {
        // confirm("Are you sure?");
        alert("Please provide an item name");
        return false;
    }

    var y = document.forms["form_sell"]["quantity"].value;
    if (y == null || y == "")
    {
        // confirm("Are you sure?");
        alert("Please provide quantity of items to sell");
        return false;
    }

    var y = document.forms["form_sell"]["price"].value;
    if (y == null || y == "")
    {
        // confirm("Are you sure?");
        alert("Please provide a price");
        return false;
    }

    if (+y === y && !(y % 1)) // checks if y is an integer and positive
    {
        // confirm("Are you sure?");
        alert("Please provide a positive integer");
        return false;
    }
}
