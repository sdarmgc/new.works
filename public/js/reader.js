/*
* reader.js
*
* 
*/

 $(function ()
{
    /*
    * CSRF checking
    */
    if (navigator.onLine) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    /*
     * menu
     */
    $(".menu-item").click(function () {
        item = $(this).prop("id");
        if (item == "menu-xml") {
            toText(false, true);
        }
        else if (item == "menu-rtf") {
            if (settings.book == 'sbl' || settings.book == 'sblpab')
                exportSblRtfText();
            else
                exportRmrhRtfText();
        }
        else if (item == "menu-indesign-text") {
            if (settings.book == 'sbl' || settings.book == 'sblpab')
                exportSblIndesignTaggedText();
            else
                alert("InDesign Tagged Text is only available for SBL.");
        }
        else if (item == "menu-indesign-text-v2") {
            if (settings.book == 'sbl' || settings.book == 'sblpab')
                exportSblIndesignTaggedText(2);
            else
                alert("InDesign Tagged Text is only available for SBL.");
        }
        else if (item == "menu-edit-prop")
            editProperty();
        else if (item == "menu-edit-book-names")
            editBookNames();
    });


    /*
     * 
     * @param {boolean} isText
     * @returns {undefined}
     */
    function toText(isText, toDownload = true)
    {
        var newXML = $('book').clone(false);

        newXML.find("*").removeAttr("class"); // remove all display attrs

        if (isText)
            window.open("", "", "scrollbars=1").document.write(newXML.text());
        else {
            var xml = '<?xml version="1.0" encoding="UTF-8"?>' + newXML.html();
            xml = xml.replace(/\&nbsp;/g, "\u00A0"); // xml does not support '&nbsp;'
            if (toDownload)
                download(xml, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + ".xml", "text/plain;charset=utf-8");
            else
                return xml;
        }
    }


    /*
     * Go-to functions
     */
    
    /*
    * current scroll position
    */
    prevScrollPos = 0;
    scrollPos = 0;
    headerHeight = $(".header-fixed").height();
    $(window).scroll(function () {
        scrollPos = $(window).scrollTop();
        if (Math.abs(scrollPos - prevScrollPos) < 20)
            return;

        rect = null;
        prevElement = document.getElementsByTagName('book').item(0);
        if (settings.book == 'sbl') {
            lessonPage = 2;
            function setPosIndicatior() {
                rect = this.getBoundingClientRect();
                if (rect.top - 30 > headerHeight) {
                    // console.log("SCROLL", rect.top, prevElement.tagName, this.tagName, lessonPage)
                    if (prevElement.tagName === 'FOREWORD') {
                        $("#cur-pos-indicator").text("FOREWORD -  p." + parseInt(lessonPage));
                    }
                    else if (prevElement.tagName === 'FSO') {
                        $("#cur-pos-indicator").text("FSO " + prevElement.getAttribute("no") + " -  p." + parseInt(lessonPage));
                    }
                    else if (prevElement.tagName === 'LESSON_HEADER'
                                || prevElement.tagName === 'SABBATH'
                                || prevElement.tagName === 'TITLE-TAG'
                                || prevElement.tagName === 'KEY_TEXT'
                                || prevElement.tagName === 'KEY_NOTE'
                                || prevElement.tagName === 'READINGS'
                                || prevElement.tagName === 'DAY_LESSON'
                    ) {
                        $("#cur-pos-indicator").text("LESSON " + prevElement.parentElement.getAttribute('no') + " -  p." + parseInt(lessonPage));
                    }
                    else {
                        // ++page;
                        console.log("SCROLL", "unknown element")
                        $("#cur-pos-indicator").text(prevElement.tagName + " -  p." + parseInt(lessonPage));
                    }
                    return false;
                }
                
                prevElement = this;
                
                if (prevElement.tagName === 'FOREWORD'
                            || prevElement.tagName === 'FSO'
                            || prevElement.tagName === 'LESSON_HEADER'
                            || (prevElement.tagName === 'DAY_LESSON' && parseInt(prevElement.getAttribute('no')) > 1 
                                    && parseInt(prevElement.getAttribute('no')) < 6) ) {
                    ++lessonPage;
                }
            }
            $("book").find('foreword, fso, lesson_header, lesson sabbath, lesson title-tag, key_text, key_note, readings, day_lesson').each(setPosIndicatior);
        }
        else if (settings.book == 'rmrh' || settings.book == 'ym') {
            $("book").children('article').each(function () {
                rect = this.getBoundingClientRect();
    
                if (rect.top > headerHeight) {
                    if (prevElement.tagName == 'ARTICLE')
                        $("#cur-pos-indicator").text(prevElement.getAttribute('xml:id'));
                    else
                        $("#cur-pos-indicator").text(prevElement.tagName);
                    return false;
                }
                prevElement = this;
            });
            if (rect.top <= headerHeight) {
                $("#cur-pos-indicator").text(prevElement.getAttribute('xml:id'));
            }
        }
        prevScrollPos = scrollPos;
    });

    
    /*
    * Jump (Go) to ...
    */
    $("#cur-pos-indicator").click(function () {
        if ($("#goto").length)
            $("#goto").remove();
        var listElements = [];
        var html = "<div id='goto'><select name='select-goto' id='select-goto' multiple=multiple' size='";
        if (settings.book == 'sbl' || settings.book == 'sblpab') {
            articles = $("book > lesson, fso");
            html += articles.length + "'>";
            let lessonPage = 4;
            for (idx = 0; idx < articles.length; idx ++) {
                if (articles[idx].tagName == 'LESSON') {
                    let padSize = 12 ;
                    let lessonNo = $(articles[idx]).attr('no');
                    if (parseInt(lessonNo) > 9) {
                        padSize = 10;
                    }
                    html += `<option value='${lessonNo}'>LESSON ${lessonNo} ${String(lessonPage).padStart(padSize, '. ')}</option>`;
                    lessonPage += 5;
                }
                else {
                    html += "<option value='" + $(articles[idx]).attr('date') + "'>FSO " + $(articles[idx]).attr('date') + ` ${String(lessonPage).padStart(4, '. ')}`  + "</option>";
                    lessonPage += 1;
                }
            }
        }
        else {
            articles = $("book > article");
            html += articles.length + "'>";
            let html = "<div id='goto'><select name='select-goto' id='select-goto' multiple=multiple' size='" + articles.length + "'>";
            for (idx = 0; idx < articles.length; idx ++) {
                html += "<option value='" + idx.toString() + "'>" + $(articles[idx]).attr('xml:id') + "</option>";
            }
        }
        html += "</select></div>";

        this.innerHTML += html;
    });


    $(document).on('change', "#select-goto", function () {
        var value = $(this).val()[0];
        if(settings.book == 'sbl') {
            if (parseInt(value) < 15) // lesson no
                $('html, body').animate({
                    scrollTop: $("lesson[no='" + value + "']").offset().top - headerHeight
                }, 500);
            else
                $('html, body').animate({
                    scrollTop: $("fso[date='" + value + "']").offset().top - headerHeight
                }, 500);
        }
        else {
            $('html, body').animate({
                scrollTop: $("article:eq(" + value + ")").offset().top - headerHeight - 10
            }, 500);
        }
    });

});

