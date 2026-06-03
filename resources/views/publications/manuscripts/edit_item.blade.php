<x-app-layout>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Item') }}
        </h2>
    </x-slot>

@push('after-styles')
    <link href="/css/manuscripts.css" rel="stylesheet">
@endpush

@push("after-scripts")
    <script>
        $(document).ready(function() {
            $("form").submit(function(event) {
                console.log("Submitting form...");
                $('.form-control').each(function() {
                    $(this).attr('disabled', false);
                });
            });
            $('.btn-delete').on( "click", function() {
            	if (confirm('Are you sure you want to delete the item?')) {
            		window.location = "{!! route('publications.manuscripts.destroyItem', ['id'=>$item->id]) !!}";
            	}
            });
            $('.btn-cancel').on('click', function() {
                window.location.href = "{{ route('publications.manuscripts.index') }}";
            });
            $('#type').on('change', function() {
                var type = $(this).val();
                if (type == 2) {
                    // url required
                    $('.form-control[name="file"]').prop('required',false);
                    $('.form-group-file').css('display','none');
                    $('.form-control[name="url"]').prop('required',true);
                    $('.form-group-url').css('display','flex');
                    $('.form-group-size').css('display','none');
                } else {
                    // neither required
                    $('.form-control[name="file"]').prop('required',true);
                    $('.form-group-file').css('display','flex');
                    $('.form-control[name="url"]').prop('required',false);
                    $('.form-group-url').css('display','none');
                    $('.form-group-size').css('display','flex');
                }
            });
            if ($('#size').val() > 0) {
                $('.form-group-size').css('display','flex');
            } else {
                $('.form-group-size').css('display','none');
            }
            // on Edit
            if ($('#name').val().length > 0) {
                $('#type').attr('disabled','true');
                if ($('#type').val() != 2) {
                    $('#url').attr('disabled','true');
                }
                $('.form-group-file').css('display','none');
            } 
            else {
                // on Create
                $('.form-group-url').css('display','none');
            }

        });
    </script>
@endpush


    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        {{ html()->form('POST', route($form_url, ['id'=>$item->id]))
                ->class('form-horizontal form-manuscript')
                ->attribute('id', 'form-'.$item->id)
                ->attribute('enctype', 'multipart/form-data')->open() }}

            <input type="hidden" name="id" value="{{ $item->id }}">
            <input type="hidden" name="manuscript_id" value="{{ $item->manuscript_id }}">

            <div class="mt-4 form-group form-group-type">
                {{ html()->label(@('Type'))->for('type')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->select('type', 
                        [1=>'Works Translator', 
                        2=>'Html Link', 
                        3=>'Text File', 
                        4=>'PDF File', 
                        5=>'Image File', 
                        6=>'Zipped File', 
                        7=>'Other File'], $item->type)->class('form-control block mt-1 w-full')->required() }}
                </div><!--col-md-9-->
            </div><!--form-group-->
        
            <div class="mt-4 form-group form-group-name">
                {{ html()->label(@('Item Name'))->for('name')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('name', $item->name)->class('form-control block mt-1 w-full')->required() }}
                </div><!--col-md-9-->
            </div><!--form-group-->
        
            <div class="mt-4 form-group form-group-description">
                {{ html()->label(@('Item Description'))->for('name')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('description', $item->description)->class('form-control block mt-1 w-full')->required() }}
                </div><!--col-md-9-->
            </div><!--form-group-->
                
            <div class="mt-4 form-group form-group-url">
                {{ html()->label(@('URL'))->for('url')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('url', $item->url)->class('form-control block mt-1 w-full') }}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <div class="mt-4 form-group form-group-file">
                {{ html()->label(@('File Upload'))->for('file')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->file( 'file')->class('form-control block mt-1 w-full')}}
                </div><!--col-md-9-->
            </div><!--form-group-->
            
            <div class="mt-4 form-group form-group-size">
                {{ html()->label(@('Size(MB)'))->for('size')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('size', $item->size)->class('form-control block mt-1 w-full')->disabled() }}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <div class="mt-4 form-group">
                {{ html()->label(@('Sort Order'))->for('sort')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('sort', $item->sort)->class('form-control block mt-1 w-full')->required() }}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <div class="mt-4 justify-content-md-center form-group">
                <div class="col-md-auto">
                @if (!empty($item->id))
                    <x-button type="button" class='btn-delete ms-4'>Delete</x-button>
                @endif
                    <x-button type="button" class='btn-cancel ms-4'>Cancel</x-button>
                    <x-button type="submit" class="btn ms-4">Submit</x-button>
                </div>
            </div><!--form-group-->

        {{ html()->form()->close() }}

    </div><!-- panel body -->

    </div></div></div>
</x-app-layout>
