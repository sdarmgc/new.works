<table class='file-table' border="0" cellpadding="1" cellspacing="1">
    <tbody>
    	<!-- Manuscript -->
        <tr style="background-color:#999999; height:3px; font-size:1px; line-height:100%;">
            <td colspan="6" scope="col">&nbsp;</td>
        </tr>
        <tr class="file-category">
            <td scope="col">Manuscripts</td>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr class="h-line">
            <td colspan="6"></td>
        </tr>
        <tr class="file-header">
            <td class="">Publication Name</td>
            <td class="middle-center">Translate (APP)</td>
            <td class="middle-center">View (TXT)</td>
            <td class="middle-center">View (PDF)</td>
            <td class="middle-center">Download<br />(DOC and TXT)</td>
            <td class="middle-center"></td>
        </tr>
        <tr class="h-line">
            <td colspan="6"></td>
        </tr>
            @foreach ($files as $file)
            @if (!empty($file->file_translate) || !empty($file->file_view_1) || !empty($file->file_view_2) || !empty($file->file_download))
                <tr class="file-row {{ $file->view_class }} {{ $file->active!='1'?'deactivated':'' }}">
                    <td class='manuscript-name'>{!! $file->name !!}</td>
                        @if (!empty($file->file_translate))
                            @php
                                $yearPos = strpos($file->file_translate, '2'); 
                                $book = substr($file->file_translate, 0, $yearPos);
                                $year = substr($file->file_translate, $yearPos, 4);
                                $issue = substr($file->file_translate, $yearPos+5 , 1);
                                $link = "publications/translator/{$book}/{$year}/{$issue}";
                            @endphp
                            <td class="middle-center"><a href="{!! url($link) !!}" target="_blank">
                                    <img alt="" src={{ Storage::url("publications/manuscripts/icons/web_app_64.png") }} class="icon-view" /></a></td>
                        @else
                            <td></td>
                        @endif

                        @if (!empty($file->file_view_1))
                            <td class="middle-center"><a href="{{ Storage::url("publications/manuscripts/old/{$file->file_view_1}") }}">
                                    <img alt="" src={{ Storage::url("publications/manuscripts/icons/view_icon.gif") }} class="icon-view" /></a></td>
                        @else
                            <td></td>
                        @endif

                        @if (!empty($file->file_view_2))
                            <td class="middle-center"><a href="{{ Storage::url("publications/manuscripts/old/{$file->file_view_2}") }}">
                                    <img alt="" src={{ Storage::url("publications/manuscripts/icons/pdf.gif") }} class="icon-pdf" /></a></td>
                        @else
                            <td></td>
                        @endif

                        @if (!empty($file->file_download))
                            <td class="middle-center">
                                <a href="{{ Storage::url("publications/manuscripts/old/{$file->file_download}") }}">
                                    <img alt="" src={{ Storage::url("publications/manuscripts/icons/download_icon.gif") }} class="icon-download" />
                                    ({{ number_format(Storage::size("public/publications/manuscripts/old/{$file->file_download}")/1000000, 2) }} MB)
                                </a>
                            </td>
                        @else
                            <td></td>
                        @endif

                        @can("view backend")
                            <td class="middle-center">
                                <a href="{!! route('frontend.publications.manuscripts.edit.old', array('id' => $file->id)) !!}" class="">
                                    {{ trans('Edit') }}
                                </a>
                           </td>
                        @else
                            <td></td>
                        @endcan
                </tr>
                <tr class="h-line">
                    <td colspan="6"></td>
                </tr>>
            @endif
            @endforeach 
        <tr class="file-row">
            <td class="middle-center" colspan="3" style="text-align: left;">
                @can("view backend")
                    <a href="{!! route('frontend.publications.manuscripts.new.old', array('category' => 'Manuscript')) !!}" class="">
                        {{ trans('Add New Manuscript') }}
                    </a>
                @endcan
            </td>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr class="h-line">
            <td colspan="6"></td>
        </tr>


		<!-- 
			Image files
		-->
		
        <tr style="background-color:#fff; height:20px; font-size:1px; line-height:100%;">
            <td colspan="6" scope="col">&nbsp;</td>
        </tr>
        <tr style="background-color:#999999; height:3px; font-size:1px; line-height:100%;">
            <td colspan="6" scope="col">&nbsp;</td>
        </tr>
        <tr class="file-category">
            <td scope="col">Images</td>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr class="h-line">
            <td colspan="6"></td>
        </tr>
        <tr class="file-header">
            <td>Publication Name</td>
            <td class="middle-center">&nbsp;</td>
            <td class="middle-center">&nbsp;</td>
            <td class="middle-center">View Thumbnails</td>
            <td class="middle-center"><span>Download</span></td>
            <td class="middle-center"></td>
        </tr>
        <tr class="h-line">
            <td colspan="6"></td>
        </tr>
            @foreach ($files as $file)
            @if (!empty($file->file_thumbnail) || !empty($file->image_download))
                <tr class="file-row {{ $file->view_class }} {{ $file->active!='1'?'deactivated':'' }}">
                    <td class='manuscript-name'>{!! $file->name !!}</td>
                    <td class="middle-center"></td>
                    <td class="middle-center"></td>

                        @if (!empty($file->file_thumbnail))
                            <td class="middle-center"><a href="{{Storage::url("publications/manuscripts/old/{$file->file_thumbnail}") }}">
                                    <img alt="" src={{Storage::url("publications/manuscripts/icons/view_icon.gif") }} class="icon-view" /></a></td>
                        @else
                            <td></td>
                        @endif
                        @if (!empty($file->image_download))
                            <td class="middle-center">
                                <a href="{{ Storage::url("publications/manuscripts/old/{$file->image_download}") }}">
                                    <img alt="" src={{Storage::url("publications/manuscripts/icons/cmyk.gif") }} class="icon-cmyk" />
                                    ({{number_format(Storage::size("public/publications/manuscripts/old/{$file->image_download}")/1000000, 2) }} MB)
                                </a>
                            </td>
                        @else
                            <td></td>
                        @endif
                        @can("view backend")
                            <td class="middle-center">
                                 <a href="{!! route('frontend.publications.manuscripts.edit.old', array('id' => $file->id)) !!}" class="">
                                    {{ trans('Edit') }}
                                </a>
                            </td>
                        @else
                            <td></td>
                        @endcan
                </tr>
                <tr class="h-line">
                        <td colspan="6"></td>
                </tr>
            @endif
            @endforeach
        <tr class="file-row">
            <td class="middle-center" colspan="3" style="text-align: left;">
                @can("view backend")
                    <a href="{!! route('frontend.publications.manuscripts.new.old', array('category' => 'Image')) !!}" class="">
                        {{ trans('Add New Image') }}
                    </a>
                @endcan
            </td>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr class="h-line">
            <td colspan="6"></td>
        </tr>
                    
            </tbody>
    </table>