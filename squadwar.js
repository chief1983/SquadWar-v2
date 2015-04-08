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
		if ( (parseInt(param, 10) === 0) || (isNaN(parseInt(param, 10))) )
		{
			features += param + ',';
		}
		else
		{
			if(w === "")
			{
				w = "width=" + param + ",";
			}
			else
			{
				h = "height=" + param;
			}
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
