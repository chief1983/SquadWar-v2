// open new window
function openAnyWindow(url, name)
{
	var l = openAnyWindow.arguments.length;
	var w = "";
	var h = "";
	var features = "";

	for (i=2; i<l; i++)
	{
		var param = openAnyWindow.arguments[i];
		if ( (parseInt(param) == 0) || (isNaN(parseInt(param))) )
		{
			features += param + ',';
		}
		else
		{
			(w == "") ? w = "width=" + param + "," : h = "height=" + param;
		}
	}

	features += w + h;
	var code = "popupWin = window.open(url, name";
	if (l > 2) code += ", '" + features;
	code += "')";
	eval(code);
	if (window.focus)
	{
		popupWin.focus();
	}
}
