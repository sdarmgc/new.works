
@push('after-styles')
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
		<link type="text/css" rel="stylesheet" href="/css/sbl_converter.css">
		<link rel="stylesheet" type="text/css" href="/css/tln.min.css"/>
		<style>
			.container {
				margin: 0;
				padding: 0;
				max-width: 100%;
			}
			.navbar { margin-bottom: 0 !important; }
		</style>
@endpush

@push("after-scripts")
    <script>
        {!!$jsVar!!} 
    </script>
	<script type="text/javascript" src="/js/tln.min.js"></script>
	<!-- <script type="text/javascript" src="../js/xmllint.js?v=0.1"></script> -->
	<script type="text/javascript" src="/js/sbl/convert_sbl_sampletext.js"></script>
	<script type="text/javascript" src="/js/sbl/sbl_set_lang_pattern.js"></script>
	<script type="text/javascript" src="/js/sbl/sbl_converter.js?v=1.1"></script>
	<script type="text/javascript" src="/js/converter.js?v=1.1"></script>
	
	<script>
        /*
         * menu
         */
        $(".menu-item").click(function () {
            item = $(this).prop("id");
            if (item == "menu-xml") {
                toText(false, true);
            }
            else {
                var xmlText = $("#output_text").val().replaceAll(/ r="[^"]*"/g, '');
                parser = new DOMParser();
                xmlDoc = parser.parseFromString(xmlText,"text/xml");
                const newDiv = document.createElement("book-holder");
                newDiv.appendChild(xmlDoc.getElementsByTagName("book")[0]);
                document.body.appendChild(newDiv);
                if (item == "menu-rtf") {
                    exportSblRtfText();
                }
                else if (item == "menu-indesign-text") {
                    exportSblIndesignTaggedText();
                }
                else if (item == "menu-indesign-text-v2") {
                    exportSblIndesignTaggedText(2);
                }
                document.getElementsByTagName('book-holder').item(0).remove();
            }
        });
    
        function toText(isText, toDownload = true)
        {
            var newXML = $('book').clone(false);
            newXML.find("*").removeAttr("class"); // remove all display attrs
    
            if (isText)
                window.open("", "", "scrollbars=1").document.write(newXML.text());
            else {
                var xmlText = $("#output_text").val().replaceAll(/ r="[^"]*"/g, '');
                // xmlText = xmlText.replace(/\&nbsp;/g, "\u00A0"); // xml does not support '&nbsp;'
                if (toDownload)
                    download(xmlText, "sbl" + settings.year + "_" + settings.issue + "_" + settings.lang + ".xml", "text/plain;charset=utf-8");
                else
                    return xmlText;
            }
        }
	</script>
@endpush

<x-app-layout>

