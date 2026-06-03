<?php
    if( (isset($_ENV['HOST_ENV']) && strpos($_ENV['HOST_ENV'], "docker") !== FALSE ) 
        || strpos($_SERVER['SERVER_ADDR'], "127.0.0.1") !== FALSE ) { // local or docker host
        $resourceServer = "https://dl.lo";
        $scriptServer = "https://dl.lo";
        $dl_server = "https://dl.lo";
    }
    else {
        $resourceServer = "https://dl.sdarm.org";
        $scriptServer = "https://dl.sdarm.org";
        $dl_server = "https://dl.sdarm.org";
    }
?>

@extends('frontend.layouts.app')

@push('after-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <link href="{!! $resourceServer !!}/css/library.css?" rel="stylesheet">
    <link href="{!! $resourceServer !!}/css/sb.css?" rel="stylesheet">
    <link href="{!! $resourceServer !!}/css/sbl_core.css?" rel="stylesheet">
    <!-- <link href="/css/translator.css?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/translator.css') !!}" rel="stylesheet"> -->
    <link href="/css/reader.css?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/reader.css') !!}" rel="stylesheet">
    <style>
        .navbar { z-index: 999; }
        
        @if ($bibleVerseSameLine)
            verse {
                display: inline;
            }
        @else
            verse {
                display: block;
            }
        @endif
        @if ($bibleRefSameLine)
            bible-text p {
                display: inline;
            }
        @else
            bible-text p {
                display: block;
            }
        @endif
    </style>
@endpush

@push("after-scripts")
    <script>
        {!!$jsVar!!} 
    </script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{!! $scriptServer !!}/js/bible_info.js?v=1.3"></script>
    <script src="{!! $scriptServer !!}/js/sdarm_dl.js?v=1.4"></script>
    <script src="/js/lang_property.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/lang_property.js') !!}"></script>
    <script src="/js/converter.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/converter.js') !!}"></script>
    <script src="/js/reader.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/reader.js') !!}"></script>

    <script>
        $("#menu-bible-verse-display").change(function() {
            if(this.checked) {
                $("verse").css("display", "inline");
                $("bible-text p").css("display", "inline");
            }
            else {
                $("verse").css("display", "block");
                $("bible-text p").css("display", "block");
            }      
        });
        
        $("#menu-ibid-full-name").change(function() {
            let els = document.querySelectorAll(".source-link");
            if (els.length < 1)
                return;
            let ibidPattern = /(Ibid\.(, )?)((((p+\. |page )([\d–\-, ]+))|((vol\. |book |bk\. )(\d+)(, p+\. )([\d–\-, ]+))|(([A-Z][a-z]+) (\d+), (\d{4}))))?/i;
            let bookName = null;
            els.forEach((el) => {
                if( this.checked ) {     // Ibid => Full Name
                    let match = el.textContent.trim().replaceAll(/\s+/g, ' ').match(ibidPattern);
                    if (!match)
                        return;
                    bookName = el.innerHTML.replace(/<em[^>]*>/, '-').replace('</em>', '=').replaceAll(/\s+/g, ' '); 
                    let strReplace = '';  
                    if (match[7]) {// Ibid., p. 14
                        let vol = el.getAttribute('bookname').match(/(vol)|(bk)/i);
                        if (vol) {  // Testimonies, vol. 3, p. 14
                            strReplace = el.getAttribute('bookname').replace('-', '<em>').replace('=', '</em>');
                            strReplace = strReplace.replace(/p+\. .+/, match[5]);
                        }
                        else {      // Desire of the Ages, p. 14
                            strReplace = el.getAttribute('bookname').replace('-', '<em>').replace('=', '</em>');
                        }
                        el.innerHTML = strReplace;
                        el.setAttribute('bookname', bookName);
                    } 
                    else if (!match[2] || match[12] || match[16]) { // Ibid || Ibid., vol. 3, p. 13 || Ibid., July 12, 1888
                        strReplace = el.getAttribute('bookname').replace('-', '<em>').replace('=', '</em>');
                        el.innerHTML = strReplace;
                        el.setAttribute('bookname', bookName);
                    }
                    else {
                        console.log( "Unknown IBID - " , match[0]);
                    }
                }
                else if( !this.checked && (bookName = el.getAttribute('bookname')) ) {    // Full Name =>Ibid
                    el.setAttribute('bookname', el.innerHTML.replace(/<em[^>]*>/, '-').replace('</em>', '='));
                    el.innerHTML = bookName.replace('-', '<em>').replace('=', '</em>');
                } 
            }); 
        });
        
    </script>
@endpush

@push('custom-menu')
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">View</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                    <label class="dropdown-item menu-item">
                        <input class="" id="menu-bible-verse-display" type="checkbox" value="" />
                        Show Bible Verses In One Line
                    </label>
                    <label class="dropdown-item menu-item">
                        <input class="" id="menu-ibid-full-name" type="checkbox" value="" />
                        Show Ibid as Full Book Name
                    </label>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Download</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                    <!-- a href="#" id="menu-xml" class="dropdown-item menu-item" title="Download XML format">XML</a -->
                    <a href="#" id="menu-rtf" class="dropdown-item menu-item" title="Export rtf format">RTF</a>
                    @if (Auth::user())
                    <a href="#" id="menu-indesign-text-v2" class="dropdown-item menu-item" title="Export Indesign tag format V2">INDESIGN TAGGED TEXT V2</a>
                    @endif
                </div>
            </li>

            @hasanyrole('administrator|translator')     
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Property</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                    <a href="#" id="menu-edit-prop" class="dropdown-item menu-item" title="Edit language properties">EDIT PROPERTY</a>
                    <a href="#" id="menu-edit-book-names" class="dropdown-item menu-item" title="Edit reference book names">EDIT BOOK NAMES</a>
                </div>
            </li>
            @endhasanyrole
@endpush

@section('content')

        <div class="app-info">
            <span id="cur-pos-indicator" class="cur-pos-indicator app-info-item">Current Scroll Position</span>
        </div>
        <div class="contents-wrapper">
            <!--div class="page-title">
                SDARM Publication Reader
            </div-->
            <div id="contents" class="contents sdarm-dl {!! $book != 'sbl' ? 'magazine' : 'sbl' !!}" dl-server="{!! $dl_server !!}">
                {!! $contents !!}
            </div>
        </div>
    
@endsection
