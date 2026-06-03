<!DOCTYPE HTML PUBLIC "-//W3C//Dtd HTML 4.0 transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>SBL Date Insert</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style type="text/css">
		body {}
		.main_title		{font-size: 2em; text-align: center; color: blue}
		.beta			{font-size: 14pt; color: red}
		.main_table		{width: 100%; background-color: #999999}
		.encoding1		{font-size: 20px; color: black}
		.encoding2		{font-size: 20px; color: blue}
		.encoding3		{font-size: 20px; color: red}
		.split_window	{width: 45%}
		.source_text	{font-size: 12pt; width: 100%}
		.result_text	{font-size: 12pt; width: 100%}
		.version		{font-size: 8pt; text-align:right; color:green}
	</style>

	<script language="javascript" type="text/javascript">
		var text;
		
		function copyit(theField) 
		{
			var tempval=eval("document."+theField)
			tempval.focus()
			tempval.select()
			therange=tempval.createTextrange()
			therange.execCommand("Copy") 
		}
		
		function resizeTextArea() 
		{
			//Wrap your form contents in a div and get its offset height
			var form_height = document.getElementById('formWrapper').offsetHeight;
			//Get height of body (accounting for user-installed toolbars)
			var body_height = document.body.clientHeight;
			var buffer = 100; //Accounts for misc. padding, etc.
			//Set the height of the textarea dynamically
			document.getElementById('source_text').style.height = (body_height - form_height) - buffer;
			document.getElementById('result_text').style.height = (body_height - form_height) - buffer;
			//NOTE: For extra panache, add onresize="resizeTextArea()" to the body
		}
	</script>
</head>
<body onLoad="resizeTextArea(); encoding_converter.source_text.focus();" onresize="resizeTextArea()">
<form name="encoding_converter">
<div class="main_title" id="formWrapper" style="height:40px">SBL Date Insertor<span class="version">v1.0</span>
</div>
<table class="main_table">
	<tbody>
		<tr>
			<td colspan="2">
				Choose a source Language: 
				<select id="lang-select">
					<option value="--">Choose a Language</option>
				</select>
				&nbsp;&nbsp;&nbsp;
				<!--input type="radio" id="lang_en" name="lang" value="en" /><label for="1" class="en">English</label>
				<input type="radio" id="lang_ko" name="lang" value="ko" checked="checked" /><label for="2" class="ko">Korean</label>
				<input type="radio" id="lang_vi" name="lang" value="vi" /><label for="3" class="vi">Vietnamee</label>
				<input type="radio" id="lang_am" name="lang" value="am" /><label for="4" class="am">Ethiopian AM</label-->

				<label for="year" class="">Year</label><input type="text" id="year" name="year" value="" size="4">&nbsp;&nbsp;&nbsp;
				<label for="quarter" class="">Quarter</label><input type="text" id="quarter" name="quarter" value="" size="2">
				<input type="button" id="sampletext_button" value="Load Sample Text" onclick="javascript:sampletext()" />
			</td>
		</tr>
		<tr align="middle">
			<td class="split_window">
				Source Text
				<input type="button" value="Insert Date" id="insert_text"><br/>
			</td>
			<td class="split_window">
				Converted Text
				<input type="button" value="Copy Result Text" onClick="copyit('encoding_converter.result_text')"/><br/>
			</td>
		</tr>
		<tr>
			<td class="split_window">
				<textarea id="source_text" class="source_text" name="source_text" spellcheck="false"></textarea>
			</td>
			<td class="split_window">
				<textarea id="result_text" class="result_text" name="result_text" spellcheck="false"></textarea>
			</td>
		</tr>
	</tbody>
</table>
<table class='pattern'>
      	<tr>
      		<td>
      			Lesson Information
      		</td>
      	</tr>
      	<tr>
      		<td> Foreword :<br>
      			<input TYPE="TEXT" id="foreword_title" SIZE="24"> <input TYPE="TEXT" id="foreword_title_tag" SIZE="24"> 
      		</td>
      	</tr>
      	<tr>
      		<td> Lesson	Start :<br>
      			<input TYPE="TEXT" id="lesson_start" SIZE="24"> <input TYPE="TEXT" id="lesson_start_tag" SIZE="24">
      		</td>
      	</tr>
      	<tr>
      		<td> Lesson	Sabbath :<br>
      			<input TYPE="TEXT" id="lesson_sabbath" SIZE="24"> <input TYPE="TEXT" id="lesson_sabbath_tag" SIZE="24">
      		</td>
      	</tr>
      	<tr>
      		<td>
      			Suggested Reading :<br>
      			<input TYPE="TEXT" id="reading_lable" SIZE="24"> <input TYPE="TEXT" id="reading_lable_tag" SIZE="24">
      		</td>
      	</tr>
      	<tr>
      		<td>
      			Day :<br>
      			<input TYPE="TEXT" id="day" SIZE="24"> <input TYPE="TEXT" id="day_tag" SIZE="24">
      		</td>
      	</tr>
      	<tr>
      		<td>
      			Date :<br>
      			<input TYPE="TEXT" id="date"  SIZE="24"> <input TYPE="TEXT" id="date_tag" SIZE="24">
      		</td>
      	</tr>
      	<tr>
      		<td>
      				Subtitle :<br>
      			<input TYPE="TEXT" id="subtitle" SIZE="24"> <input TYPE="TEXT" id="subtitle_tag" SIZE="24">
      		</td>
      	</tr>
      	<tr>
      		<td>
      			Question :<br>
      			<input TYPE="TEXT" id="day_question" SIZE="24"> <input TYPE="TEXT" id="day_question_tag" SIZE="24">
      		</td>
      	</tr>
      	<tr>
      		<td>
      				Review Question :<br>
      			<input TYPE="TEXT" id="rev_question" SIZE="24"> <input TYPE="TEXT" id="rev_question_tag" SIZE="24">
      		</td>
      	</tr>
      	<tr>
      		<td>
      			refer_text :<br>
      			<input TYPE="TEXT" id="refer_text" SIZE="24"> <input TYPE="TEXT" id="refer_text_tag" SIZE="24">
      		</td>
      	</tr>
      	<tr>
      		<td>
      				Ref. Source :<br>
      			<input TYPE="TEXT" id="ref_source" SIZE="24"> <input TYPE="TEXT" id="ref_source_tag" SIZE="24">
      		</td>
      	</tr>
     	<tr>
      		<td>
      			FSO Date : <br>
      			<input TYPE=TEXT id="fso_date" SIZE="24"> <input TYPE=TEXT id="fso_date_tag" SIZE="24">
      		</td>
		</tr>
	</table>
</form>

	<!-- Scripts -->
	@stack('before-scripts')
    {!! script(mix('js/manifest.js')) !!}
	{!! script(mix('js/vendor.js')) !!}
	{!! script(mix('js/frontend.js')) !!}
	@stack('after-scripts')

	<script type="text/javascript" src="/js/jquery.calendars.js"></script>
	<script type="text/javascript" src="/js/jquery.calendars.ethiopian.js"></script>

	<script type="text/javascript" src="/js/sbl/insert_date_sampletext.js"></script>
	<script type="text/javascript" src="/js/sbl/sbl_set_lang_pattern.js"></script>
	<script type="text/javascript" src="/js/sbl/sbl_insert_date.js"></script>

</body>
</html>