/*
    * converter.js
    *
    * functions for various converter
    * 
    */

var mode = 0; // 0 == 'translator', 1 = 'reader'
$(function() {
    if (window.location.pathname.includes('/translator/'))
        mode = 0;
    else
        mode = 1;
})

/*
* get NEXT Element element which has text node
* search till the end of the document
* 
* @param {$("book *")} element
* @returns {unresolved}
*/
function nextTextElement(element) {   // element == '.editable' or '.has-text'
    var nextElement = element.nextElementSibling;
    // search in childern or their siblings
    while (nextElement) {
        if ( mode == 0 ) {
            if ( nextElement.classList.contains('editable') ) 
                return nextElement;
        }
        else {
            for (let i = 0; i < nextElement.childNodes.length; i++) {
                const childNode = nextElement.childNodes[i];
                if (childNode.nodeType === Node.TEXT_NODE && childNode.nodeValue.trim() !== '') {
                    return nextElement;
                }
            }
        }
        // search in childern
        if (nextElement.firstElementChild) {
            nextElement = nextElement.firstElementChild;
        } else {
            nextElement = nextElement.nextElementSibling;
        }
    }
    if (element.parentElement == null || element.parentElement.tagName == "BOOK")
        return null;
    return nextTextElement(element.parentElement);
}


// function nextTextElement(element) {   // element == '.editable' or '.has-text'
//     var nextElement = element.nextElementSibling;
//     // search in childern or their siblings
//     while (nextElement) {
//         if ( (mode == 0 && nextElement.classList.contains('editable'))    // 
//             || (mode == 1 && nextElement.classList.contains('has-text'))    // 
//             || (nextElement.nodeType == Node.ELEMENT_NODE && nextElement.children.length == 0)
//             || nextElement.nodeName.toLowerCase() == 'reading' // TODO: test!!!
//             || nextElement.nodeName.toLowerCase() == 'bible-text' 
//             || nextElement.nodeName.toLowerCase() == 'p' )
//             return nextElement;
//         else {  // search in childern
//             if (nextElement.firstElementChild) {
//                 nextElement = nextElement.firstElementChild;
//             } else {
//                 nextElement = element.nextElementSibling;
//             }
//         }
//     }

//     if (element.parentElement == null || element.parentElement.tagName == "BOOK")
//         return null;

//     return nextTextElement(element.parentElement);
// }


/*
    * convert content to InDesign tagged text
    *
    * @param
    * @returns tagged text
    */
