function checkCompleteness()
{
    var form = document.getElementById("signup_form");

    // restore black color to any elements previously flagged
    colorize("name_label","black");
    colorize("pass1_label","black");
    colorize("pass2_label","black");
    colorize("licenseOK_label","black");

    if( form.visitor_name.value.length == 0 ) { // name entered
	alert("You must enter a name");
	colorize("name_label","red");
	return false;
    }

    if( form.password1.value.length == 0 ) { // password entered
	alert("You must enter a password");
	colorize("pass1_label","red");
	return false;
    }

    if( form.password1.value != form.password2.value ) { // passwords agree
	alert("Passwords do not agree");
	colorize("pass2_label","red");
	return false;
    }

    if( ! form.licenseOK.checked ) { // accept license box checked
	alert("You must accept the license conditions");
	colorize("licenseOK_label","red");
	return false;
    }

    // passed all the checks: OK to submit
    
    return true;
}

function colorize(elementName, color)
{
    document.getElementById(elementName).style.color = color;
}