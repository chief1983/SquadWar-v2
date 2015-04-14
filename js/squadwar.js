// open new window
function openAnyWindow(url, name)
{
	var l = arguments.length;
	var w = "";
	var h = "";
	var features = "";

	for (var i=2; i<l; i++)
	{
		var param = arguments[i];
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
	var popupWin = window.open.apply(window, arguments);
	if (window.focus)
	{
		popupWin.focus();
	}
}
