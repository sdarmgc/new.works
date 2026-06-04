<?php

/* 
 * TransProp.php
 * 
 * Book reference property class
 *
 * shskim@sdarmgc - Feb 13, 2017
 * 
 */

namespace App\Http\Controllers\Publications\Translator;

use Storage;

class TransProperty
{
    public $langSource = "en";
    public $langTarget = "en";
    
    public $langPropSource = array();
    public $langProp = array();

    public $bibleProp = array();
    public $versionInfo = array();
    public $bookProp = array();
    
    /**
     * @return 
     */
    public function TransProperty()
    {
        ;
    }
    

    public function init($targetLang, $originalLang)
    {
        $this->langSource = $originalLang;
        $this->langTarget = $targetLang;
        
        //$filePath = Storage::disk('local')->getAdapter()->getPathPrefix() . "publications/translator";
        $filePath = base_path() . "/../dl.sdarm.org/resources";
        
        /* language properties */
        if (! file_exists("{$filePath}/lang/langProp_{$this->langSource}.json"))
            copy("{$filePath}/lang/langProp_en.json", "{$filePath}/lang/langProp_{$this->langSource}.json");
        $this->langPropSource = json_decode(file_get_contents("{$filePath}/lang/langProp_{$this->langSource}.json") );

        if (! file_exists("{$filePath}/lang/langProp_{$this->langTarget}.json"))
            copy("{$filePath}/lang/langProp_en.json", "{$filePath}/lang/langProp_{$this->langTarget}.json");
        $this->langProp = json_decode(file_get_contents("{$filePath}/lang/langProp_{$this->langTarget}.json") );

        /* bible book name table */
        if (! file_exists("{$filePath}/book_attr/bible_abbr_{$this->langSource}.txt"))
            copy("{$filePath}/book_attr/bible_abbr_en.txt", 
                                                "{$filePath}/book_attr/bible_abbr_{$this->langSource}.txt");        
        $source = file("{$filePath}/book_attr/bible_abbr_{$this->langSource}.txt");
            
        if (! file_exists("{$filePath}/book_attr/bible_abbr_{$this->langTarget}.txt"))
            copy("{$filePath}/book_attr/bible_abbr_en.txt", 
                                                "{$filePath}/book_attr/bible_abbr_{$this->langTarget}.txt");        
            
        $target = file("{$filePath}/book_attr/bible_abbr_{$this->langTarget}.txt");

        for ($i=0; $i < count($source); $i++) {
            if (strlen($source[$i]) < 3)
                continue;
            $s = explode("\t", explode(',', rtrim($source[$i]))[0]);
            $t = explode("\t", explode(',', rtrim($target[$i]))[0]);
            if (empty($t[1]))
                $this->bibleProp[] = array($s[0], $s[1], $s[1]);
            else {
                $this->bibleProp[] = array($s[0], $s[1], $t[1]);
            }
        }       
        $this->versionInfo = array_shift($this->bibleProp);
        // to prevent confusing "JOHN 3:16" with "1 JOHN 3:16"
        usort($this->bibleProp, function($a, $b) {
            return strcmp($a[0], $b[0]);
        });

        /* Other book name table */
        $source = file("{$filePath}/book_attr/book_name_en.txt");
        $sourceArray = [];
        for ($i = 0; $i < count($source); $i++) {
            $source[$i] = trim($source[$i]);
            if (empty($source[$i]) || $source[$i][0] == ';') 
                continue;
            $s = explode("\t", rtrim($source[$i]));
            $sourceArray[$s[0]] = [$s[1], $s[2], $s[3], $s[4]];
        }

        if (! file_exists("{$filePath}/book_attr/book_name_{$this->langTarget}.txt")) 
            copy("{$filePath}/book_attr/book_name_en.txt", "{$filePath}/book_attr/book_name_{$this->langTarget}.txt");        
        $target = file("{$filePath}/book_attr/book_name_{$this->langTarget}.txt");
        $targetArray = [];
        for ($i = 0; $i < count($target); $i++) {
            $target[$i] = trim($target[$i]);
            if (empty($target[$i]) || $target[$i][0] == ';') 
                continue;
            $t = explode("\t", rtrim($target[$i]));
            $targetArray[$t[0]] = [$t[1], $t[2], $t[3], $t[4]];
        }

        foreach ($sourceArray as $key => $value) {
            if (empty($targetArray[$key])) {
                $this->bookProp[0][] = $value[1];
                $this->bookProp[1][] = $value[0];
            }
            else {
                $this->bookProp[0][] = $value[1];
                $this->bookProp[1][] = $targetArray[$key][0];
            }
        }
    }
    
    
    /**
     * @return translated text
     */
    public function transBibleProp($text) {
        $text = str_replace($this->langPropSource->sbl_quote_marks, $this->langProp->sbl_quote_marks, $text);
        foreach ($this->bibleProp as $value) {
            $text = preg_replace("/{$value[1]}\s(\d)/", $value[2] . " $1", $text);
            //$text = preg_replace("/{$value[0]}( \d+[^;\.\)]*?)(;|\.|\))/", "<bible-source>{$value[1]}$1</bible-source>$2", $text);
        }
        return $text;
    }
    
