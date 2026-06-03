
    <div class="dialog edit-dialog edit-property-dialog">
        <div class="dialog-header">
            <div class="title">LANGUAGE PROPERTIES</div>
        </div>
        <div class="dialog-body">
            <form id="edit-lang-prop">
                <!--input type="hidden" name="prop[lang]" value="{!! $langProp->lang_code !!}" /-->
@foreach ($langProp as $key => $value)
    @if (is_string($value))
        @if ($key == "lang_code" || $key == "lang_code3")
            <div class="field-item hide">
        @else
            <div class="field-item">
        @endif
                <label class="field-label">{{ $key }}</label>
                <input type="text" name="prop[{!! $key !!}]" value="{!! $value !!}" 
                       style="width:{{ max(strlen($value)*0.7, 4) }}em;" />
            </div>
    @else
            <div class="field-group">
            @php
                $index = 1;
            @endphp
            @foreach ($value as $val)
                <div class="field-item">
                    <label class="field-label">{{ $key . " [" . $index . "]" }}</label>
                    <input type="text" name="prop[{!! $key !!}][{!! $index-1 !!}]" value="{!! $val !!}" 
                           style="width:{{ max(strlen($val)*0.7, 4) }}em;" />
                </div>
                @php
                    $index ++;
                @endphp
            @endforeach
            </div>
    @endif
@endforeach
                <div class="field-item submit">
                    <input type="button" name="cancel" value="CANCEL" id="button-cancel" class="button cancel" />
                    <input type="submit" name="submit" value="SUBMIT CHANGES" class="button submit" />
                </div>
            </form>
        </div>
    </div>
