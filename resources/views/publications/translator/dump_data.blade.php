<!doctype html>
<html>
    <head>
        <title>SDARM Publication Translator</title>
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {!! Html::style('css/frontend.css') !!}
        <!--script>window.jQuery || document.write('<script src="/js/vendor/jquery/jquery-2.1.4.min.js"><\/script>')</script-->
        {!! Html::script('js/jquery.min.js') !!}
        
        <!--link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" /-->
        <!--script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script-->
    </head>
    <body>
        <script>
            
function dumpAll(content, filename, contentType)
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
        self.iDB.onerror = function (e) {
            console.log("indexedDB: Error creating/accessing IndexedDB database");
        };

        var store = self.iDB.transaction(["SblText"], "readonly").objectStore("SblText");
        var request = store.get(documentID);
        request.onsuccess = function (e) {
            // ready data stack on memory
            if (e.target.result != undefined) {
                var text = e.target.result;
                dumpAll(text, settings.book + settings.year + "_" + settings.issue + "_" + settings.lang + "_TranslatorDataDump.txt", "text/plain;charset=utf-8");
            }
            else
                alert("ERROR! Please contact the administrator.");
        }
        request.onerror = function (e) {
            console.log("indexedDB: indexedDB get error.", e.target.error.name);
        }
    }

}


$(document).ready(function ()
{
    dataStore = new idbStorage('{!! $docId !!}');
});

        </script>
    </body>
</html>
