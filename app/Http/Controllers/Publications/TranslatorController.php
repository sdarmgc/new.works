<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Auth;
use App\Models\User;
use App\Services\Publication\TransProperty;
use App\Services\Publication\SblRtfHandler;
use App\Services\Publication\RmrhRtfHandler;
use SdarmDL\BookLinkFinder\BookLinkFinder;
use SdarmDL\BookServer\BookServer;

class TranslatorController extends Controller
{
    use \App\Services\Publication\Utility;

    protected $book, $year, $issue, $lang, $s_lang;
    protected $file_log;
    protected $simpleXML;
    public $pabSubstitute = [];
    
    public $resourceServer;
    public $dl_server;

    private $regexpChars = ["\\", "/", "?", "^", "$", "*", "+", "|", ".", "(", ")", "[", "]", "{", "}"];
    private $regexpCharsEsc = ["\\\\", "\/", "\?", "\^", "\$", "\*", "\+", "\|", "\.", "\(", "\)", "\[", "\]", "\{", "\}"];
    
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('permission:access translation');
        $this->file_log = false;
        // DEBUG 
        if (App::environment('local'))
            $this->file_log = fopen('translator.log', 'w'); 

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


    function __destruct() {
        if ($this->file_log)
            fclose($this->file_log);
    }


    public function test($book, $year, $issue, $lang, $s_lang)
    {
        return "this is translator test page";         
    }
    
    
    /**
     * Display pararell translation interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function translator($book, $year, $issue)
    {
        $lang = Auth::user()->languages()->pluck("iso_639_1")->first();
        if ($lang == "" || $lang == "all") // administrator
            $lang = "en";
        $s_lang = "en";
        
        //return $this->parallelTrans($book, $year, $issue, $lang, $s_lang);
        return redirect("publications/translator/{$book}/{$year}/{$issue}/{$lang}/{$s_lang}");
    }
    
    
    /*** Display pararell translation interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function parallelTrans($book, $year, $issue, $lang, $s_lang)
    {
        $langXML = new \DOMDocument();
        $langXML->load(base_path() . "/../dl.sdarm.org/resources/lang/iso_639_codes.xml");
        $xpathLang = new \DOMXpath($langXML);

        if (strpos($book, "pab") > 0) {
            if (!auth()->user()->hasRole("administrator") && !auth()->user()->hasRole("pab")) {
                $title = "This page is for 'Publication Approval Board'.<br />You do not have access permission to access this page.</b>!";
                $desc = "Please " . "Contact the "  
                    . "<a href='" . route('contact', ['subject'=>"Translation Language Access"]) . "'>"
                    . "administrator</a> to request permission.";
                return view("errors.general", ["title"=>$title, "description"=>$desc]);
            }
            else if ($lang != $s_lang){
                $title = "Publication Approval Board";
                $desc = "<span style='color:red'>The source and target languages must be the same to use PAB features!</span>";
                return view("errors.general", ["title"=>$title, "description"=>$desc]);
            }
            else if ($lang != 'en'){
                $title = "Publication Approval Board";
                $desc = "<span style='color:red'>Only English is supported!</span>";
                return view("errors.general", ["title"=>$title, "description"=>$desc]);
            }
        } 
        else {
            $langArray = Auth::user()->languages()->pluck("iso_639_1")->all();
            if (array_search("all", $langArray) === false && array_search($lang, $langArray) === false) {
                $langName = $xpathLang->evaluate("/iso_639/language[iso_639_1='{$lang}']/language_name")[0]->nodeValue;
                $title = "You do not have access permission to translate to <b>$langName</b>!";
                $desc = "Please " . "Contact the "  
                    . "<a href='" . route('contact', ['subject'=>"Translation Language Access"]) . "'>"
                    . "administrator</a> to request permission.";
                return view("errors.general", ["title"=>$title, "description"=>$desc]);
            }
        }
            
        $settings = array( "book" => $book, "year" => $year, "issue" => $issue, "lang" => $lang, "s_lang" => $s_lang, 
                           "langEGW" => $lang, 
                           "sourceId" => $book . $year . "_" . $issue . "_" . $s_lang,
                           "targetId" => $book . $year . "_" . $issue . "_" . $lang,
                           "docId" => "translation_" . $book . $year . "_" . $issue . "_" . $lang,
                           "indesign_template" => Storage::url("publications/translator/indesign_template/sbl_template_base.indt"),
                           "userName" => Auth::user()->first_name,
                           "userEmail" => Auth::user()->email);

        $pageTitle = strtoupper($settings["book"]) . " " . $settings["year"] . " / " . $settings["issue"];
            
        /* 
         * javascript variables 
         */
        global $transProp;
        $transProp = new TransProperty;
        $transProp->init($lang, $s_lang);

        $jsVar = "var langProp=" . "{" . $s_lang . ":" . json_encode($transProp->langPropSource) . ","
                                . $lang . ":" . json_encode($transProp->langProp) . "};\n";
        $jsVar .= "var bibleProp=" . json_encode($transProp->bibleProp) . ";\n";
        $jsVar .= "var settings=" . json_encode($settings) . ";\n";

        /* book name table - target language*/
        $filePath = base_path() . "/../dl.sdarm.org/resources/book_attr";
        $bookNames = file("{$filePath}/book_name_{$lang}.txt");
        $bookArray = [];
        for ($i = 0; $i < count($bookNames); $i++) {
            if (strlen($bookNames[$i]) < 3 || $bookNames[$i][0] == ';') 
                continue;
            $parts = explode("\t", rtrim($bookNames[$i]));
            if (count(explode("/", $parts[3])) < 4) {   // 'AA/$4' or 'EGWBC/$5BC/$7'
                $bookArray[$parts[0]] = array_merge([0], $parts);
            }
            else { // RH/$5/$3/$4"
                $bookArray[$parts[0]] = array_merge([1], $parts);
            }
        }
        $jsVar .= "var bookProp=" . json_encode($bookArray) . ";\n";

        /* book name table - source language*/
        if ($lang != $s_lang) {
            $filePath = base_path() . "/../dl.sdarm.org/resources/book_attr";
            $bookNames = file("{$filePath}/book_name_{$s_lang}.txt");
            $bookArray = [];
            for ($i = 0; $i < count($bookNames); $i++) {
                if (strlen($bookNames[$i]) < 3 || $bookNames[$i][0] == ';') 
                    continue;
                $parts = explode("\t", rtrim($bookNames[$i]));
                if (count(explode("/", $parts[3])) < 4) {   // 'AA/$4' or 'EGWBC/$5BC/$7'
                    $bookArray[$parts[0]] = array_merge([0], $parts);
                }
                else { // RH/$5/$3/$4"
                    $bookArray[$parts[0]] = array_merge([1], $parts);
                }
            }
            $jsVar .= "var bookPropSource=" . json_encode($bookArray) . ";\n";
        }

