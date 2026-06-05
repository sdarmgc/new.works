<x-app-layout>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">

            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $messageTitle }}
                </h2> 
            </x-slot>

            <div class="row">
                <div class="col">
                    {!!$messageBody!!}
                </div><!--col-->
            </div><!--row-->
        </div>
    </div>
</div>

</x-app-layout>