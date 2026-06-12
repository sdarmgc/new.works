
    <div class="admin-menu justfy-end p-2 mb-2">
        @hasanyrole('administrator|executive')
        <a href="{{ route("publications.manuscripts.createManuscript") }}" class="admin-menu text-indigo-700 dark:text-indigo-300">{{ trans('Add Manuscript') }}</a>
        @endhasanyrole
    </div>
@foreach ($manuscript as $pub)
    @if (!$pub->active && !(Auth::user()->hasRole('administrator')))
        @continue
    @endif
    <div class="section">
        <div class="section-header {{ $pub->view_class }}">
            @if ($pub->active)
            <span class="name active">
            @else
            <span class="name">
            @endif
                {!! '<i class="fa fa-chevron-down"></i>' !!}&nbsp;{{ $pub->name }}
            </span>
            <span class="admin-menu" style="text-align: right">
                @hasanyrole('administrator|executive')
                <a href="{{ route("publications.manuscripts.editManuscript", [$pub->id]) }}" class="admin-menu text-indigo-700 dark:text-indigo-300">{{ trans('Edit') }}</a>
                @endhasanyrole
            </span>
        </div>
        <div class="section-content">
            <table class="manuscript-item">
                <tr class="text-left">
                    <th>Item Name</th>
                    <th>Size</th>
                    <th>Info</th>
                    @hasanyrole('administrator|executive')
                    <th></th>
                    @endhasanyrole
                </tr>
                @foreach ($pub->files as $item)
                    @if ($item->type == '1' && !(Auth::user()->hasRole('administrator') || Auth::user()->hasRole('translator')) )
                        @continue
                    @endif
                    <tr>
                        <td class="item-name">
                            <span>
                                <!-- // 1:Translator, 2:Html Link, 3:Text, 4:PDF, 5:Image File, 6: Zipped File -->
                                @if ($item->type == '1')
                                    {!! '<i class="far fa-clone"></i>' !!}
                                @elseif ($item->type == '2')
                                    {!! '<i class="far fa-folder-open"></i>' !!}
                                @elseif ($item->type == '3')
                                    {!! '<i class="far fa-file-alt"></i>' !!}
                                @elseif ($item->type == '4')
                                    {!! '<i class="far fa-file-pdf"></i>' !!}
                                @elseif ($item->type == '5')
                                    {!! '<i class="far fa-file-image"></i>' !!}
                                @elseif ($item->type == '6')
                                    {!! '<i class="far fa-file-archive"></i>' !!}
                                @else
                                    {!! '<i class="far fa-file"></i>' !!}
                                @endif
                                
                                @if ($item->type == '1')
                                    @php
                                        $yearPos = strpos($item->url, '2'); 
                                        $book = substr($item->url, 0, $yearPos);
                                        $year = substr($item->url, $yearPos, 4);
                                        $issue = substr($item->url, $yearPos+5 , 1);
                                        $link = "translator/{$book}/{$year}/{$issue}";
                                    @endphp
                                    <a href="{{ $link }}" target="_blank" rel="noopener noreferrer">{{ $item->name }}</a>
                                @elseif ($item->type == '2')
                                    <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer">{{ $item->name }}</a>
                                @else
                                    <a href="{{ Storage::url("publications/manuscripts/") . $item->url }}" target="_blank" rel="noopener noreferrer">{{ $item->name }}</a>
                                @endif
                            </span>
                        </td>
                        <td class="item-size">
                            @if (is_numeric($item->size) && $item->size > 0)
                                {{ round($item->size / 1024, 2) }} MB
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="item-desc">
                            {{ $item->description }}
                        </td>
                        @hasanyrole('administrator|executive')
                        <td class="admin-menu" >
                            <a href="{{ route("publications.manuscripts.editItem", [$item->id]) }}" class="admin-menu text-indigo-700 dark:text-indigo-300">{{ trans('Edit') }}</a>
                        </td>
                        @endhasanyrole
                    </tr>
                @endforeach
                    @hasanyrole('administrator|executive')
                    <tr class=>
                        <td class="admin-menu" colspan=1 style="text-align: left;">
                            @if (count($pub->files) > 0)
                            <a href="{{ route("email.compose") }}" class="admin-menu text-indigo-700 dark:text-indigo-300">{{ trans('Send Email Notification') }}</a>
                            @endif
                        </td>
                        <td class="admin-menu" colspan=3>
                            <a href="{{ route("publications.manuscripts.createItem", [$pub->id]) }}" class="admin-menu text-indigo-700 dark:text-indigo-300">{{ trans('Add') }}</a>
                        </td>
                    </tr>
                    @endhasanyrole
            </table>
        </div>
    </div>
@endforeach
            
@push("after-scripts")
    <script>
        // Toggle open/close with smooth animation
        document.querySelectorAll('.section-header').forEach(header => {
            header.addEventListener('click', () => {
            const section = header.parentElement;
            section.classList.toggle('open');
            });
        });
    </script>
@endpush