        if (strpos($book,'sbl') !== false)
            $rtf = new SblRtfHandler;
        else
            $rtf = new RmrhRtfHandler;
        // $jsVar .= "var rtfProp=" . json_encode(get_object_vars($rtf)) . ";\n";
        $jsVar .= "var rtfProp=" . str_replace("    ", "", json_encode(get_object_vars($rtf))) . ";\n";

        // replace patterns for download
        include_once(app_path() . "/Services/Publication/DownloadTextSubstitude.php");
        $jsVar .= "var dnReplaceProp=" . json_encode($dnSubstitute) . ";\n";// defined in 'DownloadTextSubstitude.php'

        /* all available language */
        $langCodes = Auth::user()->languages()->pluck("iso_639_1")->all();
        if (in_array('all', $langCodes)) {
            $languages = "<input type='text' name='lang' id='lang-select' value='{$lang}' />";
        }
        else {
            $languages = "<select type='select' name='lang' id='lang-select'>\n";
            $langArray = array();
            foreach ($langCodes as $code) {
                $langArray[$code] = $xpathLang->evaluate("/iso_639/language[iso_639_1='{$code}']/language_name")[0]->nodeValue;
            }
            asort($langArray);
            foreach ($langArray as $key => $value) {
                if ($lang == $key)
                    $languages .= "\t<option value='{$key}' selected='selected'>{$value}</option>\n";
                else
                    $languages .= "\t<option value='{$key}'>{$value}</option>\n";
            }
            $languages .= "</select>";
        }
        
        $langProp="";

