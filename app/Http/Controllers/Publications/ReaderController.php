<?php

/*
 * ReaderController.php
 */

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use App\Services\Publication\TransProperty;
use App\Http\Controllers\Publications\util\SblRtfHandler;
use App\Http\Controllers\Publications\util\RmrhRtfHandler;
use SdarmDL\BookLinkFinder\BookLinkFinder;
use SdarmDL\BookServer\BookServer;

class ReaderController extends Controller
{
    use \App\Services\Publication\Utility;

    protected $book, $year, $issue, $lang;
    //protected $tagBag = []; // not used
    protected $bibleVerseSameLine = true;
    protected $bibleRefSameLine = true;

    private $domDocument = null; 
    
    public $resourceServer;
    public $dl_server;
    
    public function __construct()
    {
        if( (isset($_ENV['HOST_ENV']) && strpos($_ENV['HOST_ENV'], "docker") !== FALSE ) 
            || strpos($_SERVER['SERVER_ADDR'], "127.0.0.1") !== FALSE ) { // local or docker host
            $this->resourceServer = "https://dl.lo";
            $this->dl_server = "https://dl.lo";
        }
        else {
            $this->resourceServer = "https://dl.sdarm.org";
            $this->dl_server = "https://dl.sdarm.org";
        }
    }

