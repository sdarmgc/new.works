@push('after-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <link href="{!! $resourceServer !!}/css/library.css?" rel="stylesheet">
    <link href="{!! $resourceServer !!}/css/sb.css?" rel="stylesheet">
    <link href="{!! $resourceServer !!}/css/sbl_core.css?" rel="stylesheet">
    <!-- link href="/css/translator.css?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/translator.css') !!}" rel="stylesheet" -->
    <link href="/css/reader.css?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/reader.css') !!}" rel="stylesheet">
    <style>
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
    <script src="{!! $dl_server !!}/js/bible_info.js?v=1.3"></script>
    <script src="{!! $dl_server !!}/js/sdarm_dl.js?v=1.4"></script>
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


<x-app-layout>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">

            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Reader') }}
                </h2>   
                <div class="app-info flex items-center">
                    <div class="hidden sm:flex sm:items-center sm:ms-6 mt-1">
                        <div class="ms-3 relative">
                            <x-dropdown align="left" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                            {{ __('View') }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>
                                <x-slot name="content">
                                    <div class="w-60">
                                        <x-dropdown-link href="#">
                                            <label class="dropdown-item menu-item">
                                                <input class="" id="menu-bible-verse-display" type="checkbox" value="" />
                                                {{ __('Show Bible Verses In One Line') }}
                                            </label>
                                        </x-dropdown-link>
                                        <x-dropdown-link href="#">
                                            <label class="dropdown-item menu-item">
                                                <input class="" id="menu-ibid-full-name" type="checkbox" value="" />
                                                {{ __('Show Ibid as Full Book Name') }}
                                            </label>
                                        </x-dropdown-link>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ms-6 mt-1">
                        <div class="ms-3 relative">
                            <x-dropdown align="left" width="60">
                                <x-slot name="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                            {{ __('Download') }}
                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                </x-slot>
                                <x-slot name="content">
                                    <div class="w-60">
                                        <x-dropdown-link href="#">
                                            <span id="menu-rtf" class="dropdown-item menu-item" title="Export rtf format">
                                                {{ __('RTF') }}
                                            </span>
                                        </x-dropdown-link>
                                        @if (Auth::check())
                                        <x-dropdown-link href="#">
                                            <span id="menu-indesign-text-v2" class="dropdown-item menu-item" title="Export Indesign tag format V2">
                                                {{ __('INDESIGN TAGGED TEXT V2') }}
                                            </span>
                                        </x-dropdown-link>
                                        @endif
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>

                    <span id="cur-pos-indicator" class="cur-pos-indicator app-info-item">Current Scroll Position</span>
                </div>
            </x-slot>

            <div class="contents-wrapper">
                <!--div class="page-title">
                    SDARM Publication Reader
                </div-->
                <div id="contents" class="sdarm-dl {!! $book != 'sbl' ? 'magazine' : 'sbl' !!}" dl-server="{!! $dl_server !!}">
                    {!! $contents !!}
                </div>
            </div>
    
        </div>
    </div>
</div>
</x-app-layout>
