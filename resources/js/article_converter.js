  /*
   * article_converter.js
   *
   * 2019.08.22 by Sean Kim
   * 
   * This file uses "iarna/rtf-parser" library from github.com
   * There are some modification in the library to support control token checking.
   * All modified lines has comment as "// sean" to mark it.
   */
  import rtfParser from "./rtf-parser-sdarm/index.js";
  
   $(function () {
    /*
    * data storage
    */
    function idbStorage(documentID, tableName, dbName, initFunction) {
      // implement undo feature for entire text
      this.docId = documentID;
      this.lastElement = 0; // last edited element id.
  
      this.maxUndo = 7;
      this.dataStack = []; // array of {contents:"", lastElement:""}, hold undo data
  
      this.iDB = null;
      var self = this;
      var indexedDB = window.indexedDB || window.webkitIndexedDB || window.mozIndexedDB || window.OIndexedDB || window.msIndexedDB,
          IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.OIDBTransaction || window.msIDBTransaction,
          dbVersion = 1.0;
      var openRequest = indexedDB.open(dbName, dbVersion);
  
      openRequest.onupgradeneeded = function (e) {
        console.log("indexedDB: running onupgradeneeded");
        var thisDB = e.target.result;
        thisDB.createObjectStore(tableName);
      };
  
      openRequest.onsuccess = function (e) {
        //console.log("Success!");
        self.iDB = e.target.result;
  
        self.iDB.onerror = function (event) {
          console.log("indexedDB: Error creating/accessing IndexedDB database");
        };
  
        var store = self.iDB.transaction([tableName], "readonly").objectStore(tableName);
        var request = store.get(documentID);
  
  
        request.onsuccess = function (e) {
          // clear up previous (one year old) data
          // var year = (parseInt($("#year").val()) - 1).toString();
          // for (iNomber = 1; iNomber <= 6; iNomber++) {
          //   oldId = "translation-" + "rmrh" + "-" + year + "-" + iNomber + "-" + "en";
          //   self.deleteRecord(oldId);
          // }
          // for (iNomber = 1; iNomber <= 4; iNomber++) {
          //   oldId = "translation-" + "ym" + "-" + year + "-" + iNomber + "-" + "en";
          //   self.deleteRecord(oldId);
          // }
  
          // ready data stack on memory
          if (e.target.result != undefined) {
            var result = e.target.result;
            self.dataStack = JSON.parse(result);
            if (!self.canUndo) $("#command-undo").addClass("disabled");else $("#command-undo").removeClass("disabled");
          }
  
          if (initFunction) initFunction();
        };
  
        request.onerror = function (e) {
          console.log("indexedDB: indexedDB get error.", e.target.error.name);
        };
      };
  
      openRequest.onerror = function (e) {
        console.log("indexedDB: indexedDB open error.");
        console.dir(e);
      };
  
      this.put = function (key, data) {
        var transaction = self.iDB.transaction([tableName], "readwrite");
        var store = transaction.objectStore(tableName); //Perform the add
  
        var request = store.put(data, key);
  
        request.onerror = function (e) {
          console.log("indexedDB: indexedDB put error.", e.target.error.name);
        };
  
        transaction.oncomplete = function (event) {
          console.log("indexedDB: Data saved.");
        };
      };
  
      this.get = function (key, fn) {
        var transaction = self.iDB.transaction([tableName], "readonly");
        var store = transaction.objectStore(tableName);
        var request = store.get(key);
  
        request.onsuccess = function (e) {
          var result = e.target.result;
          fn(result);
        };
  
        request.onerror = function (e) {
          console.log("indexedDB: indexedDB get error.", e.target.error.name);
        };
      };
  
      this.deleteRecord = function (key) {
        // open a read/write db transaction, ready for deleting the data
        var transaction = self.iDB.transaction([tableName], "readwrite");
  
        transaction.oncomplete = function (event) {
          console.log("indexedDB: Record delete transaction completed");
        };
  
        var objectStore = transaction.objectStore(tableName);
        var request = objectStore["delete"](key);
  
        request.onsuccess = function (e) {
          console.log("indexedDB: Record deleted. KEY: " + key);
        };
      };
      /* interfaces */
      // undo interface	
  
  
      this.canUndo = function () {
        if (this.dataStack.length > 1) return true;else return false;
      };
  
      this.putStore = function (data, factor) {
        var elementId = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '0';
        this.lastElement = elementId; // manage stack
  
        if (this.dataStack.length > this.maxUndo) this.dataStack.shift();
        var currentData = {};
        currentData.contents = data;
        currentData.lastElement = elementId;
        currentData.subTitle = $('#book-subtitle').val();
        currentData.factor = factor;
        this.dataStack.push(currentData);
        this.put(this.docId, JSON.stringify(this.dataStack));
        $("#command-save").addClass("disabled");
        $("#command-undo").removeClass("disabled");
      };
  
      this.popStore = function () {
        if (this.dataStack.length > 1) {
          this.dataStack.pop();
          this.put(this.docId, JSON.stringify(this.dataStack));
          var currentData = this.dataStack[this.dataStack.length - 1];
          this.lastElement = currentData.lastElement;
          $('#book-subtitle').val(currentData.subTitle);
          return currentData.contents;
        } else {
          $("#command-undo").addClass("disabled");
          return "";
        }
      };
  
      this.getCurrent = function () {
        if (this.dataStack.length > 0) {
          var currentData = this.dataStack[this.dataStack.length - 1];
          this.lastElement = currentData.lastElement;
          $('#book-subtitle').val(currentData.subTitle);
          return currentData.contents;
        } else return null;
      };
  
      this.getAll = function () {
        if (this.dataStack.length > 0) {
          return this.dataStack;
        } else return null;
      };
    }
  
    var dataStore = null; // local storage variable
  
    /* 
     * RTF => HTML Document 
     */
  
    $("#file-source").change(function (e) {
      // getting a hold of the file reference
      var file = e.target.files[0]; // setting up the reader
  
      var reader = new FileReader();
  
      if (file.name.endsWith(".rtf") || file.name.endsWith(".txt")) {
        reader.onload = function (readerEvent) {
          var content = readerEvent.target.result; // this is the content!
          $("#source-text").val(content);
          parseRtf(content);
        };
        reader.readAsText(file, 'UTF-8');
      } else if (file.name.endsWith(".xml")) {
        reader.onload = function (readerEvent) {
          var content = readerEvent.target.result; // this is the content!
  
          $("#source-text").val(content);
          // content = content.replace(/\n\s*/g, "");
          document.getElementById("panel-editor-content").innerHTML = content;
          dataStore.putStore(content, 0);
        };
        reader.readAsText(file, 'UTF-8');
      } else {
        alert("ERROR:Unknown file type!");
      }
    });
  
    $("#book-name, #lang, #year, #issue").change(function () {
      if ($("#book-name").val() != '' && $("#lang").val() != '' && $("#year").val() != '' && $("#issue").val() != '') {
        if ($("#book-name").val() == 'rmrh') {
          $("#book-title").val("The Reformation Herald");
          $("#volume").val( (parseInt($("#year").val()) - 2020 + 61).toString() ); //year 2020 Volume number is RH - 61, and YM - 39
        }
        else if ($("#book-name").val() == 'ym') {
          $("#volume").val( (parseInt($("#year").val()) - 2020 + 39).toString() ); //year 2020 Volume number is RH - 61, and YM - 39
          $("#book-title").val("Youth Messenger");
          let issue = parseInt($("#issue").val());
          if (issue == 1) $("#book-subtitle").val( "January-March" );
          else if (issue == 2) $("#book-subtitle").val( "April-June" );
          else if (issue == 3) $("#book-subtitle").val( "July-September" );
          else if (issue == 4) $("#book-subtitle").val( "October-December" );
        }
  
        var documentId = "".concat($("#book-name").val(), '-', $("#lang").val(), '-', $("#year").val(), '-', $("#issue").val());
        dataStore = new idbStorage(documentId, "RtfToHtml", "ArticleConverter", function () {
          content = dataStore.getCurrent();
          w = $("#panel-editor-content").width();
          if (content) {
            $("#panel-editor-content").html(content);
  
            if ($(".selected").length > 0) {
              $("#panel-editor-content").scrollTop($(".selected").position().top);
            }
          } 
          else {
            $("#panel-editor-content").html("<div>No data stored.</div>");
            $("#book-subtitle").val("");
          }
          $("#panel-editor-content").width(w);
        });
      }
    });

    $("#book-subtitle").change(function () {
      dataStore.putStore($("#panel-editor-content").html(), 0);
      console.log("#book-subtitle changed!");
    });
  
    $("#button-convert").click(function () {
      parseRtf($("#source-text").val());
    });
  
    function parseRtf(content) {
      if ($("#year").val() == '' || $("#issue").val() == '') {
        alert("Enter the book name, year and issue!");
        return;
      }
  
      if (content.substring(0, 5) != "{\\rtf") {
        // plain text
        content = encodeRtf(content);
        content = "{\\rtf1 {" + content.replace(/\n+/g, "\n").replace(/\t+/g, ' ').replace(/ *\n */g, "\\par }\n{\\pard ").replace('/ +/g', ' ') + "}}";
      } else {
        // simplify rtf
        content = content.replace(/((\\uld)([\\ ]))|((\\uldash)([\\ ]))|((\\uldashd)([\\ ]))|((\\uldashdd)([\\ ]))|((\\ulnone)([\\ ]))|((\\ulth)([\\ ]))|((\\ulw)([\\ ]))|((\\ulwave)([\\ ]))/g, "\\ul$3$6$9$12$15$18$21$24");
      }
  
      rtfParser.string(content, function (err, doc) {
        if (err) alert(err.toString());else {
          var element = document.getElementById("panel-editor-content");
          element.innerHTML = '';
          element.insertAdjacentHTML('beforeend', toHtml(doc));
          if (dataStore) dataStore.putStore($("#panel-editor-content").html(), 0);else {
            var documentId = "".concat($("#book-name").val(), '-', $("#lang").val(), '-', $("#year").val(), '-', $("#issue").val());
            dataStore = new idbStorage(documentId, "RtfToHtml", "ArticleConverter", 
              function () {
                dataStore.putStore($("#panel-editor-content").html(), 0);
              }
            );
          }
        }
      });
    }
  
    var isAutoCap = $( "#auto-cap" ).is(':checked');
    function toHtml(doc) {
      var mode = '';
      var strHtml = "";
      var strArticle = "";
      var strSection = "";
      var titleCount = 0;
      var isFirstParag = false;
      doc.content.forEach(function myFunction(item, indexParag) {
        if (item.value != undefined) { // RTFSpan
          var _str = doSpan(item, null);
          // _str = '<p class="' + styleToString(item.style) + '">' + _str + "</p>\n";
          // if (mode == "article")
          //   strArticle += _str;
          // else if (mode == "sect")
          //   strSection += _str;
          // else
          //   strHtml += _str;
          console.log(_str);
        }
        else if (item.content.length == 0) { //empty parag
          return;
        } 
        else {
          let paragInnerHtml = '';
          let strText = '';
          if (item.content.length == 1) {
            strText = paragInnerHtml = item.content[0].value.trim();
          }
          else {
            item.content.forEach(function myFunction(span, indexSpan) {
              paragInnerHtml += doSpan(span, doc.content[indexParag]);
            });
            paragInnerHtml = paragInnerHtml.trim();
            strText = paragInnerHtml.replaceAll(/<\/?[^>]+>/g, "");
          }
          if (strText.length === 0)
            return;
          if (strText.match($("#pattern-article").val())) { // article
            if (mode == 'sect') {
              strArticle += '<div class="sect1">' + strSection + '</div>';
            }
            else if (mode == 'bibliography') {
              strArticle += '<div class="bibliography">' + strSection + '</div>';
            }
            if (mode != '') { 
              strHtml += '<div class="article">' + strArticle + '</div>';
            }
            mode = 'article';
            titleCount = 0;
            isFirstParag = true;
            if (strText.indexOf('@') == 0) {  // article title
              strText = strText.slice(1);
              strArticle = '<p class="title">' + strText + '</p>';
              strArticle += '<div class="abstract"><p class=""></p></div>';
              titleCount++;
            }
            else {
              strArticle = '<p class="keyword">' + strText + '</p>';
            }
          }
          else if (mode == 'bibliography') {  // continue bibliography
            if (strText.match($("#pattern-biblioentry").val())) { //biblioentry
              strSection += '<p class="biblioentry">' + strText + '</p>'; 
              citationNo++;
            }
            else {  // new article
              mode = 'article';
              titleCount = 0;
              isFirstParag = true;
              strArticle += '<div class="bibliography">' + strSection + '</div>';
              strHtml += '<div class="article">' + strArticle + '</div>';
              if (strText.match($("#pattern-article").val())) {  // pattern keyword
                strArticle = '<p class="keyword">' + strText + '</p>';
                strArticle += '<div class="abstract"><p class=""></p></div>';
              }
              else if (strText.match($("#pattern-title").val())) { // title
                if (strText.indexOf('@') == 0) {
                  strText = strText.replace('@', '');
                }
                strArticle = '<p class="title">' + strText + '</p>';
                strArticle += '<div class="abstract"><p class=""></p></div>';
                titleCount ++;
              }
              else { // regular paragraph
                if (isAutoCap && isFirstParag) {
                  paragInnerHtml = '<span class="cap">' + paragInnerHtml.charAt(0) + '</span>' + paragInnerHtml.substring(1);
                  isFirstParag = false;
                }
                strArticle = '<p class="">' + paragInnerHtml + '</p>';
              }
            }
          }
          else if (strText.match($("#pattern-bibliography").val())) { //bibliography
            if (mode == 'sect') {
              strArticle += '<div class="sect1">' + strSection + '</div>';
            } 
            strSection = '<p class="title">' + strText + '</p>';
            mode = 'bibliography';
            citationNo = 1;
          } 
          else if (strText.length < 50 && strText.match($("#pattern-auther").val())) {  //author
            if (mode == 'sect') { // start new article - take current section as the beginning of new article
              strHtml += '<div class="article">' + strArticle + '</div>';
              mode = 'article';
              // titleCount = 1;  // uncertain if the previous section has title or not
              strArticle = strSection;  // contains only title
              strSection = '';
            }
            strArticle += '<p class="author">' + strText + '</p>';
          } 
          else if (strText.match($("#pattern-title").val())) { // title
            if (strText.indexOf('#') == 0) {   // section title
              strText = strText.slice(1);
            }
            if (titleCount == 0) {  // article title - reset article from previous lines.
              strArticle += '<p class="title">' + strText + '</p>';
              strArticle += '<div class="abstract"><p class=""></p></div>';
              isFirstParag = true;
              if (mode == '')  // for the first article.
                mode = 'article';
            }
            else {  // section title
              if (mode == 'sect') { // close previous section
                strArticle += '<div class="sect1">' + strSection + '</div>';
              }
              strSection = '<p class="title">' + strText + '</p>';
              mode = 'sect';
            }
            titleCount ++;
          } 
          else {
            // regular paragraph
            paragInnerHtml = paragInnerHtml.replace(/([”\.])\s*(\d+)$/, '$1<span class="citation">$2</span>');
            if (isAutoCap && isFirstParag) {
              paragInnerHtml = '<span class="cap">' + paragInnerHtml.charAt(0) + '</span>' + paragInnerHtml.substring(1);
              isFirstParag = false;
            }
            let strParag = '<p class="' + styleToString(item.style) + '">' + paragInnerHtml + "</p>\n";
            if (mode == "article")
              strArticle += strParag;
            else if (mode == "sect")
              strSection += strParag;
            else
              strHtml += strParag;
          }
        }
      }); 
  
      if (mode == "sect")
        strArticle += '<div class="sect1">' + strSection + '</div>';
      strHtml += '<div class="article">' + strArticle + '</div>';

      // replace unicode mis-interpreted characters
      strHtml = strHtml.replace(/É/g, '…').replace(/Ñ/g, '—').replace(/Ð/g, '–').replace(/Ê/g, '&#160;');
      return strHtml;
    }
  
    function getParagText(paragItem) {
      var text = '';
      paragItem.content.forEach(function myFunction(span) {
        text += span.value;
      });
      return text.trim();
    }
  
    function doSpan(item, parentParag) {
      var classAttr = ""; //if (item.value.length == 1 && parentParag != null && parentParag.content.length > 1 && item.value == parentParag.content[0].value && parentParag.content[1].value.length > 1) 
      //  classAttr += 'cap';
      //else 
  
      if (item.controls !== undefined) {
        if (item.controls["cs"] != undefined && item.controls["cs"] == $("#citation-style").val())
          classAttr += ' citation';
        else if (item.controls["super"] != undefined && item.value.match(/^\d+$/))
          classAttr += ' citation';
        if (item.controls["b"] != undefined) {
          if (item.controls["b"] == "0") item.style.bold = false;
          else classAttr += ' bold'; //item.style.bold = true;
        }
        if (item.controls["i"] != undefined) {
          if (item.controls["i"] == "0") item.style.italic = false;
          else classAttr += ' italic'; //item.style.italic = true;
        }
        if (item.controls["ul"] != undefined) {
          classAttr += ' underline'; //item.style.italic = true;
        } //classAttr += styleToString(item.style);  
      }
  
      if (classAttr.length == 0) return item.value;
      return '<span class="' + classAttr + '">' + item.value + '</span>';
    } // return style class name
  
  
    function styleToString(style) {
      var css = '';
  
      for (var property in style) {
        if (style.hasOwnProperty(property)) {
          if (property == "bold" && style[property]) css += " bold"; //css += "font-weight:bold;";
          else if (property == "italic" && style[property]) css += " italic"; // css += "font-style:italic;"
            else if (property == "underline" && style[property]) css += " underline"; // 
  
          ; //else if (property == "fontSize") css += "font-size:" + style[property] / 2 + ";";
        }
      } //if (css.length == 0) return '';
      //return 'style="' + css + '"';
      return css;
    }
  
  
    /*
     * Editing
     */
    var modified = false;
    var selectionRange = null;
    $("#panel-editor-content").attr("contenteditable", "true");
    $(".menu-attr").addClass("disabled");
  
  
    $("#panel-editor-content").on("keyup", function (event) {
      var code = event.keyCode ? event.keyCode : event.which;
  
      if (code > 32) {
        modified = true;
        $("#command-save").removeClass("disabled");
      }
  
      if (code === 13) {
        // CR || ESC
        $("#panel-editor-content *").removeClass("selected-attr-block");
        window.getSelection().anchorNode.classList.add("selected-attr-block");
      } else if (code === 27) {
        // ESC
        ;
      } else if (event.ctrlKey) {
        if (code === 90 || code === 122) {
          // Zz
          ;
        } else if (event.shiftKey && event.altKey && (code === 82 || code === 114)) {
          // Rr
          ;
        } else if (event.shiftKey && event.altKey && (code === 68 || code === 100)) {
          // Dd
          ;
        }
      }
    });
    
  
    $(document).on('mouseup', function (e) {
      // selection control
      if (e.target.compareDocumentPosition(document.getElementById("panel-editor-content")) & Node.DOCUMENT_POSITION_CONTAINS) {
        selectionRange = window.getSelection().getRangeAt(0);

        if (!selectionRange.collapsed && selectionRange.endOffset == 0) {
          // prevent the end offset point at the beginning of the node
          var endElement = selectionRange.endContainer.nodeType == 1 ? selectionRange.endContainer.previousElementSibling : selectionRange.endContainer.parentElement.previousElementSibling;
          selectionRange.setEnd(endElement.lastChild, endElement.lastChild.textContent.length);
        }

        $("#panel-editor-content *").removeClass("selected-attr-block"); // for Indication

        if (selectionRange.collapsed) {
          var element = window.getSelection().anchorNode.parentElement;
          var tags = '';

          if (element.attributes.length > 1) { // if attributes other then 'class' exist
            tags += '[';
            for (let idx = 0; idx < element.attributes.length; idx++) {
              if (element.attributes[idx].name != 'class') {
                tags += '@' + element.attributes[idx].name + '=' + element.attributes[idx].value;
              }
            }
            tags += ']';
          }
          while (element.id != "panel-editor-content") {
            tags = "<div style='display:inline; margin:0; padding:0' class='" + element.className 
                  + " selected-element-indicator'>&gt;&nbsp;" + element.tagName + "." + element.className + tags;
            element = element.parentElement;
          }

          $("#selection-indicator").html(tags);
          window.getSelection().anchorNode.parentElement.classList.add("selected-attr-block");
        } 
        else {
          var _element = selectionRange.startContainer.parentElement;
          var startTags = '';

          while (_element.id != "panel-editor-content") {
            startTags = "<div style='display:inline; margin:0; padding:0' class='" + _element.className + " selected-element-indicator'>&gt;&nbsp;" + _element.tagName + "." + _element.className + startTags;
            _element = _element.parentElement;
          }

          var endTags = "";
          _element = selectionRange.endContainer.parentElement;

          while (_element.id != "panel-editor-content") {
            endTags = "<div style='display:inline; margin:0; padding:0' class='" + _element.className + " selected-element-indicator'>&gt;&nbsp;" + _element.tagName + "." + _element.className + endTags;
            _element = _element.parentElement;
          }

          $("#selection-indicator").html(startTags + "&nbsp;&nbsp;...&nbsp;&nbsp;" + endTags);
        }

        $(".menu-attr").removeClass("disabled");
      } 
      else if (selectionRange && (e.target.classList.contains("menu-attr") || e.target.classList.contains("selected-element-indicator"))) {
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(selectionRange);
      } 
      else {
        // window.getSelection().empty();  // The selection is on the Event Target.
        selectionRange = null;
        $("#panel-editor-content *").removeClass("selected-attr-block");
        $(".menu-attr").addClass("disabled");
        $("#selection-indicator").html("");
      }
    });
  
  
    $(".menu").click(function (e) {
      if (this.className.includes("disabled")) {
        if (this.className.includes("menu-attr")) alert("Select content to apply the attribute!");
        console.log("Disabled menu clicked: '" + this.textContent + "'");
      }
  
      var attr = this.textContent;
  
      if (attr == 'save') {
        if (dataStore && modified) dataStore.putStore($("#panel-editor-content").html(), attr);
        return;
      } else if (attr == 'undo attribute') {
        var data = dataStore.popStore();
        if (data.length > 0) $("#panel-editor-content").html(data);
        return;
      } else if (attr == 'export') {
        exportXML();
        $( "#button-do-validation" ).click();
        return;
      }
  
      if (selectionRange == null) return;
      var startParaElement = selectionRange.startContainer.parentElement.closest('p');
      var endParaElement = selectionRange.endContainer.parentElement.closest('p');

      if (this.className.includes('block-attr')) {
        /*
        Apply block attribute
        1. get closest common ancestor element for the selection range
        2. toggle the attr for the common closest or it's children elements
        */
        let curElement = startParaElement;
        let startHierarch = [];
        while (curElement) {
          startHierarch.push(curElement);
          if (curElement.classList.contains("book")) {
            break;
          }
          curElement = curElement.parentElement;
        }
        curElement = endParaElement;
        let endHierarch = [];
        while (curElement) {
          endHierarch.push(curElement);
          if (curElement.classList.contains("book")) {
            break;
          }
          curElement = curElement.parentElement;
        }
        // find closest common ancestor
        let commonParentElement = null;
        let commonParentIndex = -1;
        let commonDepth = -(Math.min(startHierarch.length, endHierarch.length));
        for ( commonParentIndex = -1; commonParentIndex > commonDepth; commonParentIndex --) {
          commonParentElement = startHierarch.at(commonParentIndex);
          if (startHierarch.at(commonParentIndex-1) != endHierarch.at(commonParentIndex-1)) {
            commonParentIndex--;
            break;
          }
        }
        commonParentIndex ++; // adjust index after loop
        if (commonParentElement == startParaElement) {  // selection is inside the same element
          commonParentElement = startParaElement.parentElement;
        }

        function addAttr(attr, startChildElement, endChildElement) {
          // check if the attr can be applied

          let parentElement = startChildElement.parentElement;
          let wrappingElement = document.createElement("div");
          wrappingElement.classList.add(attr);

          let el = startChildElement;
          let insertElement = null;
          if ((attr.includes('sect') || attr.includes('bibliography')) 
                && el.textContent.match($("#pattern-title").val())) {  // check title paragraph
            el.innerHTML = el.textContent;
            el.className = 'title';
          }
          do {
            insertElement = el;
            el = el.nextElementSibling;
            // clear span attr on “abstract”, “annotation”, “epigraph” and "bibliography"
            if (['abstract', 'annotation', 'epigraph'].includes(attr)) {
              insertElement.innerHTML = insertElement.textContent;
            }
            else if (attr.includes('bibliography') && insertElement.tagName == 'P') {
              if (insertElement != startChildElement) {
                insertElement.innerHTML = insertElement.textContent;
                insertElement.className = 'biblioentry';
              }
            }
            wrappingElement.appendChild(insertElement);
            if (insertElement == endChildElement) {
              break;
            }
          } while (el && !el.classList.contains(attr));  // stop at next same attr element

          if (el) {
            parentElement.insertBefore(wrappingElement, el);
            if (insertElement == endChildElement)  { // finished 
              el = null;
            }
          }
          else {
            parentElement.appendChild(wrappingElement);
          }
          return el;
        }

        function removeAttr(element) {
          let parent = element.parentElement;
          while (element.firstElementChild) {
              parent.insertBefore(element.firstElementChild, element);
          }
          parent.removeChild(element);
        }

        if (commonParentElement.classList.contains(attr)) {
          // remove parent attr
          removeAttr(commonParentElement);
        } else {
          curElement = startHierarch.at(commonParentIndex-1);
          let endElement = endHierarch.at(commonParentIndex-1);
          let attrOrder = {
              article : 1,
              sect1 : 2,
              sect2 : 3,
              sect3 : 4,
              abstract : 5,
              epigraph : 5,
              annotation : 5,
              bibliography : 5
          };
          if (attrOrder[attr] <= attrOrder[commonParentElement.className]
              || attrOrder[attr] > attrOrder[curElement.className]) {
            alert("Can not apply the attribute here!");
            return;
          }
          do {
            if (curElement.classList.contains(attr)) {
              // remove attr
              let child = curElement;
              if (curElement == endElement) {
                curElement = null;
              }
              else {
                curElement = curElement.nextElementSibling;
              }
              removeAttr(child);
            } else {
              // add attr
              curElement = addAttr(attr, curElement, endElement);
            }
          } while (curElement);
        }
        window.getSelection().empty();
        selectionRange = null;  // when elements hierarchy changes, current selection range goes invalid.
        $("#panel-editor-content *").removeClass("selected-attr-block");
        $(".menu-attr").addClass("disabled");
        $("#selection-indicator").html("");
      }
      else if (this.className.includes('parag-attr-overwrite') || attr == 'plain') {
        let nextGroupElement = endParaElement.nextElementSibling;
        let nextElementSibling = null;
        do {
          nextElementSibling = startParaElement.nextElementSibling;
          startParaElement.innerHTML = startParaElement.textContent;
  
          if (attr == 'plain') {
            startParaElement.className = '';
          } else startParaElement.className = attr;
  
          startParaElement = nextElementSibling;
        } while (startParaElement && startParaElement != nextGroupElement); // reset selection
  
        window.getSelection().empty()
        selectionRange = null;
        $("#panel-editor-content *").removeClass("selected-attr-block");
        $(".menu-attr").addClass("disabled");
        $("#selection-indicator").html("");
      } else if (this.className.includes('parag-attr')) {
      }
      else if (this.className.includes('span-attr')) {
        if (selectionRange.collapsed) 
          return;
        /*
          Travese PARAG
          get BOOK child anscester of start parag
          starting at the anscester
            for each child
              apply parag (start from StartParagElement)
              till parag == EndParagElement
        */
        function applyAttr(containner) {
          do {
            if (containner instanceof Element) {  // containner should be a node to compare with selected container
              containner = containner.firstChild;
            }
            const startOffset = (containner == selectionRange.startContainer) ? selectionRange.startOffset : 0; 
            const endOffset = (containner == selectionRange.endContainer) ? selectionRange.endOffset : containner.textContent.length; 
            const preceddingText = containner.textContent.substr(0, startOffset);
            const newAttrText = containner.textContent.substring(startOffset, endOffset);
            const followingText = containner.textContent.substring(endOffset);
            let parentElement = containner.parentElement;
            const theContainner = containner;
            if (parentElement.tagName == 'SPAN') {  // text node under SPAN element
              // To prevent cascade SPAN, parent should be 'P' always. (selection started at a SPAN.)
              containner = parentElement;
              parentElement = parentElement.parentElement;  // 'P'
            }
            let newAttrNode = null;
            // containner == text node, startContainer or endContainer under SPAN
            if (containner.tagName != 'SPAN') { 
              if (preceddingText.length) {
                const textNode = document.createTextNode(preceddingText);
                parentElement.insertBefore(textNode, containner)
              }
              newAttrNode = document.createElement('span');
              newAttrNode.className = attr;
              newAttrNode.textContent = newAttrText;
              parentElement.insertBefore(newAttrNode, containner);
              if (followingText.length) {
                const textNode = document.createTextNode(followingText);
                parentElement.insertBefore(textNode, containner)
              }
            }
            // containner.tagName == 'SPAN'
            else {
              if (preceddingText.length) {
                const precedingSpan = document.createElement('span');
                precedingSpan.className = containner.className;
                precedingSpan.textContent = preceddingText;
                parentElement.insertBefore(precedingSpan, containner)
              }
              if (containner.classList.contains(attr)) { // remove attr to the selected text
                if (containner.classList.length == 1) {
                  newAttrNode = document.createTextNode(newAttrText);
                }
                else {
                  newAttrNode = document.createElement('span');
                  newAttrNode.className = containner.className;
                  newAttrNode.classList.remove(attr);
                  newAttrNode.textContent = newAttrText;
                }
              }
              else { // add attr to the selected text
                newAttrNode = document.createElement('span');
                newAttrNode.className = containner.className;
                newAttrNode.classList.add(attr);
                newAttrNode.textContent = newAttrText;
              }
              parentElement.insertBefore(newAttrNode, containner)
              if (followingText.length) {
                const followingSpan = document.createElement('span');
                followingSpan.className = containner.className;
                followingSpan.textContent = followingText;
                parentElement.insertBefore(followingSpan, containner);
              }
            }
            // selection re-position
            if (newAttrNode instanceof Element) {
              newAttrNode = newAttrNode.firstChild;
            }
            if (theContainner == selectionRange.startContainer) {
              selectionRange.setStart(newAttrNode, 0);
            }
            const oldEndSelectionContainer = selectionRange.endContainer;
            if (theContainner == selectionRange.endContainer) {
              selectionRange.setEnd(newAttrNode, newAttrNode.textContent.length);
            }
            const oldContainer = containner;
            if (theContainner != oldEndSelectionContainer) {
              containner = containner.nextSibling;
            }
            else {
              containner = null;
            }
            oldContainer.remove();
          } while(containner);
        }

        // process all decendent parag starting from StartParagElement
        let stop = false;
        let applying = false;
        function traverse(element) {
          if (stop || !element || element.classList.contains("book"))
            return;

          if (element.tagName != 'P') {
            for (const childElement of element.children) {
              traverse(childElement);
              if (stop) {
                return;
              }
            }
          }
          else {  // for each 'P'
            if (applying || element == startParaElement) {
              if (!element.classList.contains('keyword')
                  && !element.classList.contains('title')
                  && !element.classList.contains('subtitle')
                  && !element.classList.contains('part-no')
                  && !element.classList.contains('author')
                  && !element.classList.contains('biblioentry')
              ) {
                let startContainer = (element == startParaElement) ? selectionRange.startContainer : element.firstChild;
                applyAttr(startContainer);
              }
              applying = true;
            }
            if (element == endParaElement) {
              stop = true;
            }
            element = element.nextElementSibling;
            traverse(element);
          }
        }

        let bookChild = startParaElement;
        while (!bookChild.parentElement.className.includes('book')) {
          bookChild = bookChild.parentElement;
        }
        while (bookChild) {
          traverse(bookChild);
          if (stop)
            break;
          bookChild = bookChild.nextElementSibling;
        }
      }
      dataStore.putStore($("#panel-editor-content").html(), attr);
      $("#hover-element").text(attr);
    });

// debug
// $("#lang").val("en");
// $("#issue").val("1");
// $("#issue").change();


    /*
    * Export to XML
    */
  
    var infoElement = null;
    var articleRole = "";
    var xref = 0; // for <biblioentry xreflabel>
  
  
    function convertParagChild(paraElement) {
      var newParag = null;
      try {
        if (paraElement.className.length > 0 && paraElement.className.includes('subtitle')) {
          newParag = document.createElement('subtitle');
          newParag.innerHTML = paraElement.innerText;
        } else if (paraElement.className.length > 0 && paraElement.className.includes('title')) {
          newParag = document.createElement('title');
          newParag.innerHTML = paraElement.innerText;
        } else if (paraElement.className.includes('keyword')) {
          $(infoElement).append("<keywordset><keyword>" + paraElement.innerText + "</keyword></keywordset>");
          articleRole = paraElement.innerText;
        } else if (paraElement.className.includes('part-no')) {
          var volume = paraElement.innerText.match(/\d+/);
          if (volume) $(infoElement).append("<volumenum>" + volume[0] + "</volumenum>");else $(infoElement).append("<volumenum>" + paraElement.innerText + "</volumenum>");
        } else if (paraElement.className.includes('author')) {
          let names = "";
          let country = "";
          let xmlString = "";
          let tmp = paraElement.innerText.trim().replace("–", "-").split("-");
          if (tmp.length > 1) {
            names = tmp[0].trim();
            country = tmp[1].trim();
          }
          else {
            names = tmp[0].trim();
          }
          var nameArray = names.trim().replace("By ", "").replace("by ", "").replace("  ", " ").split(" ");
          if (nameArray.length == 3) 
            xmlString = "<personname><firstname>" + nameArray[0] + "</firstname><othername>" + nameArray[1] + "</othername><surname>" + nameArray[2] + "</surname></personname>";
          else if (nameArray.length == 2) 
            xmlString = "<personname><firstname>" + nameArray[0] + "</firstname><surname>" + nameArray[1] + "</surname></personname>";
          else 
            xmlString = "<personname><othername>" + nameArray.join(' ') + "</othername></personname>";
          if (country.length > 0) {
            xmlString += "<address><country>" + country + "</country></address>";
          }
          $(infoElement).append("<author>" + xmlString + "</author>");
        } else if (paraElement.className.includes('biblioentry')) {
          var newParag = document.createElement('biblioentry');
          var text = paraElement.innerText;
          /*if (text.match(/^[1-9]/)) {
            xref = text.split('\t').length > 1 ? text.split('\t') : text.split(' ');
          }
          else {
            xref++;
          }*/
          text = text.replace(/^[1-9]+[ \t]/, '');
          newParag.setAttribute('xreflabel', xref);
          $(newParag).append("<title>" + text + "</title>");
          xref++;
        } 
        else {
          newParag = document.createElement('para');
          newParag.innerHTML = paraElement.innerHTML;
          var prevLastSpan = null;
          var prevSpanRoles = '';
          var spanRoles = '';
    
          for (spanIndex = 0; spanIndex < newParag.childNodes.length; spanIndex++) {
            var spanElement = newParag.childNodes[spanIndex];
            if (spanElement.nodeType != 1) {
              // element other then span
              prevSpanRoles = "";
              continue;
            }
            if (spanElement.innerText.length == 0) {
              newParag.removeChild(spanElement);
              continue;
            }
            spanRoles = spanElement.className == undefined ? "" : spanElement.className.trim();
            var newSpan = null;
            if (spanElement.className.includes('olink')) {
              newSpan = document.createElement('olink');  // use this 'a', because 'xmlRoot.outerHTML' ignore 'link' inner text.
              newSpan.setAttribute('targetdoc', spanElement.getAttribute('targetdoc')); 
              newSpan.innerHTML = spanElement.innerHTML;
            } else if (spanElement.className.includes('citation')) {
              newSpan = document.createElement('citation');
              newSpan.innerHTML = spanElement.innerHTML;
            } else {
              newSpan = document.createElement('emphasis');
              if (prevSpanRoles != '' && prevSpanRoles == spanRoles) {
                prevLastSpan.innerHTML += spanElement.innerHTML;
                newSpan = null; // need to delete current span
                newParag.removeChild(spanElement);
                spanIndex--;
              } else {
                var spanArray = spanRoles.split(' ');
                newSpan.setAttribute('role', spanArray[0]);
                var childSpan = newSpan;
                for (ii = 1; ii < spanArray.length; ii++) {
                  var newChild = document.createElement('emphasis');
                  newChild.setAttribute("role", spanArray[ii]);
                  childSpan.appendChild(newChild);
                  childSpan = newChild;
                }
                childSpan.innerHTML = spanElement.innerHTML;
                prevLastSpan = childSpan;
              }
              prevSpanRoles = spanRoles;
            }
            if (newSpan) 
              newParag.replaceChild(newSpan, spanElement);
            }
        }
      }
      catch (error) {
        alert(`ERROR: Check console log. \nMessage: ${error}\nElement: ${paraElement}`);
        console.log(`ERROR: Check console log. \nMessage: ${error}\nElement: ${paraElement}`);
        console.log(paraElement);
      }
      return newParag;
    }
  
    function convertSectionChild(sectionElement) {
        var section = document.createElement(sectionElement.className);
        section.innerHTML = sectionElement.innerHTML; // need to implement block information
        var blockInfoElement = document.createElement('info');
        for (var _index = 0; _index < section.children.length; _index++) {
            var element = section.children[_index];
            var newElement = null;
            if (element.className?.includes('sect') || element.className?.includes('epigraph') || element.className?.includes('bibliography')) {
                newElement = convertSectionChild(element);
            } else if (element.className?.includes('abstract') || element.className?.includes('annotation')) {
                blockInfoElement.append(convertSectionChild(element));
            } else {
                newElement = convertParagChild(element);
            }
            if (newElement) 
                section.replaceChild(newElement, element);
            else {
                section.removeChild(element); // the element inserted into 'infoElement'
                _index--;
            }
        }
  
        if (blockInfoElement.children.length) section.insertBefore(blockInfoElement, section.children[0]);
            return section;
    }
    
  
    function exportXML() {
      if ($('#volume').val().length == 0 || $('#book-title').val().length == 0 || $('#book-subtitle').val().length == 0) {
        alert("Fill all empty fields!");
        return;
      }
  
      var xmlRoot = document.createElement("book");
      xmlRoot.setAttribute('status', (new Date()).toISOString());
      xmlRoot.setAttribute('xml:id', $('#book-name').val() + "-" + $('#lang').val() + "-" + $('#year').val() + "-" + $('#issue').val()); // "rmrh-en-2018-4"
      xmlRoot.setAttribute('annotations', "sdarm");
      xmlRoot.setAttribute('role', "periodical");
      xmlRoot.setAttribute('xml:base', $('#book-name').val());
      xmlRoot.setAttribute('xml:lang', $('#lang').val());
      xmlRoot.setAttribute('xmlns', "http://docbook.org/ns/docbook");
      // xmlRoot.setAttribute('xmlns:xlink', "http://www.w3.org/1999/xlink");
      xmlRoot.setAttribute('version', "5.0");
      $(xmlRoot).append("<title>" + $('#book-title').val() + "</title><subtitle>" + $('#book-subtitle').val() + "</subtitle>");
      $(xmlRoot).append("<info><date>" + $('#year').val() + "</date><issuenum>" + $('#issue').val() + "</issuenum><volumenum>" + $('#volume').val() + "</volumenum></info>");
      var articleList = document.querySelectorAll('#panel-editor-content .article');
  
      for (index = 0; index < articleList.length; ++index) {
        xref = 1;
        article = articleList.item(index);
        infoElement = document.createElement('info');
        var newArticle = document.createElement('article');
        newArticle.setAttribute('xml:id', 'article-' + (index + 1).toString());
        newArticle.innerHTML = article.innerHTML;
  
        for (let _index2 = 0; _index2 < newArticle.children.length; _index2++) {
          try {
              var element = newArticle.children[_index2];
              var newElement = null;
              if (element.className.includes('sect') || element.className.includes('epigraph') || element.className.includes('bibliography')) {
                newElement = convertSectionChild(element);
              } 
              else if (element.className.includes('abstract') || element.className.includes('annotation')) {
                infoElement.append(convertSectionChild(element));
              } 
              else {
                newElement = convertParagChild(element);
              }
              if (newElement) {
                if (newArticle != element.parentElement) 
                    lert("Error: Report to Sean, Please!!!");
                newArticle.replaceChild(newElement, element);
              } 
              else {
                newArticle.removeChild(element); // the element inserted into 'infoElement'
                  _index2--;
              }
          }
          catch (error) {
              console.log(`ERROR: Check console log. \nMessage: ${error}\nElement: ${element}`);
              alert(`ERROR: Check console log. \nMessage: ${error}\nElement: ${element}`);
              console.log(element);
          }
        }
        if (articleRole.length) {
          newArticle.setAttribute('role', articleRole); // meta information
        }
        if (newArticle.getElementsByTagName("TITLE").length == 0 || newArticle.getElementsByTagName("TITLE")[0].parentNode != newArticle) {
          alert("ERROR - No title for article no:" + parseInt(index + 1));
          article.scrollIntoView();
          return;
        }
        let titleCollection = newArticle.getElementsByTagName("SUBTITLE");
        if (titleCollection.length == 0) {
          titleCollection = newArticle.getElementsByTagName("TITLE")
        }
        if (titleCollection.length > 0) {
          newArticle.insertBefore(infoElement, titleCollection[0].nextSibling);
        }
        infoElement = null;
        articleRole = '';
        xmlRoot.append(newArticle);
      }
      var headerString = "<?xml version='1.0' encoding='UTF-8'?>\n"
                          + "<!DOCTYPE book PUBLIC '-//OASIS//DTD DocBook V5.0//EN' 'http://www.docbook.org/xml/5.0/dtd/docbook.dtd'>\n";
      var output = xmlRoot.outerHTML.replaceAll(/\&nbsp;/g, "&#xA0;");
      output = prettifyXml(output);
      output = output.replaceAll(/<[^\/>p]+\/>/gm, "");
      output = output.replaceAll(/<[^>\/p]+><\/[^>]+>/gm, ""); // remove all empty tags except '<para></para>'
      // output = output.replaceAll(/<a href=([^>]+)>([^<]*)<\/a>/gm, "<olink targetdoc=$1>$2</olink>"); // this line should be after 'prettifyXml()'
      $('#source-text').val(headerString + output);
      
      if (document.getElementById("auto-upload").checked) {
          $("#button-upload-xml").click();
      }
    }
  
    /*
     * Format XML
     *
     * Does not work with FF on xsltProcessor.importStylesheet(xsltDoc); ???
     */
    var prettifyXml = function prettifyXml(sourceXml) {
      //return sourceXml;
      var xmlDoc = new DOMParser().parseFromString(sourceXml, 'application/xml');
      var xsltDoc = new DOMParser().parseFromString([// describes how we want to modify the XML - indent everything
      '<?xml version="1.0"?>', 
      '<xsl:stylesheet  version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">', 
      '  <xsl:strip-space elements="*"/>', 
      '  <xsl:template match="para[content-style][not(text())]">', // change to just text() to strip space in text nodes
      '    <xsl:value-of select="normalize-space(.)"/>', 
      '  </xsl:template>', 
      '  <xsl:template match="node()|@*">',
      '    <xsl:copy><xsl:apply-templates select="node()|@*"/></xsl:copy>', 
      '  </xsl:template>', 
      '  <xsl:output indent="yes"/>', 
      '</xsl:stylesheet>'].join('\n'), 'application/xml');
      var xsltProcessor = new XSLTProcessor();
      xsltProcessor.importStylesheet(xsltDoc);
      var resultDoc = xsltProcessor.transformToDocument(xmlDoc);
      var resultXml = new XMLSerializer().serializeToString(resultDoc);
      return resultXml.replace('<?xml version="1.0" encoding="UTF-8"?>\n', '');  // for FF
    };
  
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
  
    // Diable ignorable on RTF converting
    // Used in RTFParser defined above
    window.disableIgnorable = true;
    $( "#disable-ignorable" ).click(function() {
      disableIgnorable = this.checked;
    });
  
    // Automatic CAPtion to first paragraph of each article on converting
    $( "#auto-cap" ).click(function() {
      isAutoCap = this.checked;
    });
  
    // Validate produced XML on the server
    $( "#button-do-validation" ).click(function() {
      var bookXML;
      let xml = $("#source-text").val();
      if (xml.length > 0 && xml.indexOf("<?xml version=") > -1) {
        xml = xml.substring( xml.indexOf("<book") );
        var xmlRoot = document.createElement("dev");
        xmlRoot.innerHTML = xml;
        bookXML = xmlRoot.getElementsByTagName("book")[0];
      }
      else {
        alert("Empty input!");
        return;
      }
      
      $("#waiting-icon").show();

      $('#log-text').val('');
      var book = $("#book-name").val();
      var year = $("#year").val();
      var issue = $("#issue").val();
      var lang = $("#lang").val();

      var articleCount = bookXML.querySelectorAll("article").length;

      var headerString = "<?xml version='1.0' encoding='UTF-8'?>\n"
                + "<!DOCTYPE book PUBLIC '-//OASIS//DTD DocBook V5.0//EN' 'http://www.docbook.org/xml/5.0/dtd/docbook.dtd'>\n";

      for (let idx = 1; idx <= articleCount; idx++) {
        let bookCopy = bookXML.cloneNode(true);
        for (let jdx = 1; jdx <= articleCount; jdx++) {
          if (jdx != idx)
            bookCopy.querySelector(`article[xml\\:id="article-${jdx}"]`).remove();
        }
        let xml_data = bookCopy.outerHTML.replaceAll(/\&nbsp;/g, "&#xA0;");

        $.ajax({
          type: "POST",
          url: "/tools/article-converter/validate",
          data: { 'content':xml_data, 'book':book, 'year':year, 'issue':issue, 'lang':lang },
          dataType: "json"
        })
        .done(function( data ) {
          var msg = `Validation result: Article ${idx}\n`;
          if (data.code == -1)
            msg += "  FAILED\n";
          if (Array.isArray(data.message)) {
            data.message.forEach((val)=>{msg += val + "\n"});
          } 
          else {
            msg += data.message + "\n";
          }
          $('#log-text').val($('#log-text').val() + msg + '\n');
        })	
        .fail(function( data ) {
          console.log(data);
        })
        .always(function( data ) {
          $("#waiting-icon").hide();
        });
      }
      return true;
  }); 
  
  // Upload produced XML to the server
  $( "#button-upload-xml" ).click(function() {
      // if (confirm("Are you sure you want to upload the result?")) {
    $("#waiting-icon").show();
    $('#log-text').val('');
    var xml_data = $('#source-text').val();
    var book = $("#book-name").val();
    var year = $("#year").val();
    var issue = $("#issue").val();
    var lang = $("#lang").val();
    var overwrite = document.getElementById("overwrite").checked;
    var validate = document.getElementById("validate").checked;
    var db_update = document.getElementById("db-update").checked;
          $.ajax({
                  type: "POST",
            url: "/tools/article-converter/upload",
                  data: { 'content':xml_data, 'book':book, 'year':year, 'issue':issue, 'lang':lang
                    , 'overwrite':overwrite, 'validate':validate, 'db-update':db_update },
            dataType: "json"
    })
          .done(function( data ) {
      var msg = "Upload result: ";
      if (data.code == -1)
        msg += " Failed\n";
      else {
        msg += "\n";
        window.open("/tools/article-converter/preview/" + book + "/" + lang + "/" + year + "/" + issue + "/article-1", "_blank");
      }
      if (Array.isArray(data.message)) {
        data.message.forEach((val)=>{msg += val + "\n"});
      } 
      else {
        msg += data.message;
      }
              $('#log-text').val(msg);
          })	
          .fail(function() {
          })
          .always(function() {
              $("#waiting-icon").hide();
          });
          return true;
  // }
  }); 
  
    /*
    * Go-to functions
    */
  
    /*
    * current scroll position
    */
    var prevScrollPos = 0;
    var scrollPos = 0;
    var headerHeight = document.getElementById("panel-editor-content").getBoundingClientRect().top;
    $("#panel-editor-content").scroll(function () {
        scrollPos = $(this).scrollTop();
        if (Math.abs(scrollPos - prevScrollPos) < 20)
            return;
  
        var rect = null;
        var book = $("#book-name").val();
        prevElement = this.firstElementChild;
        if (book == 'sbl' || book == 'sblpab') {
            fso = 0;
            $(this).children('.foreword, .fso, .lesson').each(function () {
                if ($(this).is("fso"))
                    fso++;
    
                rect = this.getBoundingClientRect();
                if (rect.top > headerHeight) {
                    if (prevElement.tagName == 'FSO')
                        $("#cur-pos-indicator").text("FSO " + fso.toString());
                    else if (prevElement.tagName == 'LESSON')
                        $("#cur-pos-indicator").text("LESSON " + prevElement.getAttribute('no'));
                    else
                        $("#cur-pos-indicator").text(prevElement.tagName);
                    return false;
                }
                prevElement = this;
            });
            if (rect.top <= headerHeight) {
                // if (prevElement.tagName == 'BOOK')
                // $("#cur-pos-indicator").text("Click to Jump");
                //     else
                $("#cur-pos-indicator").text("LESSON " + prevElement.getAttribute('no'));
            }
        }
        else if (book == 'rmrh' || book == 'ym') {
            let articles = this.querySelectorAll('.article');
            for (var number = 1; number <= articles.length; number++) {
                rect = articles[(number-1)].getBoundingClientRect();
                if (rect.top > scrollPos - headerHeight) {
                    $("#cur-pos-indicator").text("Not defined");
                    return;
                }
                if ( rect.top + rect.height > headerHeight) {
                    $("#cur-pos-indicator").text("Article no " + number);
                    return;
                }
            };
            $("#cur-pos-indicator").text("Not defined");
        }
        prevScrollPos = scrollPos;
  
    });
  
  
    /*
      * Jump (Go) to ...
      */
    $("#cur-pos-indicator").click(function () {
        var listElements = [];
  
        var goto = document.getElementById('select-goto-wrapper');
        if (goto)
            goto.parentNode.removeChild(goto);
  
        var book = $("#book-name").val();
        var html = "<div id='select-goto-wrapper'><select name='select-goto' id='select-goto' multiple=multiple' size='";
        if (book == 'sbl' || book == 'sblpab') {
            articles = $("#panel-editor-content > .lesson, .fso");
            html += articles.length + "'>";
            for (idx = 0; idx < articles.length; idx ++) {
                if (articles[idx].tagName == 'LESSON')
                    html += "<option value='" + $(articles[idx]).attr('no') + "'>LESSON " + $(articles[idx]).attr('no') + "</option>";
                else
                    html += "<option value='" + $(articles[idx]).attr('date') + "'>FSO " + $(articles[idx]).attr('date') + "</option>";
            }
        }
        else {  //if (book == 'rmrh' || book == 'ym') {
            articles = $("#panel-editor-content > .article");
            html += articles.length + "'>";
            for (var number = 1; number <= articles.length; number ++) {
                html += "<option value='" + (number-1).toString() + "'>Article " + number.toString() + "</option>";
            }
        }
        html += "</select></div>";
  
        this.innerHTML += html;
    });
  
  
    $(document).on('change', "#select-goto", function () {
        var value = $(this).val()[0];
        var book = $("#book-name").val();
        if(book == 'sbl' || book == 'sblpab') {
            if (parseInt(value) < 15) // lesson no
                $('#panel-editor-content').animate({
                    scrollTop: $("lesson[no='" + value + "']").offset().top + headerHeight + 100
                }, 500);
            else
                $('#panel-editor-content').animate({
                    scrollTop: $("fso[date='" + value + "']").offset().top + headerHeight + 100
                }, 500);
        }
        else {  //if (book == 'rmrh' || book == 'ym') {
            $('#panel-editor-content').animate({
                scrollTop: $(".article:eq(" + value + ")").offset().top + scrollPos - headerHeight
            }, 500);
        }
    });
  
    // close '#select-goto'
    $(document).on("click", function (event) {
        var goto = document.getElementById('select-goto-wrapper');
        if (goto && !event.target.closest('#cur-pos-indicator'))
            goto.parentNode.removeChild(goto);
    });
  
  
    // A function used for dragging and moving
      function dragElement(element, firstElement, secondElement, direction)
      {
          var   md; // remember mouse down info
          const first  = firstElement;
          const second = secondElement;
  
          element.onmousedown = onMouseDown;
  
          function onMouseDown(e)
          {
              md = {e,
            offsetLeft:  element.offsetLeft,
            offsetTop:   element.offsetTop,
            firstWidth:  first.offsetWidth,
            secondWidth: second.offsetWidth,
            firstHeight:  first.offsetHeight,
            secondHeight: second.offsetHeight
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
  
              if (direction === "H" ) { // Horizontal 
                  // Prevent negative-sized elements
                  delta.x = Math.min(Math.max(delta.x, -md.firstWidth), md.secondWidth);
  
                  element.style.left = md.offsetLeft + delta.x + "px";
                  first.style.width = (md.firstWidth + delta.x) + "px";
                  second.style.width = (md.secondWidth - delta.x) + "px";
              }
        else  { // Vertical 
                  delta.y = Math.min(Math.max(delta.y, -md.firstWidth), md.secondWidth);
  
                  element.style.top = md.offsetTop + delta.y + "px";
                  first.style.height = (md.firstHeight + delta.y) + "px";
                  second.style.height = (md.secondHeight - delta.y) + "px";
              }
          }
      }
  
      dragElement( document.getElementById("panel-spliter-vertical")
                , document.getElementById("panel-editor-wrapper")
                , document.getElementById("panel-source-wrapper")
                , "V" );
      dragElement( document.getElementById("panel-spliter-horizontal")
                , document.getElementById("panel-source")
                , document.getElementById("panel-logger")
                , "H" );
  
    TLN.append_line_numbers('source-text');
  
  });