function toIndesignTagForSbl()
{
    var taggedText = "";
    var parag = document.getElementsByTagName('book').item(0).cloneNode(true).firstElementChild; //$("book :first-child");//getNextEditable($("book-title"));
    var lastTag = "";
    var dayNo = "0";

    //remove un-visiable elements
    var notes = parag.parentElement.getElementsByClassName('note');
    for (let idx = 0; idx < notes.length; idx++)
        notes[idx].innerHTML = '';

    while (parag) {
        tag = parag.tagName.toLowerCase();
        if (mode == 0) {    // 0 == 'translator', 1 = 'reader'
            text = parag.getElementsByClassName('target-text').item(0).innerHTML;
            text = text.replaceAll(/<em>([^<]+)<\/em>/g, '@@EM$1@@');
            // if (tag == "key_text" || tag == "question" || tag == "sub_question") {
                text = text.replaceAll(/<bible_source>([^<]+)<\/bible_source>/g, '@BSS@$1@BSE@');
            // }
        }
        else {  // 'reader' mode
            if (tag == "reading") {
                text = parag.textContent;
            }
            else {
                text = parag.innerHTML;
                text = text.replaceAll(/<span class="source-link source-link-bible[^>]*>([^<]+)<\/span>/g, '@BSS@$1@BSE@');
                text = text.replaceAll(/<span class="source-link[^>]*><em>([^<]+)<\/em>([^<]+)?<\/span>/g, '$1$2');
                text = text.replaceAll(/(, )<span class="source-link[^>]*>([^<]+)<\/span>/g, '$1$2');
                text = text.replaceAll(/<em[^>]*>([^<]+)<\/em>/g, '@@EM$1@@');
                text = text.replaceAll(/<i>([^<]+)<\/i>/g, '@@EM$1@@');
                if (tag == 'verse') {
                    text = text.replaceAll(/<verse_no[^>]*>(\d+ ?)<\/verse_no>/g, '@@VN$1@@');
                    text = text.replaceAll(/<\/?[^>]+>/g, '');
                }
            }
            text = text.replaceAll(/\s{2,}/g, ' ');
            text = text.replaceAll(/^\s/g, '');
        }
        text = text.replaceAll(/</g, "\\<").replaceAll(/>/g, "\\>").replaceAll(/\&amp;/g, "&").replaceAll(/\&nbsp;/g, "\u00A0");  // escape special character
        
        if (tag == "paragraph" || tag == "key_note" || tag == "ref_parag") {// insert book reference italics
            for (var i = 0; i < langProp[settings.lang].sbl_ref_pattern.length; i++) {
                tmpText = text.replace(new RegExp(langProp[settings.lang].sbl_ref_pattern[i]), langProp[settings.lang].sbl_ref_replace[i]); // sbl_ref_replace == "$1<cstyle:reference_book>$2<cstyle:reference_page>$3"
                tmpText = tmpText.replace('—Ibid.', '—<cstyle:reference_book_ibid>Ibid.');  // The case that above code could not process.
                if (tmpText != text) {
                    text = tmpText.replace('<cstyle:reference_book>Ibid.', '<cstyle:reference_book_ibid>Ibid.');
                    break
                }
            }
        } else if (tag == "subtitle" || tag == "question") {  // insert tab for text indenting.
            text = text.replace(". ", ". \t");
        }
        else if (tag == "reading") { // insert book reference italics
            for (var i = 0; i < langProp[settings.lang].sbl_reading_pattern.length; i++) {
                tmpText = text.replace(new RegExp(langProp[settings.lang].sbl_reading_pattern[i]), langProp[settings.lang].sbl_reading_replace[i]);
                if (tmpText != text) {
                    text = tmpText;
                    break
                }
            }
        }

        text = text.replaceAll(/@@VN(\d+)@@/g, '<cstyle:verse_no>$1<cstyle:verse>'); 
        text = text.replaceAll(/@@EM([^@]+)@@/g, '<cstyle:em>$1<cstyle:>');
        text = text.replaceAll(/@BSS@([^@]+)@BSE@((, \d+)*)/g, '<cstyle:bible_source>$1$2<cstyle:>');
        
        // text = text.replaceAll(/@@RDIB([^@]+)@@RDIB([^@]+)@@/g, '<cstyle:reading_ibid>$1<cstyle:reference_page_a>$2<cstyle:>');
        // text = text.replaceAll(/@@RD([^@]+)@@RD([^@]+)@@/g, '<cstyle:reading>$1<cstyle:reference_page_a>$2<cstyle:>');
        // text = text.replaceAll(/@@RFIB([^@]+)@@RFIB([^@]+)@@/g, '<cstyle:reference_book_ibid>$1<cstyle:reference_page>$2<cstyle:>');
        // text = text.replaceAll(/@@RF([^@]+)@@RF([^@]+)@@/g, '<cstyle:reference_book>$1<cstyle:reference_page>$2<cstyle:>');
        // text = text.replaceAll("<cstyle:reference_book><cstyle:em>", "<cstyle:reference_book>");
        // text = text.replaceAll("<cstyle:> <cstyle:reference_page>", " <cstyle:reference_page>");

        if (tag == "fso_subtitle") {
            taggedText += "\n<pstyle:fso_subtitle>" + text;
        } else if (tag == "display_date") {
            taggedText += "\n<pstyle:fso_date>" + text;
        } else if (tag == "sabbath") {
            taggedText += "\n<pstyle:sabbath_date>" + text;
        } else if ( (mode == 0 && tag == "title-tag") ||  (mode == 1 && tag == "title") ) {
            if (parag.parentElement.tagName.toLowerCase() == "fso")
                taggedText += "\n<pstyle:space>\n<pstyle:fso_title>" + text + "\n<pstyle:space>";
            else
                taggedText += "\n<pstyle:title>" + text;
        } else if (tag == "key_note") {
            taggedText += "\n<pstyle:space>\n<pstyle:" + tag + ">" + text;
        } else if (tag == "reading_lable") {
            taggedText += "\n<pstyle:space>\n<pstyle:reading>" + text;
        } else if (tag == "reading") {
            text = text.replaceAll("<cstyle:em>", "");
            if (lastTag == "reading")
                taggedText += "\n<pstyle:reading>\t<cstyle:reading>" + text;
            else if (text.indexOf('Ibid.') == 0) {
                taggedText += "\t<cstyle:reading_ibid>" + text;
            }
            else {
                taggedText += "\t<cstyle:reading>" + text;
            }
        } else if (tag == "day") {
            dayNo = parag.parentElement.getAttribute("no");
            if ((dayNo == "1" || dayNo == "6") /*&& lastTag != "question" && lastTag != "sub_question"*/)
                taggedText += "\n<pstyle:space>\n<pstyle:day_a><cstyle:day>" + text;
            else
                taggedText += "\n<pstyle:day><cstyle:day>" + text;
        } else if (tag == "date") {
            taggedText += " <cstyle:date>\t" + text.trimEnd() + "\n<pstyle:space>";
        }
        else if (tag == "question" || tag == "sub_question") {
            if (dayNo == "6") {
                if (lastTag == "subtitle")
                    taggedText += "\n<pstyle:space>\n<pstyle:review_question>" + text;
                else
                    taggedText += "\n<pstyle:review_question>" + text;
            } 
            else if (lastTag == "question" || lastTag == "sub_question")
                taggedText += "\n<pstyle:" + tag + ">" + text + "\n<pstyle:answer_line>\n<pstyle:space>";
            else
                taggedText += "\n<pstyle:space>\n<pstyle:" + tag + ">" + text + "\n<pstyle:answer_line>\n<pstyle:space>";
        } else if (tag == "ref_parag") {
            if (lastTag == "p") {
                taggedText += "\n<pstyle:space>\n<pstyle:" + tag + ">" + text;
            }
            else {
                taggedText += "\n<pstyle:" + tag + ">" + text;
            }
        } else if (tag == "writer") {
            if (parag.parentElement.tagName.toLowerCase() == "fso")
                taggedText += "\n<pstyle:space>\n" + "<pstyle:fso_writer>" + text;
            else
                taggedText += "\n<pstyle:space>\n" + "<pstyle:writer>" + text;
        } else if (tag == "paragraph") {
            if (parag.parentElement.tagName.toLowerCase() == "fso")
                taggedText += "\n<pstyle:fso_paragraph>" + text;
            else
                taggedText += "\n<pstyle:paragraph>" + text;
        } 
        // Reader mode
        else if (tag == "span" && parag.parentElement.tagName.toLowerCase() == 'bible-ref') { 
            taggedText += "\n<pstyle:bible-text><cstyle:bible-ref>" + text + " ";
        }
        else if (tag == "verse") { 
            if (parag.style.display == 'inline') {
                if (parag == parag.parentElement.firstElementChild)
                    taggedText += "\n<pstyle:bible_text><cstyle:" + tag + ">" + text;
                else
                    taggedText += "<cstyle:" + tag + ">" + text;
            }
            else {
                taggedText += "\n<pstyle:bible_text><cstyle:" + tag + ">" + text;
            }
        }
        // Etc.
        else {
            taggedText += "\n<pstyle:" + tag + ">" + text;
        }

        lastTag = tag;
        parag = nextTextElement(parag);
    }
    
    for (const [key, value] of Object.entries(dnReplaceProp)) {
        taggedText = taggedText.replaceAll(new RegExp(key, "gm"), value);
    }
    
    return taggedText + "\n";
}


/*
 * Convert Sbl IDTT v1 to v2
*/
function convertSblIdttVersion2(idttText)
{
    let textArray = idttText.replaceAll(/<pstyle:space>\s*\n*/g, "").split('\n');
    let newText = '';
    let tempText = '';

    for (idx = 0; idx < textArray.length; idx++) {
        // if (textArray[idx].includes('<pstyle:space>'))
        //     continue;
        if (textArray[idx].includes('<pstyle:fso_date>') && textArray[idx+1].includes('<pstyle:fso_title>')) {
            newText += textArray[++idx] + '\n'; // <pstyle:fso_title>
            newText += textArray[idx-1] + '\n'; // <pstyle:fso_date>
        }
        else if (textArray[idx].includes('<pstyle:lesson_header>') && textArray[idx+1].includes('<pstyle:sabbath_date>')) {
            newText += textArray[++idx] + '\t'; // <pstyle:sabbath_date>
            newText += textArray[idx-1].replace('pstyle', 'cstyle') + '\n'; // <pstyle:lesson_header>
        }
        else if (textArray[idx].includes('<pstyle:sabbath_date>') && textArray[idx+1].includes('<pstyle:lesson_header>')) {
            newText += textArray[idx] + '\t'; // <pstyle:sabbath_date>
            newText += textArray[++idx].replace('pstyle', 'cstyle') + '\n'; // <pstyle:lesson_header>
        }
        else if (textArray[idx].includes('<pstyle:key_text>')) {
            let pos = textArray[idx].indexOf(':', '<pstyle:key_text>'.length);
            if (pos > -1) {
                newText += textArray[idx].substring(0, '<pstyle:key_text>'.length) + '<cstyle:memory verse>' // <pstyle:key_text><cstyle:memory verse>
                        + textArray[idx].substring('<pstyle:key_text>'.length, pos + 1) + '<cstyle:>'   // MEMORY VERSE:
                        + textArray[idx].substring(pos + 1) + '\n'; 
            }
            else {
                newText += textArray[idx] + '\n'; 
            }
        }
        else if (textArray[idx].includes('<pstyle:key_note>') && textArray[idx+1].includes('<pstyle:reading>')) {
            tempText = textArray[idx] + '\n'; 
            idx += 1;
            while (idx < textArray.length && !textArray[idx].includes('<pstyle:day')) {
                if (textArray[idx].includes('<pstyle:reading>')) {
                    newText += textArray[idx].replace('<pstyle:reading>', '<pstyle:reading><cstyle:memory verse>') + '\n';
                }
                else {
                    newText += textArray[idx] + '\n';
                }
                idx++;
            }
            newText += tempText;
            if (textArray[idx].includes('<pstyle:day')) {
                idx--;
            }
        }
        else if (textArray[idx].includes('<pstyle:day') && textArray[idx+1].includes('<pstyle:subtitle>')) {
            tempText = textArray[idx];
            tempText = tempText.replaceAll(/<cstyle:[^>]*>/g, "").replaceAll(/\s+/g, " ");
            tempText = tempText.replace(/<pstyle:day[^>]*>/g, "\t<cstyle:date>");
            tempText = textArray[++idx] + tempText + '\n';   // '<pstyle:subtitle>' + '<cstyle:date>'
            newText += tempText;
        }
        else if (textArray[idx].includes('<pstyle:subtitle') && textArray[idx+1].includes('<pstyle:day')) {
            tempText = textArray[++idx];
            tempText = tempText.replaceAll(/<cstyle:[^>]*>/g, "").replaceAll(/\s+/g, " ");
            tempText = tempText.replace(/<pstyle:day[^>]*>/g, "\t<cstyle:date>");
            tempText = textArray[idx-1] + tempText + '\n';   // '<pstyle:subtitle>' + '<cstyle:date>'
            newText += tempText;
        }
        else if (textArray[idx].includes('<pstyle:bible_text') && textArray[idx+1].includes('<pstyle:ref_parag')) {
            newText += textArray[idx] + '\n<pstyle:ref_parag_2>\n';
        }
        else {
            newText += textArray[idx] + '\n';
        }
    }
    newText = newText
            .replaceAll(/<pstyle:space>\s*\n*/g, "")
            .replaceAll("cstyle:lesson_header", "cstyle:Lesson_#")
            .replaceAll("pstyle:sabbath_date", "pstyle:Lesson_date")
            .replace("pstyle:title", "pstyle:foreword")  // replace first title as foreword
            .replaceAll("pstyle:title", "pstyle:Lesson_title")
            .replaceAll("pstyle:key_text", "pstyle:key_verse")
            .replaceAll("<pstyle:key_note>", "<pstyle:ref_parag_2>\n<pstyle:key_note>")
            .replaceAll("<pstyle:reading>", "<pstyle:ref_parag_2>\n<pstyle:Suggested reading>")
            // .replaceAll("<cstyle:date>", "<pstyle:date><cstyle:date>")
            .replaceAll("<pstyle:subtitle>1", "<pstyle:ref_parag_2>\n<pstyle:ref_parag_2>\n<pstyle:ref_parag_3>@@\n<pstyle:subtitle>1")
            // .replaceAll(/<pstyle:subtitle>([^\d])/g, "<pstyle:ref_parag_2>\n<pstyle:ref_parag_2>\n<pstyle:ref_parag_3>@@\n<pstyle:subtitle>$1")
            .replaceAll("<pstyle:subtitle>2", "<pstyle:newpage>@@\n<pstyle:subtitle>2")
            .replaceAll("<pstyle:subtitle>3", "<pstyle:newpage>@@\n<pstyle:subtitle>3")
            .replaceAll("<pstyle:subtitle>4", "<pstyle:newpage>@@\n<pstyle:subtitle>4")
            .replaceAll("<pstyle:subtitle>5", "<pstyle:newpage>@@\n<pstyle:subtitle>5")
            .replaceAll("pstyle:day_a", "pstyle:day")
            .replaceAll("<pstyle:question>a", "<pstyle:Question>a")
            .replaceAll("<pstyle:question>b", "<pstyle:ref_parag_2>\n<pstyle:Question>b")
            .replaceAll("<pstyle:question>c", "<pstyle:ref_parag_2>\n<pstyle:Question>c")
            .replaceAll("<pstyle:question>d", "<pstyle:ref_parag_2>\n<pstyle:Question>d")
            .replaceAll("<pstyle:question>e", "<pstyle:ref_parag_2>\n<pstyle:Question>e")
            .replaceAll("<pstyle:question>f", "<pstyle:ref_parag_2>\n<pstyle:Question>f")
            .replaceAll("<pstyle:question>g", "<pstyle:ref_parag_2>\n<pstyle:Question>g")
            .replaceAll("pstyle:sub_question", "pstyle:Subquestion")
            .replaceAll("<pstyle:answer_line>", "<pstyle:answer_line>\n<pstyle:answer_line>\n<pstyle:ref_parag_2>")
            .replaceAll("pstyle:review_question", "pstyle:Review")
            // .replaceAll(/(<[^_]+)_([^>]+>)/g, "$1\\_$2")
            ;

    return newText;
}


function toIndesignTagForRmrh()
{
    var taggedText = "";
    var parag = document.getElementsByTagName('book').item(0).cloneNode(true).firstElementChild; //$("book :first-child");//getNextEditable($("book-title"));
    var lastTag = "";
    var text = '';
    var tag = '';

    //remove un-visiable elements
    // var notes = parag.parentElement.getElementsByClassName('note');
    // for (let idx = 0; idx < notes.length; idx++)
    //     notes[idx].innerHTML = '';

    while (parag) {
        tag = parag.tagName.toLowerCase();
        if (mode == 0) {// translator 
            let el = parag.getElementsByClassName('target-text');
            if (el.length)
                text = el.item(0).innerHTML;
            else {
                parag = nextTextElement(parag);
                continue;
            }
        }
        else {  // reader mode
            if (tag == 'p') {
                text = parag.innerHTML;
                text = text.replace(/<\/?[^>]+>/g, '');
                text = text.replace(/\s{2,}/g, ' ');
            }
            else {
                text = parag.innerText;
            }
        }
        text = text.replace(/</g, "\\<").replace(/>/g, "\\>").replace(/\&amp;/g, "&").replace(/\&nbsp;/g, "\u00A0");  // escape special character

        let parentTag = parag.parentElement.tagName.toLowerCase();
        if (tag == "title-tag") {
            if (parentTag == 'book')
                taggedText += "<ParaStyle:book\_title>" + text + "\n";
            else if (parentTag == 'article')
                taggedText += "<ParaStyle:space>\n<ParaStyle:title>" + text + "\n";
            else if (parentTag == 'sect1')
                taggedText += "<ParaStyle:space>\n<ParaStyle:subtitle\_1>" + text + "\n";
            else if (parentTag == 'sect2')
                taggedText += "<ParaStyle:space>\n<ParaStyle:subtitle\_2>" + text + "\n";
            else if (parentTag == 'sect3')
                taggedText += "<ParaStyle:space>\n<ParaStyle:subtitle\_3>" + text + "\n";
            else if (parentTag == 'bibliography')
                taggedText += "<ParaStyle:Reference\_lable>" + text + "\n";
            else if (parentTag == 'biblioentry') {
                let xrefLable = parag.parentElement.getAttribute("xreflabel");
                text = book_link_find(text);
                text = text.replaceAll(/<\/?span[^>]*>/gi, "");
                text = text.replaceAll(/<((em)|(i))>/gi, "<cTypeface:Italic>").replaceAll(/<(\/(em)|(i))>/gi, "<cTypeface:Regular>");
                taggedText += "<ParaStyle:Reference\_title><CharStyle:cite\_num>" + xrefLable + "<CharStyle:> " + text + "\n";
            }
            else
                taggedText += "<ParaStyle:title>" + text + "\n";
        } 
        else if (tag == "subtitle") {
            taggedText += "<ParaStyle:book\_subtitle>" + text + "\n";
        } 
        else if (tag == "keyword") {
            taggedText += "<ParaStyle:keyword>" + text + "\n";
        }
        else if (tag == "firstname") {
            taggedText += "<ParaStyle:person\_name>" + text + " ";
        }
        else if (tag == "othername") {
            taggedText += text + " ";
        }
        else if (tag == "surname") {
            taggedText += text + "\n";
        }
        else if (tag == "para") {
            if (lastTag == "firstname" || lastTag == "othername") {
                taggedText += "\n";
            }
            text = parag.getElementsByClassName('target-text').item(0).innerHTML;
            text = book_link_find(text);
            text = text.replaceAll(/<\/?span[^>]*>/gi, "");
            // convert inner styles
            text = text.replaceAll(/<citation>([^<]+)<\/citation>/gi, "@%CharStyle:citation%@$1@%CharStyle:%@");
            text = text.replaceAll(/<emphasis role="cap"( class[^>]*)?>([^<]+)<\/emphasis>/gi, "@%CharStyle:drop\_cap%@$2@%CharStyle:%@");
            text = text.replaceAll(/<emphasis role="([^"]+)"( class[^>]*)?>/gi, "@%cTypeface:$1%@").replaceAll(/<\/emphasis>/gi, "@%cTypeface:Regular%@");
            text = text.replaceAll(/<b>/gi, "@%cTypeface:Bold%@").replaceAll(/<(\/?b[^>]*)>/gi, "@%cTypeface:Regular%@");
            text = text.replaceAll(/<((em)|(i))>/gi, "@%cTypeface:Italic%@").replaceAll(/<(\/(em)|(i))>/gi, "@%cTypeface:Regular%@");
            text = text.replaceAll(/</g, "\\<").replaceAll(/>/g, "\\>").replaceAll(/\&amp;/g, "&").replaceAll(/\&nbsp;/g, "\u00A0");  // escape special character
            text = text.replaceAll("@%", "<").replaceAll("%@", ">");
            if (parentTag == 'annotation')
                taggedText += "<ParaStyle:annotation>" + text + "\n";
            else if (parentTag == 'abstract')
                taggedText += "<ParaStyle:abstract>" + text + "\n";
            else
                taggedText += "<ParaStyle:para>" + text + "\n";
        } 
        // else if (tag == "keyword") {
        //     taggedText += "<ParaStyle:pkeywordara>" + text + "\n";
        // }
        
        lastTag = tag;
        parag = nextTextElement(parag);
    }

    return taggedText;
}


/*
 * Download InDesign tagged text
 *
 * @param
 * @returns 
*/
function exportSblIndesignTaggedText(version)
{
    taggedText = toIndesignTagForSbl();
    if (version == 2) {
        taggedText = convertSblIdttVersion2(taggedText);
    }
    // convert 2 byte code to ASCII
    var len = taggedText.length;
    var asciiText = "<ASCII-MAC>\n";

    var code;
    var hex;
    for (i = 0; i < len; i++) {
        code = taggedText.codePointAt(i);
        if (code <= 0x7f)
            asciiText += taggedText.charAt(i);
        else {
            hex = code.toString(16).toUpperCase();
            if (hex.length == 2)
                asciiText += "<0x00" + hex + ">";
            else if (hex.length == 3)
                asciiText += "<0x0" + hex + ">";
            else
                asciiText += "<0x" + hex + ">";
        }
    }

    if (location.href.indexOf('/reader/show/') > 0) {
        download(asciiText, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + "_IndesignTaggedText_Reader.txt", "text/plain;charset=utf-8");
    }
    else {
        download(asciiText, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + "_IndesignTaggedText.txt", "text/plain;charset=utf-8");
    }
}

function exportRmrhIndesignTaggedText()
{
    taggedText = toIndesignTagForRmrh();
    // convert 2 byte code to ASCII
    var len = taggedText.length;
    var asciiText = "<ASCII-MAC>\n";

    var code;
    var hex;
    for (i = 0; i < len; i++) {
        code = taggedText.codePointAt(i);
        if (code <= 0x7f)
            asciiText += taggedText.charAt(i);
        else {
            hex = code.toString(16).toUpperCase();
            if (hex.length == 2)
                asciiText += "<0x00" + hex + ">";
            else if (hex.length == 3)
                asciiText += "<0x0" + hex + ">";
            else
                asciiText += "<0x" + hex + ">";
        }
    }
    if (location.href.indexOf('/reader/show/') > 0) {
        download(asciiText, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + "_IndesignTaggedText_Reader.txt", "text/plain;charset=utf-8");
    }
    else {
        download(asciiText, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + "_IndesignTaggedText.txt", "text/plain;charset=utf-8");
    }
}

/*
    * Escape character for RTF
    */
function encodeRtf(text)
{
    // escape text to ASCII
    var len = text.length;
    var asciiText = ""; //"\\uc0 ";
    var code;
    for (i = 0; i < len; i++) {
        code = text.codePointAt(i);  //non-negative integer
        if (text.charAt(i) == '{' || text.charAt(i) == '}' || text.charAt(i) == '\\')
            asciiText += "\\'" + code.toString(16) + "";
        else if (code < 0x80)
            asciiText += text.charAt(i);
        else if (code >= 0x80 && code <= 0xFF){
            asciiText += "\\'" + code.toString(16) + "";
        }else{ // code > 0xFF
            asciiText += "\\u" + code.toString() + " ";
            //asciiText += "\\uc2\\u" + code.toString() + " ";
            //asciiText += "\\'" + ((code >> 8) & 0xFF).toString(16) + ""; // only works with codepage settings
            //asciiText += "\\'" + (code & 0xFF).toString(16) + "\\uc1 "; // only works with codepage settings
            //asciiText += "??";  // for old RTF, can be omitted by '\uc0'
        }
    }
    asciiText = asciiText.replaceAll(/<(0x[^>]{2,4})>/g, function(i, p1) { 
        return "\\u" + Number(p1) + ' '; 
    });
    return asciiText;    
}
    
/*
    * Download RTF text for SBL
    *
    * @param
    * @returns 
    */
function exportSblRtfText()
{
    taggedText = toIndesignTagForSbl();
    taggedText = convertSblIdttVersion2(taggedText);
    
    taggedText = encodeRtf(taggedText);
    //taggedText = taggedText.replaceAll(/[\{\}\u0080-\u00FF]/gm, function(i) { return '\\' + i.codePointAt(0).toString(16); });

    // Indesign tag to rtf
    taggedText = taggedText.replaceAll(/<cstyle:bible-ref>/gm, rtfProp.cs_bible_ref);
    taggedText = taggedText.replaceAll(/<cstyle:verse_no>([^<]+)/gm, "{" + rtfProp.cs_verse_no + "$1}");
    taggedText = taggedText.replaceAll(/<cstyle:verse>/gm, rtfProp.cs_verse);
    taggedText = taggedText.replaceAll(/<pstyle:bible-text>/gm, rtfProp.ps_bible_ref);
    
    taggedText = taggedText.replaceAll(/<cstyle:em>([^<]*)<cstyle:>/gm, "{" + rtfProp.cs_em + "$1}");
    taggedText = taggedText.replaceAll(/<cstyle:bible_source>([^<]+)<cstyle:>/gm, "{" + rtfProp.cs_bible_source + "$1}");
    taggedText = taggedText.replaceAll(/<cstyle:reference_book>([^<\n]+)/gm, "{" + rtfProp.cs_reference_book + "$1}");
    taggedText = taggedText.replaceAll(/<cstyle:reference_book_ibid>([^<\n]+)/gm, "{" + rtfProp.cs_reference_book_ibid + "$1}");
    taggedText = taggedText.replaceAll(/<cstyle:reference_page>(.+)$/gm, "{" + rtfProp.cs_reference_page + "$1}");

    taggedText = taggedText.replaceAll(/<pstyle:space>/gm, rtfProp.ps_space);
    taggedText = taggedText.replaceAll(/<pstyle:book-title>/gm, rtfProp.ps_title);
    taggedText = taggedText.replaceAll(/<pstyle:foreword>/gm, rtfProp.ps_title + "\\pagebb ");
    taggedText = taggedText.replaceAll(/<pstyle:paragraph>/gm, rtfProp.ps_parag);
    taggedText = taggedText.replaceAll(/<pstyle:writer>/gm, rtfProp.ps_writer);

    taggedText = taggedText.replaceAll(/<pstyle:fso_date>/gm, rtfProp.ps_fso_date);
    taggedText = taggedText.replaceAll(/<pstyle:fso_title>/gm, rtfProp.ps_fso_title);
    taggedText = taggedText.replaceAll(/<pstyle:fso_subtitle>/gm, rtfProp.ps_fso_subtitle);
    taggedText = taggedText.replaceAll(/<pstyle:fso_paragraph>/gm, rtfProp.ps_fso_paragraph);
    taggedText = taggedText.replaceAll(/<pstyle:fso_writer>/gm, rtfProp.ps_fso_writer);
    
    taggedText = taggedText.replaceAll(/<cstyle:Lesson_#>(.+)$/gm, "{" + rtfProp.cs_lesson_header + "$1}");
    taggedText = taggedText.replaceAll(/<pstyle:Lesson_date>/gm, rtfProp.ps_sabbath_date);
    taggedText = taggedText.replaceAll(/<pstyle:Lesson_title>/gm, rtfProp.ps_title);
    taggedText = taggedText.replaceAll(/<pstyle:key_verse>/gm, rtfProp.ps_key_text);
    taggedText = taggedText.replaceAll(/<pstyle:key_note>/gm, rtfProp.ps_key_note);
    taggedText = taggedText.replaceAll(/<pstyle:Suggested reading>/gm, rtfProp.ps_reading);
    
    taggedText = taggedText.replaceAll(/<cstyle:reading>([^<\n]+)/gm, "{" + rtfProp.cs_reading + "$1}");
    taggedText = taggedText.replaceAll(/<cstyle:reading_ibid>([^<\n]+)/gm, "{" + rtfProp.cs_reading_ibid + "$1}");
    taggedText = taggedText.replaceAll(/<cstyle:reference_page_a>(.+)$/gm, "{" + rtfProp.cs_reference_page_a + "$1}");

    taggedText = taggedText.replaceAll(/<pstyle:day>/gm, rtfProp.ps_day);
    taggedText = taggedText.replaceAll(/<pstyle:day_a>/gm, rtfProp.ps_day_a);
    
    taggedText = taggedText.replaceAll(/<cstyle:day>/gm, "{" + rtfProp.cs_day);
    taggedText = taggedText.replaceAll(/<cstyle:date>(.+)$/gm, "{" + rtfProp.cs_date + "$1}");

    taggedText = taggedText.replaceAll(/<pstyle:subtitle>(1\.)\s*/gm, rtfProp.ps_subtitle + "$1 ");
    taggedText = taggedText.replaceAll(/<pstyle:subtitle>([2-5]\.)\s*/gm, rtfProp.ps_subtitle_pb + "$1 ");
    taggedText = taggedText.replaceAll(/<pstyle:subtitle>/gm, rtfProp.ps_subtitle);     // PERSONAL REVIEW QUESTIONS ...


    taggedText = taggedText.replaceAll(/<pstyle:Question>/gm, rtfProp.ps_question);
    taggedText = taggedText.replaceAll(/<pstyle:Subquestion>/gm, rtfProp.ps_sub_question);
    taggedText = taggedText.replaceAll(/<pstyle:answer_line>\n<pstyle:answer_line>/gm, rtfProp.ps_answer_line);
    taggedText = taggedText.replaceAll(/<pstyle:ref_parag>/gm, rtfProp.ps_ref_parag);
    taggedText = taggedText.replaceAll(/<pstyle:Review>/gm, rtfProp.ps_review_question);

    // ect
    taggedText = taggedText.replace(/<pstyle:ref_parag_\d>@*/gm, rtfProp.ps_etc);
    taggedText = taggedText.replaceAll(/<pstyle:newpage>@@/gm, "");
    taggedText = taggedText.replaceAll(/<pstyle:answer_line>/gm, "");
    taggedText = taggedText.replaceAll(/<pstyle:[^>]*>/gm, rtfProp.ps_etc);
    taggedText = taggedText.replaceAll(/<cstyle:[^>]*>([^<\n]+)/gm, "{" + rtfProp.cs_etc + "$1}");

    taggedText = taggedText.replaceAll(/\\'5c<span[^>]*\\'5c>/gm, "");
    taggedText = taggedText.replaceAll(/\\'5c<\/span\\'5c>/gm, "");
    
    taggedText = taggedText.replaceAll(/\n/gm, rtfProp.ps_suffix);
    
    taggedText = taggedText.replaceAll(/\\</gm, "<").replace(/\\>/gm, ">").replace(/\&lt;/gm, "<").replace(/\&gt;/gm, ">");

    // error checking - used for debugging.
    if (0) {
        let text = taggedText.split("\n")
        var count = 0
        let modified = false
        text.forEach((line, lineIndex, theTextArray) => {
            count = 0
            let index
            for (index  = 0; index < line.length; index++) {
                if (line.charAt(index) == '{') count++
                else if (line.charAt(index) == '}') count--;
            }
            if (count != 0) {
                console.log("RTF converter error: line:" + lineIndex + " column:" + index)
                while (count > 0) {
                    theTextArray[lineIndex] += '}'
                    count--
                }
                while (count < 0) {
                    theTextArray[lineIndex] = '{' + theTextArray[lineIndex]
                }
                modified = true
            }
        })
        if (modified)
            taggedText = text.join("\n")
    }
    if (location.href.indexOf('/reader/show/') > 0) {
        download(rtfProp.header.replace(/^\s+/g, '') + taggedText + rtfProp.footer, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + "_Reader.rtf", "text/plain;charset=utf-8");
    }
    else {
        download(rtfProp.header.replace(/^\s+/g, '') + taggedText + rtfProp.footer, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + ".rtf", "text/plain;charset=utf-8");
    }
}

/*
    * Download RTF text for RMRH, YM
    *
    * @param
    * @returns 
    */
function exportRmrhRtfText()
{
    var rtfText = "";
    var state = '';

    traverseBook(document.getElementsByTagName('book')[0]);

    function traverseBook(element) 
    {
        for (let childNode of element.childNodes) {
            if (childNode.nodeType == 1) {
                if (childNode.className.indexOf("source-text") > -1 || childNode.className.indexOf("idLable") > -1) {
                    continue;
                } if (childNode.tagName == "PERSONNAME") {
                    state = childNode.parentElement.tagName; 
                    addParagControls(childNode);
                    let names = childNode.getElementsByClassName("target-text");
                    for (let index = 0; index < names.length; index ++) {
                        if (index > 0)
                            rtfText += " ";
                        rtfText += names.item(index).innerText;
                    }
                    rtfText += rtfProp.ps_suffix;
                    continue;
                } else if (childNode.tagName == "SPAN" && childNode.className.indexOf("target-text") > -1) {
                    addParagControls(childNode);
                } else if (childNode.tagName == "EMPHASIS") {
                    addCharacterControls(childNode); 
                } else if (childNode.tagName == "TITLE-TAG" || childNode.tagName == "PARA" || childNode.tagName == "PERSONNAME") {
                    state = childNode.parentElement.tagName; 
                } else {
                    state = ""; 
                }
                traverseBook(childNode);

                if (childNode.tagName == "SPAN" && childNode.className.indexOf("target-text") > -1) {
                    rtfText += rtfProp.ps_suffix;
                } else if (childNode.tagName == "EMPHASIS") {
                    rtfText += rtfProp.cs_suffix;
                }
            }
            else if (childNode.nodeType == 3) {  // text node
                if (childNode.parentElement.className.indexOf("target-text") < 0 && childNode.parentElement.tagName.indexOf("EMPHASIS") < 0) {
                    continue;
                }
                rtfText += encodeRtf(childNode.nodeValue);
            }
        }
    }

    function addParagControls(element) {
        if (element.parentElement.tagName == "TITLE-TAG") {
            if (state == "BIBLIOGRAPHY") rtfText += rtfProp.ps_title_bibliograpy;
            else if (state == "BIBLIOENTRY") rtfText += rtfProp.ps_title_biblioentry;
            else if (state == "SECT3") rtfText += rtfProp.ps_title_sect3;
            else if (state == "SECT2") rtfText += rtfProp.ps_title_sect2;
            else if (state == "SECT1") rtfText += rtfProp.ps_title_sect1;
            else rtfText += rtfProp.ps_title;
        }
        else if (element.parentElement.tagName == "KEYWORD") {
            rtfText += rtfProp.ps_keyword;
        } else if (element.parentElement.tagName == "PARA") {
            if(state == "ANNOTATION") {
                rtfText += rtfProp.ps_para_annotation;
            } else if(state == "ABSTRACT") {
                rtfText += rtfProp.ps_para_abstract;
            } else if(state == "EPIGRAPH") {
                rtfText += rtfProp.ps_para_epigraph;
            } else {
                rtfText += rtfProp.ps_para;
            }
        } else if (state == "AUTHOR") {
            rtfText += rtfProp.ps_auther;
            state = "PERSONNAME";  // dummy value for children element to eliminate the controls
        } else if (element.parentElement.tagName == "BIBLIOENTRY") {
            rtfText += rtfProp.ps_biblioentry;
        } else if (element.parentElement.tagName == "FIRSTNAME" || element.parentElement.tagName == "OTHERNAME" || element.parentElement.tagName == "SURNAME") {
            rtfText += ""; // processed at "AUTHER"
        } else { 
            rtfText += rtfProp.ps_para;
        }
    } 

    function addCharacterControls(element) {
        rtfText += rtfProp.cs_prefix;
        if (element.className.includes('cap')) rtfText += rtfProp.cs_cap;
        else if (element.className.includes('citation')) rtfText += rtfProp.cs_citation;
        if (element.className.includes('bold')) rtfText += "\\b ";
        if (element.className.includes('italic')) rtfText += "\\i ";
    }

    if (location.href.indexOf('/reader/show/') > 0) {
        download(rtfProp.header + rtfText + rtfProp.footer, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + "_Reader.rtf", "text/plain;charset=utf-8");
    }
    else {
        download(rtfProp.header + rtfText + rtfProp.footer, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + ".rtf", "text/plain;charset=utf-8");
    }
}


/*
    * helper functions
    */
function XMLDocErrString(xml)
{
    try {
        if (window.DOMParser) {  // ff & chrome
            parser = new DOMParser();
            xmlDoc = parser.parseFromString(xml, "text/xml");
            if (xmlDoc.documentElement.firstChild.localName == "parsererror") // chrome
                return xmlDoc.documentElement.firstChild.innerText;
            else if (xmlDoc.documentElement.localName == "parsererror")  // firefox
                return xmlDoc.documentElement.firstChild.nodeValue;
        } else {  // code for IE
            xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
            xmlDoc.async = false;
            xmlDoc.loadXML(xml);
            if (xmlDoc.parseError.errorCode != 0) {
                return "Error in line " + xmlDoc.parseError.line +
                        " position " + xmlDoc.parseError.linePos +
                        "\nError Code: " + xmlDoc.parseError.errorCode +
                        "\nError Reason: " + xmlDoc.parseError.reason +
                        "Error Line: " + xmlDoc.parseError.srcText;
            }
        }
    } catch (e) {
        return e.message
    }
    return "";
}


function download(content, filename, contentType)
{
    if (!contentType)
        contentType = 'application/octet-stream';
    var a = document.createElement('a');
    //var blob = new Blob([new Uint8Array([0xEF, 0xBB, 0xBF]), content], {'type':contentType});
    var blob = new Blob([content], {'type': contentType});

    if (typeof (window.navigator.msSaveBlob) !== "undefined")
        window.navigator.msSaveBlob(blob, filename);  // MSIE10 
    else {
        var url = window.URL.createObjectURL(blob);
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        setTimeout(function () {  // Firefox
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }, 100);
    }
}


function downloadTemplate()
{
    if (navigator.onLine) {
        $('<form></form>').attr('action', settings.indesign_template)
                .appendTo('body').submit().remove();
    } else
        alert("Please connect to internet to download!!!")
}


/*
* function book_link_find()
*
* Insert book link tag for given text.
*   ex) Christ’s Object Lessons,</em> p. 414 =>
*       <span class="source-link selected" title="COL/414"><em>Christ’s Object Lessons,</em> p. 414</span>
*
* This is ported from 'BookLinkFinder.php'
*/

function book_link_find(text) {
    if (!bookProp || bookProp.length == 0)
        return text;
    var bookMatches = [];
    let linkTemplate = "<span class='source-link source-link-book' title='@1@'><em>@2@</em>@3@</span>";
    let linkTemplatePage = ", <span class='source-link source-link-book' title='@1@'>@3@</span>";

    for (abbr in bookProp) {
        const pattern = bookProp[abbr];
        let expPtn = pattern[3].match(new RegExp('^/(.*?)/([gimy]*)$'));
        let regex = new RegExp(expPtn[1], expPtn[2] + 'gd');
        let match;
        let replaceText = '';
        while ((match = regex.exec(text)) !== null) {
            let lastMatchIndex = match.length - 1;
            // convert all matches to variable (var mN)
            // for (let keyNumeric = 1; keyNumeric <= lastMatchIndex; keyNumeric++) { // replace numeric key to 'm$key' for 'extract()'
            //     eval('var m' + keyNumeric + ' = "' + match[keyNumeric] + '";');
            // }
            try {
                if (pattern[0] === 0) { // page base link
                    let pages = match[lastMatchIndex].split(', ');
                    for (let idx = 0; idx < pages.length; idx++) {
                        let mm = pages[idx].match(/(\d+)[^\d]+(\d+)/);
                        if (mm) // replace page range charactor ('1-2')
                            match[lastMatchIndex] = mmm[1] + '-' + mmm[2];
                        else 
                            match[lastMatchIndex] = pages[idx];
                        if (idx === 0) {  // page with full book name
                            linkTemplate = linkTemplate.replace('@1@', pattern[4]);
                            linkTemplate = linkTemplate.replace('@2@', pattern[5]);
                            linkTemplate = linkTemplate.replace('@3@', pattern[6].split(',')[1]);
                        }
                        else {  // page only part
                            linkTemplate = linkTemplatePage.replace('@1@', pattern[4]);
                            linkTemplate = linkTemplate.replace('@3@', match[lastMatchIndex]);
                        }
                        let evalStr = "replaceText += `" + linkTemplate + "`";
                        eval(evalStr.replaceAll(/\$(\d{1,2})/g, '${match[$1]}'));
                    }
                }
                else {  // periodical link
                    linkTemplate = linkTemplate.replace('@1@', pattern[4]);
                    linkTemplate = linkTemplate.replace('@2@', pattern[5]);
                    linkTemplate = linkTemplate.replace('@3@', pattern[6].split(',')[1]);
                    
                    let evalStr = "replaceText = `" + linkTemplate + "`";
                    eval(evalStr.replaceAll(/\$(\d{1,2})/g, '${match[$1]}'));
                    // if (is_numeric($newMatches['m'.strval(count($newMatches)-2)]) && is_numeric($newMatches['m'.strval(count($newMatches)-1)]) // Month to Number
                    //         && langProp[settings.lang] && langProp[settings.lang]->monthToNumber) {
                    //     $replaceText = str_replace(langProp[settings.lang]->monthToNumber[0], langProp[settings.lang]->monthToNumber[1], $replaceText);
                    // }
                }
            } catch (e) {
                throw e;
            }
            text = text.substr(0, match.indices[0][0]) + replaceText + text.substr(match.indices[0][1] + match[0].length);
            let offset = match.indices[0][0] + replaceText.length;
            for (aBookMatche of bookMatches) {
                let offsetIncresed = replaceText.length - match[0].length;
                if ( aBookMatche[1] > offset)
                    aBookMatche[1] += offsetIncresed;
            }
            bookMatches.push([replaceText, offset]);
            found = 1;
        }
    }
    text = text.replaceAll(/(Ibid\.,?)/gi, "<em>$1</em>");  // TEMPORARY IMPLEMENTATION!!!
    return text;

    // TODO: 'Ibid.' match is not yet implemented.

//     usort(bookMatches, function($a, $b) {
//         if ($a[1] == $b[1]) return 0;
//         return ($a[1] < $b[1]) ? -1 : 1;;
//     });

//     /*
//      * Ibid ... 
//      */
//     if (lang == 'en')
//         $ibidPattern = '(Ibid\.?)';
//     else
//         $ibidPattern = '(@@@)';
//     $offset = 0;
//     $offsetIncresed = 0;
//     while (preg_match("/$ibidPattern(, (((p+\.|page) ([\d–\-, ]+))|((vol\.|book|bk\.) (\d+)(, p+\. )([\d–\-, ]+))|(([A-Z][a-z]+) (\d+), (\d{4}))))?/", $text, $matches, PREG_OFFSET_CAPTURE, $offset) === 1) {
//         for ($key = count(bookMatches)-1; $key >= 0; $key--) { // find preceding match
//             if ( (bookMatches[$key][1]) > $matches[0][1] - $offsetIncresed)
//                 continue;

//             if (preg_match("/title='([^']+)'/", bookMatches[$key][0], $matchPath)) { // find preceding match
//                 if (count($matches) == 2) {  // Ibid.
//                     $ibidReplaceText = "<span class='source-link' title='" . $matchPath[1] . "'><em>Ibid.</em></span>";
//                 }
//                 else if (count($matches) == 7) {  // Ibid., p. 264.
//                     if (strncmp($matchPath[1], "EGWBC", 5) == 0) {
//                         $abbr = 'EGWBC/' . explode('/', $matchPath[1])[1];
//                     } else {
//                         $abbr =  explode('/', $matchPath[1])[0];
//                     }
//                     $pages = explode(', ', $matches[6][0]);
//                     for ($idx = 0; $idx < count($pages); $idx ++) {
//                         if ($idx == 0) 
//                             $ibidReplaceText = "<span class='source-link' title='" . $abbr . "/" . $pages[$idx] . "'><em>Ibid.,</em> " . $matches[5][0] . $pages[$idx] . "</span>";
//                         else
//                             $ibidReplaceText .= ", <span class='source-link' title='" . $abbr . "/" . $pages[$idx] . "'>" . $pages[$idx] . "</span>";
//                     }
//                 }
//                 elseif (count($matches) == 12) {  // Ibid., bk. 2, p. 52.
//                     preg_match("/\d+([A-Za-z]+)/", $matchPath[1], $matchesAbbr);
//                     if(empty($matchesAbbr)) // no previous book match found.
//                         $ibidReplaceText = $matches[0][0];
//                     else if (strncmp($matchPath[1], "EGWBC", 5) == 0)
//                         $abbr = 'EGWBC/' . $matches[9][0] . $matchesAbbr[1];
//                     else {
//                         $abbr = $matches[9][0] . $matchesAbbr[1];
//                     }
//                     $pages = explode(', ', $matches[11][0]);
//                     for ($idx = 0; $idx < count($pages); $idx ++) {
//                         if ($idx == 0) 
//                             $ibidReplaceText = "<span class='source-link' title='" . $abbr . "/" . $pages[$idx]
//                                 . "'><em>Ibid.,</em> " . $matches[8][0] . $matches[9][0] . $matches[10][0] . $pages[$idx] . "</span>";
//                         else
//                             $ibidReplaceText .= ", <span class='source-link' title='" . $abbr . "/" . $pages[$idx]
//                                 . "'>" . $pages[$idx] . "</span>";
//                     }
//                 }
//                 else if (count($matches) > 15) {  // Ibid., May 28, 1889.
//                     $ibidReplaceText = "<span class='source-link' title='" . explode('/', $matchPath[1])[0] . "/" . $matches[15][0] . "/" . $matches[13][0] . "/" . $matches[14][0]
//                                 . "'><em>Ibid.,</em> " . $matches[3][0] . "</span>";
//                     $ibidReplaceText = str_replace(langProp[settings.lang]->monthToNumber[0], langProp[settings.lang]->monthToNumber[1], $ibidReplaceText);
//                 }
//                 else {
//                     continue;
//                 }
//                 $text = substr($text, 0, $matches[0][1]) . $ibidReplaceText . substr($text, $matches[0][1] + strlen($matches[0][0])); 
//                 $offsetIncresed += strlen($ibidReplaceText) - strlen($matches[0][0]); 
//                 $offset = $matches[0][1] + strlen($ibidReplaceText);
//                 array_splice( bookMatches, $key+1, 0, [array($ibidReplaceText, $offset - $offsetIncresed)] );
//             }
//             else { // no previous book match found.
//                 $offset = $matches[0][1] + strlen($matches[0][0]);
//             }
//             break;
//         }
//         if ($offset == 0) // no previous book match found.
//             break;
//     }
//     if (count(bookMatches)) // leave last match for continuous search 'Ibid.'
//         bookMatches = array(array(bookMatches[count(bookMatches)-1][0], 0));
}