    /**
     * @return translated text
     */
    public function transBookProp($text) {
        $text = str_replace($this->langPropSource->sbl_quote_marks, $this->langProp->sbl_quote_marks, $text);
        return preg_replace($this->bookProp[0], $this->bookProp[1], $text, -1);
    }
    
    /**
     * @param string $tagName XML tag name to identify the text
     * @param string $text Text to translate
     * @return translated text
     */
    public function transProp($tagName, $text, &$lesson, $year, $month, $day, $dayNo, $linkFinder, $bookServer) {
        
        if ($this->langSource == $this->langTarget)
            return $text;
        
        if ($lesson == 0 && $tagName == "title") {   // forword title
            $text = $this->langProp->sbl_foreword_title;
            $lesson = 1; // to prevent FSO title
        }
        else if ($tagName == "lesson_header") {
            $text = str_replace("$1", $lesson, $this->langProp->sbl_lesson_header);
        }
        else if ($tagName == "sabbath" || $tagName == "display_date") {
            $text = str_replace("$1", $year, $this->langProp->sbl_sabbath);
            $text = str_replace("$2", $this->langProp->months[$month-1], $text);
            $text = str_replace("$3", $day, $text);        
        } 
        else if ($tagName == "reading_lable") {
            $text = $this->langProp->sbl_reading_lable;
        } 
        else if ($tagName == "day") {
            $text = $this->langProp->days[$dayNo];
        } 
        else if ($tagName == "date") {
            $text = str_replace("$1", $this->langProp->months[$month-1], $this->langProp->sbl_date);
            $text = str_replace("$2", $day, $text);
        } 
        else if ($tagName == "key_text") {
            if ($this->langSource == 'en' && $linkFinder && $bookServer) {
                $tmpText = $text;
                if( $linkFinder->link_find($tmpText) > -1) {
                    if (\preg_match("/title='([^']+)'>/", $tmpText, $matche)) {  // <span class="source-link" title="KJV/Dan/2/21">Daniel 2:21</span>
                        $arrPath = explode('/', $matche[1]);
                        $arrPath[0] = $this->versionInfo[2];
                        $chapter = $bookServer->get_data($arrPath, 'content', 'X', '');
                        $patterns = ["/[\n\s]+/", "/<\/p>/", "/\*<span class='note-parag'>[^>]+>/", "/<[^>]+>/"];
                        $chapter = preg_replace($patterns, [" ", "\n", "", ""], $chapter);
                        $verseNo = $arrPath[count($arrPath)-1];
                        if (\preg_match("/$verseNo(.+)\n/", $chapter, $matche)) { 
                            $verse = trim($matche[1]);
                            $text = $this->transBibleProp($text);
                            $ms = $this->langProp->sbl_quote_marks[0];
                            $me = $this->langProp->sbl_quote_marks[1];
                            return preg_replace("/$ms.+$me/", "$ms$verse$me", $text);
                        }
                    }
                };
            }
            $text = $this->transBibleProp($text);
        } 
        else if ($tagName == "question" || $tagName == "sub_question") {
            $text = $this->transBibleProp($text);
        } 
        else if ($tagName == "reading" || $tagName == "key_note" 
                        || $tagName == "ref_parag" || $tagName == "paragraph") {
            $text = $this->transBookProp($text);
        }
        else if ($tagName == "subtitle" && $dayNo == 5) {
            $text = $this->langProp->sbl_subtitle_review;
        }
        
        return $text;
    }
}
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    