/*
 * 
 * @sbl_insert_date.js
 * 
 * @copyleft Sean Kim
 * 
 */

var dateObj; // javascript date object
var jdDate;  // hold the value of Julian Date (the number of days since 1 January 4713 BC - Julian Calendar)
var year, month, day, date, quarter; // alwasy maintain the current date in number.
var lang = "en";

$(function() 
{
	dateObj = new Date();
	setLangList();
	$("#lang-select").val(lang);
	setLangPattern(lang);
	$("#year").val(new Date().getFullYear());

	$('#year, #quarter').change(function () {
		;//insertDate();
	});
	
	$('#lang-select, input[name="lang"]').change(function () {
		lang = $(this).val();
		setLangPattern(lang);
		insertDate();
	});
		
	$('#insert_text').click(function () {
		insertDate();
	});
	
	$('#source_text').change(function () {
		insertDate();
	});
	
	$('#source_text').keyup(function () {
		;//insertDate();
	});
	
});

// set the date at the beginning of the quater - (lesson start date - sunday)
function setDateObj()
{
	dateObj.setHours(0);
	
	year = $("#year").val();
	if (year == "")
		year = dateObj.getFullYear();
	else
		dateObj.setFullYear( year );
	
	quarter = $("#quarter").val();
	if (quarter == "") {
		quarter = 1 + Math.floor(dateObj.getMonth()/3)+1;
		if (quarter > 4) {
			quarter = 1;
			dateObj.setFullYear( ++year );
		}
	}
	
	month = quarter*3-3;
	dateObj.setMonth(month);
	
	date = 1;
	dateObj.setDate(date);
	
	day = dateObj.getDay();
	if (day > 0) {  // set date on the beginning of the quarter (sunday)
		dateObj.setDate(0 - day + 1);
	}
	
	day = dateObj.getDay();
	date = dateObj.getDate();	// 1 - 31
	month = dateObj.getMonth();	// 0 - 11
	year = dateObj.getFullYear();
	
	if (quarter == 1 && date > 1)  // the lesson start date is set on December.
		$("#year").val(year+1);
	else
		$("#year").val(year);
	$("#quarter").val(quarter);
	
	jdDate = $.calendars.instance(name).newDate(year, month+1, date).toJD(); // month is 1 base.
	
	// for init value, set proper converted day, date, month and year
	jdDate--;
	day--;
	increaseDate();
}

function increaseDate()
{
	jdDate ++;
	day++;
	if(day == 7)
		day = 0;
	
	if (lang == "am") 	// Ethiopian Calander
		var d = $.calendars.instance('ethiopian').fromJD(jdDate);
	else
		var d = $.calendars.instance('gregorian').fromJD(jdDate);
	
	date = d.day();	// 1 - 31
	month = d.month() - 1; // 1 - 12 => convert to 0 base for array access
	year = d.year();
}

function insertDate()
{
	$("#result_text").css("background-color", "gray");
	
	setDateObj();
	
	var rpLessonStart = new RegExp($("#lesson_start").val(), "gi") ;
	var rpSubtitle = new RegExp($("#subtitle").val()) ;
	var arrayDays = $("#day").val().split('|') ;
		
	// language specific settings
	if (lang != "ja" && lang != "ko" && lang != "zh") {
		var match = /[^\(]+\|.+\|.+\|.+\|.+\|.+\|.+\|.+\|.+\|.+\|.+\|[^\)]+/.exec($("#date").val());
		if( match && match.length == 1 ) {
			var arrayMonths = match[0].split("|");
		}
		else
			alert("The chosen language is not supported!");
	}
	else {
		var arrayMonths = "0|" + "1|2|3|4|5|6|7|8|9|10|11|12".split('|'); // the var month is 0 base.
	}
	
	output = '';
	lessonHeaderText = "" ;
	lessonText = '';

	var in_text = $("#source_text").val();
	in_text = in_text.replace( /\n+/g, "<BR>");
	in_text = in_text.replace( /\r+/g, "");
	in_text = in_text.replace( /\s+/mg, " ");

	// split by line
	inLines = in_text.split( "<BR>" ) ;
	lineNo = 0;
	lessonNo = 0 ;

	for (lineNo = 0; lineNo < inLines.length; lineNo++ ) 
	{
		if( inLines[lineNo].search(rpLessonStart) == 0 ) {
			if( lessonNo > 0) { //flush lesson text.
				output += lessonHeaderText;
				output += " " + getSabbathDate(arrayDays,arrayMonths);
				output += "\n" + lessonText;
				increaseDate();
			}
			lessonHeaderText = inLines[lineNo] ;
			lessonText = '';
			lessonNo ++ ;
		}
		else if( lessonNo > 0 && day < 6 && inLines[lineNo].search(rpSubtitle) == 0 ) {
			lessonText += getDayDate(arrayDays,arrayMonths);
			lessonText += "\n" + inLines[lineNo] + "\n";
			increaseDate();
		}
		else {
			if( lessonNo == 0 ) { // forword and etc.
				output += inLines[lineNo] + "\n" ;
			}
			else {
				lessonText += inLines[lineNo] + "\n" ;
			}
		}
	}
	output += lessonHeaderText;
	output += " " + getSabbathDate(arrayDays,arrayMonths);  // sabbath pattern
	output += "\n" + lessonText;

	$("#result_text").val(output);
	
	if (lessonNo < 13 || day != 6)
		alert("Not Completed!");
	
	$("#result_text").css("background-color", "white");
}

function getDayDate(arrayDays,arrayMonths)
{
	if (lang == "ko") {
		return arrayDays[day] + " " + (month+1).toString() + "월 " + date.toString() + "일 ";
	}
	else { //if (lang == "vi" || lang == "am") {
		return arrayDays[day] + " " + arrayMonths[month] + " " + date.toString();
	}
}

function getSabbathDate(arrayDays,arrayMonths)
{
	if (lang == "ko") {
		return year.toString() + "년 " + (month+1).toString() + "월 " + date.toString() + "일 " + arrayDays[day];
	}
	else { //if (lang == "vi" || lang == "am") {
		return arrayDays[day] + " " + arrayMonths[month] + " " + date.toString() + " " + year.toString();
	}
}



