<div class="py-12">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">

            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('SBL Converter') }}
                </h2>   
                <div class="app-info text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                    <div class="app-info-menu flex justify-end">
                        <div class="hidden sm:flex sm:items-center sm:ms-6 mt-1">
                            <div class="ms-3 relative">
                                <x-dropdown align="left" width="60">
                                    <x-slot name="trigger">
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                                {{ __('DOWNLOAD') }}
                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="w-60">
                                            <x-dropdown-link href="#">
                                                <span id="menu-xml" class="dropdown-item menu-item" title="Export xml format">
                                                    {{ __('XML') }}
                                                </span>
                                            </x-dropdown-link>
                                            <x-dropdown-link href="#">
                                                <span id="menu-rtf" class="dropdown-item menu-item" title="Export rtf format">
                                                    {{ __('RTF') }}
                                                </span>
                                            </x-dropdown-link>
                                            <x-dropdown-link href="#">
                                                <span id="menu-indesign-text-v2" class="dropdown-item menu-item" title="Export Indesign tag format V2">
                                                    {{ __('INDESIGN TAGGED TEXT') }}
                                                </span>
                                            </x-dropdown-link>
                                        </div>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot>
			<div class="panel_outter_wrapper">
				<div class="panel-container">
					<div class="panel panel-lang-attr">
						<input type="checkbox" id="auto_attr" checked> <label for="auto_attr">Auto Attributes</label>
						<div class="panel panel-langauge">
							<select id="lang-select">
								<option value="--">Choose a Language</option>
							</select>
							Lang Code: <input type="text" id="lang_code" size="1"> <input type="text" id="lang_code3" size="2">
						</div>
						<div class="panel panel-lesson-info">
							<div class="">Lesson Title</div>
							<input type="text" id ="quarterly_title" title="Quarterly Title"><input type="button" id="clear_title_button" value="X">
							<div class="">Year <input type="text" id="year" size="3">&nbsp;&nbsp;Qtr&nbsp;
								<select id="issue" size="1">
									<option value="" selected="selected">Select</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
								</select>
								&nbsp;&nbsp;Period Mark&nbsp;
								<input type="text" id="period_mark" size="1">
								<hr>
							</div>
							<div>
								<input type="checkbox" id="take_abstract" > <label for="take_abstract">Take Abstract from line 4</label>
								<!--<br style="" />-->
								<!--<input type="checkbox" id="take_summary" > <label for="take_summary">Take Summary from line 5</label>-->
							</div>
						</div>
						<div class="panel panel-replace-pattern">
							<div class="">Foreword<br>
								<input type="text" id="foreword_title" size="20"> <input type="text" id="foreword_title_tag" size="20">
							</div>
							<div class="">Lesson Start<br>
								<input type="text" id="lesson_start" size="20"> <input type="text" id="lesson_start_tag" size="20">
							</div>
							<div class="">Lesson Sabbath<br>
								<input type="text" id="lesson_sabbath" size="20"> <input type="text" id="lesson_sabbath_tag" size="20">
							</div>
							<div class="">Suggested Reading<br>
								<input type="text" id="reading_lable" size="20"> <input type="text" id="reading_lable_tag" size="20">
							</div>
							<div class="">Day<br>
								<input type="text" id="day" size="20"> <input type="text" id="day_tag" size="20">
							</div>
							<div class="">Date<br>
								<input type="text" id="date"  size="20"> <input type="text" id="date_tag" size="20">
							</div>
							<div class="">Subtitle<br>
								<input type="text" id="subtitle" size="20"> <input type="text" id="subtitle_tag" size="20">
							</div>
							<div class="">Question<br>
								<input type="text" id="day_question" size="20"> <input type="text" id="day_question_tag" size="20">
							</div>
							<div class="">Review Question<br>
								<input type="text" id="rev_question" size="20"> <input type="text" id="rev_question_tag" size="20">
							</div>
							<div class="">Refer_text<br>
								<input type="text" id="refer_text" size="20"> <input type="text" id="refer_text_tag" size="20">
							</div>
							<div class="">Ref. Source<br>
								<input type="text" id="ref_source" size="20"> <input type="text" id="ref_source_tag" size="20">
							</div>
							<div class="">FSO Start : <br>
								<input type=text id="fso_start" size="20"> <input type=text id="fso_start_tag" size="20">
							</div>
							<div class="">FSO Date : <br>
								<input type=text id="fso_date" size="20"> <input type=text id="fso_date_tag" size="20">
							</div>
							<div class="">Replace Pattern : &nbsp;&nbsp;<input type="button" id="replace_source_text" value="Replace in Source Text" title="Replace in Source Text" ><br>
								<textarea spellcheck="false" class="" name="replace_text" id="replace_text" title="" rows=5 dir="auto" ></textarea>
							</div>
						</div>
						<div class="">
							<input type="button" id="savepattern_button" value="Save Pattern" disabled style="width: 4cm">
						</div>
					</div>
					<div class="panel panel-contents" id="panel-contents">
						<div class="panel panel-source-text">
							<div class="panel panel-title" >
								<b>Source Text</b>&nbsp;&nbsp;
								<input type="button" id="clear_input_text" value="Clear" title="Clear Source Text" >&nbsp;&nbsp;
								<input type="button" id="go_top_input_text" value="Go to Top" title="Scroll to top of the editor" >&nbsp;&nbsp;
								<input type="button" id="set_title_input_text" value="Set Title" title="Set line 2 as Title">&nbsp;&nbsp;
								|&nbsp;&nbsp;Find <input type="text"  id="regex_pattern" title="regex pattern" placeholder="Find text/pattern"> Replace with <input type="text" id="regex_replace" title="regex replace" placeholder="Replace with text/pattern" value="">
								<input type="button" id="regex_button" value="Replace" >
							</div>
						</div>
						<div class="textarea-wrapper" id="input_text-wrapper">
							<textarea spellcheck="false" class="TextArea100" id="input_text" title="Paste Source Text Here" rows=10 dir="auto"></textarea>
						</div>
						<div class="panel panel-result">
							<div class="panel panel-title">
								<b> Result XML</b>&nbsp;&nbsp;
								<input type="button" id="clear_output_text" value="Clear" title="Clear Result Text">
								&nbsp;&nbsp;&nbsp;
								<input type="checkbox" id="allow_edit_result"> <label for="allow_edit_result">Allow Editing</label>
								| &nbsp;&nbsp;
								<input type="button" id="convert_button" value="Generate XML"> &nbsp;&nbsp;&nbsp;
								<input type="checkbox" id="auto_convert" checked disabled> <label for="auto_convert">Auto Convert</label> &nbsp;&nbsp;&nbsp;
								<select id="fix_tags" size="1">
									<option value="">Choose an option</option>
									<option value="add-writer">Add missing &lt;writer&gt; tag</option>
									<option value="remove-writer">Remove duplicate &lt;writer&gt; tag</option>
									<option value="replace-writer-paragraph">Replace &lt;writer&gt; tag</option>
									<option value="replace-subquestion">Replace &lt;sub_question&gt; tag</option>
								</select>
								<input type="button" id="fix_tags_button" value="fix tags">
							</div>
						</div>
						<div class="textarea-wrapper" id="output_text-wrapper">
							<textarea spellcheck="false" class="TextArea100" name="output_text" id="output_text" title="Converted XML File" rows=10 dir="auto" readOnly></textarea>
						</div>
						<div class="panel panel-error-check">
							<div class="panel panel-error-check-message">
								<div class="panel panel-title">
									<b>Result Message</b>&nbsp;&nbsp;
									<input type="button" id="clear_error_text" value="Clear">
									<input type="button" id="check_xml_button" value="Check XML">
									<input type="button" id="preview_button" value="Result Preview">
									<input type="button" id="copy_result_button" value="Select XML">
									<!--img src="pass.png" width="20" height="20" border="0" alt="pass"> <img src="error.png" width="20" height="20" border="0" alt="fail"-->
									<!--label id="xmlValidate" style="color:#00f">XML not validated</label><br/-->
								</div>
							</div>
							<div class="textarea-wrapper">
								<textarea class="" id="error_text" rows=4></textarea>
							</div>
						</div>
						<div class="panel panel-save">
							<!--input type="checkbox" name="overwrite" />
							<span>Overwrite server file</span-->
							<input type=button id="validate_button" value="Validate" />
							<input type="checkbox" id="overwrite" name="overwrite"> <label for="overwrite">Overwrite Server file</label>
							<input type=submit id="submit_button" value="Submit Result to Server" />
							<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
							<input type="button" id="save_button" value="Save XML" />
							<input type="button" id="saveresult" value="Save Result" />
							<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
							<input type="button" id="reset_button" value="Reset Page" style="width: 4cm" />
							<input type="button" id="sampletext_button" value="Load Sample Text" onclick="javascript:sampletext()" />
							<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
							<select onchange="window.open(this.options[this.selectedIndex].value,'new_window')">
								<option value="">Other Resources</option>
								<option value="http://sdarm.org/sbl">SBL Page</option>
								<option value="http://sdarm.org/admin/publication/update-db">Update Publ. db</option>
								<option value="../date/sbl_insert_date.htm">SBL Insert Date</option>
								<option value="../tamil/converter.htm">Encoding Converter</option>
								<option value="regex.html">RegEx</option>
								<option value="http://translate.google.com">Google Translate</option>
								<option value="http://bing.com/translator">Bing Translate</option>
								<option value="http://babelfish.com">Babelfish</option>
								<option value="http://translate.com">Translate</option>
								<option value="http://freetranslation.com">SDL Free Translation</option>
								<option value="http://translation.babylon.com">Babylon Translation</option>
								<option value="http://translation.paralink.com">IMtranslator</option>
								<option value="http://webtranslation.paralink.com">Web Translator</option>
								<option value="http://translation2.paralink.com">PROMPT Online</option>
								<option value="http://reverso.net/text_translation.aspx?lang=EN">Reverso Translation</option>
								<option value="http://translate.net">Translate.net</option>
								<option value="http://worldlingo.com/en/products_services/worldlingo_translator.html">WorldLingo</option>
								<option value="http://spanishdict.com/translation">Spanish Dict</option>
								<option value="http://science.co.il/language/locale-codes.asp">Locale codes</option>
							</select>
						</div>
					</div>
					<div class="panel-spliter" id="panel-spliter"></div>
					<div class="panel panel-preview" id="panel-preview"></div>
				</div>
				<div class="footer">
					<table border="1">
						<tbody align="center">
						<tr>
							<td><strong>1</strong> Sunday</td><td><strong>2</strong> Monday</td><td><strong>3</strong> Tuesday</td><td><strong>4</strong> Wednesday</td><td><strong>5</strong> Thursday</td><td><strong>6</strong> Friday</td><td><strong>7</strong> Sabbath</td><td><strong>1</strong> January</td><td><strong>2</strong> February</td><td><strong>3</strong> March</td><td><strong>4</strong> April</td><td><strong>5</strong> May</td><td><strong>6</strong> June</td><td><strong>7</strong> July</td><td><strong>8</strong> August</td><td><strong>9</strong> September</td><td><strong>10</strong> October</td><td><strong>11</strong> November</td><td><strong>12</strong> December</td></tr>
						<tr>
							<td><label class="label-days" id="day1">day1</label></td><td><label class="label-days" id="day2">day2</label></td><td><label class="label-days" id="day3">day3</label></td><td><label class="label-days" id="day4">day4</label></td><td><label class="label-days" id="day5">day5</label></td><td><label class="label-days" id="day6">day6</label></td><td><label class="label-days" id="day7">day7</label></td>
							<td><label class="label-months" id="month1">month1</label></td><td><label class="label-months" id="month2">month2</label></td><td><label class="label-months" id="month3">month3</label></td><td><label class="label-months" id="month4">month4</label></td><td><label class="label-months" id="month5">month5</label></td><td><label class="label-months" id="month6">month6</label></td><td><label class="label-months" id="month7">month7</label></td><td><label class="label-months" id="month8">month8</label></td><td><label class="label-months" id="month9">month9</label></td><td><label class="label-months" id="month10">month10</label></td><td><label class="label-months" id="month11">month11</label></td><td><label class="label-months" id="month12">month12</label></td>
						</tr>
						</tbody>
					</table>
					<table border="1">
						<tbody align="center">
						<tr>
							<td>0</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td><td>8</td><td>9</td><td>10</td><td>A a</td><td>B b</td><td>C c</td><td>D d</td><td>E e</td><td>F f</td><td>G g</td></tr>
						<tr>
							<td><label class="label-numbers" id="num0">num0</label></td><td><label class="label-numbers" id="num1">num1</label></td><td><label class="label-numbers" id="num2">num2</label></td><td><label class="label-numbers" id="num3">num3</label></td><td><label class="label-numbers" id="num4">num4</label></td><td><label class="label-numbers" id="num5">num5</label></td><td><label class="label-numbers" id="num6">num6</label></td><td><label class="label-numbers" id="num7">num7</label></td><td><label class="label-numbers" id="num8">num8</label></td><td><label class="label-numbers" id="num9">num9</label></td><td><label class="label-numbers" id="num10">num10</label></td><td><label class="label-alphabets" id="a">A a</label></td><td><label class="label-alphabets" id="b">B b</label></td><td><label class="label-alphabets" id="c">C c</label></td><td><label class="label-alphabets" id="d">D d</label></td><td><label class="label-alphabets" id="e">E e</label></td><td><label class="label-alphabets" id="e">F f</label></td><td><label class="label-alphabets" id="e">G g</label></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<img id="wait-gif" src="https://media.giphy.com/media/sSgvbe1m3n93G/giphy.gif" alt="this slowpoke moves" style="width:100px; height:100px; display: none; z-index: 99; position:absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);" />

	</div>
</div>
</x-app-layout>