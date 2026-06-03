<?php
/*
 * RmrhRtfHandler.php
 * ver 1.0
 * 
 * 20190602
 * 
 * variable definitions for rtf converter
 */

namespace App\Http\Controllers\Frontend\Publications\util;


class RmrhRtfHandler
{

    public $header =
        "{\\rtf1\\ansi\\deff4
        {\\fonttbl{\\f0\\froman\\fprq2\\fcharset0 Times New Roman;}}
        {\\colortbl;\\red0\\green0\\blue0;\\red0\\green0\\blue255;\\red0\\green255\\blue255;\\red0\\green255\\blue0;\\red255\\green0\\blue255;\\red255\\green0\\blue0;\\red255\\green255\\blue0;\\red255\\green255\\blue255;\\red0\\green0\\blue128;\\red0\\green128\\blue128;\\red0\\green128\\blue0;\\red128\\green0\\blue128;\\red128\\green0\\blue0;\\red128\\green128\\blue0;\\red128\\green128\\blue128;\\red192\\green192\\blue192;\\red0\\green0\\blue1;}
        {\\stylesheet{\\s0\\snext0\\cf0\\kerning1\\dbch\\af8\\langfe1042\\dbch\\af9\\afs20\\alang1081\\loch\\f0\\hich\\af0\\fs20 Normal;}
        {\*\\cs15\\snext15\\b\\fs50\\dropcapli3\\dropcapt1 cs_dropcap;}
        {\*\\cs16\\snext16\\i0\\b cs_bold;}
        {\*\\cs17\\snext17\\i\\b0 cs_italic;}
        {\*\\cs18\\snext18\\super cs_citation;}
        {\\s28\\snext28\\sl288\\slmult1\\ql\\sb0\\sa0\\aspalpha\\kerning1\\dbch\\af8\\langfe1042\\dbch\\af9\\afs20\\alang1081\\loch\\f0\\hich\\af0\\fs20\lang1033 [RMRH];}
        {\\s29\\sbasedon28\\snext29\\qj\\fi360\\sb0\\sa0\\loch\\f0\\hich\\af4\\fs20 body;}
        {\\s30\\sbasedon29\\snext34\\qc\\sb170\\sa283\\b\\fs50 title;}
        {\\s31\\sbasedon29\\snext34\\ql\\sb170\\sa283\\b\\fs36 title_sect1;}
        {\\s32\\sbasedon29\\snext34\\ql\\sb170\\sa283\\b\\fs28 title_sect2;}
        {\\s33\\sbasedon29\\snext34\\ql\\sb170\\sa283\\b\\fs24 title_sect3;}
        {\\s34\\sbasedon29\\snext34\\qj\\fi360\\sb0\\sa0\\fs20\\hyphpar1 paragraph;}
        {\\s35\\sbasedon29\\snext34\\ql\\sb170\\sa283\\b\\i\\fs28 keyword;}
        {\\s36\\sbasedon29\\snext34\\qr\\sb170\\sa283\\i\\fs20 annotation;}
        {\\s37\\sbasedon29\\snext34\\qc\\i\\b\\fs24 abstract;}
        {\\s38\\sbasedon29\\snext34\\qc\\li504\\ri504\\b\\fs20 epigraph;}
        {\\s39\\sbasedon29\\snext34\\qr\\sa283\\i\\fs20 auther;}
        {\\s40\\sbasedon29\\snext41\\ql\\sb170\\b\\fs24 reference_title;}
        {\\s41\\sbasedon29\\snext41\\ql\\fi360\\i\\fs20 reference_entry;}
        }
        {\*\\generator LibreOffice/6.0.2.1_Windows_X86_64 LibreOffice_project/f7f06a8f319e4b62f9bc5095aa112a65d2f3ac89, Modified by ://works.sdarm.org}
        \\deftab720\\deftab720\\hyphauto1\\viewscale100\\margmirror
        \\paperh16838\\paperw11906\\margl720\\margr720\\margt720\\margb720 
        \\uc0 
        ";

    public $footer = "}";

    /*
    * - coding style
    * "{ $PREFIX $TEXT $SUFFIX }"
    */

    public $ps_title = "{\\pard\\plain \\s30\\qc\\sb170\\sa283\\b\\fs50 ";

    public $ps_title_sect1 = "{\\pard\\plain \\s31\\ql\\sb170\\sa170\\b\\fs36 ";

    public $ps_title_sect2 = "{\\pard\\plain \\s32\\ql\\sb170\\b\\fs28 ";

    public $ps_title_sect3 = "{\\pard\\plain \\s33\\ql\\sb170\\b\\fs24 ";

    public $ps_suffix = "\\par}\n";

    public $ps_para = "{\\pard\\plain \\s34\\qj\\fi360\\fs20\\hyphpar1 ";

    public $ps_keyword = "{\\pard\\plain \\s35\\ql\\sb170\\b\\i\\fs28 ";

    public $ps_para_annotation = "{\\pard\\plain \\s36\\qr\\sb170\\sa283\\i\\fs20 ";

    public $ps_para_abstract = "{\\pard\\plain \\s37\\qc\\i\\b\\fs24 ";

    public $ps_para_epigraph = "{\\pard\\plain \\s38\\qc\\li504\\ri504\\b\\fs20 ";

    public $ps_auther = "{\\pard\\plain \\s39\\qr\\sa283\\i\\fs20 ";

    public $ps_title_bibliograpy = "{\\pard\\plain \\s40\\ql\\sb170\\b\\fs24 ";

    public $ps_title_biblioentry = "{\\pard\\plain \\s41\\ql\\fi360\\i\\fs20 ";


    public $cs_prefix = "{";
    public $cs_suffix = "}";

    public $cs_cap = "\\cs15\\snext15\\b\\fs50\\dropcapli3\\dropcapt1 ";

    public $cs_bold = "\\cs16\\b ";

    public $cs_italic = "\\cs17\\i ";

    public $cs_citation = "\\cs18\\super ";
}
