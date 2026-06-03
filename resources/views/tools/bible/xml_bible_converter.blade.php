@section('title', 'XML Bible Converter | SDARM WORKS')

@extends('frontend.layouts.app')

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
            $("#waiting-icon").show();

            const lang = document.getElementById('input-lang').value;
            const title = document.getElementById('input-title').value;
            const abbr = document.getElementById('input-abbr').value;
            const chapterLable = document.getElementById('input-chapter').value;
            const bibleText = document.getElementById('bible-text').value.trim();
            const bibleAbbr = document.getElementById('bible-abbr').value.split("\n");

            if (!lang.length || !title.length || !abbr.length || !chapterLable.length || !bibleText.length || !bibleAbbr.length) {
                alert("Enter values for the empty fields!");
            }
            
            bibleAbbr.forEach((element, index, array) => {
                array[index] = element.split("\t");
            });
            
            let strXml = "<\?xml version='1.0' encoding='UTF-8'?>\n";
            strXml += `<book annotations="sb" xml:lang="${lang}" role="bible" xml:base="${abbr}" title="${title}" source_type="Title" title_path="book/chapter/verse" content_depth="2" title_deliminater=" :::" description="">\n`;
            let bookNo = 0;
            let chapterNo = 0;
            let verseNo = 0;
            
            const parser = new DOMParser();
            const sourceXML = parser.parseFromString(bibleText, "application/xml");
            
            sourceXML.querySelectorAll('book').forEach((elBook) => {
                bookNo = elBook.getAttribute('number');
                strXml += `<H1 val='${bibleAbbr[bookNo][0]}'>\n`;
                strXml += `<parag ref='${abbr}/${bibleAbbr[bookNo][0]}//' role='title'>${bibleAbbr[bookNo][1]}</parag>\n`;
                elBook.querySelectorAll('chapter').forEach((elChapter) => {
                    chapterNo = elChapter.getAttribute('number');
                    strXml += `<H2 val='${chapterNo}'>\n`;
                    strXml += `<parag ref='${abbr}/${bibleAbbr[bookNo][0]}/${chapterNo}/' role='title'>${chapterLable} ${chapterNo}</parag>\n`;
                    elChapter.querySelectorAll('verse').forEach((elVerse) => {
                        verseNo = elVerse.getAttribute('number');
                        strXml += `<H3 val='${verseNo}'>\n`;
                        strXml += `<parag ref='${abbr}/${bibleAbbr[bookNo][0]}/${chapterNo}/${verseNo}'>${verseNo} ${elVerse.textContent}</parag>\n`;
                        strXml += "</H3>\n";
                    });
                    strXml += "</H2>\n";
                });
                strXml += "</H1>\n";
            });
            strXml += "</book>\n";

            document.getElementById('result-textarea').value = strXml;

            const xmlError = XMLDocErrString(strXml);
            if( xmlError.length > 0 ) {
                messageTextarea.value +=  xmlError + "\n";
            }
            else {
                messageTextarea.value += "Conversion completed!\nXML is well formed!\n";
            }
			messageTextarea.scrollTop = messageTextarea.scrollHeight;
            
            $("#waiting-icon").hide();
        });


        // Upload produced XML to the server
        $( "#button-upload-xml" ).click(function() {
            // if (confirm("Are you sure you want to upload the result?")) {
            $("#waiting-icon").show();
            var xml_data = document.getElementById('result-textarea').value;
            var overwrite = document.getElementById("overwrite").checked;
            $.ajax({
                type: "POST",
                url: "/tools/bible/xml-bible-converter/upload",
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
                url: "/tools/bible/xml-bible-converter/validate",
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

@section('content')

<div>
    <header></header>
    <h3>XML Bible Converteraaa</h3>
    <div class="info">
        <label>Language Code:</label>
        <input type="text" id="input-lang" name="lang" value="sr"></input>
        <br />
        <label>Title:</label>
        <input type="text" id="input-title" name="title" value="Serbian Cyrillic Version == Same Translators as Latin Version == 1865 Public Domain == Daničić-Karadžić"></input>
        <br />
        <label>Abbriviation:</label>
        <input type="text" id="input-abbr" name="abbr" value="SCY"></input>
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
&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;bible translation="Serbian Cyrillic Version == Same Translators as Latin Version == 1865 Public Domain == Daničić-Karadžić"&gt;
  &lt;testament name="Old"&gt;
    &lt;book number="1"&gt;
      &lt;chapter number="1"&gt;
        &lt;verse number="1"&gt;У почетку створи Бог небо и земљу.&lt;/verse&gt;
        &lt;verse number="2"&gt;А земља беше без обличја и пуста, и беше тама над безданом; и дух Божји дизаше се над водом.&lt;/verse&gt;
        &lt;verse number="3"&gt;И рече Бог: Нека буде светлост. И би светлост.&lt;/verse&gt;
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

<div id="waiting-icon" class="hide"></div>
@endsection