    /* Show book content with bible verses.
     *
     * @return \Illuminate\View\View
     */
    public function index() // __invoke($book, $year, $issue, $lang)
    {
        $book = empty($_GET['book']) ? 'sbl' : $_GET['book'];
        $year = empty($_GET['year']) ? '' : $_GET['year'];
        $issue = empty($_GET['issue']) ? '' : $_GET['issue'];
        $lang = empty($_GET['lang']) ? 'en' : $_GET['lang'];
        // $bVersion = empty($_GET['bv']) ? 'KJV' : $_GET['bv'];
        
        $bookServer = new BookServer();

        $langList = $this->bookLanguages('sbl', '', '', false);
        // if (strlen($langList) > 0) {
        //     $langList = json_decode("{" . substr($langList, 0, -1) . "}");
        //     $settings["langList"] = $langList;
        //     // $bibleVersion = "KJV";
        // }
        
        if ($lang == 'en')
            $bibles = $bookServer->execute_query("sb", "xquery for \$x in /book[@role='bible'] "
                            . "return concat('\"', data(\$x/@xml:base), '\"', ':', '\"', data(\$x/@title), '\"', ',')");
        else
            $bibles = $bookServer->execute_query("sb", "xquery for \$x in /book[@role='bible' and @xml:lang='$lang'] "
                            . "return concat('\"', data(\$x/@xml:base), '\"', ':', '\"', data(\$x/@title), '\"', ',')");
                    
        $bibleVersions = [];
        if (strlen($bibles) > 0) {
            $bibleVersions = json_decode("{" . substr($bibles, 0, -1) . "}");
            $settings["bibleVersions"] = $bibleVersions;
            // $bibleVersion = "KJV";
        }
        
        return view('publications.reader.reader', [
                    "book"=>$book, "lang"=>$lang, "year"=>$year, "issue"=>$issue
                    , "langList"=>(array)$langList
                    , "bibleVersions"=>((array)$bibleVersions)
                    , "resourceServer"=>$this->resourceServer
                    , "dl_server"=>$this->dl_server
        ]);
    }

    
    /* Return avalilable bible versions by language
     *
     * @return JSON
     */
    public function bibleVersions($lang) 
    {
        $this->lang = empty($lang) ? (empty($_GET['lang']) ? '' : $_GET['lang']) : $lang;
        
        $bookServer = new BookServer();
        if ($lang == 'en')
            $bibles = $bookServer->execute_query("sb", "xquery for \$x in /book[@role='bible'] "
                            . "return concat('\"', data(\$x/@xml:base), '\"', ':', '\"', data(\$x/@title), '\"', ',')");
        else
            $bibles = $bookServer->execute_query("sb", "xquery for \$x in /book[@role='bible' and @xml:lang='$lang'] "
                            . "return concat('\"', data(\$x/@xml:base), '\"', ':', '\"', data(\$x/@title), '\"', ',')");

        $bibleVersions = [];
        if (strlen($bibles) > 0) {
            return response( "{" . substr($bibles, 0, -1) . "}", 200)->header('Content-Type', 'application/json'); 
        }
        else {                         
            return response( '{}', 200)->header('Content-Type', 'application/json'); 
        }
    }

    
    /* Return avalilable language list by book
     *
     * @return JSON or Array
     */
    public function bookLanguages($book, $year, $issue, $isJson = true) 
    {
        $bookServer = new BookServer();

        $xArgs = "@role='$book'";
        if (!empty($year)) {
            $xArgs .= " and @year='$year'";
            if (!empty($issue)) {
                $xArgs .= " and @quarter='$issue'";
            }
        }
        $langString = $bookServer->execute_query("sdarm", "xquery for \$x in distinct-values(/book[$xArgs]/@xml:lang)"
                            . "order by \$x return data(\$x)");

        $arr = explode("\n", $langString);
        $isoCodesString = file_get_contents(base_path() . "/../dl.sdarm.org/resources/lang/iso_639_codes.xml");
        $sxml = new \SimpleXMLElement($isoCodesString);

        $resultArray = [];
        foreach ($arr as &$value) {
            $langName = $sxml->xpath("/iso_639/language[iso_639_1='$value']/language_name");
            if (count($langName) > 0) {
                $resultArray[$value] = (string)$langName[0][0];
            }
        }

        if (!$isJson) 
            return $resultArray;

        if (count($resultArray) > 0) {
            return response(json_encode($resultArray), 200)->header('Content-Type', 'application/json'); 
        }
        
        return response( '[]', 200)->header('Content-Type', 'application/json'); 
    }

    
    /* Show book content with bible verses.
     *
     * @return \Illuminate\View\View
     */
    public function show($book='', $lang='', $year='', $issue='') // __invoke($book, $year, $issue, $lang)
    {
        $this->book = empty($book) ? (empty($_GET['book']) ? '' : $_GET['book']) : $book;
        $this->year = empty($year) ? (empty($_GET['year']) ? '' : $_GET['year']) : $year;
        $this->issue =empty($issue) ? (empty($_GET['issue']) ? '' : $_GET['issue']) : $issue;
        $this->lang = empty($lang) ? (empty($_GET['lang']) ? '' : $_GET['lang']) : $lang;
        $this->bVersion = empty($_GET['bv']) ? '' : $_GET['bv'];
        $this->bibleVerseSameLine = empty($_GET['verse-same-line']) ? false : $_GET['verse-same-line'];
        $this->bibleRefSameLine = empty($_GET['bible-ref-same-line']) ? false : $_GET['bible-ref-same-line'];

        if (isset($_GET['pab']) && $_GET['pab'] == 'true')
            if ($_GET['source'] == 'tr') {
                $fileName = "../storage/app/private/publications/translator/data/{$this->book}pab{$this->year}_{$this->issue}_{$this->lang}.xml";
            } else { //if ($_GET['source'] == 'ms') {
                $fileName = "../storage/app/private/publications/manuscripts/{$this->book}pab{$this->year}_{$this->issue}_{$this->lang}.xml";
            } 
        else if (! empty($_GET['source']))
            if ($_GET['source'] == 'ms') {
                $fileName = "../storage/app/private/publications/manuscripts/{$this->book}{$this->year}_{$this->issue}_{$this->lang}.xml";
            } else if ($_GET['source'] == 'tr') {
                $fileName = "../storage/app/private/publications/translator/data/{$this->book}{$this->year}_{$this->issue}_{$this->lang}.xml";
            } 
            else { //if ($_GET['source'] == 'dl') {
                $fileName = "../../dl.sdarm.org/contents/publications/periodicals/{$this->book}/xml/{$this->book}{$this->year}_{$this->issue}_{$this->lang}.xml";
            }
        else
            $fileName = "../../dl.sdarm.org/contents/publications/periodicals/{$this->book}/xml/{$this->book}{$this->year}_{$this->issue}_{$this->lang}.xml";

        $contents = $this->buildContent($fileName);

        $settings = array( "book" => $book, "year" => $year, "issue" => $issue, "lang" => $lang, "langEGW" => $lang);

        $bookServer = new BookServer();
        if ($lang == 'en')
            $bibles = $bookServer->execute_query("sb", "xquery for \$x in /book[@role='bible'] "
                            . "return concat('\"', data(\$x/@xml:base), '\"', ':', '\"', data(\$x/@title), '\"', ',')");
        else
            $bibles = $bookServer->execute_query("sb", "xquery for \$x in /book[@role='bible' and @xml:lang='$lang'] "
                            . "return concat('\"', data(\$x/@xml:base), '\"', ':', '\"', data(\$x/@title), '\"', ',')");

        $bibleVersions = [];
        if (strlen($bibles) > 0) {
            $bibleVersions = json_decode("{" . substr($bibles, 0, -1) . "}");
            $settings["bibleVersions"] = $bibleVersions;
        }
                    
        $jsVar = "var settings=" . json_encode($settings) . ";\n";

        $transProp = new TransProperty;
        $transProp->init($lang, $lang);
        $jsVar .= "var langProp=" . json_encode($transProp->langProp) . ";\n";
        // replace patterns for download
        include_once(dirname(__FILE__) . "/util/DownloadTextSubstitude.php");
        $jsVar .= "var dnReplaceProp=" . json_encode($dnSubstitute) . ";\n";// defined in 'DownloadTextSubstitude.php'


        if ($this->book == 'sbl')
            $rtf = new SblRtfHandler;
        else 
            $rtf = new RmrhRtfHandler;
        $jsVar .= "var rtfProp=" . json_encode(get_object_vars($rtf)) . ";\n";

        return view('publications.reader.show', [
                "book"=>$book,
                "jsVar"=>$jsVar,
                "contents"=>$contents,
                "bibleVersion"=>$this->bVersion,
                "bibleVerseSameLine"=>$this->bibleVerseSameLine,
                "bibleRefSameLine"=>$this->bibleRefSameLine,
                "resourceServer"=>$this->resourceServer,
                "dl_server"=>$this->dl_server
            ]
        ); 
    }
    
