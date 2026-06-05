

@push('after-styles')
        <style>
            label {font-weight: bold;}
            textarea {width: 100%;height: 200px;}
            #message-textarea {width: 100%;height: 100px;}
			.container {
				margin: 0;
				padding: 0.5em;
				max-width: 100%;
			}
			.navbar { margin-bottom: 0 !important; }
            #waiting-icon { 
                position: fixed; 
                left:48%;
                top:48%;
                margin:auto; 
                background: url(/css/images/loading.gif);
                width:32px;
                height:32px;
                z-index:99999999;
            }
            .hide {
                display : none;
            }
		</style>
@endpush

@push("after-scripts")
    <script>
        let messageTextarea = null;
        $( document ).ready(function() {
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            messageTextarea = document.getElementById('message-textarea');
        });

        function XMLDocErrString(xml) {
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

        const reader = new FileReader();
        const fileSelector = document.getElementById('source-file');
        fileSelector.addEventListener('change', (event) => {
            reader.readAsText(event.target.files[0]);
        });
        reader.addEventListener('load', (event) => {
            document.getElementById('bible-text').value = event.target.result;
        });

        const buttonConvert = document.getElementById('button-convert');
        buttonConvert.addEventListener('click', (event) => {
            const lang = document.getElementById('input-lang').value;
            const title = document.getElementById('input-title').value;
            const abbr = document.getElementById('input-abbr').value;
            const chapterLable = document.getElementById('input-chapter').value;
            const bibleText = document.getElementById('bible-text').value.split("\n");
            const bibleAbbr = document.getElementById('bible-abbr').value.split("\n");

            if (!lang.length || !title.length || !abbr.length || !chapterLable.length || !bibleText.length || !bibleAbbr.length) {
                alert("Enter the empty fields!");
            }
            
            bibleAbbr.forEach((element, index, array) => {
                array[index] = element.split("\t");
            });
            
            let xml = "<\?xml version='1.0' encoding='UTF-8'?>\n";
            xml += `<book annotations="sb" xml:lang="${lang}" role="bible" xml:base="${abbr}" title="${title}" source_type="Title" title_path="book/chapter/verse" content_depth="2" title_deliminater=" :::" description="">\n`;
            let book = 0;
            let prevBook = 0;
            let chapter = 0;
            let prevChapter = 0;
            let verse = 0;

            bibleText.forEach((element) => {
                const parts = element.split("\t");
                book = parseInt(parts[0]);
                if (!book || book < 1 || book > 66) {
                    return;
                }
                chapter = parseInt(parts[1]);
                verse = parseInt(parts[2]);
                if (book != prevBook) {
                    if( prevBook > 0 ) {
                        xml += "</H2>\n";
                        xml += "</H1>\n";
                    }
                    xml += `<H1 val='${bibleAbbr[book][0]}'>\n`;
                    xml += `<parag ref='${abbr}/${bibleAbbr[book][0]}//' role='title'>${bibleAbbr[book][1]}</parag>\n`;
                    prevBook = book ;
                    prevChapter = 0;
                }
                if (chapter != prevChapter) {
                    if (chapter > 1) {
                        xml += "</H2>\n";
                    }
                    xml += `<H2 val='${chapter}'>\n`;
                    xml += `<parag ref='${abbr}/${bibleAbbr[book][0]}/${chapter}/' role='title'>${chapterLable} ${chapter}</parag>\n`;
                    prevChapter = chapter;
                }
                xml += `<H3 val='${verse}'>\n`;
                xml += `<parag ref='${abbr}/${bibleAbbr[book][0]}/${chapter}/${verse}'>${verse} ${parts[3]}</parag>\n`;
                xml += "</H3>\n";
            });
            xml += "</H2>\n";
            xml += "</H1>\n";
            xml += "</book>";

            document.getElementById('result-textarea').value = xml;

            const xmlError = XMLDocErrString(xml);
            if( xmlError.length > 0 ) {
                messageTextarea.value +=  xmlError + "\n";
            }
            else {
                messageTextarea.value += "Conversion completed!\nXML is well formed!\n";
            }
			messageTextarea.scrollTop = messageTextarea.scrollHeight;
        });


        // Upload produced XML to the server
        $( "#button-upload-xml" ).click(function() {
            // if (confirm("Are you sure you want to upload the result?")) {
            $("#waiting-icon").show();
            var xml_data = document.getElementById('result-textarea').value;
            var overwrite = document.getElementById("overwrite").checked;
            $.ajax({
                type: "POST",
                url: "/tools/bible/bible-converter/upload",
                data: { 'content':xml_data, 'overwrite':overwrite },
                dataType: "json"
            }).done(function( data ) {
                var msg = "Upload result: \n";
                if (data.code == -1) {
                    msg += " Failed\n";
                }
                if (Array.isArray(data.message)) {
                    data.message.forEach((val)=>{msg += val + "\n"});
                } 
                else {
                    msg += data.message;
                }
                messageTextarea.value += "\n" + msg + "\n";
            }).fail(function() {
            }).always(function() {
			    messageTextarea.scrollTop = messageTextarea.scrollHeight;
                $("#waiting-icon").hide();
            });
        }); 


        // Validate produced XML on the server
        $( "#button-do-validation").click(function() {
            var bookXML;
            let xml_data = document.getElementById('result-textarea').value;

            $("#waiting-icon").show();
            $.ajax({
                type: "POST",
                url: "/tools/bible/bible-converter/validate",
                data: { 'content':xml_data },
                dataType: "json"
            }).done(function( data ) {
                var msg = "Validation result: \n";
                if (data.code == -1) {
                    msg += "Not Valid\n";
                }
                if (Array.isArray(data.message)) {
                    data.message.forEach((val)=>{msg += val + "\n"});
                } 
                else {
                    msg += data.message;
                }
                messageTextarea.value += "\n" + msg + "\n";
            }).fail(function() {
            }).always(function() {
			    messageTextarea.scrollTop = messageTextarea.scrollHeight;
                $("#waiting-icon").hide();
            });
        }); 


    </script>
@endpush

<x-app-layout>

<div class="py-12">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">

        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Bible Converter') }}
            </h2>
        </x-slot>

        <div>
            <header></header>
            <h3>Bible Converter for Numbered Text</h3>
            <div class="info">
                <label>Language Code:</label>
                <input type="text" id="input-lang" name="lang" value="sr"></input>
                <br />
                <label>Title:</label>
                <input type="text" id="input-title" name="title" value="Serbian Daničic-Karadžić Biblija"></input>
                <br />
                <label>Abbriviation:</label>
                <input type="text" id="input-abbr" name="abbr" value="SRDKCY"></input>
                <br />
                <label>Chapter label:</label>
                <input type="text" id="input-chapter" name="input-chapter" value="Поглавље"></input>
            </div>

            <br />
            <div class="source">
                <label>Bible Source Text:</label>
                <br />
                <input type="file" id="source-file">
                <br />
                <textarea id="bible-text" name="bible-text" wrap="soft" rows="4" cols="50">
        1	1	1	У почетку створи Бог небо и землу.
        1	1	2	А земља беше без обличја и пуста, и беше тама над безданом; и дух Божји дизаше се над водом.
        1	1	3	И рече Бог: Нека буде светлост. И би светлост.
        1	1	4	И виде Бог светлост да је добра; и растави Бог светлост од таме.
        ...
                </textarea>
                <br />
                <label>Bible Abbr:</label>
                <br />
                <textarea id="bible-abbr" name="bible-abbr" wrap="soft" rows="4" cols="50">
        Past bible abbr table here!
        Ver	SRDKCY
        Gen	1 Мојсијева
        Exo	2 Мојсијева
        Lev	3 Мојсијева
        Num	4 Мојсијева
        Deut	5 Мојсијева
        ...
                </textarea>
            </div>
            <br />
            <div class="convert">
                <button type="button" id="button-convert" >Convert</button>
            </div>
            <br />
            <div>
                <label>Return Messages:</label>
                <br />
                <textarea id="message-textarea" name="message-textarea" rows="4" cols="50"></textarea>
            </div>
            <br />
            <div>
                <label>Result:</label>
                <br />
                <textarea id="result-textarea" name="result-textarea" rows="4" cols="50"></textarea>
            </div>
            <button type="button" id="button-do-validation" >Do Validation</button>
            <button type="button" id="button-upload-xml" >Upload to Server</button>
            <input type="checkbox" id="overwrite" name="overwrite"> <label for="overwrite">Overwrite Server file</label>
        </div>
    </div>
    <div id="waiting-icon" class="hide"></div>
</x-app-layout>