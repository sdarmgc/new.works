<?php

namespace App\Services\Publication;

use SdarmDL\BookServer\BookServer;

trait Utility
{
    public $bVersion = '';
	/**
	 * Generate Bible verse text node for given node
	 * @param \DOMNode $bookServer
	 * @param BookServer $bookServer
	 * @return \DOMNode
	 */
	protected function GenerateBibleVerse(&$node, &$bookServer) 
	{
        static $book = '';
        static $chapter = '';
        $matches = [];
        foreach ($node->childNodes as $elem) {
            if ($elem->nodeType == XML_ELEMENT_NODE && $elem->tagName == "span") {
                $t = $elem->getAttribute("title");
                if ($t == "") {
                    continue;
                }
                $matches[0][] = $elem->getAttribute("title");
                $matches[1][] = $elem->textContent;
            }
        }
        if (!empty($matches)) {
            $continuousVerseCount = 0;
            // verse label as humen readable form
            for ($index = 0; $index < count($matches[0]); $index ++) {
                $path = explode('/', $matches[0][$index]);
                if (strlen($this->bVersion)) {
                    $path[0] = $this->bVersion;
                }
                $contents = $bookServer->get_data($path, 'content', 'X', '3');
                if (is_array($contents)) {
                    // echo "<div style='display:none'>PATH: " . implode('/', $path) . "</div>";
                    $contents = "";
                }
                $contents = str_replace(["<a id='captcha-anchor' name='captcha-anchor' class='anchor'></a>"], 
                                        [''], $contents); 
                $contents = preg_replace(["/<span class='note-parag'>[^<]*<\/span>/m", "/<span class='note'>[^<]*<\/span>/m"], 
                                        ["", ""], $contents);
                $contents = preg_replace(["/>(\d+) ([^ ])/m", "/<\/?p[^>]*>/m"], 
                                        ["><verse_no>$1</verse_no> $2", ""], $contents);
                $label = $matches[1][$index] ;   // can be 'Psalm 23:1', '23:1', '1', 'Verse 3'
                if (stripos($label, 'Verse') !== false) // when start without chapter - 'Verse 3'
                    $label = substr($label, strpos($label, ' ')+1);
                if (! \preg_match('/^(\d?[^\d:–]+)/', $label)) { // lable without book name, '23:1' or '1'
                    if ($index == 0) { // when start with unknow book name, 'Chapter' or not clear
                        $book = "???-$book "; 
                    }
                    if (strpos($label, ':') === false) { // only verse - '12' or '12-15'
                        if ($index == 0) {  // when start without chapter - 'Verse 3'
                            $chapter = "???-$chapter";
                        }
                        $currentVerse = strstr($label, '–', true) ?: (strstr($label, '-', true) ?: $label);
                        // check if continuous verse
                        if (stripos($matches[1][$index], 'Verse')===false && $index > 0 
                                && \preg_match('/(\d+)$/', $matches[1][$index-1], $m) && (intval($currentVerse)-intval($m[1])) == 1) { 
                            $continuousVerseCount ++;
                            $matches[1][$index-$continuousVerseCount] .= ", $label";
                        }
                        else {
                            $continuousVerseCount = 0;
                        }
                        if ($continuousVerseCount === 0)
                            $matches[1][$index] = $book . $chapter . $label;
                    }
                    else {  // without book name '23:1'
                        $matches[1][$index] = $book . $label;
                        $continuousVerseCount = 0;
                    }
                }
                else {
                    $continuousVerseCount = 0;
                }
                if (\preg_match('/^\d?[^\d:]+/', $matches[1][$index] , $m)) {  // keep book name for following context
                    $book = $m[0];
                }
                if (\preg_match('/\d+:/', $matches[1][$index] , $m)) { // keep chapter name for following context
                    $chapter = $m[0];
                }
                else if ($index == 0) { //['Oba', 'Philem', '2 John', '3 John', 'Jude'];
                    $chapter = '';
                }
                if ($continuousVerseCount > 0) {
                    $matches[2][$index-$continuousVerseCount] .= $contents;
                    $matches[2][$index] = "";
                }                                
                else
                    $matches[2][$index] = $contents;
            }
            
            $verses = "";
            for ($index = 0; $index < count($matches[2]); $index ++) {
                if (empty($matches[2][$index]))
                    continue;
                $verses .= "<bible-text><bible-ref><span class='source-link source-link-bible' title='{$matches[0][$index]}'>{$matches[1][$index]}</span></bible-ref><p>{$matches[2][$index]}</p></bible-text>";
            }
            $verses = str_replace("&nbsp;", "&#160;", $verses);
            $verses = str_replace("H3", "verse", $verses);
            $verses = str_replace("<a id='captcha-anchor' name='captcha-anchor' class='anchor'></a>", '', $verses);
            $DOM_inner_XML = new \DOMDocument();
            $DOM_inner_XML->preserveWhiteSpace = false;
            //$internal_errors = libxml_use_internal_errors( true );
            $DOM_inner_XML->loadXML("<bible-verses>" . $verses . "</bible-verses>");
            //libxml_use_internal_errors( $internal_errors );
            $content_node = $DOM_inner_XML->getElementsByTagName('bible-verses')->item(0);
            $content_node = $node->ownerDocument->importNode( $content_node, true );
            if ($node->nextSibling)
                $node->parentNode->insertBefore($content_node, $node->nextSibling);
            else
                $node->parentNode->appendChild($content_node);
        }
	    return $node;
	}
}