    /* Return book content in XML format.
     *
     * @return JSON
     */
    public function xml($book='', $lang='', $year='', $issue='') 
    {
        $this->book = empty($book) ? (empty($_GET['book']) ? '' : $_GET['book']) : $book;
        $this->year = empty($year) ? (empty($_GET['year']) ? '' : $_GET['year']) : $year;
        $this->issue =empty($issue) ? (empty($_GET['issue']) ? '' : $_GET['issue']) : $issue;
        $this->lang = empty($lang) ? (empty($_GET['lang']) ? '' : $_GET['lang']) : $lang;
        
        $fileName = "../../dl.sdarm.org/contents/publications/periodicals/{$this->book}/xml/{$this->book}{$this->year}_{$this->issue}_{$this->lang}.xml";

        return response( $this->buildContent($fileName), 200)->header('Content-Type', 'text/plain'); 
    }

    
    /* Return book content in XML format.
     *
     * @return \Illuminate\View\View
     */
    public function composer($book='', $lang='', $year='', $issue='') 
    {
        $this->book = empty($book) ? (empty($_GET['book']) ? '' : $_GET['book']) : $book;
        $this->year = empty($year) ? (empty($_GET['year']) ? '' : $_GET['year']) : $year;
        $this->issue =empty($issue) ? (empty($_GET['issue']) ? '' : $_GET['issue']) : $issue;
        $this->lang = empty($lang) ? (empty($_GET['lang']) ? '' : $_GET['lang']) : $lang;
        
        $fileName = "../../dl.sdarm.org/contents/publications/periodicals/{$this->book}/xml/{$this->book}{$this->year}_{$this->issue}_{$this->lang}.xml";

        return response( "On Construction!", 200); 
    }

    
    // Build book contents
    protected function buildContent($fileName)
    {
        if (file_exists($fileName)) {
            $linkFinder = new BookLinkFinder($this->lang);
            $bookServer = new BookServer();
            $contents = file_get_contents($fileName);
            $linkFinder->link_find($contents);
            $this->domDocument = new \DOMDocument("1.0", "utf-8");
            $this->domDocument->preserveWhiteSpace = false;
            $this->domDocument->formatOutput = true;
            $this->domDocument->loadXML($contents);
            $this->traverseDom($this->domDocument->firstChild, $linkFinder, $bookServer);
            $contents = $this->domDocument->saveXML($this->domDocument->getElementsByTagName("book")->item(0));
            $pattern = array("&lt;em&gt;", "&lt;/em&gt;", "&lt;/span&gt;", "&lt;bible-text&gt;", "&lt;/bible-text&gt;");
            $replace = array("<em>", "</em>", "</span>", "<bible-text>", "</bible-text>");
            $contents = \str_replace($pattern, $replace, $contents);
            $contents = \preg_replace("/\&lt;span([^\&]+)\&gt;/", "<span$1>", $contents);
            $contents = \preg_replace("/\&lt;bible-text([^\&]+)\&gt;/", "<bible-text$1>", $contents);
        }
        else
            $contents = "File not exist!";

        return $contents;
    }
    
    
    // translate text in the DOM object
    protected function traverseDom(\DOMElement $element, &$linkFinder, &$bookServer)
    {
        static $book = '';
        static $chapter = '';
        static $serial = 0;
        foreach ($element->childNodes as $node) {
            if ($node->nodeType != XML_ELEMENT_NODE || $node->childNodes->length == 0 /*|| $node->tagName == 'bible-text'*/)
                continue;
            if ($node->nodeName == "fso") {  // support new design (v2)
                $sabbath = $node->childNodes->item(0);
                $title = $node->removeChild($node->childNodes->item(1));
                $node->insertBefore($title, $sabbath);
            }
            else if ($node->nodeName == "lesson") {  // support new design (v2)
                $sabbath = $node->removeChild($node->childNodes->item(1));
                $node->prepend($sabbath);
                $key_note = $node->childNodes->item(4);
                $readings = $node->removeChild($node->childNodes->item(5));
                $node->insertBefore($readings, $key_note);
            }
            else if ($node->nodeName == "day_lesson") {     // support new design (v2)
                $dayLessonHeader = $this->domDocument->createElement("div");
                $dayLessonHeader->setAttribute('class', 'day_lesson_header');
                $dateWrapper = $this->domDocument->createElement("span");
                $dateWrapper->setAttribute('class', 'date_wrapper');

                $subtitle = $node->removeChild($node->childNodes->item(2));
                $date = $node->removeChild($node->childNodes->item(1));
                $day = $node->removeChild($node->childNodes->item(0));
                
                $dateWrapper->appendChild($day);
                $dateWrapper->appendChild($date);
                $dayLessonHeader->appendChild($subtitle);
                $dayLessonHeader->appendChild($dateWrapper);

                $node->prepend($dayLessonHeader);
            }
            if ($node->firstChild->nodeType == XML_TEXT_NODE || $node->firstChild->tagName == 'em') {
                if ($this->book == "sbl") {  // Get bible verses
                    if (in_array($node->nodeName, ["question", "sub_question"]) 
                            && $node->parentNode->parentNode->getAttribute("no") != '6') {
                        $this->GenerateBibleVerse($node, $bookServer);

                        // $matches = [];
                        // foreach ($node->childNodes as $elem) {
                        //     if ($elem->nodeType == XML_ELEMENT_NODE && $elem->tagName == "span") {
                        //         $t = $elem->getAttribute("title");
                        //         if ($t == "") {
                        //             continue;
                        //         }
                        //         $matches[0][] = $elem->getAttribute("title");
                        //         $matches[1][] = $elem->textContent;
                        //     }
                        // }
                        // if (!empty($matches)) {
                        //     $continuousVerseCount = 0;
                        //     // verse label as humen readable form
                        //     for ($index = 0; $index < count($matches[0]); $index ++) {
                        //         $path = explode('/', $matches[0][$index]);
                        //         if (strlen($this->bVersion)) {
                        //             $path[0] = $this->bVersion;
                        //         }
                        //         $contents = $bookServer->get_data($path, 'content', 'X', '3');
                        //         if (is_array($contents)) {
                        //             // echo "<div style='display:none'>PATH: " . implode('/', $path) . "</div>";
                        //             $contents = "";
                        //         }
                        //         $contents = str_replace(["<a id='captcha-anchor' name='captcha-anchor' class='anchor'></a>"], 
                        //                                 [''], $contents); 
                        //         $contents = preg_replace(["/<span class='note-parag'>[^<]*<\/span>/m", "/<span class='note'>[^<]*<\/span>/m"], 
                        //                                 ["", ""], $contents);
                        //         $contents = preg_replace(["/>(\d+) ([^ ])/m", "/<\/?p[^>]*>/m"], 
                        //                                 ["><verse_no>$1</verse_no> $2", ""], $contents);
                        //         $label = $matches[1][$index] ;   // can be 'Psalm 23:1', '23:1', '1', 'Verse 3'
                        //         if (stripos($label, 'Verse') !== false) // when start without chapter - 'Verse 3'
                        //             $label = substr($label, strpos($label, ' ')+1);
                        //         if (! \preg_match('/^(\d?[^\d:–]+)/', $label)) { // lable without book name, '23:1' or '1'
                        //             if ($index == 0) { // when start with unknow book name, 'Chapter' or not clear
                        //                 $book = "???-$book "; 
                        //             }
                        //             if (strpos($label, ':') === false) { // only verse - '12' or '12-15'
                        //                 if ($index == 0) {  // when start without chapter - 'Verse 3'
                        //                     $chapter = "???-$chapter";
                        //                 }
                        //                 $currentVerse = strstr($label, '–', true) ?: (strstr($label, '-', true) ?: $label);
                        //                 // check if continuous verse
                        //                 if (stripos($matches[1][$index], 'Verse')===false && $index > 0 
                        //                         && \preg_match('/(\d+)$/', $matches[1][$index-1], $m) && (intval($currentVerse)-intval($m[1])) == 1) { 
                        //                     $continuousVerseCount ++;
                        //                     $matches[1][$index-$continuousVerseCount] .= ", $label";
                        //                 }
                        //                 else {
                        //                     $continuousVerseCount = 0;
                        //                 }
                        //                 if ($continuousVerseCount === 0)
                        //                     $matches[1][$index] = $book . $chapter . $label;
                        //             }
                        //             else {  // without book name '23:1'
                        //                 $matches[1][$index] = $book . $label;
                        //                 $continuousVerseCount = 0;
                        //             }
                        //         }
                        //         else {
                        //             $continuousVerseCount = 0;
                        //         }
                        //         if (\preg_match('/^\d?[^\d:]+/', $matches[1][$index] , $m)) {  // keep book name for following context
                        //             $book = $m[0];
                        //         }
                        //         if (\preg_match('/\d+:/', $matches[1][$index] , $m)) { // keep chapter name for following context
                        //             $chapter = $m[0];
                        //         }
                        //         else if ($index == 0) { //['Oba', 'Philem', '2 John', '3 John', 'Jude'];
                        //             $chapter = '';
                        //         }
                        //         if ($continuousVerseCount > 0) {
                        //             $matches[2][$index-$continuousVerseCount] .= $contents;
                        //             $matches[2][$index] = "";
                        //         }                                
                        //         else
                        //             $matches[2][$index] = $contents;
                        //     }
                            
                        //     $verses = "";
                        //     for ($index = 0; $index < count($matches[2]); $index ++) {
                        //         if (empty($matches[2][$index]))
                        //             continue;
                        //         $verses .= "<bible-text><bible-ref><span class='source-link' title='{$matches[0][$index]}'>{$matches[1][$index]}</span></bible-ref><p>{$matches[2][$index]}</p></bible-text>";
                        //     }
                        //     $verses = str_replace("&nbsp;", "&#160;", $verses);
                        //     $verses = str_replace("H3", "verse", $verses);
                        //     $verses = str_replace("<a id='captcha-anchor' name='captcha-anchor' class='anchor'></a>", '', $verses);
                        //     $DOM_inner_XML = new \DOMDocument();
                        //     $DOM_inner_XML->preserveWhiteSpace = false;
                        //     //$internal_errors = libxml_use_internal_errors( true );
                        //     $DOM_inner_XML->loadXML("<bible-verses>" . $verses . "</bible-verses>");
                        //     //libxml_use_internal_errors( $internal_errors );
                        //     $content_node = $DOM_inner_XML->getElementsByTagName('bible-verses')->item(0);
                        //     $content_node = $node->ownerDocument->importNode( $content_node, true );
                        //     if ($node->nextSibling)
                        //         $node->parentNode->insertBefore($content_node, $node->nextSibling);
                        //     else
                        //         $node->parentNode->appendChild($content_node);
                        // }
                    }
                }
                else if ($this->book == 'rmrh') {
                    if ($node->tagName == 'title' && $node->parentNode->tagName == 'biblioentry') {
                        $xrefNo = $node->parentNode->getAttribute('xreflabel');
                        $node->firstChild->nodeValue = "$xrefNo " . $node->firstChild->nodeValue;; 
                    }
                }
                $node->setAttribute ("id", ++ $serial);
                if(str_contains($node->getAttribute("class"), "source-link")) {
                    // $node->setAttribute ("title", $serial);
                    $node->setAttribute ("class", $node->getAttribute("class") . " has-text");
                }
                else {
                    $node->setAttribute ("title", $serial);
                    $node->setAttribute ("class", "has-text");
                }
            }
            else {
                $this->traverseDom($node, $linkFinder, $bookServer);
            }
        }    
    }
        
}