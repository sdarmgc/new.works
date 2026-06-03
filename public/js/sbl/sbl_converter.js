/*
 * sbl_converter.js
 * 
 * Convert SBL text to XML
 */
var dateObj = new Date();
if (!dateObj.__proto__) {  // IE
//if (!dateObj.prototype) {
	(function () {
	    function pad(number) {
	        var r = String(number);
	        if (r.length === 1) {
	            r = '0' + r;
	        }
	        return r;
	    }
	    //dateObj.__proto__.toISOString = function () {s
	    dateObj.toISOString = function () {
	        return this.getUTCFullYear()
	            + '-' + pad(this.getUTCMonth() + 1)
	            + '-' + pad(this.getUTCDate())
	            + 'T' + pad(this.getUTCHours())
	            + ':' + pad(this.getUTCMinutes())
	            + ':' + pad(this.getUTCSeconds())
	            + '.' + String((this.getUTCMilliseconds() / 1000).toFixed(3)).slice(2, 5)
	            + 'Z';
	    };
    } ());
}

$( document ).ready(function() {
	$.ajaxSetup({
		headers: {
		  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	TLN.append_line_numbers('input_text');
	TLN.append_line_numbers('output_text');

	setDateObj();

	$("#foreword_title_tag").val("<title>$1</title>\n");
	$("#lesson_start_tag").val("<lesson_header>$1</lesson_header>\n");
	$("#lesson_sabbath_tag").val("<sabbath>$1</sabbath>\n");	//change to <display_date>
	$("#reading_lable_tag").val("<reading_lable>$1</reading_lable><reading>$2</reading>\n");
	$("#day_tag").val("<day>$1</day>");
	$("#date_tag").val("<date>$1</date>\n");
	$("#subtitle_tag").val("<subtitle>$1</subtitle>\n");
	$("#day_question_tag").val("<question>$1</question>\n") ;
	$("#rev_question_tag").val("<question>$1</question>\n") ;
	$("#refer_text_tag").val("<ref_parag>$1<ref_parag>") ;
	$("#ref_source_tag").val("<book_link>$1</book_link>\n") ;
	$("#fso_date_tag").val("<display_date>$1</display_date>\n") ;

	setLangList();
	$("#lang-select").change( function () {
	
		var selected = $("#lang-select").selectedIndex;
		var lang = $("#lang-select").find(":selected").val();

		/*
		 * Set REGEXP pattern by language
		 * -- this function is defined in sbl_set_lang_pattern.js
		 */
		setLangPattern(lang);
	});
	
	$("#check_xml_button").click(function() {
		check_xml();
	});
	
	$("#regex_button").click( function() {
		alert("Execute Find & Replace? \n" + "" );
		var in_text = $("#input_text").val() ;
		var def_pattern = new RegExp($("#regex_pattern").val(), "gi") ;
		var def_replace = $("#regex_replace").val() ;
	
		in_text = in_text.replace( def_pattern , def_replace ) ;
	 	$("#input_text").val(in_text);
	});
	
	$("#replace_source_text").click( function() {
		if (confirm("Replace all in the source text? \n" + "" )) {
    		var in_text = $("#input_text").val() ;
    		var lines = $("#replace_text").val().split("\n") ;
    		lines.forEach ((el) => {
        		let patterns = el.split("=>")
        		if (patterns.length == 2) {
            		let def_pattern = new RegExp(patterns[0], "g") ;
            		let def_replace = patterns[1] ;
        		    in_text = in_text.replace( def_pattern , def_replace ) ;
        		}
    		});
    	 	$("#input_text").val(in_text);
		}
	});
	
	$("#fix_tags_button").click( function() {
		var in_text = $("#output_text").val() ;
//		if fix_tags = add-writer {
/*		} else if fix_tags = replace-writer-paragraph {
			var find = "<writer>(.*)</writer>(\\n<paragraph>.*</paragraph\\n>)";
			var replace = "<paragraph>$1</paragraph>$2";
		} else if fix_tags = remove-writer {
			var find = "<writer>(.*)</writer>(\\n<writer>.*</writer>\\n)(</foreword>|</fso>)";
			var replace = "<paragraph>$1</paragraph>$2$3";
		} else if fix_tags = replace-subquestion {
			var find = "<sub_question>(.*)</sub_question>";
			var replace = "<paragraph>$1</paragraph>";
		}
*/
		var find = "<paragraph>(.*)</paragraph>\\n(</foreword>|</fso>)";
		var replace = "<writer>$1</writer>\n$2";
		var find_pattern = new RegExp(find, "gi") ;
		in_text = in_text.replace( find_pattern , replace ) ;
	 	$("#output_text").val(in_text );
	});

	$("#preview_button").click(function() {
		var source = $("#output_text").val();
	
		source = source.replace("<\?xml version='1.0' encoding='UTF-8'\?>", "");
	
        source = source.replace( /<i>/gi , "!i!") ;
		source = source.replace( /<\/i>/gi , "!/i!") ;
		source = source.replace( /<\/\w+>/g , "</div>");
		source = source.replace( /(<)(\w+)/g , "$1div class='$2' title='$2'");
		//source = source.replace( /\n+/g , "\n<br />\n") ;
		source = source.replace( /!i!/gi , "<i>") ;
		source = source.replace( /!\/i!/gi , "</i>") ;
	
		var in_text = ""//"<html>\n<head>\n" ;
			in_text = in_text + "<style type='text/css'>\n<!--\n" ;
			in_text = in_text + ".panel-preview div {margin:0 0 1em}\n";
			in_text = in_text + ".em {font-weight: bold; font-style: italic; display:inline;}\n";
			in_text = in_text + ".s {font-weight: bold; text-decoration: line-through; display:inline;}\n";
			in_text = in_text + ".writer {font-size: 12pt; font-weight: normal; font-style: italic; color: #ff9900; text-align: right}\n";
			in_text = in_text + ".title {font-size: 24pt; font-weight: bold; text-align: center; color: #0000aa; }\n";
			in_text = in_text + ".lesson {font-size: 14pt; font-weight: bold; color: #0000aa}\n";
			in_text = in_text + ".sabbath, .display_date {font-size: 14pt; font-weight: bold; color: #0000aa; text-align: right}\n";
			in_text = in_text + ".key_text {font-size: 12pt; font-weight: bold; color: #9933ff; text-align: justify; text-indent: 1em;}\n";
			in_text = in_text + ".key_note {font-size: 12pt; font-weight: normal; font-style: italic; color: #9933ff; text-align: justify; text-indent: 1em;}\n";
			in_text = in_text + ".reading_lable {font-size: 12pt; font-weight: bold; color: #006666}\n";
			in_text = in_text + ".reading {font-size: 12pt; font-style: italic; color: #006666; text-align: left; text-indent: 13em;}\n";
			in_text = in_text + ".day {font-size: 18pt; font-style: italic; font-weight: bold; color: #00aa00; margin:0}\n";
			in_text = in_text + ".date {font-size: 12pt; font-style: italic; font-weight: bold; color: #00aa00; text-align: right; margin:0}\n";
			in_text = in_text + ".subtitle {font-size: 14pt; font-weight: bold; color: #00aa00}\n";
			in_text = in_text + ".question {font-size: 12pt; font-weight: bold; text-align: justify; color: #000000; text-indent: -1.5em; margin-left: 1.5em !important;}\n";
			in_text = in_text + ".sub_question {font-size: 12pt; font-weight: bold; text-align: justify; color: #ff0000; text-indent: 0em; margin-left: 1.5em}\n";
			in_text = in_text + ".ref_parag, .foreword .paragraph, .fso .paragraph {font-size: 12pt; font-weight: normal; text-align: justify; color: #aaaaaa; text-indent: 1em;}\n";
			in_text = in_text + ".paragraph {font-size: 12pt; font-weight: normal; text-align: justify; color: #ff0000; text-indent: 1em;}\n";
			in_text = in_text + ".abstract {font-size: 12pt; font-weight: normal; text-align: justify; color: #ff7700; text-indent: 1em;}\n";
			in_text = in_text + ".summary {font-size: 12pt; font-weight: normal; text-align: justify; color: #ff7700; text-indent: 1em;}\n";
			in_text = in_text + ".desc {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; color: #666666}\n";
			in_text = in_text + ".lesson, .display_date {margin:1em 0 0; padding:1em 0 0; border-top:1px solid #777;}\n";
			in_text = in_text + "-->\n</style>\n</head>\n<body style='font-size:10pt;color: #770000' dir='auto'>\n";
			in_text = in_text + source;// + "</body>\n</html>";

		$('.panel-preview').html(in_text);
		$('.panel-preview').css("width", "36%");
		//window.open("", "", "scrollbars=1").document.write( in_text ) ;
	});
	
	$("#clear_title_button").click(function () {
		$("#quarterly_title").val("");
		$("#quarterly_title").focus();
	});
	
	$("#clear_input_text").click(function () {
		$("#input_text").val("");
		$("#input_text").focus();
		$("#auto_attr").attr("checked", "checked");
	});
	
	$("#go_top_input_text").click(function () {
		document.getElementById('input_text').scrollTop = 0;
	});

	$("#set_title_input_text").click(function () {
		text = document.getElementById('input_text').value.split('\n')[1].trim();
		document.getElementById('quarterly_title').value = text;
		validate();
	});

	$("#clear_output_text").click(function () {
		$("#output_text").val("");
		$("#output_text").focus();
	});

	$("#clear_error_text").click(function () {
		$("#error_text").val("");
	});

	$("#reset_button").click(function () {
		$("#quarterly_title").val("");
		$("#input_text").val("");
		$("#output_text").val("");
		$("#error_text").val("");
		$("#input_text").focus();
		$("#auto_attr").attr("checked", "checked");
		$("#auto_attr").prop("checked", true);
		$("#lang-select").val("--");
		$("#lang_code").val("");
		$("#lang_code3").val("");
	});

	$("#allow_edit_result").click( function() {
		$("#output_text").attr('readonly', !$("#output_text").attr('readonly'));
	});
	
	function highlight_result_text() {
		$("#output_text").select();
		$("#output_text").focus();
	}
	
	$("#copy_result_button").click( function () {
		highlight_result_text();
		textRange = document.getElementById("output_text").createTextRange();
		textRange.execCommand("RemoveFormat");
		textRange.execCommand("Copy");
	});
	
	$("#saveresult").click( function () {
		str = $("#output_text").val();
		mydoc = document.open("text/html; charset=UTF-8");
		mydoc.write(str);
		mydoc.execCommand("saveAs",true,"sblyear_qtr_lang.xml");
		mydoc.close();
		mydoc.Charset = "utf-8";
	});
	
	$( "#save_button" ).click( function doDL() {
          var file_name = "sbl" + $("#year").val() + "_" + $("#issue").val()+ "_" + $("#lang_code").val() + ".xml";
          var data = $("#output_text").val().replace(/\r?\n/g,"\r\n");
          var _file = new Blob([data],{type:'application/octet-stream'});
          window.URL = window.URL || window.webkitURL;
	
          var a = document.createElement('a');
          a.href = window.URL.createObjectURL(_file); 
          a.download = file_name;
          document.body.appendChild(a);
          a.click();	
	});
	
	$( "#validate_button" ).click(function() {
		var updatedDate = new Date();
		$("#error_text").val( $("#error_text").val().concat("\n*************** " + updatedDate.toString() + " ***************\n") ) ;
		document.getElementById('error_text').value += 'Validating on server ...\n';
		$("#wait-gif").show();
		var xml_data = $("#output_text").val().replaceAll(/ r="[^"]*"/g, '');
		$.ajax({
				type: "POST",
				url: "/tools/sbl/sbl-converter/validate",
				data: { xml:xml_data, validate:"true"}
		})
		.done(function( data ) {
			var textarea = document.getElementById('error_text') ;
			textarea.value += data.message;
			textarea.scrollTop = textarea.scrollHeight;
			// if any error in the document
			let matches = data.message.match(/line[^\d]+(\d+)/);
			if (matches) {
				ta = document.querySelector("#output_text");
				selectTextearaLine(ta, parseInt(matches[1]));
				jumpToTextearaLine(ta, parseInt(matches[1]));
			}
		})	
		.fail(function() {
			var textarea = document.getElementById('error_text') ;
			textarea.value += "Data transfer error. Try again.\n";
			textarea.scrollTop = textarea.scrollHeight;
		})
		.always(function() {
			$("#wait-gif").hide();
		});
		return true;
	}); 
	
	$( "#submit_button" ).click(function() {
		//if (confirm("Are you sure you want to submit the result?")) {
			var updatedDate = new Date();
			$("#error_text").val( $("#error_text").val().concat("\n*************** " + updatedDate.toString() + " ***************\n") ) ;
			document.getElementById('error_text').value += 'Submitting to the server ...\n';
			$("#wait-gif").show();
			var xml_data = $("#output_text").val().replaceAll(/ r="[^"]*"/g, '');
			$.ajax({
			        type: "POST",
			        url: "/tools/sbl/sbl-converter/upload",
			        data: { xml:xml_data, overwrite:document.getElementById("overwrite").checked}
			})
			.done(function( data ) {
				var textarea = document.getElementById('error_text') ;
				textarea.value += data.message;
				textarea.scrollTop = textarea.scrollHeight;
				// if any error in the document
				let matches = data.message.match(/line[^\d]+(\d+)/);
				if (matches) {
					ta = document.querySelector("#output_text");
					selectTextearaLine(ta, parseInt(matches[1]));
					jumpToTextearaLine(ta, parseInt(matches[1]));
				}
			})	
			.fail(function() {
				var textarea = document.getElementById('error_text') ;
				textarea.value += "Submit transfer error. Try again.\n";
				textarea.scrollTop = textarea.scrollHeight;
			})
			.always(function() {
				$("#wait-gif").hide();
			});
			return true;
        //}
	}); 
	
	$("#quarterly_title").keyup(function() {
		validate()
	});

	$("#auto_attr").click( function() {
		if (this.checked)
			detectLanguage();
	});

	$("#take_abstract").click( function() {
		validate();
	});

// 	$("#take_summary").click( function() {
// 		validate();
// 	});

	$("#input_text").on("input onpropertychange", function() {
		detectLanguage();
		console.log('$("#input_text").on("propertychange change keyup paste input", function() {}');
	});

	$("#convert_button").click(function() {
		$("#panel-preview").html('');
		validate();
	});

    /*
     * preview panel operations
     */
    $(document).on('click', "#panel-preview", function(event) {
    	if (event.target) {
    		searchWord = event.target.getAttribute('r');
    		if (searchWord) {
    			let textarea = document.getElementById("output_text");
    			let lines = textarea.value.split('\n');
    			let startLine = parseInt(searchWord);
    			searchWord = 'r="' + searchWord + '"';
    			for (index = startLine; index < lines.length; index++) {
    				if (lines[index].indexOf(searchWord) > -1 ) {
    					selectTextearaLine(textarea, index+1);
    					jumpToTextearaLine(textarea, index+1);
    					console.log("Line:" + parseInt(index))
    				}
    			}
    		}
    	}
    });

	dragElement( document.getElementById("panel-spliter"), "H" );
}); 

	
function convert_xml()
{
/*
Order of convertion
	1. remove all white space. '/t', duplicated ' ' and '/n'.
	2. Process by sbl order.
        a. Foreword - title - paragraphs.
        b. Lessons - lesson lable - lesson title - key text - key note - suggested reading - daily lessons.
        c. else - process as paragraph.
	3. convert lines by pattern
*/	
	var in_lang = $("#lang_code").val();

	if (in_lang == "") {
		alert("Select a language.");
		return false;
	}

	var quarterly_title = $("#quarterly_title").val();
	if (quarterly_title == "") {
		alert("Enter the quarterly's title.");
		document.getElementById("quarterly_title").style.border = "2px solid #ff0000";
		$("#quarterly_title").focus();
		return false;
	}
	else 
		document.getElementById("quarterly_title").style.border = "";

	var in_text = $("#input_text").val();
	if (in_text.length == 0) {
		alert("Enter source text.");
		document.getElementById("input_text").style.border = "2px solid #ff0000";
		$("#input_text").focus();
		return false;
	}
	else
		document.getElementById("input_text").style.border = "";


// 1. remove all white space. '/t', duplicated ' ' and '/n'.
//    replace special character.
	in_text = in_text.replace( /&/g, "&amp;");
	in_text = in_text.replace( /</g, "&lt;");
	in_text = in_text.replace( />/g, "&gt;");
	in_text = in_text.replace( /^(\s+)/g, "");
	in_text = in_text.replace( /(\s+)$/g, "");
	in_text = in_text.replace( /\n+/g, "<BR>");
	in_text = in_text.replace( /\r+/g, "");
	in_text = in_text.replace( /\s+/mg, " ");
// split by line
	inLines = in_text.split( "<BR>" ) ;
	
//
// 2. Process by sbl order.
//
// date setting
    setDateObj();
    
// define all patterns. Default is English.
    var lessonTitle = $("#quarterly_title").val();
    var year = $("#year").val();
    var issue = $("#issue").val();
    var day = $("#begin-date").val()- 1; // set day on last sabbath.
    var language = $("#lang_code").val();
    
    var rpForewordTitle = new RegExp($("#foreword_title").val(), "g") ;
    var rpLessonStart = new RegExp($("#lesson_start").val(), "gi") ;
    var rpLessonSabbath = new RegExp($("#lesson_sabbath").val(), "gi") ;
    var rpReadingLable = new RegExp($("#reading_lable").val(), "gi") ;
    //var rpDay = new RegExp($("#day").val(), "gi") ;
    var rpDay = $("#day").val().split('|') ;
    var rpDate = new RegExp($("#date").val(), "gi") ;
    var rpSubtitle = new RegExp($("#subtitle").val()) ;
    var rpDayQuestion = new RegExp($("#day_question").val(), "gi") ;
    var rpRevQuestion = new RegExp($("#rev_question").val(), "gi") ;
    var rpReferText = new RegExp($("#refer_text").val(), "gi") ;
    //var rpRefSource = new RegExp($("#ref_source").val(), "gi") ;
    var rpFSODate = new RegExp($("#fso_date").val(), "gi") ;
    var rpFSOStart = new RegExp($("#fso_start").val(), "g") ;

    // var rrForewordTitle = $("#foreword_title_tag").val();
    // var rrLessonStart = $("#lesson_start_tag").val();
    // var rrLessonSabbath = $("#lesson_sabbath_tag").val();
    // var rrReadingLable = $("#reading_lable_tag").val();
    // var rrDay = $("#day_tag").val();
    // var rrDate = $("#date_tag").val();
    // var rrSubtitle = $("#subtitle_tag").val();
    // var rrDayQuestion = $("#day_question_tag").val() ;
    // var rrRevQuestion = $("#rev_question_tag").val() ;
    // var rrReferText = $("#refer_text_tag").val() ;
    // //var rrRefSource = $("#ref_source_tag").val() ;
    // var rrFSODate = $("#fso_date_tag").val() ;
    var periodMark = $("#period_mark").val() ;
    
    var version="2.1";
	var output = "<\?xml version='1.0' encoding='UTF-8'\?>\n" ;
    var buffer = "empty" ;
	var bufferReading = "";
	var bufferKeyNote = "";
	var bufferSubtitle = "";
    var state = "start" ;
    var lineNo = 0 ;
    var lessonNum = 0 ;
    var dayNum = -1;
    var revQuestionNum = -1 ;
    var fsoNum = 0;
	var serial = 0;
    
	var errorMessage = "";

	output = output.concat( '<book title="', lessonTitle, '" role="sbl" xml:base="sbl" xml:id="sbl-', 
							language, '-', year, '-', issue, '" year="', year, '" quarter="', issue, '" xml:lang="', language, 
							'" annotations="sdarm" updated="' , (new Date()).toISOString(),
							'" version="2.1">\n' );
	settings = {
	    book : 'sbl',
        year : year,
        issue : issue,
        lang : language
	};
	window.settings = settings;
	
// from the first line,
    while( lineNo < inLines.length )
    {          
      if(inLines[lineNo].length == 0 || (inLines[lineNo].length == 1 && inLines[lineNo] == " ")) {
        lineNo ++ ; 
        continue ;
      }
	  
	  // for abstract
	  else if (lineNo == 3 && document.getElementById('take_abstract').checked) {
        output = output.concat( '<abstract r="', ++serial, '">', inLines[lineNo], '</abstract>\n' ) ;
	  }
	  
	  // for summary
	  //else if (lineNo == 4 && document.getElementById('take_summary').checked) {
//         output = output.concat( '<summary r="', ++serial, '">', inLines[lineNo], '</summary>\n' ) ;
	  //}
         
 	  // for Foreword
      else if( inLines[lineNo].search( rpForewordTitle ) > -1 ) {
        output = output.concat( '<foreword>\n' ) ;
        output = output.concat( '<title r="', ++serial, '">', inLines[lineNo], '</title>\n' ) ;
        state = "foreword" ;
      }

 	  // for FSO - First Sabbath Offering
      else if( state != 'fso' && ((version == '2.1' && inLines[lineNo].search( rpFSODate ) == 0) || inLines[lineNo].search( rpFSOStart ) == 0)) {
      	fsoNum ++;
      	if(state == "foreword")
      		output = output.concat( '</foreword>\n' ) ;
      	else                                                                      // state == lesson
      		output = output.concat( '</questions></day_lesson></lesson>\n' ) ;
        dateObj.setDate(dateObj.getDate() + 7);	// set date at coming sabbath
      	output = output.concat( '<fso no="', fsoNum, '" date="', dateObj.toISOString().substring(0,10).replace(/-/g,''), '">\n' ) ;
        dateObj.setDate(dateObj.getDate() - 7);	// restore date
      	
		if( inLines[lineNo].search( rpFSODate ) == 0) {
			output = output.concat( '<display_date r="', ++serial, '">', inLines[lineNo], '</display_date>\n' ) ;
			lineNo ++;
			output = output.concat( '<title r="', ++serial, '">', inLines[lineNo], '</title>\n' ) ;
		}
		else { //  || inLines[lineNo].search( rpFSODate ) == 0
			let title = '<title r="' + ++serial + '">' + inLines[lineNo] + '</title>\n' ;
			lineNo ++;
			output = output.concat( '<display_date r="', ++serial, '">', inLines[lineNo], '</display_date>\n' ) ;
			output = output + title;
			lineNo ++;
			output = output.concat( '<fso_subtitle r="', ++serial, '">', inLines[lineNo], '</fso_subtitle>\n' ) ;
		}	
      	state = 'fso' ;
		dayNum = 0 ;
      	lineNo ++;
      	output = output.concat( '<paragraph r="', ++serial, '">', inLines[lineNo], '</paragraph>\n' ) ; // sometimes here the title continues...
      }

	  // for Lessons (<!ELEMENT lesson (lesson_header, sabbath, title, key_text, key_note, readings, (day_lesson)+ ) >)
      else if( inLines[lineNo].search( rpLessonStart ) > -1 ) {
      	// check error for previous lesson
      	if( lessonNum > 0 ) { 
      		if( dayNum != 6 || revQuestionNum != 5 ) {
				errorMessage = errorMessage.concat( 'ERROR - Lesson Num:' + lessonNum.toString() + ' Day Num:' + dayNum.toString() + ' Review Num:' + revQuestionNum.toString() + ' \n');
      			break;
      		}
      	}
      	if(state == 'foreword')
      		output = output.concat( '</foreword>\n' ) ;
      	else if(state == 'fso')
      		output = output.concat( '</fso>\n' ) ;
      	else if(state == 'day_lesson')  // state == lesson
      		output = output.concat( '</questions>\n</day_lesson>\n</lesson>\n' ) ;
      	// Lesson 1
      	lessonNum ++ ;
        dateObj.setDate(dateObj.getDate() + 7);	// set date at coming sabbath
      	output = output.concat( '<lesson no="', lessonNum, '" date="', dateObj.toISOString().substring(0,10).replace(/-/g,''), '">\n' ) ;
		var match = inLines[lineNo].match(new RegExp( rpLessonStart, 'i' )) ;
		if (!match) {
		    lineNo++;
	        continue;
		}
		if (match.index == 0) {	// version == 2.1
			buffer = inLines[lineNo].slice(match[0].length+1) ;
		}
		else {  // version == 2.2
			buffer = inLines[lineNo].slice(0, match.index-1) ;
			version = '2.2';
		}
		output = output + '<lesson_header r="' + ++serial + '">' + match[0] + '</lesson_header>\n<sabbath r="' + ++serial + '">' + buffer + '</sabbath>\n' ;
        dateObj.setDate(dateObj.getDate() - 6);	// restore date
		state = "lesson" ;
		dayNum = 0 ;
		revQuestionNum = 0 ;
      }

      // for Lesson Title, Keytext, Keynote
      else if( state == 'lesson' ) {
        output = output.concat( '<title r="', ++serial, '">', inLines[lineNo], '</title>\n' ) ;
        state = 'title' ;
      }

      else if( state == 'title' ) {
        output = output.concat( '<key_text r="', ++serial, '">', inLines[lineNo], '</key_text>\n' ) ;
        state = 'key_text' ;
      }

	  // <xs:element name="key_note">
      else if( (state == 'key_text' || state == 'readings') && inLines[lineNo].search( rpReferText ) == 0 ) {
        if(state == 'readings') {
		    bufferReading += '</readings>\n' ;
        }
		bufferKeyNote = '<key_note r="' + ++serial + '">' + inLines[lineNo] + '</key_note>\n' ;
		state = 'key_note' ;
      }

	  // <xs:element name="readings">
	  else if( inLines[lineNo].search( rpReadingLable ) == 0 ) {
		bufferReading = '<readings>\n' ;
		let match = inLines[lineNo].match(new RegExp(rpReadingLable, 'i'));
		if (!match) {
		    lineNo++;
	        continue;
		}
		bufferReading = bufferReading + '<reading_lable r="' + ++serial + '">' + match[1] + '</reading_lable>\n';
		if (match[2] && match[2].length > 2)
		    bufferReading = bufferReading + '<reading r="' + ++serial + '">' + match[2] + '</reading>\n' ;
		state = 'readings' ;
	  }

	  // <xs:element name="reading">
      else if( state == 'readings' && inLines[lineNo].search( rpReferText ) != 0 && inLines[lineNo].search( new RegExp( '^'+rpDay[dayNum],'i' ) ) != 0 ) {
		bufferReading = bufferReading + '<reading r="' + ++serial + '">' + inLines[lineNo].trim() + '</reading>\n' ;
      }

	  // <xs:element name="subtitle">
      else if( (state == 'key_note' || /*state == 'reading' ||*/ state == 'questions' || state == 'ref_parag') && inLines[lineNo].search( rpSubtitle ) > -1 ) {
		if (state == 'questions' || state == 'ref_parag') {
			output = output.concat( '</questions>\n</day_lesson>\n' );
		}
		if( state == 'key_note' ) {	// version = 2.1
			output = output + bufferKeyNote;
			output = output + bufferReading;
		}
		dayNum ++ ;
		output = output.concat( '<day_lesson no="', dayNum, '" date="', dateObj.toISOString().substring(0,10).replace(/-/g,''), '">\n' ) ;
		dateObj.setDate(dateObj.getDate() + 1);
		// Date Day
		var match = inLines[lineNo].match(new RegExp( '(' + $("#subtitle").val() + '.*\\s+)' + $("#date").val() + '\\s+('+ rpDay[dayNum-1] + ')','i' )) ;
		if (match) {
			output = output + '<day r="' + ++serial + '">' + match[6] + '</day>\n<date r="' + ++serial + '">' + match[3] + '</date>\n' ;
			output = output + '<subtitle r="' + ++serial + '">' + match[1] + '</subtitle>\n' ;
			state = 'day_lesson';
		}
		// Day Date
		else if (match = inLines[lineNo].match(new RegExp( '(' + $("#subtitle").val() + '.*\\s+)(' + rpDay[dayNum-1] + ')(.+)','i' )) ) {
			output = output + '<day r="' + ++serial + '">' + match[3] + '</day>\n<date r="' + ++serial + '">' + match[4] + '</date>\n' ;
			output = output + '<subtitle r="' + ++serial + '">' + match[1] + '</subtitle>\n' ;
			state = 'day_lesson';
		}
		else {
			lineNo++;
			continue;
		}
	  }

	  // <xs:element name="day_lesson">
      else if( dayNum >= 0 && inLines[lineNo].length < 20
                && inLines[lineNo].search( new RegExp( '^'+rpDay[dayNum],'i' ) ) == 0 ) {
		dayNum ++ ;
		if( state == 'readings' ) {	// version = 2.1
			output = output + bufferKeyNote;
			output = output + bufferReading.concat( '</readings>\n' );
		}
		else if (state == 'key_note') { // version = 2.2
			output = output + bufferKeyNote;
			output = output + bufferReading;
		}
		else { //if (state == 'questions' || state == 'ref_parag') {
			output = output.concat( '</questions>\n</day_lesson>\n' );
		}
		output = output.concat( '<day_lesson no="', dayNum, '" date="', dateObj.toISOString().substring(0,10).replace(/-/g,''), '">\n' ) ;
		dateObj.setDate(dateObj.getDate() + 1);

        var match = inLines[lineNo].match(new RegExp( '^'+rpDay[dayNum-1],'i' )) ;
		if (!match) {
		    lineNo++;
	        continue;
		}
        buffer = inLines[lineNo].slice(match[0].length) ;
        output = output + '<day r="' + ++serial + '">' + match[0] + '</day>\n<date r="' + ++serial + '">' + buffer + '</date>\n' ;
		lineNo++ ;
		output = output.concat( '<subtitle r="', ++serial, '">', inLines[lineNo], '</subtitle>\n' ) ;
		state = 'day_lesson' ;
	  }

      // for Review question
      else if( dayNum == 6 && inLines[lineNo].search(rpRevQuestion) == 0 ) {
		if( revQuestionNum == 0 ) {
			output = output.concat( '<questions>\n' );
		}
		else 
			output = output.concat( '</questions>\n<questions>\n' );
        output = output.concat( '<question r="', ++serial, '">', inLines[lineNo], '</question>\n' ) ;
        revQuestionNum ++ ;
      }
      // for sub_question for Friday.
      else if(dayNum == 6 && inLines[lineNo].search(rpReferText) != 0 ) {
        output = output.concat( '<sub_question r="', ++serial, '">', inLines[lineNo], '</sub_question>\n' ) ;
      }
      // for Questions (<!ELEMENT questions (question, sub_question*, ref_parag*)>)
      else if( (state == 'day_lesson' || state == 'questions' || state == 'ref_parag' ) && inLines[lineNo].search(rpDayQuestion) == 0 ) {
        if( state == 'day_lesson' )  
          output = output.concat( '<questions>\n' );
		else
		  output = output.concat( '</questions>\n<questions>\n' );
        output = output.concat( '<question r="', ++serial, '">', inLines[lineNo], '</question>\n' ) ;
        state = 'questions' ;
      }
      // for sub_question for Sunday to Thrusday.
      else if( state == 'questions' && inLines[lineNo].search( rpReferText ) != 0 ) {
        output = output.concat( '<sub_question r="', ++serial, '">', inLines[lineNo], '</sub_question>\n' ) ;
      }
      // for refer_text text,
      // state == 'ref_parag' is added for some unknown bug, inLines[lineNo].search(rpReferText) does not work properly. 
      else if( (state == 'questions' || state == 'ref_parag') ) {  //&& inLines[lineNo].search(rpReferText) == 0 ) {
        output = output.concat( '<ref_parag r="', ++serial, '">', inLines[lineNo], '</ref_parag>\n' ) ;
        state = 'ref_parag' ;
      }
    
// foreword or fso continue... 
      else if( state == 'foreword' || state == 'fso' ) {
        if( 
            ((version == '2.1' && inLines[lineNo+1].search( rpFSODate ) == 0) 
                || inLines[lineNo+1].search( rpFSOStart ) == 0)
                || (inLines[lineNo+1].search( rpLessonStart ) > -1)
        ) {
            output = output.concat( '<writer r="', ++serial, '">', inLines[lineNo], '</writer>\n' ) ;
            dayNum = 6 ;  // to avoid error text
        }
        else {
            output = output.concat( '<paragraph r="', ++serial, '">', inLines[lineNo], '</paragraph>\n' ) ;
        }
      }
      
// the other.
      else {
      	if( state == 'day_lesson' && dayNum == 6 ) {  // at the end of a lesson
      		output = output.concat( '</questions></day_lesson></lesson>\n' ) ;
      		state = '';
      	}
        output = output.concat( '<paragraph r="', ++serial, '">', inLines[lineNo], '</paragraph>\n' ) ;
      }
              
      lineNo = lineNo + 1 ;
    }
    
	if( state == 'day_lesson' && dayNum == 6 ) {  // at the end of a lesson
		output = output.concat( '</questions></day_lesson></lesson>\n' ) ;
		state = '';
	}
	output = output.concat( '</book>' ) ;
// 		output = output.replaceAll(/@@(\/?[a-z]{1,2})@@/g, '<$1>');
	output = output.replaceAll(/&lt;(\/?[a-z]{1,2})&gt;/g, '<$1>');

    	// display the results
 	$('#output_text').val(output );

 	// check the last lesson
    if( dayNum != 6 || revQuestionNum != 5 ) {
		var updatedDate = new Date();
		errorMessage = errorMessage.concat( "ERROR - Lesson Num:" + lessonNum.toString() + " Day Num:" + dayNum.toString() + " Review Num:" + revQuestionNum.toString() + " \n");
	}
	
	var textarea = document.getElementById('error_text') ;
	var updatedDate = new Date();
	textarea.value += "\n*************** " + updatedDate.toString() + " ***************\n";

	if( errorMessage.length > 0)
		textarea.value += errorMessage;
	else {
		xmlError = XMLDocErrString(output);
		if( xmlError.length > 0 ) 
			textarea.value +=  xmlError + "\n";
		else {
			$("#preview_button").click(); // display preview
			var report = "Conversion completed!\n";
			report = report + countTags() ;
			textarea.value +=  report + '\n';
			textarea.value += "XML is well formed!\n";

			// check lesson and question numbering (order)
			report = "\n";  //"\n\n----------------------------------------" 
			// report += "\n--- Element Order Checking --- \n";
			const questionLetter = ['a', 'b', 'c', 'd', 'e', 'f', 'g'];
			const sblElement = document.createElement('sbl');
			sblElement.innerHTML = output.replace("<?xml version='1.0' encoding='UTF-8'?>", '');
			sblElement.querySelectorAll('lesson').forEach((lesson, key) => {
				const subtitles = lesson.querySelectorAll('subtitle');
				// check subtitle numbering
				for (let idx = 0; idx < subtitles.length-1; idx++) {
					if (subtitles[idx].textContent.trim().at(0) != (idx+1).toString()) {
						report += "CHECK: " + subtitles[idx].outerHTML + "\n";
					}
				}
				// check question numbering
				lesson.querySelectorAll('day_lesson').forEach((day_lesson, key) =>{
					questions = day_lesson.querySelectorAll('question');
					if (key < 5) {
						for (let idx = 0; idx < questions.length; idx++) {
							if (questions[idx].textContent.trim().at(0) != questionLetter[idx]) {
								report += "CHECK: " + questions[idx].outerHTML + "\n";
							}
						}
					}
					else {
						for (let idx = 0; idx < questions.length; idx++) {
							if (questions[idx].textContent.trim().at(0) != (idx+1).toString()) {
								report += "CHECK: " + questions[idx].outerHTML + "\n";
							}
						}
					}
				}) 
			});
			// report += "--- END Element Order Checking --- \n";
			textarea.value +=  report;
		}
	}
	textarea.scrollTop = textarea.scrollHeight;

	// to reset line number
	document.getElementById("output_text").dispatchEvent(new KeyboardEvent('keyup',{'key':'Shift'}));
};


function validate()
{
	document.getElementById("lang-select").style.border = "2px solid #ff0000";
	document.getElementById("quarterly_title").style.border = "2px solid #ff0000";
	document.getElementById("input_text").style.border = "2px solid #ff0000";
	document.getElementById("clear_title_button").style.display = "none";
    var in_lang = $("#lang_code").val() ;
	if (in_lang.length > 0)
		document.getElementById("lang-select").style.border = "2px solid #c0c0c0";
	var quarterly_title = $("#quarterly_title").val() ;
	if (quarterly_title.length > 0) {
		document.getElementById("quarterly_title").style.border = "";
		document.getElementById("clear_title_button").style.display = "";
	}
	var in_text = $("#input_text").val() ;
	if ( in_text.length > 0 )
		document.getElementById("input_text").style.border = "";
	if ( in_lang.length > 0 && quarterly_title.length > 0 && /*beginDate.length > 0 &&*/ in_text.length > 0 )
		convert_xml();
}


// count all occurrences of the tags
function countTags()
{
	if ($("#output_text").val().length == 0)
		return "";
	var scope = this;
	var tagBag = {};
	
	function tagCount(domNode, tagBag)
	{
		if ( typeof counter == 'undefined' ) { // just to prevent expaeted roop.
			counter = 0;
		}
		if (counter++ > 10000) return;

		if (!domNode.getAttribute('r')) { 
			for (let index = 0; index < domNode.children.length; index++) {
				tagCount(domNode.children.item(index), tagBag);
			}
		}
		else {
			let tag = domNode.getAttribute('class');
			if (tagBag[tag] != undefined) {
				tagBag[tag] ++;
			}
			else {
				tagBag[tag] = 1;
			}
			//console.log(`${tag}: ${tagBag[tag]}`);
		}   
	}
	
	tagCount(document.querySelector('.book'), tagBag);

	let report = '\nlesson_header:' + tagBag['lesson_header'] + ', sabbath:' + tagBag['sabbath'] 
					+ ', key_text:' + tagBag['key_text'] + ', key_note:' + tagBag['key_note'] + ', reading_lable:' + tagBag['reading_lable']; 
	tagBag['lesson_header'] = tagBag['sabbath'] = tagBag['key_text'] = tagBag['key_note'] = tagBag['reading_lable'] = 0;
	 
	report += '\nday:' + tagBag['day'] + ', date:' + tagBag['date'] + ', subtitle:' + tagBag['subtitle']; 
	tagBag['day'] = tagBag['date'] = tagBag['subtitle'] = 0; 
	for (const property in tagBag) {
		if (tagBag[property] > 0)
			report +=`\n${property}: ${tagBag[property]}`;
		//console.log(`${property}: ${tagBag[property]}`);
	}
	return report + "\n";
}


function XMLDocErrString(xml)
{
	try {
		if (window.DOMParser) {  // ff & chrome
			parser = new DOMParser();
			xmlDoc = parser.parseFromString(xml,"text/xml");
	          if (xmlDoc.documentElement.firstChild.localName=="parsererror") // chrome
	          	return xmlDoc.documentElement.firstChild.innerText ;
	          else if(xmlDoc.documentElement.localName=="parsererror")  // firefox
	          	return xmlDoc.documentElement.firstChild.nodeValue ;
		}
		else {  // code for IE
			xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
			xmlDoc.async=false;
			xmlDoc.loadXML(xml); 
			if (xmlDoc.parseError.errorCode != 0) {
				return "Error in line " + xmlDoc.parseError.line +
			          " position " + xmlDoc.parseError.linePos +
			          "\nError Code: " + xmlDoc.parseError.errorCode +
			          "\nError Reason: " + xmlDoc.parseError.reason +
			          "Error Line: " + xmlDoc.parseError.srcText;
			}
		}
	}
	catch(e) {
		return e.message
	}
	
	return "";
}


function read_file(filePath)
{
      var xmlhttp = new XMLHttpRequest();
      if(window.XMLHttpRequest){
          xmlhttp = new XMLHttpRequest();
      }
      else{
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.open("GET", filePath, false);
      xmlhttp.send(null);
      return xmlhttp.responseText;
}


// this function use "xmllint.js", but produces too many error and warning reports.
function validate_xsd() 
{	
	var schema = read_file("sbl.xsd");
	var xml = $("#output_text").val();
	var result = xmllint.validateXML({
		xml: [xml, xml],
		schema: [schema, schema]
	});
	console.log(result);
	return result;
}

function check_xml() {
	var textarea = document.getElementById('error_text') ;
	var updatedDate = new Date();
	textarea.value += "\n\n*************** " + updatedDate.toString() + " ***************\n";

	xmlError = XMLDocErrString($("#output_text").val());

	if( xmlError.length > 0 ) {
		textarea.value += "XML Error: \n" + xmlError + "\n";
		let matches = xmlError.match(/line[^\d]+(\d+)/);
		if (matches) {
			ta = document.querySelector("#output_text");
			selectTextearaLine(ta, parseInt(matches[1]));
			jumpToTextearaLine(ta, parseInt(matches[1]));
		}
		document.querySelector("#panel-preview").innerHTML = '';
	}
	else
		textarea.value += "XML is well formed!\n";
	textarea.scrollTop = textarea.scrollHeight;
}

function detectLanguage() {
	if (document.getElementById('auto_attr').checked) {
		let found = false;
		let text = $("#input_text").val();
		for (var option_element of document.querySelector("#lang-select").options) { // test language with the list
			let code = option_element.value;
			if (code != '--') {  
				setLangPattern(code);
				let title = $("#foreword_title").val();
				if (title[0] == '^')
					title = '\n' + title.slice(1);
				else if (title[0] == '(' && title[1] == '^')
					title = '(\n' + title.slice(2);
	            let rpForewordTitle = new RegExp(title, "gi")
				if (text.search( rpForewordTitle ) > -1) { // found "FOREWORD" text.
					document.querySelector("#lang-select").value = code;
					text = document.getElementById('input_text').value.split('\n')[1].trim();
					document.getElementById('quarterly_title').value = text;
					found = true;
					break;
				}
			}
		}
		if (found) {
			validate();
			document.getElementById('auto_attr').checked = false;
		}
		else {
			setLangPattern('--');
			document.querySelector("#lang-select").value = "";
		}
	}
}
	
// A function is used for dragging and moving
function dragElement(element, direction)
{
	var   md; // remember mouse down info
	const first  = document.getElementById("panel-contents");
	const second = document.getElementById("panel-preview");

	element.onmousedown = onMouseDown;

	function onMouseDown(e)
	{
		//console.log("mouse down: " + e.clientX);
		md = {e,
			offsetLeft:  element.offsetLeft,
			offsetTop:   element.offsetTop,
			firstWidth:  first.offsetWidth,
			secondWidth: second.offsetWidth
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

		if (direction === "H" ) // Horizontal
		{
			// Prevent negative-sized elements
			delta.x = Math.min(Math.max(delta.x, -md.firstWidth),
					md.secondWidth);

			element.style.left = md.offsetLeft + delta.x + "px";
			first.style.width = (md.firstWidth + delta.x) + "px";
			second.style.width = (md.secondWidth - delta.x) + "px";
		}
	}
}

// set date on the last sabbath of previous quarter - the day before the beginning of the quarter (sunday)
function setDateObj()
{
	dateObj.setHours(12);
	
	year = $("#year").val();
	if (year == "")
		year = dateObj.getFullYear();
	else
		dateObj.setFullYear( year );
	
	quarter = $("#issue").val();
	if (quarter == "") {
		let curMonth = dateObj.getMonth() + 1;
		if (curMonth == 12 || curMonth == 1 || curMonth == 2 ) {
			quarter = 1;
			if (curMonth == 12)
				dateObj.setFullYear( ++year );
		}
		else if (curMonth >= 3 && curMonth < 6 ) {
			quarter = 2;
		}
		else if (curMonth >= 6 && curMonth < 9 ) {
			quarter = 3;
		}
		else if (curMonth >= 9 && curMonth < 12 ) {
			quarter = 4;
		}
	}
	
	month = quarter*3-3;
	dateObj.setMonth(month);
	
	date = 1;
	dateObj.setDate(date);
	
	day = dateObj.getDay();
	// set date on the last sabbath of previous quarter - the day before the beginning of the quarter (sunday)
	//if (day > 0) {  
		dateObj.setDate(0 - day);
		day = dateObj.getDay();
		date = dateObj.getDate();
	//}
	
	$("#year").val(year);
	$("#issue").val(quarter);
}


function selectTextearaLine(textarea, line) {
	var selStart = 0; 
	var selEnd = 0;
	for (index = 0; index < line; index++) {
		selStart = (selEnd === 0) ? 0 : selEnd + 1;
		selEnd = textarea.value.indexOf('\n', (selEnd === 0) ? 0 : selEnd + 1);
	}
	if (selEnd == -1) {
		console.log("Warning: (fn:selectTextearaLine) No such line. line:" + parseInt(line));
	}
	textarea.focus();
	textarea.setSelectionRange(selStart, selEnd);
}


function jumpToTextearaLine(textarea, line) {
	// if (textarea.currentStyle) {
	// 	var y = textarea.currentStyle['line-height'];
	// } else if (window.getComputedStyle) {
	// 	var y = document.defaultView.getComputedStyle(textarea, null).getPropertyValue('line-height');
	// }
	// var top = line * parseInt(y, 10);

	var lineCount = textarea.value.split("\n").length;
	var lineHeight = textarea.scrollHeight / lineCount ;
	var top = lineHeight * (line - 1);
	var vc = textarea.clientHeight / 2;

	textarea.scrollTop = (top < vc) ? 0 : top - vc ;
}

