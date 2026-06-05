
@push('after-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
    <link href="{!! $resourceServer !!}/css/library.css?" rel="stylesheet">
    <link href="{!! $resourceServer !!}/css/sb.css?" rel="stylesheet">
    <link href="{!! $resourceServer !!}/css/sbl_core.css?" rel="stylesheet">
    <link href="/css/sdarm.css" rel="stylesheet">
    <link href="/css/translator.css?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/translator.css') !!}" rel="stylesheet">

@endpush


@push("after-scripts")
    <script>
        {!!$jsVar!!} 
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{!! $dl_server !!}/js/bible_info.js?v=1.3"></script>
    <script src="{!! $dl_server !!}/js/sdarm_dl.js?v=1.4"></script>
    <script src="/js/lang_property.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/lang_property.js') !!}"></script>
    <script src="/js/translator.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/translator.js') !!}"></script>
    <script src="/js/converter.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/converter.js') !!}"></script>
@endpush

<x-app-layout>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">

            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Translator') }}
                </h2>   
                <div class="app-info text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                    <div class="app-info-menu flex justify-end">
                        <div class="hidden sm:flex sm:items-center sm:ms-6 mt-1">
                            <div class="ms-3 relative">
                                <x-dropdown align="left" width="60">
                                    <x-slot name="trigger">
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                                {{ __('EDIT') }}
                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="w-60">
                                            <x-dropdown-link href="#">
                                                <span id="menu-undo" class="dropdown-item menu-item" title="Undo last change">
                                                    {{ __('UNDO') }}
                                                </span>
                                            </x-dropdown-link>
                                            <x-dropdown-link href="#">
                                                <span id="menu-reset" class="dropdown-item menu-item" title="Reset current translation">
                                                    {{ __('RESET TRANSLATION') }}
                                                </span>
                                            </x-dropdown-link>
                                            <x-dropdown-link href="#">
                                                <span id="menu-dump" class="dropdown-item menu-item" title="Dump all data">
                                                    {{ __('DUMP RAW DATA') }}
                                                </span>
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
                                                {{ __('PROPERTIES') }}
                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="w-60">
                                            <x-dropdown-link href="#">
                                                <span id="menu-edit-prop" class="dropdown-item menu-item" title="Edit language properties">
                                                    {{ __('EDIT PROPERTY') }}
                                                </span>
                                            </x-dropdown-link>
                                            <x-dropdown-link href="#">
                                                <span id="menu-edit-book-names" class="dropdown-item menu-item" title="Edit reference book names">
                                                    {{ __('EDIT BOOK NAMES') }}
                                                </span>
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
                                                {{ __('DOWNLOAD') }}
                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="w-60">
                                            <x-dropdown-link href="#">
                                                <span id="menu-xml" class="dropdown-item menu-item" title="Export xml format">
                                                    {{ __('XML') }}
                                                </span>
                                            </x-dropdown-link>
                                            <x-dropdown-link href="#">
                                                <span id="menu-rtf" class="dropdown-item menu-item" title="Export rtf format">
                                                    {{ __('RTF') }}
                                                </span>
                                            </x-dropdown-link>
                                            <x-dropdown-link href="#">
                                                <span id="menu-indesign-text-v2" class="dropdown-item menu-item" title="Export Indesign tag format V2">
                                                    {{ __('INDESIGN TAGGED TEXT') }}
                                                </span>
                                            </x-dropdown-link>
                                            @if($book == "sbl" || $book == "sblpab")
                                            <x-dropdown-link href="#">
                                                <span id="menu-indesign-template" class="dropdown-item menu-item" title="Download Indesign template file">
                                                    {{ __('INDESIGN TEMPLATE') }}
                                                </span>
                                            </x-dropdown-link>
                                            @endif
                                        </div>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>

                        @if($book == "sblpab")
                        <div id="button-diff-missmatch" class="app-info-item inline-flex items-center px-5 py-2 border border-transparent ">DIFF MISMATCH</div>
                        @endif
                        <div id="menu-synchronize" class="app-info-item inline-flex items-center px-5 py-2 border border-transparent "
                            title="Submit entire data to the server">SAVE</div>
                        <div type="button" id="button-ref-dlg" class="app-info-item inline-flex items-center px-5 py-2 border border-transparent "
                            value="LOOK UP REF">LOOK UP REF</div>
                        <div id="menu-language" class="app-info-item inline-flex items-center px-5 py-2 border border-transparent "
                            title="Change translation language">LANGUAGE&nbsp; {!! $languages !!}</div>
                    </div>
                    <div class="app-info-menu flex justify-between">
                        <div id="version-info" class="version-info app-info-item"></div>
                        <div id="cur-pos-indicator" class="app-info-item cur-pos-indicator" title="Click to Jump to ...">Click to Jump</div>
                        <div id="cur-book-name" class="app-info-item"></div>
                        <div class="app-info-item" style="margin-top: -3px;">
                            <span id="page-label" class="">Page</span>
                            <input id="cur-page-no" name="cur-page-no" type=text placeholder="1" title="Enter the Page number to Jump" class="app-info-item w-24 px-4 text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150" />
                            <span id="total-page-no" class="">of 71</span>
                        </div>
                        <input id="jump-to-parag-id" name="jump-to-parag-id" type=text placeholder="Jump to #ID" title="Enter the #ID number to Jump" 
                            class="app-info-item text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150"/>
                        <div class="error-count-block app-info-item">
                            <span class="button" id="prev-error-btn">&lt;&lt; Prev Mismatch</span>
                            <span class="error-count" id="error-count">0</span>
                            <span class="button" id="next-error-btn">Next Mismatch &gt;&gt;</span>
                        </div>
                        <div id="update-time" class="synchronized app-info-item"></div>

                    </div>
                </div>
            </x-slot>

            <div class="container">
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
    </div>
</div>
</x-app-layout>