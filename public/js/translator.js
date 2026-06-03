/*
 * translator.js
 * 
 * version 1.2
 * 
 * by Sean Kim
 * 
 */


/*
 * JSON.parse helper
 * convert function declaration in JSON (as string) into real function 
 */
var jsonReviver = function (key, value) {
    if (value && (typeof value === 'string') && value.indexOf("function") === 0) {
        // we can only pass a function as string in JSON ==> doing a real function
        var jsFunc = new Function('return ' + value)();
        return jsFunc;
    }

    return value;
};


/*
 * initialization
 */

var errorCount = 0;  //global variable for Error count
var dataStore; // translator dom data with Indexed DB
let envStore =  // ENV object with WEB API STORAGE
{
    data : {
        currentID: '0'  // last scroll position for reload
    },
    store : (id) => {
        envStore.data.currentID = id;
        let key = "translation-" + settings.book + "-" + settings.year + "-" + settings.issue + "-" + settings.lang;
        localStorage.setItem(key, JSON.stringify(envStore.data));
    },
    init : () => {
        let key = "translation-" + settings.book + "-" + settings.year + "-" + settings.issue + "-" + settings.lang;
        let envStr = localStorage.getItem(key);
        if (envStr && envStr != 'null') {
            envStore.data = JSON.parse(envStr);  
        }  
        let y = (parseInt(settings.year) - 1).toString();
        let oldKey = "translation-" + settings.book + "-" + y + "-" + settings.issue + "-" + settings.lang;
        localStorage.removeItem(oldKey);
    }
}


//  local data storage
function idbStorage(documentID)
{
    // implement undo feature for entire text
    this.docId = documentID;
    this.lastElement = 0;  // last edited element id.
    this.maxUndo = 7;
    this.dataStack = []; // array of {contents:"", lastElement:""}, hold undo data
    this.iDB = null;

    var self = this;

    var indexedDB = window.indexedDB || window.webkitIndexedDB || window.mozIndexedDB || window.OIndexedDB || window.msIndexedDB,
            IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.OIDBTransaction || window.msIDBTransaction,
            dbVersion = 1.0;

    var openRequest = indexedDB.open("Translator", dbVersion);

    openRequest.onupgradeneeded = function (e) {
        console.log("indexedDB: running onupgradeneeded");
        var thisDB = e.target.result;
        thisDB.createObjectStore("SblText");
    }

    openRequest.onsuccess = function (e) {
        //console.log("Success!");
        self.iDB = e.target.result;
        self.iDB.onerror = function (event) {
            console.log("indexedDB: Error creating/accessing IndexedDB database");
        };

        var store = self.iDB.transaction(["SblText"], "readonly").objectStore("SblText");
        var request = store.get(documentID);
        request.onsuccess = function (e) {
            // clear up previous (one year old) data
            y = (parseInt(settings.year) - 1).toString();
            let oldId = "translation-" + settings.book + "-" + y + "-" + settings.issue + "-" + settings.lang;
            self.deleteRecord(oldId);

            // ready data stack on memory
            if (e.target.result != undefined) {
                var result = e.target.result;
                self.dataStack = JSON.parse(result);
            }
            initDocument();
        }
        request.onerror = function (e) {
            console.log("indexedDB: indexedDB get error.", e.target.error.name);
        }
    }

    openRequest.onerror = function (e) {
        alert("Database Error. If you are using browser PRIVATE mode, please open this window in normal mode.")
        console.log("indexedDB: indexedDB open error.");
        console.dir(e);
    }

    this.put = function (key, data) {
        var transaction = self.iDB.transaction(["SblText"], "readwrite");
        var store = transaction.objectStore("SblText");

        //Perform the add
        var request = store.put(data, key);

        request.onerror = function (e) {
            console.log("indexedDB: indexedDB put error.", e.target.error.name);
        }

        transaction.oncomplete = function (event) {
            console.log("indexedDB: Data saved.");
        }
    };

    this.get = function (key, fn) {
        var transaction = self.iDB.transaction(["SblText"], "readonly");
        var store = transaction.objectStore("SblText");

        var request = store.get(key);

        request.onsuccess = function (e) {
            var result = e.target.result;
            fn(result);
        }

        request.onerror = function (e) {
            console.log("indexedDB: indexedDB get error.", e.target.error.name);
        }
    };

    this.deleteRecord = function (key) {
        // open a read/write db transaction, ready for deleting the data
        var transaction = self.iDB.transaction(["SblText"], "readwrite");

        transaction.oncomplete = function (event) {
            console.log("indexedDB: Record deleted.");
        };

        var objectStore = transaction.objectStore("SblText");

        var request = objectStore.delete(key);

        request.onsuccess = function (e) {
            console.log("indexedDB: Record deleted. KEY: " + key);
        }
    }

    /* interfaces */

    // undo interface	
    this.canUndo = function () {
        if (this.dataStack.length > 1)
            return true;
        else
            return false;
    };

    this.putStore = function (data, elementId = '0') {
        this.lastElement = elementId;
        // manage stack
        if (this.dataStack.length > this.maxUndo)
            this.dataStack.shift();
        let currentData = {};
        currentData.contents = data;
        currentData.lastElement = elementId;
        this.dataStack.push(currentData);

        this.put(this.docId, JSON.stringify(this.dataStack));
    };

    this.popStore = function () {
        if (this.dataStack.length > 1) {
            this.dataStack.pop();
            this.put(this.docId, JSON.stringify(this.dataStack));
            let currentData = this.dataStack[this.dataStack.length - 1];
            this.lastElement = currentData.lastElement;
            return currentData.contents;
        } else
            return "";
    };

    this.getCurrent = function () {
        if (this.dataStack.length > 0) {
            let currentData = this.dataStack[this.dataStack.length - 1];
            this.lastElement = currentData.lastElement;
            return currentData.contents;
        } else
            return null;
    };
    
    this.getAll = function () {
        if (this.dataStack.length > 0) {
            return this.dataStack;
        } else
            return null;
    };
}


