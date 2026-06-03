@extends('frontend.layouts.app')

@section('content')

@push('after-styles')
    <link href="/css/reader.css?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/reader.css') !!}" rel="stylesheet">
@endpush

<?php
    if( (isset($_ENV['HOST_ENV']) && strpos($_ENV['HOST_ENV'], "docker") !== FALSE ) 
        || strpos($_SERVER['SERVER_ADDR'], "127.0.0.1") !== FALSE ) { // local or docker host
        $resourceServer = "https://works.lo/dl";
        $dl_server = "https://dl.sdarm.lo";
    }
    else {
        $resourceServer = "https://dl.sdarm.org";
        $dl_server = "https://dl.sdarm.org";
    }
?>

@push("after-scripts")
<script src="{!! $dl_server !!}/js/bible_info.js?v=1.3"></script>
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

<div>
    <h1>Reader</h1>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col col-md-10 align-self-center">

            <div class="card">

                <div class="card-header">Choose a Book</div>
                <div class="card-body">
                    @if (Auth::user())
                        <div class="row form-group">
                            {{ html()->label(@('Source from'))->for('source')->class('col-md-3 control-label') }}
                            <div class="col-md-9">
                                {{ html()->radio('source', true, 'ms')->class('') }} <span>Manuscript</span>
                                {{ html()->radio('source', false, 'tr')->class('') }} <span>Translator</span>
                                {{ html()->radio('source', false, 'dl')->class('') }} <span>Digital Library</span>
                            </div><!--col-md-9-->
                        </div><!--form-group-->
                        @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('pub'))
                        <div class="row form-group">
                            {{ html()->label(@('PAB version'))->for('pab-version')->class('col-md-3 control-label') }}
                            <div class="col-md-9">
                                {{ html()->checkbox('pab-version', false, 'false')->class('') }}
                            </div><!--col-md-9-->
                        </div><!--form-group-->
                        @endif
                    @endif
                        <div class="row form-group">
                            {{ html()->label(@('Book'))->for('book')->class('col-md-3 control-label') }}
                            <div class="col-md-9">
                                {{ html()->select('book', ['sbl'=>'Sabbath Bible Lesson'
                                                            //, 'rmrh'=>'The Reformation Herald'
                                                            //, 'ym'=>'Youth Messanger'
                                                            ]
                                                        , $book == ''?'sbl':$book)->class('form-control')->required()}}
                            </div><!--col-md-9-->
                        </div><!--form-group-->

                        <div class="row form-group">
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
                                                    , $lang == ''?'en':$lang)->class('form-control')->required()}} -->
                                
                                {{ html()->select('lang-select', $langList, $lang)->class('form-control')->required()}}
                            </div><!--col-md-9-->
                        </div><!--form-group-->

                        <div class="row form-group">
                            {{ html()->label(@('Year'))->for('year')->class('col-md-3 control-label') }}
                            <div class="col-md-9">
                                {{ html()->text('year', $year==''?date("Y"):$yaear)->class('form-control')->required() }}
                            </div><!--col-md-9-->
                        </div><!--form-group-->

                        <div class="row form-group">
                            {{ html()->label(@('Issue(Quarter)'))->for('issue')->class('col-md-3 control-label') }}
                            <div class="col-md-9">
                                {{ html()->text('issue', $issue==''?'1':$issue)->class('form-control')->required() }}
                            </div><!--col-md-9-->
                        </div><!--form-group-->

                        <div class="row form-group">
                            {{ html()->label(@('Bible Version'))->for('bible-version')->class('col-md-3 control-label') }}
                            <div class="col-md-9">
                                {{ html()->radio('lang-filter', false, 'all')->class('lang-filter') }} <span>List all versions</span>
                                {{ html()->radio('lang-filter', true, 'lang')->class('lang-filter') }} <span>List selected language</span>
                            </div><!--col-md-9-->
                            {{ html()->label(@(' '))->for('bible-version')->class('col-md-3 control-label') }}
                            <div class="col-md-9">
                                {{ html()->select('bible-version', $bibleVersions)->class('form-control')->required()}}
                            </div><!--col-md-9-->
                        </div><!--form-group-->
                        
                        <div class="row justify-content-md-center form-group">
                            <div class="col-md-auto">
                                <button type="button" id='button-read' class='btn-show btn btn-success btn-sm'>Read the book</button>
                            </div>
                        </div><!--form-group-->

                </div><!-- panel body -->

            </div><!-- panel -->

        </div><!-- col-md-9 -->

    </div><!-- row -->
</div>

@endsection