        return view('publications.translator.parallel_trans', [
                    "book"=>$book, "year"=>$year, "issue"=>$issue, "lang"=>$lang, "s_lang"=>$s_lang, 
                    "jsVar"=>$jsVar, "langProp"=>$langProp, "languages"=>$languages, "pageTitle"=>$pageTitle, "xmlText"=>"",
                    "resourceServer"=>$this->resourceServer, "dl_server"=>$this->dl_server]); 
    }

    /*
     * To support a paragraph as a translation block which contains child xml tags
     */
    static function escapeChildTagForDom(&$text, $book) 
    {
        // if ($book == 'sbl') {
        //     return $text;
        // }
        // else {
            //return preg_replace(array("/<(\/?emphasis[^>]*)>/i", "/<(\/?citation[^>]*)>/i"), "&lt;$1&gt;", $text);
            return preg_replace(["/<(\/?emphasis[^>]*)>/i", "/<(\/?em[^>]*)>/i", "/<(\/?citation[^>]*)>/i", "/<(\/?b)>/i", "/<(\/?i)>/i"], "@@$1@@", $text);
        // }
    }

    /*
     * To support a paragraph as a translation block which contains child xml tags
     */
    static function escapeChildTagForXML(&$text, $book) {
        // if ($book == 'sbl') {
        //     return $text;
        // }
        // else {
            //return preg_replace("/&lt;([^@]*)&gt;/g", "<$1>", $text);
            return preg_replace("/\@\@([^@]*)\@\@/", "<$1>", $text);
        // }
    }

    function titleToDom($text) 
    {
        $text = str_replace("<title", "<title-tag", $text); // HTML does not display <title> tag
        $text = str_replace("</title>", "</title-tag>", $text); // HTML does not display <title> tag
        return $text;
    }

    function titleToXML($text) 
    {
        $text = str_replace("<title-tag", "<title", $text); // HTML does not display <title> tag
        $text = str_replace("</title-tag>", "</title>", $text); // HTML does not display <title> tag
        return $text;
    }


    /**
     * Get translation contents.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTranslationContents($book, $year, $issue, $lang, $s_lang)
    {        
        if( empty($book) || empty($year) || empty($issue) || empty($lang) || empty($s_lang))
            return Response::json([ 'message'=>"Wrong access!", 'code'=>-1, 'contents'=>"" ]);

        $this->book = $book;
        $this->year = $year;
        $this->issue = $issue;
        $this->lang = $lang;
        $this->s_lang = $s_lang;
        $xmlSourceText = "";
        $xmlTargetText = "";
        
        try {
            $linkFinder = new BookLinkFinder($s_lang);
        } catch (Throwable $e) {
            $linkFinder = null;
        }
        try {
            $bookServer = new BookServer();
        } catch (Throwable $e) {
            $bookServer = null;
        }

        // prepare variables for traslation
        if (strpos($book, 'pab') !== false) {
            include_once(app_path() . "/Services/Publication/PabTextSubstitute.php");
            $this->pabSubstitute = $pabSubstitute; // defined in 'PabTextSubstitute.php'
        }

        /* 
         * prepare source XML Dom
         */
        if (Storage::disk('local')->exists("publications/translator/data/{$book}{$year}_{$issue}_{$s_lang}.xml")) {
            $xmlSourceText = Storage::disk('local')->get("publications/translator/data/{$book}{$year}_{$issue}_{$s_lang}.xml");
        }
        else if (Storage::disk('local')->exists("public/publications/manuscripts/{$book}{$year}_{$issue}_{$s_lang}.xml")) {
            $xmlSourceText = Storage::disk('local')->get("public/publications/manuscripts/{$book}{$year}_{$issue}_{$s_lang}.xml");
        }
        if ($xmlSourceText == "")
            return Response::json(['message'=>"error: No source exist!", 'code'=>0]);
        $xmlSourceText = preg_replace("/<\!DOCTYPE[^>]+>/", "", $xmlSourceText); // prevent structure validation
        $xmlSourceText = TranslatorController::escapeChildTagForDom($xmlSourceText, $book);
        
        global $sourceDom;
        $sourceDom = new \DOMDocument("1.0", "utf-8");
        $sourceDom->preserveWhiteSpace = false;
        $sourceDom->formatOutput = true;
        //$sourceDom->validateOnParse = true;
        $sourceDom->loadXML($xmlSourceText);

        $isSourceUpdated = false;
        $sourceDate = $sourceDom->firstChild->getAttribute("updated"); 
        if (empty($sourceDate)) {
            $sourceDate = gmdate("Y-m-d\TH:i:s\Z");
            $sourceDom->firstChild->setAttribute("updated", $sourceDate);
            $isSourceUpdated = true;
        }

        // insert 'book-title' and 'id's
        $partialText = substr($xmlSourceText, 0, 500);
        if (strpos($partialText, 'id="1"') === false) {
            if (strpos($book,'sbl') !== null && $sourceDom->getElementsByTagName("book-title")->length == 0) {
                $title = $sourceDom->firstChild->getAttribute("title");
                $titleElement = $sourceDom->createElement("book-title", $title);                
                $sourceDom->firstChild->insertBefore($titleElement, $sourceDom->firstChild->firstChild);
            }
            $serial = 0;
            $this->addDomId($sourceDom->firstChild, $serial);
            $isSourceUpdated = true;
        }

        if ($isSourceUpdated) {
            $xmlSourceText = $sourceDom->saveXML($sourceDom->getElementsByTagName("book")->item(0));
            Storage::disk('local')->put("publications/translator/data/{$book}{$year}_{$issue}_{$s_lang}.xml", "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n" . TranslatorController::escapeChildTagForXML($xmlSourceText, $book));
        }
        
        /*
         * prepare target XML Dom
         */
        if (Storage::disk('local')->exists("publications/translator/data/{$book}{$year}_{$issue}_{$lang}.xml")) {
            $xmlTargetText = Storage::disk('local')->get("publications/translator/data/{$book}{$year}_{$issue}_{$lang}.xml");
            $xmlTargetText = TranslatorController::escapeChildTagForDom($xmlTargetText, $book);
        }
        else if ($s_lang == $lang) { //($s_lang != 'en') {
            $xmlTargetText = $xmlSourceText;
        }
        
        global $targetDom;
        $targetDom = new \DOMDocument("1.0", "utf-8");
        $targetDom->preserveWhiteSpace = false;
        $targetDom->formatOutput = true;
        //$targetDom->validateOnParse = true;
        if ($xmlTargetText != "") {
            $targetDom->loadXML($xmlTargetText);
            $partialText = substr($xmlTargetText, 0, 500);
            if (strpos($partialText, 'id="1"') === false) {
                $serial = 0;
                $this->addDomId($targetDom->firstChild, $serial);    
            }
        }
        else {  // produce new target
            $updated = gmdate("Y-m-d\TH:i:s\Z");
            $targetDom->loadXML($xmlSourceText);
            // set <book> attributes
            $targetDom->firstChild->setAttribute("xml:id", "$book-$lang-$year-$issue");
            $targetDom->firstChild->setAttribute("xml:lang", $lang);
            $targetDom->firstChild->setAttribute("updated", $updated);

            // pre-translate text
            if ($s_lang == 'en' && $lang != 'en') {                 
                global $transProp;
                $transProp = new TransProperty;
                $transProp->init($lang, $s_lang);
                $this->translateDom($targetDom->firstChild, $book, $transProp, $linkFinder, $bookServer);
            }            
            $xmlTargetText = $targetDom->saveXML($targetDom->getElementsByTagName("book")->item(0));
            Storage::disk('local')->put("publications/translator/data/{$book}{$year}_{$issue}_{$lang}.xml", "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n" . TranslatorController::escapeChildTagForXML($xmlTargetText, $book));
        }
                
        // compose source and target xml element parallelly.
        try {
            $this->simpleXml = new \SimpleXMLElement($xmlSourceText);
            $sourceXpath = new \DOMXpath( $sourceDom );
            $sourceXpath->registerNamespace('s', 'http://docbook.org/ns/docbook'); // may not needed ???
            $this->composeDom($targetDom->firstChild, $sourceXpath, $book, $linkFinder, $bookServer);
        } 
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        $xmlText = $targetDom->saveXML($targetDom->getElementsByTagName("book")->item(0));
        $xmlText = str_replace("<title", "<title-tag", $xmlText); // HTML does not display <title> tag
        $xmlText = str_replace("</title>", "</title-tag>", $xmlText); // HTML does not display <title> tag

        //$xmlText = preg_replace("/<\?xml[^>]*>/", "", $xmlText);
        $emailNode = $targetDom->getElementsByTagName("book")->item(0)->getAttributeNode("editor");
        if ($emailNode) {
            $user = User::where('email', $emailNode->value)->first();
            if ($user) {
            $email = $user->email;
            $userName = $user->first_name;
            }
            else {
                $email = $userName = $emailNode->value;
            }
        }
        else {
            $email = $userName = "WORKS";
        }
        $xmlText = '<translator source-date="' . $sourceDate . '" version="1.0" editor-email="' . $email . '" editor-name="' . $userName . '">' . $xmlText . '</translator>';
        // DEBUG 
        if ($this->file_log) {
            //fwrite($this->file_log, $xmlText);
            $log = "Time: " . date("Y-m-d H:i:s") . "\n";
            $log = $log . "function: TranslatorController::getTranslationContents()\n"; 
            $log = $log . "book: " . $book . " year: " . $year . " issue: " . $issue . " lang: " . $lang . " s_lang: " . $s_lang . "\n"; 
            $log = $log . "----------------------------------------------------------------\n";
            fwrite($this->file_log, $log);
        }
        return Response::json([ 'message'=>"success!", 'code'=>1, 'contents'=>$xmlText ]);
    }

    // add id to all text node
    function addDomId(\DOMElement $domNode, &$serial) 
    {
        // replace element position for SBL XML v2.2
        if ($serial == 0) {
            if (strpos($this->book,'sbl') !== null) {  
                if ($domNode->getAttribute("version") == "2.1")
                    $domNode->setAttribute("version", "2.2"); 
            }
        }
        foreach ($domNode->childNodes as $node) {
            if ($node->nodeType == XML_TEXT_NODE || $node->childNodes->length == 0) { 
                continue; 
            }
            // replace element position for SBL XML v2.2
            if (strpos($this->book,'sbl') !== null) {
                if ($node->nodeName == "fso") {
                    $title = $node->childNodes->item(1);
                    if ($title->nodeName == "title") {
                        $title = $node->removeChild($title);
                        $node->prepend($title);
                    }
                }
                else if ($node->nodeName == "lesson") {
                    $sabbath = $node->childNodes->item(1);    
                    if ($sabbath->nodeName == "sabbath") {
                        $sabbath = $node->removeChild($sabbath);
                        $node->prepend($sabbath);
                    }
                    $key_note = $node->childNodes->item(4);
                    $readings = $node->childNodes->item(5);
                    if ($key_note->nodeName == "key_note" && $readings->nodeName == "readings") {
                        $readings = $node->removeChild($readings);
                        $node->insertBefore($readings, $key_note);
                    }
                }
                else if ($node->nodeName == "day_lesson") {
                    $subtitle = $node->childNodes->item(2);    
                    if ($subtitle->nodeName == "subtitle") {
                        $subtitle = $node->removeChild($subtitle);
                        $node->prepend($subtitle);
                    }
                }
            }

            $hasTextNode = false; 
            foreach ($node->childNodes as $cn) {
                if ($cn->nodeType == XML_TEXT_NODE) {
                    $hasTextNode = true; 
                    break;
                }
            }
            if ($hasTextNode) { //if ($node->firstChild->nodeType == XML_TEXT_NODE) {
                $node->setAttribute ("id", ++ $serial);
            }
            else {
                $this->addDomId($node, $serial);
            }
        }    
    }

    // translate text in the DOM object
    function translateDom(\DOMElement $domNode, $book, $transProp, $linkFinder, $bookServer)
    {
        global $contextLesson, $contextYear, $contextMonth, $contextDay, $contextDayNo;
        foreach ($domNode->childNodes as $node) {
            if ($node->nodeType == XML_TEXT_NODE || $node->childNodes->length == 0)
                continue;
            if ($node->nodeName == "fso" || $node->nodeName == "lesson" || $node->nodeName == "day_lesson") {
                if ($node->nodeName == "lesson")
                    $contextLesson = $node->getAttribute("no");
                else if ($node->nodeName == "day_lesson")
                    $contextDayNo = intVal( $node->getAttribute("no") ) - 1;
                $date = $node->getAttribute("date");
                $contextYear = substr($date, 0, 4);
                $contextMonth = intval( substr($date, 4, 2) );
                $contextDay = intval( substr($date, 6, 2) );
            }
            if ($node->firstChild->nodeType == XML_TEXT_NODE) {
                $text = $node->firstChild->nodeValue;
                //$text = htmlspecialchars($node->firstChild->nodeValue, ENT_NOQUOTES | ENT_HTML5);
                if (strpos($book, "sbl") === 0) {
                    $text = $transProp->transProp($node->nodeName, $text, 
                            $contextLesson, $contextYear, $contextMonth, $contextDay, $contextDayNo, $linkFinder, $bookServer);
                }
                else if ($book == 'rmrh' && $node->tagName == 'title') {
                    if ($node->parentNode->tagName == 'biblioentry') {
                        $xrefNo = $node->parentNode->getAttribute('xreflabel');
                        $text = "$xrefNo $text";
                    }
                } 
                $node->firstChild->nodeValue = $text; 
            }
            else {
                $this->translateDom($node, $book, $transProp, $linkFinder, $bookServer);
            }
        }    
    }

    // compose translator xml structure
    protected function composeDom(\DOMElement $domNode, $sourceXpath, &$book, $linkFinder, $bookServer)
    {
        static $prev_text = ''; // for a paragraph which the reference link is included in the following paragraph. 
        foreach ($domNode->childNodes as $node) {
            if ($node->nodeType == XML_TEXT_NODE || $node->childNodes->length == 0) {
                continue;
            }
            if ($node->attributes->getNamedItem('id') && is_numeric($node->attributes->getNamedItem('id')->value)) { //($hasTextNode) { 
                $serial = $node->attributes->getNamedItem('id')->value;//$node->setAttribute ("id", ++ $serial);
                $nodeList = $sourceXpath->query( "//*[@id=$serial]" );
                // get inner html - 
                $text_s = "";
                foreach($nodeList->item(0)->childNodes as $child){
                    $text_s .= $nodeList->item(0)->ownerDocument->saveXML($child); //$child->C14N();
                }
                
                $text_t = "";
                foreach($node->childNodes as $child){
                    $text_t .= $node->ownerDocument->saveXML($child); //$child->C14N();
                }

                $text_s = TranslatorController::escapeChildTagForXML($text_s, $book);
                $text_t = TranslatorController::escapeChildTagForXML($text_t, $book);

                $linkFinder->link_find($text_s);
    
                // '*pab' reference text check
                $sourceMismatch = 0;  // 1 = text mismatch-caution, 2 = text mismatch, 4 = page mismatch
                $classStr = '';
                while (strpos($book, "pab") !== false && $node->tagName != "key_text") { // 'sbl|rmrh pab' on English
                    if (strpos($book, "sbl") !== false) { // 'sbl pab' on English
                        if ( strpos($text_s, '“') !== 0) { //!in_array($node->nodeName, ["key_note", "ref_parag"])) { // check only reference parag
                            $prev_text = ''; 
                            break;
                        }
                        if (\preg_match("/”\s*—.+/", $text_s, $matches) !== 1) {
                            $prev_text .= "$text_t\n"; // keep the text to check if the following parag contains reference link
                            $text_t = "SEE THE FOLLOWING PARAGRAPH.";
                            break;
                        }
                        $linkText = $matches[0]; // '”—The Acts of the Apostles, p. 355.'
                        $text_t = $prev_text . strstr($text_t, "”", true);
                    } 
                    else { // 'rmrh pab' on English
                        $linkText = '';
                        if (\preg_match_all("/<citation>(\d+)<\/citation>/", $text_s, $matches)) { 
                            $citeNo = $matches[1][0];
                            //$queryStr = "//*[@id='" . $serial . "']/ancestor::s:article//*[@xreflabel='" . $citeNo . "']/s:title"; // with docbook ns - xmlns="http://docbook.org/ns/docbook" 
                            $queryStr = "//*[@id='" . $serial . "']/ancestor::article//*[@xreflabel='" . $citeNo . "']/title";  // without docbook ns - xmlns="http://docbook.org/ns/docbook" 
                            $nodeList = $sourceXpath->query($queryStr);
                            if ($nodeList->length) {
                                $linkText = $nodeList[0]->nodeValue;
                                if( $linkFinder->link_find($linkText) < 1) {
                                    $prev_text = ''; 
                                    break;
                                };
                            }
                        }
                        else {
                            $prev_text = ''; 
                            break;
                        }  
                        $text_t = $prev_text . $text_t;      
                    }                 
                    if (!\preg_match_all("/title='([^']+)'>/", $linkText, $matches, PREG_SET_ORDER)) {  // Reference source text. ex) '”—The Acts of the Apostles, pp. 412, 413.'
                        $text_t = "THE QUOTED RESOURCE IS NOT AVAILABLE. (SOURCE:$linkText)";
                        $prev_text = '';
                        $sourceMismatch = 4;
                        break;
                    }
                    $prev_text = ''; 
                    $refLinkText = ''; // get all source text from the DB
                    $sourceMismatch = $this->_pabMatchText($text_t, $refLinkText, $matches, $bookServer, $classStr, $serial);
                    $text_t = $refLinkText;
                    break;
                }

                if (strpos($book, "rmrh") !== false && $node->tagName == 'title') {
                    if ($node->parentNode->tagName == 'biblioentry') {
                        $xrefNo = $node->parentNode->getAttribute('xreflabel');
                        $text_s = "$xrefNo $text_s"; //$text_t = "$xrefNo $text_t";
                    }
                }
                $oddEvenClass = ($serial % 2) ? 'odd' : 'even';
                $node->removeChild($node->firstChild);
                $node->setAttribute ("title", $serial);
                $node->setAttribute("class", "editable $oddEvenClass");
                $fragment = $node->ownerDocument->createDocumentFragment();
                if (strpos($book, "pab") !== false
                        && in_array($node->nodeName, ["question", "sub_question"])) {
                    $fragment->appendXML("<span class='source-text'><span>" . $text_s . "</span></span>");
                    $node->appendChild($fragment);
                    $this->GenerateBibleVerse($node->firstChild->firstChild, $bookServer);
                }
                else {
                    $fragment->appendXML("<span class='source-text'>" . $text_s . "</span>");
                    $node->appendChild($fragment);
                }
                $fragment = $node->ownerDocument->createDocumentFragment();
                $fragment->appendXML("<span class='idLable' name='$serial'>#$serial</span>");
                $node->appendChild($fragment);
                $fragment = $node->ownerDocument->createDocumentFragment();
                try {
                    set_error_handler(array($this, '_error_handler'));
                    if ($sourceMismatch & 1)
                        $classStr .= " source-warning source-caution";
                    if ($sourceMismatch & 2)
                        $classStr .= " source-warning page-mismatch";
                    if ($sourceMismatch & 4)
                        $classStr .= " source-warning source-mismatch";
                    $fragment->appendXML("<span class='target-text $classStr'>" . $text_t . "</span>");
                }
                catch (ErrorException $e) {
                    echo '';
                }
                finally {
                    if ($this->errorMessage != '') {
                        if($this->file_log) {
                            fwrite($this->file_log, "\n--- HTML INSERTION ERROR\n" . $this->errorMessage . "---\n");
                            fwrite($this->file_log, $text_t . "----END HTML INSERTION ERROR\n");
                        }
                        $fragment->appendXML("<span class='target-text source-mismatch '><span>SOMETHING WENT WRONG.</span><br />"
                                             . "<span style='color:red'>ERROR-MESSAGE:</span><br /><span>" . $this->errorMessage . "</span></span>") ;
                    } 
                    $this->errorMessage = '';
                    restore_error_handler();
                }
                $node->appendChild($fragment);
            }
            else {
                $this->composeDom($node, $sourceXpath, $book, $linkFinder, $bookServer);
            }
        }    
    }

    /*
     * Compare text with the text from DB and highlight the matched text.
     */
    function isRomanNumberContinuous($a, $b) {
        $romanNumerals = [
                "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X",
                "XI", "XII", "XIII", "XIV", "XV", "XVI", "XVII", "XVIII", "XIX", "XX",
                "XXI", "XXII", "XXIII", "XXIV", "XXV", "XXVI", "XXVII", "XXVIII", "XXIX", "XXX"
            ];
        $aPos = array_search(strtoupper($a), $romanNumerals);
        $bPos = array_search(strtoupper($b), $romanNumerals);
        return $bPos - $aPos === 1;
    }

    function _matchText(&$processedText, &$refLinkText, $searchText, &$count, $checkBegining) {
        if ($checkBegining) {
            $refLinkText = str_replace($searchText, "<z><c>" . $searchText[0] . '</c>' . substr($searchText, 1) . '</z>', $refLinkText, $count);
        }
        else {
            $refLinkText = str_replace($searchText, "<z>" . $searchText . '</z>', $refLinkText, $count);
        }
        if ($count == 1) { // found
            $tailPos = strpos($refLinkText,'</z>') + 4;
            $processedText .= substr($refLinkText, 0, $tailPos);
            $refLinkText = substr($refLinkText, $tailPos);
        }
    }

    function _pabMatchText(&$text_t, &$refLinkText, &$matches, &$bookServer, &$classStr, $serial) {
        $sourceMismatch = 0;
        $sourceStr = '';
        $sourcePath = [];
        for ($index = 0; $index < count($matches); $index++) { // $matches[1] == 'book/page' or 'book/year/month/day'; 
            if ($index == 0) 
                $sourceStr = $matches[$index][1];
            else 
                $sourceStr = $sourceStr . ", " . $matches[$index][1];
            $sourcePath = explode('/', $matches[$index][1]);
            if (count($sourcePath) == 2 && $index+1 < count($matches)) { // check if the next match is the continuous page
                $p2 = explode('/', $matches[$index+1][1]);
                if (count($p2) == 2 
                        && (intval($p2[1]) - intval($sourcePath[1]) == 1
                        || $this->isRomanNumberContinuous($sourcePath[1], $p2[1]))) {  // combine neighbouring pages
                    $sourcePath[1] = $sourcePath[1] . "-" . $p2[1];
                    $index++;
                }
            }
            try {
                if (!in_array('KJV', $sourcePath)) {
                    $t = $bookServer->get_data($sourcePath, 'content', 'X', ''); 
                    if (!is_array($t)) {
                        $refLinkText .= str_replace("<a id='captcha-anchor' name='captcha-anchor' class='anchor'></a>", "", $t);
                        
                    }
                    else {
                        $refLinkText = '[No data!]';
                    } 
                }
            }
            catch (\Exception $e) {
                if ($this->file_log) {
                    fwrite($this->file_log, "\n--------\n\DB error: " . implode('/', $sourcePath) . "\n");
                }
            }
        } 
        if ($refLinkText == '' || $refLinkText == '[No data!]') {
            $refLinkText = "THE QUOTED RESOURCE IS NOT AVAILABLE. - $sourceStr";
            $classStr .= "no-compare-source "; 
            $sourceMismatch |= 4;
            return $sourceMismatch;
        }

        $refLinkText = str_replace(array_keys($this->pabSubstitute), array_values($this->pabSubstitute), $refLinkText);  
        $refLinkText = preg_replace(["/<\/?span[^>]*>/"], [""], $refLinkText);
        $refLinkText = preg_replace(["/‘([^’]+)’/", '/“([^”]+)”/', '/“/', '/”/'], ['&ldquo;$1&rdquo;', '&lsquo;$1&rsquo;', '&lsquo;', '&rsquo;'], $refLinkText);
        $refLinkText = str_replace(["&lsquo;", "&rsquo;", "&ldquo;", "&rdquo;", "&apos;"], ["‘", "’", '“', '”', "’"], $refLinkText);
        
        // get "<page />" position to restore after matching done.
        $arrPage = [];
        preg_match_all('/<page[^>]*>[\[\d\]a-z]+<\/page> ?/', $refLinkText, $arrPage, PREG_OFFSET_CAPTURE);
        $refLinkText = preg_replace(["/<page[^>]*>[\[\d\]a-z]+<\/page> ?/"],  [""], $refLinkText);  // remove page number
        
        // get "<ALL>" tag position to restore after matching done.
        $arrTag = [];
        preg_match_all('/<\/?[^>]+>/', $refLinkText, $arrTag, PREG_OFFSET_CAPTURE);
        $refLinkText = preg_replace(["/<\/?[^>]+>/"], [""], $refLinkText);  // remove page number

        $classStr .= " pab-parag";           
        $tmp = preg_replace(["/<citation>(\d+)<\/citation>/", "/<\/?[^>]*>/", "/  /"], ["", "", " "], $text_t); 
        $tmp = preg_replace("/^“ ?/m", "", $tmp);       // "...
        $tmp = preg_replace("/(\. ){2,}/", "\n", $tmp);  // . . . .
        $tmp = preg_replace("/\[[^\]]+\]/", "\n", $tmp); // remove Author's comment
        $textCompare = explode("\n", $tmp);
        $count = 0;
        $processedText = "";
        //
        // comparing unit => paragraph
        //
        for ($index = 0; $index < count($textCompare); $index++) { 
            $aText = trim($textCompare[$index]);
            if (strlen($aText) < 10)
                continue;
            if (preg_match('/\W$/', $aText)) {  // remove the last character
                $aText = mb_substr($aText, 0, -1); 
            }
            $this->_matchText($processedText, $refLinkText, $aText, $count, false);
            if ($count == 0) {  // try with case altered text
                $sourceMismatch |= 1;
                $isCaseChanged = 0;  // 0 = no case change, 1=changed to lowercase, 2=changed to uppercase
                if (ctype_alpha($aText[0])) {
                    if (ctype_upper($aText[0])) {
                        $isCaseChanged = 1;
                        $aText[0] = strtolower($aText[0]); // lowercase for first character
                    }
                    else {
                        $isCaseChanged = 2;
                        $aText[0] = strtoupper($aText[0]); // uppercase for first character
                    }
                    $this->_matchText($processedText, $refLinkText, $aText, $count, true);
                }
                else {
                    $this->_matchText($processedText, $refLinkText, $aText, $count, false);
                }
                if ($count == 0) {
                    $sourceMismatch |= 4;
                    if ($isCaseChanged == 1) {
                        $aText[0] = strtoupper($aText[0]); // restore first character
                    }
                    else if ($isCaseChanged == 2) {
                        $aText[0] = strtolower($aText[0]); // restore first character
                    }
                    $tmp = preg_replace("/[^A-Za-z0-9,\-\;\!\? ]+/", "\n", $aText); // only MEANINGFUL character
                    $textCompare2 = explode("\n", $tmp);
                    for ($index2 = 0; $index2 < count($textCompare2); $index2++) {  // compare unit => Sentence block
                        $aText = trim($textCompare2[$index2]);
                        if (mb_strlen($aText) < 10)
                            continue;
                        if (preg_match('/\W$/', $aText)) {  // remove the last character
                            $aText = mb_substr($aText, 0, -1); 
                        }
                        $this->_matchText($processedText, $refLinkText, $aText, $count, false);
                        if ($count == 0 && ctype_alpha($aText[0])) { // alter first character case
                            $aText[0] = strtolower($aText[0]); 
                            $this->_matchText($processedText, $refLinkText, $aText, $count, true);
                            if ($count == 0) {
                                $aText[0] = strtoupper($aText[0]);
                                $this->_matchText($processedText, $refLinkText, $aText, $count, true);
                            }
                        }
                    }
                }
            }
        }
        $refLinkText = $processedText . $refLinkText;

        //
        // Restore all tags
        //
        $inZ = false; 
        $arrAll = [$arrTag[0], $arrPage[0]];
        for ($i = 0; $i < count($arrAll); $i++) {
            $arr = $arrAll[$i];
            $pos = 0;
            $extLength = 0;
            for ($idx = 0; $idx < count($arr); $idx++) {
                $insPos = $arr[$idx][1];
                while ($pos < $insPos + $extLength) {
                    if ($pos >= strlen($refLinkText)) {
                        throw new \Exception('Text index is out of the range.');
                    }
                    if ($refLinkText[$pos] == '<') {
                        if (($refLinkText[$pos+1] == 'z' || $refLinkText[$pos+1] == 'c') && $refLinkText[$pos+2] == '>') { // bypass '<z>'
                            $extLength += 3;
                            $pos += 3;
                            $inZ = true;
                            continue;
                        }
                        else if (strpos($refLinkText, '</z>', $pos) === $pos || strpos($refLinkText, '</c>', $pos) === $pos) { // bypass '</z>'
                            $extLength += 4;
                            $pos += 4;
                            $inZ = false;
                            continue;
                        }
                    }
                    ++$pos;
                }
                if ($inZ > 0) {  // closing tag
                    $prefix = substr($refLinkText, 0, $pos);
                    $suffix = substr($refLinkText, $pos);
                    $refLinkText = $prefix . '</z>' . $suffix;
                    $extLength += 4;
                    $pos += 4;
                }
                do {
                    $prefix = substr($refLinkText, 0, $pos);
                    $suffix = substr($refLinkText, $pos);
                    $refLinkText = $prefix . $arr[$idx][0] . $suffix;
                    $pos += strlen($arr[$idx][0]);
                    $idx++;
                    if ($idx == count($arr))
                        break;
                    $insPos = $arr[$idx][1];
                } while ($pos == $insPos + $extLength);
                $idx--;
                if ($inZ > 0) {  // opening tag
                    $prefix = substr($refLinkText, 0, $pos);
                    $suffix = substr($refLinkText, $pos);
                    $refLinkText = $prefix . '<z>' . $suffix;
                    $extLength += 3;
                    $pos += 3;
                }
            }
        }

        // Check if matched area is in the page range
        $pattern = '/(\[([^\]]+)\]<\/page>)|(<\/?z>)/';
        if (count($sourcePath) == 3 && preg_match_all($pattern, $refLinkText, $tagMatches, PREG_OFFSET_CAPTURE) ) {
            if (strpos($sourcePath[2], '-')!== false) 
                $arrPg = explode('-', $sourcePath[2]);  // '-' = U2D
            else
                $arrPg = explode('–', $sourcePath[2]);  // '–' = U2013
            $startPage = $arrPg[0];
            $endPage = (count($arrPg) > 1) ? $arrPg[1] : $arrPg[0];
            $startPagePassed = false; // to check start page boundry
            $endPagePassed = false; // to check end page boundry
            $endPageBoundryPos = -1;
            for ($idx = 0; $idx < count($tagMatches[0]); $idx++) {
                $match = $tagMatches[0][$idx];
                if ($match[0][0] == '[')  {  // page no - [256]
                    $page = substr($match[0], 1, strpos($match[0], ']')-1) ;
                    if (!$startPagePassed && $page == $startPage) {
                        $startPagePassed = true;
                    }
                    else if ($endPagePassed) {
                        $endPageBoundryPos = $match[1] + strlen($match[0]);
                    }
                    if ($startPagePassed && intval($page) >= intval($endPage)) {
                        $endPagePassed = true;
                    }
                }
                else {  // <z> or </z>
                    if (!$startPagePassed) {
                        $sourceMismatch |= 2;
                    }
                    else if ($endPagePassed && $endPageBoundryPos > -1 && $match[1] > $endPageBoundryPos) {
                        $sourceMismatch |= 2;
                    }
                }
            }
        }

        // Restore escaped character
        $refLinkText = str_replace(["<z>", "</z>", "&"], ["<span class='pab-matched'>", "</span>", "&amp;"], $refLinkText);
        
        return $sourceMismatch;
    }

    protected $errorMessage = '';
    function _error_handler($severity, $message, $filename, $lineno) {
        if (error_reporting() & $severity) 
            echo '' ; //throw new \ErrorException($message, 0, $severity, $filename, $lineno);
        else 
            echo "";
        $this->errorMessage .= $message . "\n";  // to check error at 'finally' block
    }
    
    /**
     * Save or reset server side data.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $content = $request->input('content');
        $docId = $request->input('docId');
        $paragId = $request->input('paragId');
        $updated = $request->input('updated');

        // DEBUG 
        if ($this->file_log) {
            $log = "Time: " . date("Y-m-d H:i:s") . "\n";
            $log = $log . "function: TranslatorController::save()\n"; 
            $log = $log . "docId: " . $docId . "\n"; 
            $log = $log . "content length:" . strlen($content) . "\n";
            $log = $log . "paragId: " . $paragId . "\n";
            $log = $log . "----------------------------------------------------------------\n";
            fwrite($this->file_log, $log);
        }

        if (empty($docId)) {  // 
            return Response::json(['message' => "Wrong access!"]);
        }
        else if ($content == "reset") {  // request to reset changes.
            //unlink(Storage::disk('local')->getAdapter()->getPathPrefix() . "publications/translator/data/{$docId}.xml");
            if (Storage::disk('local')->exists("publications/translator/data/{$docId}.xml")) // need to backup instead to delete.
                Storage::disk('local')->delete("publications/translator/data/{$docId}.xml");
            session()->regenerate();
            return Response::json(['message' => "RESET", 'code'=>1, "token"=>csrf_token()], 200);
        }
        else {  // store data
            if (Storage::disk('local')->exists("publications/translator/data/{$docId}.xml")) {
                $time = Storage::disk('local')->lastModified("publications/translator/data/{$docId}.xml");
                if (!$time || time()-$time > 60*60 ) {  // auto save for backup every 60 min.
                    if (Storage::disk('local')->exists("publications/translator/data/backup/backup/{$docId}.xml"))
                        Storage::disk('local')->delete("publications/translator/data/backup/backup/{$docId}.xml");
                    if (Storage::disk('local')->exists("publications/translator/data/backup/{$docId}.xml"))
						Storage::disk('local')->move("publications/translator/data/backup/{$docId}.xml", 
													 "publications/translator/data/backup/backup/{$docId}.xml");
                    Storage::disk('local')->copy("publications/translator/data/{$docId}.xml", 
                                                 "publications/translator/data/backup/{$docId}.xml");
                }
            }
            $xmlDom = new \DOMDocument("1.0", "utf-8");
            $xmlDom->preserveWhiteSpace = false;
            try {
                if (0) { //$paragId != 0) {  // save(replace) only the paragraph text
                    if (! $xmlDom->load(Storage::disk('local')->getAdapter()->getPathPrefix() . "publications/translator/data/{$docId}.xml") )
                        return Response::json(['message' => "ERROR", 'code' => -1, 'paragId'=>$paragId]);
                    $xpath = new \DOMXPath($xmlDom);
                    //$element = $xpath->query("//*[@id='$paragId']/span[@class='target-text']")->item(0); // if there are more then one class, this does not work
                    $nodeList = $xpath->query("//*[@serial='$paragId']");
                    if ($nodeList->length == 0)
                        throw new Exception('Wrong serial number.');
                    $element = $nodeList->item(0);
                    $element->textContent = $content;
                    $xmlDom->getElementsByTagName("book")->item(0)->getAttributeNode("updated")->value = $updated;
                    //$content = $xmlDom->saveXML($xmlDom->getElementsByTagName("translator")->item(0));
                    $content = $xmlDom->saveXML();
                }
                else {
                    if ( strpos($content, "<book ") === false || strlen($content) < 1000 )
                        return Response::json(['message' => "ERROR: invalid contents", 'code' => -1, 'paragId'=>$paragId]);
                    /*$content = '<?xml version="1.0" encoding="utf-8" ?>\n' . $content; */

                    $xmlDom->loadXML($content); // to validate the content
                    $bookNode = $xmlDom->getElementsByTagName("book")->item(0);
                    $bookNode->setAttribute ("editor", Auth::user()->email);
                }
            } catch (Exception $e) {
                return Response::json(['message' => "ERROR: " . $e->getMessage(), 'code' => -1, 'paragId'=>$paragId]);
            }
            
            try {
                Storage::disk('local')->put("publications/translator/data/{$docId}.xml", $xmlDom->saveXML());

                // refresh session
                session()->regenerate();
                return Response::json(['message' => "SAVED", 'code' => 1, 'paragId'=>$paragId, "token"=>csrf_token()], 200);
                /*
                $filePath = Storage::disk('local')->getAdapter()->getPathPrefix() . "publications/periodicals/sbl";
                if($xmlDom->schemaValidate($filePath . "/sbl.xsd")) {
                    Storage::disk('local')->put("publications/translator/data/{$docId}.xml", $xmlDom->saveXML());
                    return Response::json(['message' => "SAVED", 'code' => 1, 'paragId'=>$paragId]);
                } else {
                    return Response::json(['message' => "Invalid!", 'code' => -1]);
                }*/
            } catch (Exception $e) {
                return Response::json(['message' => "ERROR: " . $e->getMessage(), 'code' => -1, 'paragId'=>$paragId]);
            }

        }

        return Response::json(['message' => "Wrong access!", 'code' => -1]);
    }


    /**
     * Display language property editing interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function editProperty(Request $request)
    {
        $langPropPosted = $request->input('prop');

        if (!$langPropPosted || empty($langPropPosted["lang_code"]))
            return "Invalid access!";

        /* property file */
        //$filePath = Storage::disk('local')->getAdapter()->getPathPrefix() . "publications/translator/lang";
        $filePath = base_path() . "/../dl.sdarm.org/resources/lang";


        $fileName = "langProp_{$langPropPosted["lang_code"]}.json";

        if (!empty($langPropPosted["months"])) {    // editing submitted
            copy("{$filePath}/{$fileName}", "{$filePath}/backup/{$fileName}");
            $langPropString = json_encode($langPropPosted, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            file_put_contents( "{$filePath}/{$fileName}", $langPropString );
            return "Data saved!";
        }

        $langProp = json_decode( file_get_contents("{$filePath}/{$fileName}") );
        
        return view('publications.translator.edit_property', ["langProp"=>$langProp]); 
    }


    /**
     * Display language property editing interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function editBookName(Request $request)
    {
        $lang_code = $request->input('lang_code');
        $bible = $request->input('bible');
        $book = $request->input('book');

        if (empty($lang_code))
            return "Invalid access!";

        //$filePath = Storage::disk('local')->getAdapter()->getPathPrefix() . "publications/translator/resources";
        $filePath = base_path() . "/../dl.sdarm.org/resources/book_attr";
        $bibleFileName = "bible_abbr_{$lang_code}.txt";
        $bookFileName = "book_name_{$lang_code}.txt";

        if (!empty($bible)) {    // editing submitted
            copy("{$filePath}/{$bibleFileName}", "{$filePath}/backup/{$bibleFileName}");
            $resultString = "";
            foreach ($bible as $key => $value) {
                $resultString .= $key . "\t" . $value . "\n";
            }
            file_put_contents( "{$filePath}/{$bibleFileName}", $resultString );

            if( $lang_code === 'en') // do not overwrite English book table 
                return "Data saved!";
            
            copy("{$filePath}/{$bookFileName}", "{$filePath}/backup/{$bookFileName}");
            $resultString = ";KEY\tTranslation Replace<\tDL(Digital Library) Search Pattern\tDL Path Replace\tDL Display Replace(BOOK,PAGE)\n";
            foreach ($book as $key => $value) {
                $resultString .= $key . "\t" . $value[0] . "\t" . $value[1] . "\t" . $value[2] . "\t" . $value[3] . "\n";
            }
            file_put_contents( "{$filePath}/{$bookFileName}", $resultString );

            return "Data saved!";
        }

        $nameProp = array();

        /* bible */
        $source = file("{$filePath}/bible_abbr_en.txt");
        $target = file("{$filePath}/$bibleFileName");
        for ($i=0; $i < count($source); $i++) {
            $s = explode("\t", rtrim($source[$i]));
            $s[1] = explode(',', $s[1])[0]; // use the first one as the book name.
            if (empty($target))
                $nameProp["bible"][] = array($s[0], $s[1], $s[1]);
            else {
                $t = explode("\t", rtrim($target[$i]));
                $nameProp["bible"][] = array($s[0], $s[1], $t[1]);
            }
        }

        /* book name table */
        $source = file("{$filePath}/book_name_en.txt");
        $sourceArray = [];
        for ($i = 0; $i < count($source); $i++) {
            if (strlen($source[$i]) < 3 || $source[$i][0] == ';') 
                continue;
            $s = explode("\t", rtrim($source[$i]));
            $sourceArray[$s[0]] = [$s[1], $s[2], $s[3], $s[4]];
        }

        $target = file("{$filePath}/$bookFileName");
        $targetArray = [];
        for ($i = 0; $i < count($target); $i++) {
            if (strlen($target[$i]) < 3 || $target[$i][0] == ';') 
                continue;
            $t = explode("\t", rtrim($target[$i]));
            $targetArray[$t[0]] = [$t[1], $t[2], $t[3], $t[4]];
        }

        foreach ($sourceArray as $key => $value) {
            if (empty($targetArray[$key]))
                $nameProp["book"][$key] = [$value[1], $value[0], $value[1], $value[2], $value[3]];
            else
                $nameProp["book"][$key] = [$value[1], $targetArray[$key][0], $targetArray[$key][1], $targetArray[$key][2], $targetArray[$key][3]];
        }
        // !!! Do Not Sort the list order. ERROR-CASE: Fundamentals of Christian Education, p. 531.
        // ksort($nameProp["book"]);
        
        return view('publications.translator.edit_book_names', 
            ["lang_code"=>$lang_code, "nameProp"=>$nameProp]); 
    }
    
    /**
     * Send HTML5 manifest file as text.
     *
     * It is not used for auth problem.
     * @return 
     */
    public function getManifest($book, $year, $issue, $lang, $s_lang) {
        header('Content-Type: text/cache-manifest'); 
        //if (config('app.debug'))
        //    \Debugbar::disable();
        return view('publications.translator.parallel_trans_manifest', 
            ["book"=>$book, "year"=>$year, "issue"=>$issue, "lang"=>$lang, "s_lang"=>$s_lang]);
    }

    /**
     * Send HTML5 manifest file as text.
     *
     * It is not used for auth problem.
     * @return 
     */
    public function dumpData($book, $year, $issue, $lang, $s_lang) {
        return view('publications.translator.dump_data', 
            ["docId" => "translation_" . $book . $year . "_" . $issue . "_" . $lang]);
    }

    
}