/*
 * validate target bible source matching with original source and insert tags for it.
 * 
 * @param {$("#editable")} element
 * @returns {Boolean}   return false if any of bible source is not matched
 */
function validateBibleSource(element) {
    var sourceText = element.children(".source-text").text();
    var targetChild = element.children(".target-text");
    var targetText = targetChild.text().replace(/</g, "&lt;").replace(/>/g, "&gt;");  //escape XML special character

    /*
     * \u2013 -> "–"
     * \uFF1A -> "："
     * \uFF0C -> "，"
     * 2nd Capturing Group (\d+[^\u0041-\u2012\u2014-\uFF0B\uFF1B-\uFFFF]*?) -> "00:00 ..."
     * 3rd Capturing Group (\)|;|\.|）|；|。|( \([^\)]+?\)(;|\.|）|；|。))) -> DELIMINATOR
     * 7th Alternative ( \([^\)]+?\)(;|\.|）|；|。)) -> "(first|..|last part)"
     */
    var regSubfix = /(( (\d+[^\u0041-\u2012\u2014-\uFF0B\uFF1B-\uFFFF]*?)(\)|;|\.|）|；|。|( \([^\)]+?\)(;|\.|）|；|。))))+)/;

    var regPattern;
    var targetPattern;
    var found = false;
    var notMatched = false;
    var textReplaced = "";
    var sourcePos = 0;  // global mathcing is not working. repeat till no more matchin.
    for (var index = 0; index < 66; index++) {
        regPattern = new RegExp(bibleProp[index][1] + regSubfix.source, '');
        found = sourceText.substr(sourcePos).match(regPattern);
        if (found) {
            targetPattern = bibleProp[index][2] + found[1].slice(0, -found[4].length); // exclude last deliminater
            textReplaced = targetText.replace(targetPattern, "<bible_source>" + targetPattern + "</bible_source>");
            if (textReplaced === targetText) { // if original source is not found so text is not changed then,
                notMatched = true;
            } else {
                targetText = textReplaced;
            }
            sourcePos = found.index;
            sourceText = sourceText.replace(found[0], "");

            // global mathcing is not working. repeat till no more matchin.
            // may not needed for SBL because the references group by the book name.
            if (sourcePos >= sourceText.length)
                sourcePos = 0;
            else
                index--;
        } else
            sourcePos = 0;
    }
    if (notMatched) {
        if (!targetChild.hasClass("source-warning")) {
            errorCount++;
            targetChild.addClass("source-warning");
        }
    } else {
        if (targetChild.hasClass("source-warning")) {
            errorCount--;
            targetChild.removeClass("source-warning");
        }
    }
    $(".error-count").text(errorCount.toString());

    targetChild.html(targetText);
    return !notMatched;
}


