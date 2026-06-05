@push('after-styles')
    <link href="/css/reader.css?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/reader.css') !!}" rel="stylesheet">
@endpush

@push("after-scripts")
<script src="{!! $dl_server !!}/js/bible_info.js?v=1.3"></script>
<script src="{!! $dl_server !!}/js/sdarm_dl.js?v=1.4"></script>
<script src="/js/reader.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/reader.js') !!}"></script>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $("#button-read").click(function() {
            var source = '';
            if (document.getElementById('pab-version')) {
                source = "?pab=" + ($("#pab-version").prop("checked") ? 'true' : 'false');
                source += "&source=" + document.querySelector('input[name="source"]:checked').value;
            }
            else if (document.getElementById('source_tr')) {
                source = "?pab=false";
                source += "&source=" + document.querySelector('input[name="source"]:checked').value;
            }
            else {
                source = "?pab=false";
                source += "&source=dl";
            }
            source += "&bv=" + $("#bible-version").val();
            window.location.href = "/publications/reader/show/" + $("#book").val() + "/" + $("#lang-select").val() + "/" + $("#year").val() + "/" + $("#issue").val() + source;
        });

        $("#pab-version").change(function() {
            if (this.checked) {
                if (document.getElementById('source_dl').checked) {
                    document.getElementById('source_ms').checked = true;
                }
                document.getElementById("source_dl").disabled = true;
            } 
            else {
                document.getElementById("source_dl").disabled = false;
            }
        });

        $("#lang-select").on('change', function() {
            // console.log("Lang changed.");
            // document.getElementById("bible-version").innerHTML = "";
            // $.get(`/publications/reader/bible-versions/${this.value}`, 
            //     (data) => {
            //         console.log(data);
            //         for (const property in data) {
            //             console.log(`${property}: ${data[property]}`);
            //             let opt = document.createElement("option");
            //             opt.value = property;
            //             opt.text = data[property];
            //             document.getElementById("bible-version").add(opt);
            //         }
            //     })
            //     .fail(function() {
            //         alert( "error" );
            //     });
            setBibleVersions(this.value);
        });

        $(".lang-filter").change(function() {
            if (this.value == 'all') {
                setBibleVersions("");
            } 
            else {  
                const lang = document.getElementById("lang-select").value;
                setBibleVersions(lang);
            }
        });

        const version = document.cookie.split(';')?.find((item) => item.trim().startsWith('BibleVersion='))?.split('=')[1];
        if (version) {
            $("#bible-version").val(version);
        }
        $("#bible-version").on('change', function() {
            let bv = this.value;
            document.cookie = `BibleVersion=${bv}; expires=Fri, 31 Dec 9999 23:59:59 GMT;`;
        });

        function setBibleVersions(lang) {
            if (typeof bibleData !== 'undefined') {
                const bibleList = bibleData.getLangVersion(lang, "DL");
                if (bibleList.length) {
                    document.getElementById("bible-version").innerHTML = "";
                    for (const property in bibleList) {
                        //console.log(`${property}: ${bibleList[property]}`);
                        let opt = document.createElement("option");
                        opt.value = bibleList[property][0];
                        opt.text = bibleList[property][1];
                        document.getElementById("bible-version").add(opt);
                    }
                }
                else {
                    document.getElementById("bible-version").innerHTML = "";
                }
            }
        }
        const lang = document.getElementById("lang-select").value;
        setBibleVersions(lang);
        
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
        </x-slot>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="card-body">
            @if (Auth::user())
            <div class="row block mt-4 form-group">
                {{ html()->label(@('Source from'))->for('source')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->radio('source', true, 'ms')->class('') }} <span>Manuscript</span>
                    {{ html()->radio('source', false, 'tr')->class('') }} <span>Translator</span>
                    {{ html()->radio('source', false, 'dl')->class('') }} <span>Digital Library</span>
                </div><!--col-md-9-->
            </div><!--form-group-->
            @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('pub'))
            <div class="row block mt-4 form-group">
                {{ html()->label(@('PAB version'))->for('pab-version')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->checkbox('pab-version', false, 'false')->class('') }}
                </div><!--col-md-9-->
            </div><!--form-group-->
            @endif
            @endif
            <div class="row block mt-4 form-group">
                {{ html()->label(@('Book'))->for('book')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->select('book', ['sbl'=>'Sabbath Bible Lesson'
                                                //, 'rmrh'=>'The Reformation Herald'
                                                //, 'ym'=>'Youth Messanger'
                                                ]
                                            , $book == ''?'sbl':$book)->class('form-control block mt-1 w-full')->required()}}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <div class="row block mt-4 form-group">
                {{ html()->label(@('Language'))->for('lang-select')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    <!-- {{ html()->select('lang-select'
                                        , ['en'=>'English'
                                        , 'es'=>'Spenish'
                                        , 'hr'=>'Croatian'
                                        , 'ja'=>'Japanese'
                                        , 'ko'=>'Korean'
                                        , 'pt'=>'Portuguese'
                                        , 'ru'=>'Russian'
                                        , 'rw'=>'Kinyarwanda'
                                        , 'ta'=>'Tamil']
                                        , $lang == ''?'en':$lang)->class('form-control block mt-1 w-full')->required()}} -->
                    
                    {{ html()->select('lang-select', $langList, $lang)->class('form-control block mt-1 w-full')->required()}}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <div class="row block mt-4 form-group">
                {{ html()->label(@('Year'))->for('year')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('year', $year==''?date("Y"):$yaear)->class('form-control block mt-1 w-full')->required() }}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <div class="row block mt-4 form-group">
                {{ html()->label(@('Issue(Quarter)'))->for('issue')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('issue', $issue==''?'1':$issue)->class('form-control block mt-1 w-full')->required() }}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <div class="row block mt-4 form-group">
                {{ html()->label(@('Bible Version'))->for('bible-version')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->radio('lang-filter', false, 'all')->class('lang-filter') }} <span>List all versions</span>
                    {{ html()->radio('lang-filter', true, 'lang')->class('lang-filter') }} <span>List selected language</span>
                </div><!--col-md-9-->
                {{ html()->label(@(' '))->for('bible-version')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->select('bible-version', $bibleVersions)->class('form-control block mt-1 w-full')->required()}}
                </div><!--col-md-9-->
            </div><!--form-group-->
            
            <div class="row block mt-4 justify-content-md-center form-group">
                <div class="col-md-auto">
                    <x-button type="submit" id='button-read' class='ms-4'>Read the book</x-button>
                </div>
            </div><!--form-group-->

        {{ html()->form()->close() }}

    </div><!-- panel body -->

</div></div></div>

</x-app-layout>