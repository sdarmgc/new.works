<?php
/*
 * SblRtfHandler.php
 * ver 1.0
 * 
 * 20190602
 * 
 * variable definitions for rtf converter
 */

namespace App\Http\Controllers\Publications\util;

class SblRtfHandler 
{
    public $header = "{\\rtf1\\ansi\\deff4
        {\\fonttbl{\\f0\\froman\\fprq2\\fcharset0 Times New Roman;}{\\f1\\froman\\fprq2\\fcharset2 Symbol;}{\\f2\\fswiss\\fprq2\\fcharset0 Arial;}{\\f3\\froman\\fprq2\\fcharset129 Liberation Serif{\*\\falt Times New Roman};}{\\f4\\froman\\fprq2\\fcharset0 Times New Roman;}}
        {\\colortbl;\\red0\\green0\\blue0;\\red0\\green0\\blue255;\\red0\\green255\\blue255;\\red0\\green255\\blue0;\\red255\\green0\\blue255;\\red255\\green0\\blue0;\\red255\\green255\\blue0;\\red255\\green255\\blue255;\\red0\\green0\\blue128;\\red0\\green128\\blue128;\\red0\\green128\\blue0;\\red128\\green0\\blue128;\\red128\\green0\\blue0;\\red128\\green128\\blue0;\\red128\\green128\\blue128;\\red192\\green192\\blue192;\\red0\\green0\\blue1;}
        {\\stylesheet{\\s0\\snext0\\cf0\\kerning1\\dbch\\af8\\langfe1042\\dbch\\af9\\afs20\\alang1081\\loch\\f0\\hich\\af0\\fs20 Normal;}
        {\*\\cs15\\snext15\\fs20 cs_bible_source;}
        {\*\\cs16\\snext16\\i\\b0\\fs19 cs_reference_book;}
        {\*\\cs17\\snext17\\b0\\fs20 cs_reference_page;}
        {\*\\cs18\\snext18\\i\\b0 cs_reading;}
        {\*\\cs19\\snext19\\b0\\fs20 cs_reference_page_a;}
        {\*\\cs20\\snext20\\fs24 cs_day;}
        {\*\\cs21\\snext21\\fs24 cs_date;}
        {\*\\cs22\\snext23\\fs20\\b cs_verse_no;}
        {\*\\cs23\\snext24\\i0\\b0\\charscalex95\\fs20 cs_verse;}
        {\*\\cs24\\snext24\\fs20\\b\\i cs_em;}
        {\*\\cs25\\snext25\\fs20 cs_etc;}
        {\*\\cs26\\snext26\\i\\b0\\fs19 cs_reference_book_ibid;}
        {\*\\cs27\\snext27\\i\\b0 cs_reading_ibid;}
        {\*\\cs28\\snext15\\b\\fs20 cs_bible_ref;}
        {\*\\cs29\\snext28\\i0\\b cs_lesson_header;}
        {\\s28\\snext28\\sl288\\slmult1\\ql\\li0\\ri0\\lin0\\rin0\\fi0\\aspalpha\\charscalex95\\kerning1\\dbch\\af8\\langfe1042\\dbch\\af9\\afs20\\alang1081\\loch\\f0\\hich\\af0\\fs20\lang1033 [SBL];}
        {\\s29\\sbasedon28\\snext29\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\loch\\f4\\hich\\af4\\fs20 body;}
        {\\s30\\sbasedon29\\snext30\\sl540\\qc\\li0\\ri0\\lin0\\rin0\\fi0\\sb180\\sa283\\i0\\b\\charscalex95\\fs50 title;}
        {\\s31\\sbasedon29\\snext31\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20 paragraph;}
        {\\s32\\sbasedon29\\snext32\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20 space;}
        {\\s33\\sbasedon29\\snext33\\sl228\\qr\\li0\\ri0\\lin0\\rin0\\fi360\\i\\b0\\charscalex95\\fs20 writer;}
        {\\s34\\sbasedon29\\snext34\\sl240\\ql\\li0\\ri0\\b\\charscalex95\\fs20 fso_date;}
        {\\s35\\sbasedon30\\snext35\\sl360\\qc\\li0\\ri0\\fi0\\sb180\\sa120\\i0\\b\\charscalex95\\fs36\\pagebb fso_title;}
        {\\s54\\sbasedon30\\snext35\\sl240\\ql\\li0\\ri0\\fi0\\sb120\\sa120\\i0\\b\\charscalex95\\fs24 fso_subtitle;}
        {\\s36\\sbasedon29\\snext36\\sl228\\qj\\li72\\ri72\\lin72\\rin72\\fi360\\charscalex95\\fs20 fso_paragraph;}
        {\\s37\\sbasedon36\\snext37\\sl228\\qr\\li72\\ri72\\lin72\\rin72\\fi360\\i\\b0\\charscalex95\\fs20 fso_writer;}
        {\\s38\\sbasedon29\\snext38\\sl240\\ql\\tqr\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\i0\\b\\charscalex95\\fs24 lesson_header;}
        {\\s39\\sbasedon29\\snext39\\sl240\\ql\\tqr\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\i0\\b\\charscalex95\\fs24\\pagebb sabbath_date;}
        {\\s40\\sbasedon29\\snext40\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\i0\\b\\charscalex95\\fs20 key_text;}
        {\\s41\\sbasedon29\\snext41\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\sb120\\charscalex95\\fs20 key_note;}
        {\\s42\\sbasedon29\\snext42\\sl228\\qj\\tx2400\\li0\\ri0\\lin0\\rin0\\fi360\\sb120\\i0\\b\\charscalex95\\fs20 reading;}
        {\\s43\\sbasedon29\\snext43\\sl228\\qr\\tqr\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\b\\charscalex95\\fs24 day;}
        {\\s44\\sbasedon29\\snext44\\sl228\\qr\\tqr\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\b\\charscalex95\\fs24 day_a;}
        {\\s45\\sbasedon29\\snext45\\sl540\\ql\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\caps\\i0\\b\\charscalex95\\fs28 subtitle;}
        {\\s55\\sbasedon29\\snext45\\sl540\\ql\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\caps\\i0\\b\\charscalex95\\fs28\\pagebb subtitle_pb;}
        {\\s46\\sbasedon29\\snext46\\sl220\\qj\\tx360\\li360\\ri0\\lin360\\rin0\\fi-360\\sb240\\i0\\b\\charscalex95\\fs20 question;}
        {\\s47\\sbasedon29\\snext47\\sl300\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\sa60\\brdrb\\brdrs\\brdrw10\\brdrcf17\\brsp60\\charscalex95\\fs20 answer_line;}
        {\\s48\\sbasedon29\\snext48\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20 ref_parag;}
        {\\s49\\sbasedon46\\snext49\\sl250\\qj\\tx360\\li360\\ri0\\lin360\\rin0\\fi-360\\i0\\b\\charscalex95\\fs20 review_question;}
        {\\s50\\sbasedon46\\snext50\\sl220\\qj\\tx360\\li360\\ri0\\lin360\\rin0\\fi0\\i0\\b\\charscalex95\\fs20 sub_question;}
        {\\s51\\sbasedon29\\snext52\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20\\b bible_ref;}
        {\\s52\\sbasedon29\\snext53\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20 bible_text;}
        {\\s53\\sbasedon29\\snext53\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20 ect;}
        }
        {\*\\generator LibreOffice/6.0.2.1_Windows_X86_64 LibreOffice_project/f7f06a8f319e4b62f9bc5095aa112a65d2f3ac89, Modified by ://works.sdarm.org}
        \\deftab720\\deftab720\\hyphauto1\\viewscale100\\margmirror
        {\*\\pgdsctbl {\\pgdsc0\\pgdscuse455\\pgwsxn7937\\pghsxn12189\\marglsxn567\\margrsxn737\\margtsxn567\\margbsxn907\\pgdscnxt0 Default;}}
        \\formshade\\paperh12189\\paperw7937\\margl567\\margr737\\margt567\\margb907\\sectd\\sbknone\\sectunlocked1\\pgwsxn7937\\pghsxn12189\\marglsxn567\\margrsxn737\\margtsxn567\\margbsxn907\\ftnbj\\ftnstart1\\ftnrestart\\ftnnar\\aenddoc\\aftnrstcont\\aftnstart1\\aftnnrlc
        {\*\\ftnsep\\chftnsep}
        \\uc0 
        ";

    public $footer = "}";

    /*
    * - coding style
    * "{ $PRIFIX $TEXT $SUFFIX }"
    *  ex:
    *      <pstyle:day_a><cstyle:day>Sunday <cstyle:date>	June 30
    *      $ps_day_a . $cs_day . $text_day . $cs_suffix . $cs_date $text_date . $cs_suffix . $ps_suffix
    */

    public $ps_title = "\\pard\\plain \\s30\\sl540\\qc\\li0\\ri0\\lin0\\rin0\\fi0\\sb180\\sa283\\i0\\b\\charscalex95\\fs50 ";

    public $ps_suffix = "\\par\n";

    public $ps_space = "\\pard\\plain \\s32\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20 ";

    public $ps_parag = "\\pard\\plain \\s31\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20 ";

    public $cs_suffix = "}";

    public $cs_bible_source = "\\cs15\\fs20 ";

    public $cs_reference_book = "\\cs16\\i\\b0\\fs19\\charscalex95 \\loch ";

    public $cs_reference_book_ibid = "\\cs26\\i\\b0\\fs19\\charscalex95 \\loch ";

    public $cs_reference_page = "\\cs17\\b0\\fs20 ";

    public $ps_writer = "\\pard\\plain \\s33\\sl228\\qr\\li0\\ri0\\lin0\\rin0\\fi360\\i\\b0\\charscalex95\\fs20 ";

    public $ps_fso_date = "\\pard\\plain \\s34\\sl240\\ql\\li0\\ri0\\b\\charscalex95\\fs20 ";

    public $ps_fso_title = "\\pard\\plain \\s35\\snext35\\sl540\\qc\\li0\\ri0\\fi0\\sb180\\sa120\\i0\\b\\charscalex95\\fs36\\pagebb ";

    public $ps_fso_subtitle = "\\pard\\plain \\s54\\sl240\\ql\\li0\\ri0\\fi0\\sb120\\sa120\\b\\charscalex95\\fs24 ";

    public $ps_fso_paragraph = "\\pard\\plain \\s36\\sl228\\qj\\li72\\ri72\\lin72\\rin72\\fi360\\charscalex95\\fs20 ";

    public $ps_fso_writer = "\\pard\\plain \\s37\\sl228\\qr\\li72\\ri72\\lin72\\rin72\\fi360\\i\\b0\\charscalex95\\fs20 ";

    public $ps_lesson_header = "\\pard\\plain \\s38\\sl240\\ql\\tqr\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\i0\\b\\charscalex95\\fs24 ";

    public $cs_lesson_header = "\\cs29\\i0\\b ";

    public $ps_sabbath_date = "\\pard\\plain \\s39\\sl240\\ql\\tqr\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\i0\\b\\charscalex95\\fs24\\pagebb ";

    public $ps_key_text = "\\pard\\plain \\s40\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\i0\\b\\charscalex95\\fs20 ";

    public $ps_key_note = "\\pard\\plain \\s41\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\sb120\\charscalex95\\fs20 ";

    public $ps_reading = "\\pard\\plain \\s42\\sl228\\qj\\tx2400\\li0\\ri0\\lin0\\rin0\\fi360\\sb120\\i0\\b\\charscalex95\\fs20 ";

    public $cs_reading = "\\cs18\\i\\b0\\charscalex95 \\loch\\fs19 ";

    public $cs_reading_ibid = "\\cs27\\i\\b0\\charscalex95 \\loch\\fs19 ";

    public $cs_reference_page_a = "\\cs19\\b0\\fs20 ";

    public $ps_day = "\\pard\\plain \\s43\\sl228\\qr\\tqr\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\b\\charscalex95\\fs24 ";

    public $ps_day_a = "\\pard\\plain \\s44\\sl228\\qr\\tqr\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\b\\charscalex95\\fs24 ";

    public $cs_day = "\\cs20 \\loch\\charscalex95\\fs24 ";

    public $cs_date = "\\cs21 \\loch\\charscalex95\\fs24 ";

    public $ps_subtitle = "\\pard\\plain \\s45\\sl540\\ql\\tqr\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\caps\\i0\\b\\charscalex95\\fs28 ";

    public $ps_subtitle_pb = "\\pard\\plain \\s55\\sl540\\ql\\tqr\\tx6420\\li0\\ri0\\lin0\\rin0\\fi0\\caps\\i0\\b\\charscalex95\\fs28\\pagebb ";

    public $ps_question = "\\pard\\plain \\s46\\sl220\\qj\\tx360\\li360\\ri0\\lin360\\rin0\\fi-360\\sb240\\i0\\b\\charscalex95\\fs20 ";

    public $ps_sub_question = "\\pard\\plain \\s50\\sl220\\qj\\tx360\\li360\\ri0\\lin360\\rin0\\fi0\\i0\\b\\charscalex95\\fs20 ";

    public $ps_answer_line = "\\pard\\plain \\s47\\sl300\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\sa60\\brdrb\\brdrs\\brdrw10\\brdrcf17\\brsp60\\fs20 ";

    public $ps_ref_parag = "\\pard\\plain \\s48\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20 ";

    public $ps_review_question = "\\pard\\plain \\s49\\sl250\\qj\\tx360\\li360\\ri0\\lin360\\rin0\\fi-360\\i0\\b\\charscalex95\\fs19 ";
        
    public $ps_bible_ref = "\\pard\\plain \\s51\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20\\b ";
    public $cs_bible_ref = "\\cs28\\b\\fs20 ";
    public $ps_bible_text = "\\pard\\plain \\s52\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20 ";
    public $cs_verse_no = "\\cs22\\fs20\\b ";
    public $cs_verse = "\\cs23\\i0\\b0\\charscalex95\\fs20 ";  
    
    public $ps_etc = "\\pard\\plain \\s53\\sl228\\qj\\li0\\ri0\\lin0\\rin0\\fi360\\charscalex95\\fs20 ";
    public $cs_etc = "\\cs25\\charscalex95 \\loch\\fs20 ";  
    public $cs_em = "\\cs24\\i\\b\\charscalex95 \\loch\\fs20 ";  
}