function initContents(content) {
    if (content) {
        content = content.replace(/role=(\"[^\"]+\")/g, "role=$1 class=$1");  // initial replaces 
        $("#contents").html(content);

        // replace bible link to target language
        settings.bibleTransVerAbbr = '';
        if(settings.lang == 'es') settings.bibleTransVerAbbr = 'ESRV';
        else if(settings.lang == 'hr') settings.bibleTransVerAbbr = 'HRVV';
        else if(settings.lang == 'ja') settings.bibleTransVerAbbr = 'JA1955';
        else if(settings.lang == 'ko') settings.bibleTransVerAbbr = 'HRV';
        else if(settings.lang == 'pt') settings.bibleTransVerAbbr = 'PTAA';
        else if(settings.lang == 'ru') settings.bibleTransVerAbbr = 'RSV';
        else if(settings.lang == 'rw') settings.bibleTransVerAbbr = 'BYSB';
        else if(settings.lang == 'ta') settings.bibleTransVerAbbr = 'TKJV';
        $('.source-link').each(function(){
            if (settings.bibleTransVerAbbr.length)
                this.title = this.title.replace('KJV', settings.bibleTransVerAbbr);
        });
                
        // parag height for PAB target text
        $('.pab-parag').each(function( index ) {
            this.style.height = "300px"; //$(this).siblings(".source-text")[0].offsetHeight * 2;
            if (this.getElementsByClassName("pab-matched").length)
                //$(this).scrollTop($(this).children(".pab-matched").position().top);
                $(this).scrollTop( this.getElementsByClassName("pab-matched")[0].getBoundingClientRect().top
                                    - this.getBoundingClientRect().top )
        });    
        // Validate bible source matching and surround all bible source with "<bible_source />"
        $(".editable").each(function () {
            var element = $(this);
            if (element.prop("tagName") == "KEY_TEXT" || element.prop("tagName") == "QUESTION" || element.prop("tagName") == "SUB_QUESTION")
                validateBibleSource(element);
        });
        errorCount = $(".source-warning").length;
        $(".error-count").text(errorCount.toString());

        email = $("#contents").find("translator").attr("editor-email");
        var editor = (email == undefined) ? 'unknown' : ("<span title='" + email + "'>" + $("#contents").find("translator").attr("editor-name") + "</span>");
        var updatedDate = new Date($("#contents").find("book").attr("updated"));
        updatedStr = updatedDate.toString();
        $("#update-time").html("Last Updated: " + updatedStr.replace(/GMT.+$/, '') + " by " + editor);
        $("#update-time").prop('title', updatedStr);
        if (updatedDate.getTime() == serverUpdatedTime.getTime())
            $("#update-time").addClass("synchronized");
        else
            $("#update-time").removeClass("synchronized");

        if ($("#editing").length) {
            $("html, body").animate({
                scrollTop: ($("#editing").offset().top - $("header").outerHeight())
            }, 500);
            $("#editing").focus();
        } else if (envStore.data.currentID != "0") {
            $("html, body").animate({
                scrollTop: ($('#' + envStore.data.currentID).offset().top - $("header").outerHeight()*2)
            }, 500);
        }
        
    } else
        alert("There is no saved contents.");
    
    $(".wait-message").addClass("hide");
}


/*
 * init document
 */
var serverUpdatedTime;  // Date object for server side copy

function initDocument()
{
    var localContent = dataStore.getCurrent();

    if (navigator.onLine) {  // should be always online in this case
        //param = "prop[lang_code]=" + langEGWs.lang;
        $("body").css("cursor", "progress");
        $.ajax({
            type: "POST",
            url: "/publications/translator/contents/"
                    + settings.book + "/" + settings.year + "/" + settings.issue + "/" + settings.lang + "/" + settings.s_lang,
        })
        .done(function (data) {
            if (data.code == 1) {
                serverUpdatedTime = new Date(data.contents.match(/" updated="([^"]+)"/)[1]);
                if (!localContent) {    // first time access
                    dataStore.putStore(data.contents);
                    initContents(data.contents);
                } else {
                    try {
                        var localUpdated = new Date(localContent.match(/" updated="([^"]+)"/)[1]);
                        if (localUpdated.getTime() < serverUpdatedTime.getTime()) {
                            var message = "There is an updated translation on the server. <br /><br />"
                                    + "Server Version: <br /><b>&nbsp;&nbsp;&nbsp;&nbsp;" + serverUpdatedTime.toString() + "</b><br />"
                                    + "Local Version: <br /><b>&nbsp;&nbsp;&nbsp;&nbsp;" + localUpdated.toString() + "</b><br /><br />"
                                    + "Which version of translation do you want to use?";

                            $( "#confirm-message" ).html(message);
                            $( "#dialog-confirm" ).attr("title", "Attention!");
                            $( "#dialog-confirm" ).removeClass("hide");
                            $( "#dialog-confirm" ).dialog( { height: "auto", width: "60%", modal: true,
                                buttons: {
                                    "Use SERVER version": function() {
                                        $( this ).dialog( "close" );
                                        dataStore.putStore(data.contents);// save to local storage
                                        initContents(data.contents);
                                        $( "#dialog-confirm" ).addClass("hide");
                                    },
                                    "Use LOCAL version": function() {
                                        $( this ).dialog( "close" );
                                        initContents(localContent);
                                    }
                                }
                            });                                                                        
                        } else if (localUpdated.getTime() == serverUpdatedTime.getTime()) {  // always use server version for same language to reflect last change. ex) en => en
                            initContents(data.contents);
                            console.log("initDocument: Server version loaded.");
                        } else {
                            initContents(localContent);
                        }
                    } catch (err) {
                        console.log("Error: " + err);
                        initContents(localContent);
                    }
                }
                // total page number
                if (settings.book.indexOf('sbl') == 0) {
					let lessonPage = 2;
					function setPosIndicatior() {
						if (this.tagName === 'FOREWORD'
									|| this.tagName === 'FSO'
									|| this.tagName === 'LESSON_HEADER'
									|| (this.tagName === 'DAY_LESSON' && parseInt(this.getAttribute('no')) > 1 
											&& parseInt(this.getAttribute('no')) < 6) ) {
							++lessonPage;
						}
					}
					$("book").find('foreword, fso, lesson_header, lesson sabbath, lesson title-tag, key_text, key_note, readings, day_lesson').each(setPosIndicatior);
					$("#total-page-no").text(`of ${lessonPage}`);
                }
                else {
                    $("#page-label, #cur-page-no, #total-page-no").hide();
                }
            } else
                alert("Something went wrong! Please reload your page.");
            console.log("initDocument: ajax message - " + data.message);
            $("body").css("cursor", "default");
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (errorThrown === "Unauthorized") {
                window.location.replace('/login');
                return;
            }
            console.log(jqXHR);
            alert("Error:" + errorThrown + "; " + jqXHR.responseJSON.message);
            if (localContent) {
                alert("Local data will be loaded.");
                initContents(localContent);
            }                
        });
    } else {
        if (localContent) {
            initContents(localContent);
        } else
            alert("Please connect internet.");
    }
}


function handleCacheEvent(e) {
    console.log('handleCacheEvent: ' + e.type);

    if (e.type == 'cached' || e.type == 'noupdate' || e.type == 'updateready') { // load the contents;
        if (e.type == 'updateready') {
            window.applicationCache.swapCache();
        }
        //initDocument() ;
    }
}


function handleCacheError(e) {
    if (e.url !== undefined && e.url.indexOf("/publications/translator/") > -1) {  // main page or manifest page.
        if (e.url.indexOf("manifest.appcache") > -1)   // manifest page - sometimes requested even off-line 
            ;//initDocument() ;
        else  // main page - must login when on line
            window.location.replace('/login');
    }
    /*else if( navigator.userAgent.indexOf("Firefox") != -1 || typeof InstallTrigger !== 'undefined' )
        initDocument() ;  // Firefox, even offline it trys to check the manifest
    else
        alert('Error: Cache failed to update! Please refresh your page.');
     */
    //else  // other then Chrome
        ;//initDocument() ;
}


/*var appCache = window.applicationCache;

appCache.addEventListener('cached', handleCacheEvent, false);
appCache.addEventListener('checking', handleCacheEvent, false);
appCache.addEventListener('downloading', handleCacheEvent, false);
appCache.addEventListener('error', handleCacheError, false);
appCache.addEventListener('noupdate', handleCacheEvent, false);
appCache.addEventListener('obsolete', handleCacheEvent, false);
//appCache.addEventListener('progress', handleCacheEvent, false);
appCache.addEventListener('updateready', handleCacheEvent, false);
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

    dataStore = new idbStorage(settings.docId);
    //dataStore.readyStore(settings.docId);	// do it here for time-taking process for asynchronous IndexDB

    // ENV storage.
     envStore.init();

    // to indicate version change.
    $(".app-title a").css("color", "#007");
    //$("#version-info").text("1.1");

    //give space for header
    $(".page-title").css("margin-top", $("header").outerHeight() + 10);


    /*
     * clean up
     */
    $(window).on('beforeunload', function() {  // does not fired well !!!
        if ($("#editing").length) {
            endEditing($("#editing"));
            return true;
        }

        return false;
    });


    /*
     * Go-to functions
     */

     /*
     * current scroll position
     */
    prevScrollPos = 0;
    scrollPos = 0;
    headerHeight = $("header").outerHeight();
    $(window).scroll(function () {
        scrollPos = $(window).scrollTop();
        if (Math.abs(scrollPos - prevScrollPos) < 20)
            return;

        prevElement = document.getElementsByTagName('book').item(0);
        if (settings.book.indexOf('sbl') == 0) {
            lessonPage = 2;
            function setPosIndicatior() {
                let rect = this.getBoundingClientRect();
                if (rect.top - 30 > headerHeight) {
                    // console.log("SCROLL", rect.top, prevElement.tagName, this.tagName, lessonPage)
                    if (prevElement.tagName === 'FOREWORD') {
                        $("#cur-pos-indicator").text("FOREWORD");
                    }
                    else if (prevElement.tagName === 'FSO') {
                        $("#cur-pos-indicator").text("FSO " + prevElement.getAttribute("no"));
                    }
                    else if (prevElement.tagName === 'LESSON_HEADER'
                                || prevElement.tagName === 'SABBATH'
                                || prevElement.tagName === 'TITLE-TAG'
                                || prevElement.tagName === 'KEY_TEXT'
                                || prevElement.tagName === 'KEY_NOTE'
                                || prevElement.tagName === 'READINGS'
                                || prevElement.tagName === 'DAY_LESSON') {
                        $("#cur-pos-indicator").text("LESSON " + prevElement.parentElement.getAttribute('no'));
                    }
                    else {
                        // ++page;
                        console.log("SCROLL - ", "Unknown element : ", prevElement.tagName);
                        $("#cur-pos-indicator").text(prevElement.tagName);
                    }
                    $("#cur-page-no").val(parseInt(lessonPage));
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
        else if (settings.book.indexOf('rmrh') == 0 || settings.book.indexOf('ym') == 0) {
            $("book").children('article').each(function () {
                let rect = this.getBoundingClientRect();
    
                if (rect.top - 30 > headerHeight) {
                    if (prevElement.tagName == 'ARTICLE')
                        $("#cur-pos-indicator").text(prevElement.getAttribute('xml:id'));
                    else
                        $("#cur-pos-indicator").text(prevElement.tagName);
                    return false;
                }
                prevElement = this;
            });
            if (rect.top - 30 <= headerHeight) {
                $("#cur-pos-indicator").text(prevElement.getAttribute('xml:id'));
            }
        }
        prevScrollPos = scrollPos;

    });


    /*
     * Jump (Go) to ...
     */
    $("#cur-pos-indicator").click(function () {
        var listElements = [];

        var goto = document.getElementById('goto');
        if (goto)
            goto.parentNode.removeChild(goto);

        var html = "<div id='goto'><select name='select-goto' id='select-goto' multiple=multiple' size='";
        if (settings.book.indexOf('sbl') == 0) {
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
            for (idx = 0; idx < articles.length; idx ++) {
                html += "<option value='" + idx.toString() + "'>" + $(articles[idx]).attr('xml:id') + "</option>";
            }
        }
        html += "</select></div>";

        this.innerHTML += html;
    });

    $(document).on('change', "#select-goto", function () {
        var value = $(this).val()[0];
        if(settings.book.indexOf('sbl') == 0) {
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
                scrollTop: $("article:eq(" + value + ")").offset().top - headerHeight
            }, 500);
        }
    });

    // close '#select-goto'
    $(document).on("click", function (event) {
        var goto = document.getElementById('goto');
        if (goto && !event.target.closest('#cur-pos-indicator')) {
            goto.parentNode.removeChild(goto);
        }

        let els = $(event.target).closest(".editable");
        if (els.length) {
            envStore.store(els[0].getAttribute('id'));
        }
    });
    

    // goto page no 
    $("#cur-page-no").on("keydown",function search(e) {
        if (settings.book.indexOf('sbl') !== 0) {
            return;
        }
        if(e.keyCode == 13) {
            const val = parseInt($(this).val());
            let lessonPage = 2;
            function setPosIndicatior() {
                if (this.tagName === 'FOREWORD'
                            || this.tagName === 'FSO'
                            || this.tagName === 'LESSON_HEADER'
                            || (this.tagName === 'DAY_LESSON' && parseInt(this.getAttribute('no')) > 1 
                                    && parseInt(this.getAttribute('no')) < 6) ) {
                    ++lessonPage;
                }
                if (lessonPage === val) {
                    this.scrollIntoView();
                    window.scrollBy(0, -headerHeight-20, {behavior: 'smooth'});
                    return false;
                }
            }
            $("book").find('foreword, fso, lesson_header, lesson sabbath, lesson title-tag, key_text, key_note, readings, day_lesson').each(setPosIndicatior);
        }
    });

    // goto #ID 
    $("#jump-to-parag-id").on("keydown",function search(e) {
        if(e.keyCode == 13) {
            let element = document.getElementById($(this).val());
            if (element) {
                element.scrollIntoView();
                window.scrollBy(0, -headerHeight-20, {behavior: 'smooth'});
            }
        }
    });


    /*
     * editing
     */
    
    //convert target text into editable form
    $(document).on("click", ".target-text", function (event) {
        if (settings.book.indexOf('pab') < 0) {
            if ($("#editing").length) {
                if ($(this).hasClass("editing-parent"))
                    return false;
                if ($("#editing").length)
                    endEditing($("#editing"));
            }
            startEditing($(this));
            event.stopPropagation();
        }
    });

  
    /*
     * save current editing field
     */
    var onEndEditing = false;
    $(document).on( "focusout", "#editing", function(event) {
        if (!onEndEditing)
            endEditing($("#editing"));
    });

    
    $(document).on("click", ".source-text", function (event) {
        if ($("#editing").length)
            endEditing($("#editing"));
    });


    $(document).on("keyup", function (event) {
        var code = (event.keyCode ? event.keyCode : event.which);

        // move to next .target-text
        if ((code === 13 || code === 27) && $("#editing").length) { // CR || ESC
            var nextEditable = getNextEditable($("#editing").parent());
            endEditing($("#editing"));
            if (nextEditable && code === 13) {    // CR
                startEditing(nextEditable.children('.target-text'));
            }
        } 
        else if (event.ctrlKey) {
            if ((code === 90 || code === 122) && $("#editing").length === 0 && $(".dialog").length === 0) { // Zz
                undo();
            } else if (event.shiftKey && event.altKey && (code === 82 || code === 114)) { // Rr
                resetDocument();
            } else if (event.shiftKey && event.altKey && (code === 68 || code === 100)) { // Dd
                dumpAll();
            }
        }
    });


    /*
     * get NEXT editable element
     * search till the end of the document
     * 
     * @param {$("book *")} element
     * @returns {unresolved}
     */
    function getNextEditable(element) {   // element == '.editable'
        var nextElement = element.next();

        // search in childern or their siblings
        while (nextElement.length) {
            if (nextElement.length && nextElement.is('.editable'))    // 
                return nextElement;
            else {  // search in childern
                if (nextElement.children().length) {
                    nextElement = nextElement.children().first();
                } else {
                    nextElement = nextElement.next();
                }
            }
        }

        var parent = element.parent();
        if (parent.is("book"))
            return null;

        return getNextEditable(parent);
    }


    /*
     * 
     * @param {$(".target-text")} element
     * @returns {undefined}
     */
    var textPrev = '';
    function startEditing(element) {
        textPrev = element.html().replace(/<\/?bible_source>/g, "");
        var text = textPrev.replace(/<([^>]+)>/g, "@@$1@@");//.replace(/\u00A0/g, "&amp;nbsp;");//.replace(/\&lt;/g, "<").replace(/\&gt;/g, ">").replace(/\&amp;/g, "&"); // escape inner tag elements
        text = text.replace(/&#[\u00A0-\u9999];/g, function(str) { 
            return "&#" + str.charCodeAt(0).toString(16) + ";"; 
        });
        text = text.replace(/\&nbsp;/g, "&amp;nbsp;");
        height = element.innerHeight();
        element.addClass("editing-parent");
        inputForm = $("<textarea id='editing' class='editing' style='height:" + height + "px;'>" + text + "</textarea>");
        element.html("");
        element.append(inputForm);

        //inputForm.on("keypress", returnPressed);

        inputForm.focus();
    }


    /*
     * 
     * @param element $("#editing") 
     * @returns {bool}  return false if the editing is not able to end for incorrect input.
     */
    function endEditing(element) {
        onEndEditing = true;
        var modified = false;
        //var textPrev = element.html();
        var textEdited = element.val().replace(/[\n\r]/g, "").replace(/\s+/g, " "); 
        if (textEdited !== textPrev)
            modified = true;
        //textEdited = textEdited.replace(/@@([^@]+)@@/g, "<$1>").replace(/\&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        textEdited = textEdited.replace(/&#[A-Za-z0-9]+;/g, function(str) { 
            return String.fromCharCode(parseInt("0x" + str.slice(2, str.length-1))); 
        });
        textEdited = textEdited.replace(/&nbsp;/g, "\u00A0").replace(/@@([^@]+)@@/g, "<$1>");
        if (textEdited.length == 0)
            textEdited = textPrev;
        var elementParent = element.parent();
        element.remove();
        elementParent.html(textEdited);
        elementParent.removeClass("editing-parent");

        // check if the bible refference is correct
        var parent = elementParent.parent();
        if (parent.prop("tagName") == "KEY_TEXT" || parent.prop("tagName") == "QUESTION" || parent.prop("tagName") == "SUB_QUESTION")
            validateBibleSource(parent);

        if (modified) {
            var id = parent.prop("id");
            if (parseInt(id) < 1) {
                id = (typeof id === 'undefined') ? 'undefined' : id;
                alert("ERROR: Wrong paragraph id - This paragraph will not be saved. Please contact administrator with the id. ID:" + id);
            } else
                saveDocument(id, true);
            onEndEditing = false;
        }
    }


    // auto save
    var timerId = null;
    if (timerId == null) {
        timerId = setInterval(function request() {
            if (!$("#update-time").hasClass("synchronized"))
                saveDocument("0", false);
            }, 10*60*1000);
    }
    
    /*
     * save entire document("book") to local storage
     * @param {$(".editable").pro("id")} - element id
     * @param documentText - edited text or entire text if id === '0'
     * @param localUpdate(bool) - if true store codumentText on local storege.
     * @returns {undefined}
     */

    var lastEditedId;
    function saveDocument(id, localUpdate)
    {
        if (settings.book.indexOf('pab') > -1)
            return;
        // update <book title="">
        if (id === '1') 
            $("book").attr("title", $('book-title .target-text').text());

        let updatedDate = new Date();

        var content = "";
        if (localUpdate) {
            document.querySelector('book').setAttribute('updated', updatedDate.toISOString()) ;
            let editor = "<span title='" + settings.userEmail + "'>" + settings.userName + "</span>";
            updatedStr = updatedDate.toString();
            $("#update-time").html("Last Updated: " + updatedStr.replace(/GMT.+$/, '') + " by " + editor);
            $("#update-time").prop('title', updatedStr);
            $("#update-time").removeClass("synchronized");
            content = $("#contents").html();
            dataStore.putStore(content, id);
            console.log("local updated. ID:" + id);
            return;
        }
        else if (id === '0') {
            content = toText(false, false);
            if (!content || content.length == 0)
                return;
        } else { 
            // not defined ???
            // content = documentText;
            console.log('saveDocument: NOT PROCESSED!'); 
            return ;
        }

        lastEditedId = id;

        // server side
        if (navigator.onLine && id === '0') {
            $("#waiting-message-box").removeClass("hide");
            $("#waiting-icon").removeClass("hide");
            $.ajax({
                type: "POST",
                url: "/publications/translator/save",
                data: {'docId': settings.targetId, 'content': content, 'paragId': id, 'updated': updatedDate.toISOString()},
                dataType: "json"
            }).done(function (data) {
                if (data.paragId === lastEditedId && data.code === 1) {
                    $("#update-time").addClass("synchronized"); //alert( data.message );
                    serverUpdatedTime = updatedDate;
                }
                console.log("remote updated. ID:" + data.paragId);
                $('meta[name="csrf-token"]').attr('content', data.token);
                $.ajaxSetup({
                    headers: {
                       'X-CSRF-TOKEN': data.token
                    }
                });
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                if (jqXHR.status == 419) alert("SESSION TIME OUT: Please reload your page and continue.");
                else alert("Error:" + errorThrown + "; " + jqXHR.responseJSON.message);
            }).always(function (jqXHR, textStatus, errorThrown) {
                $("#waiting-icon").addClass("hide");
                $("#waiting-message-box").addClass("hide");
            });
        }
    }
    

    /*
     * Menu
     */

    //error searching
    $("#next-error-btn").click(function () {
        var count = $(".source-warning").length;
        var hearderHeight = $("header").outerHeight();
        var viewPos = Math.ceil($(window).scrollTop() + hearderHeight + 10);

        for (index = 0; index < count; index++) {
            let errorObj = $(".source-warning").eq(index);
            let objectPos = Math.ceil(errorObj.offset().top)
            if (viewPos < objectPos) {
                let pos = $('#error-count').text().lastIndexOf(' ');
                let total = $('#error-count').text().substring(pos+1);
                $('#error-count').text((index+1).toString() + ' of ' + total.toString());
                $(window).scrollTop(objectPos - hearderHeight - 10);
                break;
            }
        }
    });

    $("#prev-error-btn").click(function () {
        var index = $(".source-warning").length - 1;
        var hearderHeight = $("header").outerHeight();
        var viewPos = Math.ceil($(window).scrollTop() + hearderHeight + 10);

        for (; index > -1; index--) {
            var errorObj = $(".source-warning").eq(index);
            var objectPos = Math.ceil(errorObj.offset().top)
            if (viewPos > objectPos) {
                let pos = $('#error-count').text().lastIndexOf(' ');
                let total = $('#error-count').text().substring(pos+1);
                $('#error-count').text((index+1).toString() + ' of ' + total.toString());
                $(window).scrollTop(objectPos - hearderHeight - 10);
                break;
            }
        }
    });


    // Main manu

    if (typeof (Storage) === "undefined") {
        $("#menu-submit, #menu-restore").addClass("menu-disabled");
    }


    $(".menu-item").click(function () {
        if ($("#editing").length)
            endEditing($("#editing"));
        if (jQuery(".source-view-dialog").length > 0) { // if the source is not placed in the dialog box.
            closePopup();
        }

        item = $(this).prop("id");
        //alert(item);
        if (item == "menu-undo")
            undo();
        else if (item == "menu-reset")
            resetDocument();
        else if (item == "menu-dump")
            dumpAll();
        else if (item == "menu-edit-prop")
            editProperty();
        else if (item == "menu-edit-book-names")
            editBookNames();
        else if (item == "menu-xml" &&  settings.book.indexOf('pab')) {
            if (navigator.onLine)
                saveDocument("0", false);    // force to commit to the server
            else {
                alert("Please connet interner!");
                return;
            }
            toText(false, true);
        }
        else if (item == "menu-rtf-text" &&  settings.book.indexOf('pab')) {
            /*if (navigator.onLine)
                saveDocument("0", false);    // force to commit to the server
            else {
                alert("Please connet interner!");
                return;
            }*/
            if (settings.book.indexOf('sbl') == 0)
                exportSblRtfText();
            else
                exportRmrhRtfText();
        }
        else if (item.includes("menu-indesign-text") && settings.book.indexOf('pab')) {
            if (settings.book.indexOf('sbl') == 0) {
                if (item == "menu-indesign-text")
                    exportSblIndesignTaggedText(1);
                else if (item == "menu-indesign-text-v2")
                    exportSblIndesignTaggedText(2);
            }
            else
                exportRmrhIndesignTaggedText();
                // alert("InDesign Tagged Text is only available for SBL.");
        }
        else if (item == "menu-indesign-template")
            downloadTemplate();
        else if (item == "menu-synchronize") {
            //if (window.confirm("You are about to submit your translation to the server. Are you sure?"))
                saveDocument("0", false);
        }
        else if (item == "menu-contact")
            contactForm();
        // hide menu
        $("#navbarSupportedContent").removeClass("show")
        /*menuButton = $(".navbar-toggler");
        if (menuButton.attr("aria-expanded") == "true") // close expanded menu
            menuButton.click();
        */
    });

    /*
     * hide menu
     */
    /*$( ".header .container" ).focusout(function() {
        menuButton = $(".navbar-toggle");
        if (menuButton.attr("aria-expanded") === "true")
            menuButton.click();
    });*/

    
    /*
     * Change language selection
     */
    $("#lang-select").change(function () {
        if ($(this).attr('type') == 'select') {
            var fromLang = $("#lang-select option[value='" + settings.lang + "']").text();
            var toLang = $("#lang-select option[value='" + $(this).val() + "']").text();
        }
        else {
            var fromLang = settings.lang.toUpperCase();
            var toLang = $(this).val().toUpperCase();
        }
        var r = confirm("Are you sure you want to change the language from '" + fromLang + "' to '" + toLang + "'?\n");
        if (r == true) {
            var lang = $(this).val();
            var url = window.location.origin + '/publications/translator/'
                    + settings.book + '/' + settings.year + '/'
                    + settings.issue + '/' + lang + '/' + settings.s_lang;     // Returns full URL
            window.location = url;
        } else {
            $(this).val(settings.lang);
        }
    });


    /*
     * delete local document as well as server
     * @returns {undefined}
     */
    function resetDocument() {
        if (navigator.onLine) {
            var r = confirm("All translation will be lost. \n\nAre you sure you want to reset the translation?");
            if (r == true) {
                $.ajax({
                    type: "POST",
                    url: "/publications/translator/save",
                    data: {docId: settings.targetId, content: "reset"},
                    dataType: "json"
                }).done(function (data) {
                    //dataStore.deleteRecord(settings.docId);
                    location.reload();//alert(data.message);
                    console.log("Document reset");
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert("Error! Please try again." + errorThrown);
                });
            }
        } else
            alert("Please connect to internet to reset the work!!!")
    }


    /*
     * undo last change
     * @returns {undefined}
     */
    function undo() {
        if (dataStore.canUndo()) {
            if (confirm("Are you sure to undo last change?") == true) {
                initContents(dataStore.popStore());
            }
        }
        else
            alert("No more changes to undo!");
    }



    function dumpAll()
    {
        text = JSON.stringify(dataStore.getAll());
        download(text, "TranslatorDataDump.txt", "text/plain;charset=utf-8");
    }


    /*
     * 
     * @param {boolean} isText
     * @returns {undefined}
     */
    function toText(isText, toDownload = true)
    {
        var xmlWrapper = $("translator").clone(false);
        var newXML = xmlWrapper.children('book');
        //newXML.attr("xml:lang", settings.lang);
        //newXML.attr("title", newXML.find("book-title .target-text").text());
        //newXML.find("book-title").remove();

        var txt;
        newXML.find(".editable").each(function (index, element) { // element == this
            txt = $(element).children(".target-text").html();
            $(element).children().remove();
            $(element).html(txt);
            $(element).removeAttr("class");
        });

        newXML.find("title-tag").replaceWith(function () {
            serial = $(this).attr("id");
            return $('<title id="' + serial + '" title="' + serial + '">' + $(this).text() + "</title>");
        });

        newXML.find("*").removeAttr("class"); // remove all display attrs

        if (isText)
            window.open("", "", "scrollbars=1").document.write(xmlWrapper.text());
        else {
            var xml = '<?xml version="1.0" encoding="UTF-8"?>' + xmlWrapper.html();
            xml = xml.replace(/<\/?textarea[^>]*>/g, "");
            xml = xml.replace(/<\/?bible_source[^>]*>/g, "");
            xml = xml.replace(/\&nbsp;/g, "\u00A0"); // xml does not support '&nbsp;'
            xml = xml.replace("\\n", "");
            if ((msg = XMLDocErrString(xml)) == "") {
                if (toDownload)
                    download(xml, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + ".xml", "text/plain;charset=utf-8");
                else
                    return xml;
            }
            //window.open("", "", "scrollbars=1").document.write( "<![CDATA[" + xml.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, "<br />") + "]]>" ) ;
            else {
                alert("XML ERROR - XMLDocErrString(): " . msg);
                return false;
            }
        }
    }


    /*
     * Contact administrator
     *
     * @param {undefined}
     * @returns {undefined}
     */
    window.onRecaptchaLoad = function() {
        grecaptcha.render(document.getElementsByClassName('g-recaptcha')[0], {
            'sitekey' : $('.g-recaptcha').attr('data-sitekey'),
            'callback' : function(response) {
                    //console.log(response);
            }
        });
    }
    
    function contactForm()
    {
        const scrollPos = $(document).scrollTop();
        $.get( "/contact", function( data ) {
            $(document).scrollTop(scrollPos);
            buildContactForm(data);
        });
    }
    
    function buildContactForm(data) {
        $( "#ajax-content" ).html( data );

        // ready for recaptcha
        if(typeof grecaptcha == 'undefined') { 
            $.getScript("https://www.google.com/recaptcha/api.js?render=explicit&onload=onRecaptchaLoad")
            .done(function (script, textStatus) {
                console.log("script loaded");
            });
        }
        else {
            onRecaptchaLoad()
        }

        dialog = $( "#contactForm" );
        if (dialog.length) {
            dialog.dialog({
                autoOpen: true,
                title: 'Contact',
                height: 'auto',
                width: '80%',
                modal: true,
                close: function() {
                    $(".ui-dialog").remove();
                }
            });
            dialog.find( "form" ).on( "submit", function( event ) {
                event.preventDefault();
                $.post( "/contact/send", $("form").serialize(), function(data) {
                    $(".ui-dialog").remove();
                    alert(data);
                })
                .fail(function(data ) {
                    if (data.responseText.includes("g-recaptcha-response"))
                        alert( "Prove if you are not ROBOT, Please! ))")
                    else
                        alert( "Form submission error. Please check the fields try again." );
                    console.log(data);
                });
            });
        }
    }

   
    /*
     * DL support
     */
    // if (settings.lang == 'ko'/* || settings.lang == 'ja' || settings.lang == 'ru' || */)
    //     settings.langEGW = settings.lang; // var for DL
    // else
    //     settings.langEGW = 'en';


    $('.sdarm-dl').on('mouseenter', 'citation', function (e) {
        var element = $(this).parents('article').find("biblioentry[xreflabel='" + this.textContent +  "']").find(".target-text"); 
        if (element.length) {
            $(this).attr("title", element.get(0).innerText);
            $(this).addClass("source-link");
        }
    });

    // adjust source view width
    $('.sdarm-dl').on('click', ".source-link, citation", function () {
        if (location.href.indexOf('pab/2') > -1) {
            $(".source-view-dialog").css('width', this.parentElement.clientWidth + 20);
            $(".source-view-dialog").css('top', this.parentElement.offsetTop + parseInt(getComputedStyle(this.parentElement).paddingTop));
        }
    });
    
    $('#button-ref-dlg').click(function() {
        let targetText = $('.target-text');
        SourceLink('', $(this), 
            targetText.offset().left, 
            this.top, // + this.height + 10, 
            targetText.width() + 20, 
            window.innerHeight * 0.5);
    })
    
    $('#button-diff-missmatch').click(function() {
        // PAD - Text diff for mismatch
        if (settings.book.indexOf('pab') > 0) {
            try {
                const myWorker = new Worker('/js/diff_worker.js');
                myWorker.onmessage = (e) => {
                    console.log(`Message received from worker - ID: ${e.data[0]} ST: ${e.data[1]} TT: ${e.data[2]}`);
                    
                    let p = $(`#${e.data[0]}`);
                    let source = p.find('.source-text');
                    let target = p.find('.target-text');
                    let sh = source.html();
                    let idx = sh.lastIndexOf('—');
                    if (idx > -1) {
                        sh = sh.substring(idx); // source link
                        idx = e.data[1].lastIndexOf('—');
                        if (idx > -1) {                            
                            let st = e.data[1].slice(0, idx);
                            sh = st + sh;
                        }
                        else {
                            sh = e.data[1] + sh;
                        }
                    }
                    source.html(sh);
                    target.html(e.data[2].replaceAll('@@@', '<br />'));
                    let elemeents = target.find(".matched");
                    if (elemeents.length > 0) {
                        elemeents[0].scrollIntoView();
                    }
                    
                    $("#jump-to-parag-id").val(`${e.data[0]}`);
                }

                $(".editable").each(function () {
                    const target = $(this).find(".source-warning");
                    if(target.length) {
                        // check if previous paragraph contains "SEE THE FOLLOWING PARAGRAPH"
                        let prevText = '';
                        let sibling = $(this).prev(".editable");
                        while (sibling.length) {
                            if (sibling.find(".target-text").text().indexOf("SEE THE FOLLOWING PARAGRAPH.") > -1) {
                                const el = sibling.find(".source-text");
                                prevText = el.text() + "<br />" + prevText;
                                el.text("SEE THE FOLLOWING PARAGRAPH.");
                                sibling = sibling.prev(".editable");
                            }
                            else {
                                break;
                            }
                        }

                        const source = $(this).find(".source-text");
                        const label =$(this).attr("id");
                        let th = target.html();
                        let startPos = th.indexOf('<span class="pab-matched">');
                        startPos = startPos < 400 ? 0 : startPos - 400; 
                        th = '... ' + th.substring(startPos);
                        th = th.replaceAll('</parag>', '@@@');
                        const tt = th.replaceAll(/<\/?[^>]+(>|$)/g, "");
                        myWorker.postMessage([label, prevText + source.text(), tt]);
                    };
                });
            }
            catch(e) {
                console.log(e);
            }
        }
        
    })

});
