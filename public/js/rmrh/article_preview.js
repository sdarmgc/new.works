$(function () {

  $("#book-name, #lang, #year, #issue").change(function () {
    if ($("#book-name").val() != '' && $("#lang").val() != '' && $("#year").val() != '' && $("#issue").val() != '') {
      if ($("#book-name").val() == 'rmrh') {
        $("#book-title").val("The Reformation Herald");
        $("#volume").val( (parseInt($("#year").val()) - 2020 + 61).toString() ); //year 2020 Volume number is RH - 61, and YM - 39
      }
      else if ($("#book-name").val() == 'ym') {
        $("#volume").val( (parseInt($("#year").val()) - 2020 + 39).toString() ); //year 2020 Volume number is RH - 61, and YM - 39
        $("#book-title").val("Youth Messenger");
        let issue = parseInt($("#issue").val());
        if (issue == 1) $("#book-subtitle").val( "January-March" );
        else if (issue == 2) $("#book-subtitle").val( "April-June" );
        else if (issue == 3) $("#book-subtitle").val( "July-September" );
        else if (issue == 4) $("#book-subtitle").val( "October-December" );
      }
      window.location =  "/tools/article-converter/preview/" 
                    + $("#book-name").val() + "/" + $("#lang").val() + "/" + $("#year").val() + "/" + $("#issue").val() + "/article-1";
    }
  });


  	// A function is used for dragging and moving
	function dragElement(element, firstElement, secondElement, direction)
	{
		var   md; // remember mouse down info
		const first  = firstElement;
		const second = secondElement;

		element.onmousedown = onMouseDown;

		function onMouseDown(e)
		{
			md = {e,
                    offsetLeft:  element.offsetLeft,
                    offsetTop:   element.offsetTop,
                    firstWidth:  first.offsetWidth,
                    secondWidth: second.offsetWidth,
                    firstHeight:  first.offsetHeight,
                    secondHeight: second.offsetHeight
				};

			document.onmousemove = onMouseMove;
			document.onmouseup = () => {
				//console.log("mouse up");
				document.onmousemove = document.onmouseup = null;
			}
		}

		function onMouseMove(e)
		{
			//console.log("mouse move: " + e.clientX);
			var delta = {x: e.clientX - md.e.clientX,
						y: e.clientY - md.e.clientY};

			if (direction === "H" ) { // Horizontal 
				// Prevent negative-sized elements
				delta.x = Math.min(Math.max(delta.x, -md.firstWidth), md.secondWidth);

				element.style.left = md.offsetLeft + delta.x + "px";
				first.style.width = (md.firstWidth + delta.x) + "px";
				second.style.width = (md.secondWidth - delta.x) + "px";
			}
      else  { // Vertical 
				delta.y = Math.min(Math.max(delta.y, -md.firstWidth), md.secondWidth);

				element.style.top = md.offsetTop + delta.y + "px";
				first.style.height = (md.firstHeight + delta.y) + "px";
				second.style.height = (md.secondHeight - delta.y) + "px";
			}
		}
	}

	dragElement( document.getElementById("panel-spliter-horizontal")
              , document.getElementById("attributes-panel")
              , document.getElementById("content-panel")
              , "H" );

});