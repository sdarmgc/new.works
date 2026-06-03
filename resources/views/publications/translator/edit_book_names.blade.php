<div class="dialog edit-dialog edit-book-name-dialog">
    <div class="dialog-header">
        <div class="title">EDIT BOOK NAMES ({{ strtoupper($lang_code) }})</div>
    </div>
    <div class="dialog-body">
        <form id="edit-book-name">
            <input type="hidden" name="lang_code" value="{{ $lang_code }}" >
            
            <div class="subtitle" style="">BIBLE</div>
            <div class="field-group">
            @foreach ($nameProp["bible"] as $value)
                <div class="field-item">
                    <label class="field-label">{{ $value[1]}}</label>
                    <input type="text" name="bible[{{ $value[0]}}]" 
                           value="{{ $value[2]}}" 
                           style="width:{{ max(strlen($value[2])*0.7, 8)}}em;"  />
                </div>
            @endforeach
            </div>
            <div class="space" style="padding:.5em;"></div>
            
            
            <div class="subtitle" style="">E. G. White Writings</div>
            <table>
            <thead>
                    <tr>
                        <td class="field-label" style="width:35%">English Pattern</td>
                        <td class="field-label" style="width:20%">Translation Replace (by English Pattern)</td>
                        <td class="field-label" style="width:30%">DL(Digital Library) Search Pattern</td>
                        <td class="field-label" style="width:10%">DL Path Replace</td>
                        <td class="field-label" style="width:5%">DL Display Replace(BOOK,PAGE)</td>
                    </tr>
                </thead>
                <tbody>
                @foreach ($nameProp["book"] as $key => $value)
                    <tr>
                        <td class="field-label"><label class="field-label">{{ $value[0]}}</label></td>
                        <td class="field-label"><input type="text" name="book[{{ $key }}][0]" value="{{ $value[1] }}"  style="width:100%" /></td>
                        <td class="field-label"><input type="text" name="book[{{ $key }}][1]" value="{{ $value[2] }}"  style="width:100%" /></td>
                        <td class="field-label"><input type="text" name="book[{{ $key }}][2]" value="{{ $value[3] }}"  style="width:100%" /></td>
                        <td class="field-label"><input type="text" name="book[{{ $key }}][3]" value="{{ $value[4] }}"  style="width:100%" /></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="field-item submit">
                <input type="button" name="cancel" value="CANCEL" id="button-cancel" class="button cancel" />
                <input type="submit" name="submit" value="SUBMIT CHANGES" class="button submit" />
            </div>
        </form>
    </div>
</div>
