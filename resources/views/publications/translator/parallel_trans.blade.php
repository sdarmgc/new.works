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

<!doctype html>
<!--html manifest="/publications/translator/{!! $book !!}/{!! $year !!}/{!! $issue !!}/{!! $lang !!}/{!! $s_lang !!}/manifest.appcache"-->
<html>

<head>
    <title>SDARM Publication Translator</title>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />

    <link href="{!! $resourceServer !!}/css/sbl_core.css?" rel="stylesheet">
    <link href="{!! $resourceServer !!}/css/sb.css" rel="stylesheet">
    <link href="{!! $resourceServer !!}/css/library.css?" rel="stylesheet">
    <link href="/css/frontend.css" rel="stylesheet">
    <link href="/css/sdarm.css" rel="stylesheet">
    <link href="/css/translator.css?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/translator.css') !!}" rel="stylesheet">

    <script>
        {!!$jsVar!!} 
    </script>
    <!--script id="lang-prop" style="display:none;">{!! $langProp !!}</script-->
</head>

<body class="{!! (strpos($book, 'pab') !== null) ? 'pab' : '' !!}">
    <div id="app">
        <header class="header bg-light" id="app-header">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a href="{{ route('frontend.index') }}" class="navbar-brand">{{ app_name() }} Translator</a>

                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="@lang('labels.general.toggle_navigation')">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav">
                        <!--li class="nav-item"><a href="{{ route('frontend.index') }}" class="nav-link {{ active_class(Active::checkRoute('frontend.index ')) }}">@lang('navs.general.home')</a></li-->
                        <li class="nav-item"><a id="menu-language" class='nav-link menu-item' title="Change translation language">LANGUAGE {!! $languages !!}</a></li>
                        <li class="nav-item"><a id="menu-synchronize" class='nav-link menu-item' title="Submit entire data to the server">SAVE</a></li>
                        @if (Auth::user()->hasRole('administrator'))
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">EDIT</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                                <a href="#" id="menu-undo" class="dropdown-item menu-item" title="Undo last change">UNDO</a>
                                <a href="#" id="menu-reset" class="dropdown-item menu-item" title="Reset current translation">RESET TRANSLATION</a>
                                <a href="#" id="menu-dump" class="dropdown-item menu-item" title="Dump all data">DUMP ALL DATA</a>
                            </div>
                        </li>
                        @else
                        <li class="nav-item"><a id="menu-undo" class="nav-link menu-item" title="Undo last change">UNDO</a></li>
                        @endif      
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">PROPERTY</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                                <a href="#" id="menu-edit-prop" class="dropdown-item menu-item" title="Edit language properties">EDIT PROPERTY</a>
                                <a href="#" id="menu-edit-book-names" class="dropdown-item menu-item" title="Edit reference book names">EDIT BOOK NAMES</a>
                            </div>
                        </li>
                        @if (strpos($book, 'pab') === false)
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">DOWNLOAD</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                                <a href="#" id="menu-xml" class="dropdown-item menu-item" title="Download XML format">XML</a>
                                <a href="#" id="menu-rtf-text" class="dropdown-item menu-item" title="Export rtf format">RTF</a>
                                <a href="#" id="menu-indesign-text" class="dropdown-item menu-item" title="Export Indesign tag format">INDESIGN TAGGED TEXT</a>
                                @if($book == "sbl")
                                <a href="#" id="menu-indesign-text-v2" class="dropdown-item menu-item" title="Export Indesign tag format">INDESIGN TAGGED TEXT V2</a>
                                @endif
                                @if($book == "sbl" || $book == "sblpab")
                                <a href="#" id="menu-indesign-template" class="dropdown-item menu-item" title="Download Indesign template file">INDESIGN TEMPLATE</a>
                                @endif
                            </div>
                        </li>
                        @endif

                        <!--li id="menu-edit-prop" class="nav-item" title="Edit language properties"><a class='nav-link'>EDIT PROPERTY</a></li>
                        <li id="menu-edit-book-names" class="nav-item" title="Edit reference book names"><a class='nav-link'>EDIT BOOK NAMES</a></li>
                        <li id="menu-xml" class="nav-item" title="Download XML format"><a class='nav-link'>XML</a></li>
                        <li id="menu-tag-text" class="nav-item" title="Download Indesign tag format"><a class='nav-link'>INDESIGN TAG</a></li>
                        <li id="menu-tag-template" class="nav-item" title="Download Indesign template file"><a class='nav-link'>INDESIGN TEMPLATE</a></li-->
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a href="#" id="menu-contact" class='nav-link menu-item' title="Contact administrator">CONTACT</a></li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuUser" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">{{ $logged_in_user->name }}</a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                                @can('view backend')
                                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item">@lang('navs.frontend.user.administration')</a>
                                @endcan
                                <a href="{{ route('frontend.user.account') }}" class="dropdown-item {{ active_class(Active::checkRoute('frontend.user.account')) }}">@lang('navs.frontend.user.account')</a>
                                <a href="{{ route('frontend.auth.logout') }}" class="dropdown-item">@lang('navs.general.logout')</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="app-info">
                <div id="version-info" class="version-info app-info-item"></div>
                <div id="cur-pos-indicator" class="cur-pos-indicator app-info-item" title="Click to Jump to ...">Click to Jump</div>
                <div id="page-label" class="app-info-item">Page</div>
                <input id="cur-page-no" name="cur-page-no" type=text placeholder="1" title="Enter the Page number to Jump" class="app-info-item" />
                <div id="total-page-no" class="app-info-item">of 71</div>
                <input id="jump-to-parag-id" name="jump-to-parag-id" type=text placeholder="Jump to #ID" title="Enter the #ID number to Jump"  class="app-info-item"/>
                <input type="button" id="button-ref-dlg" class="app-info-item" value="LOOK UP REF"></button>
                @if($book == "sblpab")
                <input type="button" id="button-diff-missmatch" class="app-info-item" value="DIFF MISMATCH"></button>
                @endif
                <div id="update-time" class="synchronized app-info-item"></div>
                <div class="error-count-block app-info-item">
                    <span class="button" id="prev-error-btn">&lt;&lt; Prev Mismatch</span>
                    <span class="error-count" id="error-count">0</span>
                    <span class="button" id="next-error-btn">Next Mismatch &gt;&gt;</span>
                </div>
            </div>
        </header>
        <div class="container">
            <div class="page-title">
                {!! $pageTitle !!}
            </div>
            <div class="wait-message">Please wait while the translation content loads.</div>
            <div id="contents" 
                    class="contents sdarm-dl {!! $book != 'sbl' ? 'magazine' : 'sbl' !!} {!! $book == 'sblpab' ? 'sblpab' : '' !!}" 
                    dl-server="{!! $dl_server !!}"
                    langEGW={!! $lang !!}
                    sourcePage="page-original"
                    >
                {!! $xmlText !!}
            </div>
            <div id="dialog-confirm" title="" class="hide">
                <p><span class="ui-icon ui-icon-alert" style="float:left; margin:2px 12px 20px 0;"></span>
                    <span id="confirm-message">
                        There is an updated version on the server.<br />
                        Do you want to use the updated version on the server?
                    </span>
                </p>
            </div>
            <div id="ajax-content" class="hide"></div>
            <div id="waiting-icon" class="hide"></div>
            <div id="waiting-message-box" class="hide">
                <div id="waiting-message">Processing request . . .</div>
            </div>
        </div>
    </div>
    
        <!-- Scripts -->
        @stack('before-scripts')
        {!! script(mix('js/manifest.js')) !!}
        {!! script(mix('js/vendor.js')) !!}
        {!! script(mix('js/frontend.js')) !!}
        @stack('after-scripts')

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="{!! $scriptServer !!}/js/bible_info.js?v=1.3"></script>
        <script src="{!! $scriptServer !!}/js/sdarm_dl.js?v=1.4"></script>
        <script src="/js/lang_property.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/lang_property.js') !!}"></script>
        <script src="/js/translator.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/translator.js') !!}"></script>
        <script src="/js/converter.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/converter.js') !!}"></script>
</body>

</html> 