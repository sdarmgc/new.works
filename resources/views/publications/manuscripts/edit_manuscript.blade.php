@push('after-styles')
    <link href="/css/manuscripts.css" rel="stylesheet">
@endpush

@push("after-scripts")
    <script>
        $(document).ready(function(){
            $('.btn-delete').on( "click", function() {
            	if (confirm('Are you sure you want to delete the item?')) {
            		window.location = "{!! route('publications.manuscripts.destroyManuscript', ['id'=>$manuscript->id]) !!}";
            	}
            });
            $('.btn-cancel').on('click', function() {
                window.location.href = "{{ route('publications.manuscripts.index') }}";
            });
            $('#p-name, #year, #issue').on('change', function() {
                let pText = $('#p-name option:selected').text();
                if ($('#p-name').val() == '' || pText == 'Other') {
                    $('#name').val('');
                    $('#view_class').val('none');
                    $('.year-wrapper, .issue-wrapper').addClass('hidden');
                    return;
                }
                $('.year-wrapper, .issue-wrapper').removeClass('hidden');
                let name = pText + ' ' + $('#year').val() + '/' + $('#issue').val();
                $('#name').val(name);
                if (pText.indexOf("Sabbath Bible Lessons") > -1)
                    $('#view_class').val('background-heighted')
                else if (pText.indexOf("Lecciones Biblicas Sabaticas") > -1)
                    $('#view_class').val('background-aqua')
                else if (pText.indexOf("Reformation Herald") > -1)
                    $('#view_class').val('background-orange')
                else
                    $('#view_class').val('none');
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
            {{ $title }}
        </h2>
    </x-slot>

    <div class="card-body">
        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ $value }}
            </div>
        @endsession

        {{ html()->form('POST', route($form_url, ['id'=>$manuscript->id]))
                ->class('form-horizontal form-manuscript')
                ->attribute('id', 'form-' . $manuscript->id)
                ->attribute('enctype', 'multipart/form-data')->open() }}

            <input type="hidden" name="id" value="{{ $manuscript->id }}" />
            <input type="hidden" name="category" value="manuscript" />
            
            <div class="flex bg-orange-200">
                <div class="">
                    {{ html()->label(@('Publication Name'))->for('p-name')->class('col-md-3 control-label') }}
                    <div class="col-md-9">
                        {{ html()->select('p-name', $pNameList)->id('p-name')->class('')->value($pName) }}
                    </div><!--col-md-9-->
                </div>
                <div class="px-4 year-wrapper hidden">
                    {{ html()->label(@('Year'))->for('year')->class('col-md-3 control-label') }}
                    <div class="col-md-9">
                        {{ html()->select('year', $yearList)->id('year')->class('')->value($year) }}
                    </div><!--col-md-9-->
                </div>
                <div class="issue-wrapper hidden">
                    {{ html()->label(@('Quarter'))->for('issue')->class('col-md-3 control-label') }}
                    <div class="col-md-9">
                        {{ html()->select('issue', [1=>1, 2=>2, 3=>3, 4=>4])->id('issue')->class('')->value($issue) }}
                    </div><!--col-md-9-->
                </div>
            </div><!--form-group-->
            
            <div class="mt-4">
                {{ html()->label(@('Manuscript Name'))->for('name')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('name', $manuscript->name)->id('name')->class('block mt-1 w-full')->required() }}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <!-- div class="mt-4">
                {{ html()->label(@('Category'))->for('category')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->select('category', ['manuscript'=>'Manuscript'], $manuscript->category)->class('block mt-1 w-full')}}
                </div>
            </div -->

            <div class="mt-4">
                {{ html()->label(@('CSS Class'))->for('view_class')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->select('view_class', 
                        ['none'=>'Plain', 
                        'background-heighted'=>'Yellow', 
                        'background-red'=>'Red', 
                        'background-orange'=>'Orange',  
                        'background-greenyellow'=>'Greenyellow', 
                        'background-lime'=>'Lime', 
                        'background-olive'=>'Olive', 
                        'background-cadetblue'=>'Cadetblue',
                        'background-aqua'=>'Aqua'], 
                        $manuscript->view_class)->id('view_class')->class('block mt-1 w-full')}}
                </div><!--col-md-9-->
            </div><!--form-group-->
                    
            <div class="mt-4">
                {{ html()->label(@('Activate Manuscript'))->for('active')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    <x-toggle name="active" :checked="$manuscript->active" />
                </div><!--col-md-9-->
            </div><!--form-group-->
            
            <div class="mt-4">
                {{ html()->label(@('Sort Order'))->for('sort')->class('col-md-3 control-label') }}
                <div class="col-md-9">
                    {{ html()->text('sort', $manuscript->sort)->class('block mt-1 w-full')->required() }}
                </div><!--col-md-9-->
            </div><!--form-group-->

            <div class="block mt-4">
                <div class="col-md-auto">
                @if (!empty($manuscript->id))
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
