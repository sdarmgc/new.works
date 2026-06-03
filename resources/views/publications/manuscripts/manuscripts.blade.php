<x-app-layout>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manuscripts') }}
        </h2>
    </x-slot>

@push('after-styles')
    <link href="/css/manuscripts.css" rel="stylesheet">
    <style>
        p { 
            margin: 0;
        }
        .text-tiny {
            font-size: .7rem;
        }
        .text-small {
            font-size: .85rem;
        }
        .text-big {
            font-size: 1.4rem;
        }
        .text-huge {
            font-size: 1.8rem;
        }
    </style>
@endpush

@push("after-scripts")
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/decoupled-document/ckeditor.js"></script>
<script type="module">
    var originalMessageContents = "";
    var ckEditor;
    
    $(document).ready(function(){
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $("#message-edit").click(function() {
            if ($(this).text() == "Edit Message") {
                // var contents = $("#message-board-body").html();
                // $("#message-board-body").html("<form id='message-form' method='post' action=''><textarea name='editor' id='editor'></textarea> ");
                // $("#editor").html(contents);
                // CKEDITOR.config.allowedContent = true;
                // CKEDITOR.replace( 'editor' );

                originalMessageContents = $("#message-board-body").html();
                var contents = (originalMessageContents ? originalMessageContents.trim() : "");
                $("#message-board-body").html("<form id='message-form' method='post' action=''><div name='editor' id='editor'></div><div id='toolbar-container'></div> ");
                $("#editor").html(contents.length < 4 ? "<div>ENTER NESSAGE HERE!</div>" : contents);
                DecoupledEditor.create( document.querySelector( '#editor' ) )
                    .then( editor => {
                        ckEditor = editor;
                        const toolbarContainer = document.querySelector( '#toolbar-container' );
                        toolbarContainer.appendChild( editor.ui.view.toolbar.element );
                    } )
                    .catch( error => {
                        console.error( error );
                    } );

                $(this).text("Save");
            }
            else { // save
                dataString = ckEditor.getData();
                fetch("/publications/manuscripts/update-message", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            editor: dataString,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("message-board-body").innerHTML = data;
                    })
                    .catch(error => {
                        alert( "Error! Please try again. - " + errorThrown );
                        $("#message-board-body").html(originalMessageContents);
                    });
                $(this).text("Edit");
            }
        });
        $("#notice-edit").click(function() {
            if ($(this).text() == "Edit Notice") {
                originalMessageContents = $("#message-board-notice-wrapper").html().trim();
                var contents = (originalMessageContents.length > 3 ? originalMessageContents : "ENTER NOTICE HERE!");
                $("#message-board-notice-wrapper").html("<form id='message-form' method='post' action=''><div name='editor' id='editor'></div><div id='toolbar-container'></div>");
                $("#editor").html(contents);
                DecoupledEditor.create( document.querySelector( '#editor' ) )
                    .then( editor => {
                        ckEditor = editor;
                        const toolbarContainer = document.querySelector( '#toolbar-container' );
                        toolbarContainer.appendChild( editor.ui.view.toolbar.element );
                    } )
                    .catch( error => {
                        console.error( error );
                    } );
                $(this).text("Save");
            }
            else { // save
                dataString = ckEditor.getData();
                if (dataString.length > 0) {
                    dataString = '<div class="message-board-notice" id="message-board-notice">' + ckEditor.getData() + '</div>';
                }
                fetch("/publications/manuscripts/update-notice", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            editor: dataString,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("message-board-notice-wrapper").innerHTML = data;
                    })
                    .catch(error => {
                        alert( "Error! Please try again. - " + errorThrown );
                        $("#message-board-notice-wrapper").html(originalMessageContents);
                    });
                $(this).text("Edit Notice");
            }
        });
    });
</script>
@endpush

<div>
    <table class="message-board" align="left" border="0" cellpadding="1" cellspacing="1" class="">
        <tbody>
            <tr>
                <td id="message-board-notice-wrapper">
                    {!!$notice!!}
                </td>
            </tr>
            <tr>
                <!--td>&nbsp;</td-->
                <td class="admin-menu">
                    @hasanyrole('administrator|executive')
                    <a href="#" class="admin-menu text-indigo-700 dark:text-indigo-300" id="notice-edit">{{ trans('Edit Notice') }}</a>
                    @endhasanyrole
                </td>
            </tr>
            @if (!empty($notice))
            <tr>
                <td>&nbsp;</td>
            </tr>
            @endif
            <tr>
                <!--td class="message-board-title">
                    <p>Manuscript <br />Downloads</p>
                </td-->
                <td class="message-board-body" id="message-board-body">
                    {!!$message!!}
                </td>
            </tr>
            <tr>
                <!--td>&nbsp;</td-->
                <td class="admin-menu">
                    @hasanyrole('administrator|executive')
                    <a href="#" class="admin-menu text-indigo-700 dark:text-indigo-300" id="message-edit">{{ trans('Edit Message') }}</a>
                    @endhasanyrole
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
    
    @include("publications.manuscripts.manuscript_items")
    
</div>

    </div></div></div>

</x-app-layout>