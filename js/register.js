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
    var x = document.forms["form_register"]["email"].value;
    var atpos= x.indexOf("@");
    var dotpos= x.lastIndexOf(".");
    if (atpos < 1 || dotpos<atpos+2 || dotpos+2>=x.length)
    {
        // confirm("Are you sure?");
        alert("Not a valid email address");
        return false;
    }

    var y = document.forms["form_register"]["password"].value;
    if (y == null || y == "")
    {
        // confirm("Are you sure?");
        alert("Please provide a password");
        return false;
    }

    var z = document.forms["form_register"]["confirmation"].value;
    if (z == null || z == "")
    {
        // confirm("Are you sure?");
        alert("Please retype your password");
        return false;
    }

    var a = document.forms["form_register"]["firstname"].value;
    if (a == null || a == "")
    {
        // confirm("Are you sure?");
        alert("Please enter your first name");
        return false;
    }

    var b = document.forms["form_register"]["lastname"].value;
    if (b == null || b == "")
    {
        // confirm("Are you sure?");
        alert("Please enter your last name");
        return false;
    }
}
