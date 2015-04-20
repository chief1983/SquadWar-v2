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

$(document).ready(function() {
	$('form.validate').validate();

	// Set up panzoom plugin on the SVG image
	var svg = $(".svg_wrapper svg").panzoom({
		contain: 'invert',
		minScale: 1
	});

	// Bind the mouse wheel to zoom controls on the SVG in lieu of buttons
	svg.on('wheel', function(e) {
		e.preventDefault();
		console.log(e);
		var oEvent = e.originalEvent,
		delta = oEvent.deltaY || oEvent.wheelDelta;
		console.log(oEvent);

		if (delta > 0) {
			// Scrolled down
			$(this).panzoom("zoom", true, {focal: oEvent});
		} else {
			// Scrolled up
			$(this).panzoom("zoom", false, {focal: oEvent});
		}
		// Hack to force refreshing in Webkit (Chrome)
		svg.hide();
		svg.get(0).offsetHeight; // no need to store this anywhere, the reference is enough
		svg.show();
		// End hack
	});

	$(".svg_wrapper svg g.node").each(function() {
		var ele = $(this);
		var anchor = $('a', ele);
		var title = anchor.attr('xlink:title');
		var titletag = $('title', ele);
		var titletagtitle = titletag.html();

		// Set each g node to use tooltipster
		ele.tooltipster({
			theme: 'tooltipster-light',
			trigger: 'click',
			interactive: true,
			onlyOne: true,
			positionTracker: true,
			content: $( title )
		});

		// Manually disable the title text in the 'a' tags since it's not in an
		// actual title attribute, but still behaves like it is.
		// Also the 'title' tag, because apparently chrome will display it as
		// a tooltip too.
		ele.mouseenter(function() {
			anchor.attr('xlink:title', '');
			titletag.html('');
		});
		ele.mouseleave(function() {
			anchor.attr('xlink:title', title);
			titletag.html(titletagtitle);
		});
	});
});
