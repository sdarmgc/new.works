<?php

namespace App\Http\Controllers\Tools\Sbl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use App\Http\Controllers\Publications\util\SblRtfHandler;


class SblConverterController extends Controller
{
    public $langTarget = "en";
    public $langProp = array();
    
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        
        /* language properties */
        $filePath = base_path() . "/../dl.sdarm.org/resources";
        $this->langProp = json_decode(file_get_contents("{$filePath}/lang/langProp_{$this->langTarget}.json") );
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // replace patterns for download
        include_once(dirname(__FILE__) . "/../../Publications/util/DownloadTextSubstitude.php");
        $jsVar = "var dnReplaceProp=" . json_encode($dnSubstitute) . ";\n";// defined in 'DownloadTextSubstitude.php'
        
        $jsVar .= "var langProp=" . json_encode($this->langProp) . ";\n";
        
        $rtf = new SblRtfHandler;
        $jsVar .= "var rtfProp=" . str_replace("    ", "", json_encode(get_object_vars($rtf))) . ";\n";
        
        return view('frontend.tools.sbl.sbl_converter', ["jsVar"=>$jsVar]);
    }

    /**
     * Display a listing of the resource. aaa
     *
     * @return \Illuminate\Http\Response
     */
    public function insertDate()
    {
        return view('frontend.tools.sbl.sbl_insert_date', []);
    }

    /**
     * Upload xml data from the client.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $content = $request->input('xml');
        $overwrite =  $request->input('overwrite') == 'true' ? true : false;
        // $validate =  $request->input('validate') == 'true' ? true : false;
        // $db_update =  $request->input('db-update') == 'true' ? true : false;

        $dom = new \DOMDocument();
		$dom->encoding = "UTF-8";
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		
		if(!$dom->loadXML($content)) {
			throw new Exception( "Document is not well formed.\n" );
		}

		$lang = $dom->getElementsByTagName("book")->item(0)->getAttribute("xml:lang");
		$year = $dom->getElementsByTagName("book")->item(0)->getAttribute("year");
		$issue = $dom->getElementsByTagName("book")->item(0)->getAttribute("quarter");

        if(empty($lang) || empty($issue) || empty($year)) {
            return Response::json(['message' => "Wrong access!"]);
        }
        else {  // store data
            $retMessage = '';
            try {
                $retMessage = $this->xsdValidate($content);
                if (strpos($retMessage, "not valid!") !== false) {
                    return Response::json(['message' => "ERROR: " . $retMessage, 'code' => 1]);
                }
        
                $fileName = realpath("../..") . "/dl.sdarm.org/contents/publications/periodicals/sbl/xml/sbl{$year}_{$issue}_{$lang}.xml";
        
                if (!file_exists("$fileName") || $overwrite) {
                    if (file_exists("$fileName")) {
                        $retMessage .= "The target file already exists and has been overwritten!\n";
                    }
                    $dom->save("$fileName");
                    $retMessage .= "Document is saved.\n\tFile: $fileName\n";
                    // simbolic link for 'il' and 'mq' language code
                    if ($lang == 'il' || $lang == 'mq') {
                        if ($lang == 'il') {
                            $symbolicName = realpath("../..") . "/dl.sdarm.org/contents/publications/periodicals/sbl/xml/sbl{$year}_{$issue}_pi.xml";
                        }
                        else {// if ($lang == 'mq') {
                            $symbolicName = realpath("../..") . "/dl.sdarm.org/contents/publications/periodicals/sbl/xml/sbl{$year}_{$issue}_mi.xml";
                        }
                        if (symlink($fileName, $symbolicName)) {
                            $retMessage .= "Simbolic link created.\n\tFile: $symbolicName\n";
                        }
                        else {
                            $retMessage .= "Simbolic link failed.\n\tFile: $symbolicName\n";
                        };
                    }
                }
                else {
                    $retMessage .= "Document is not saved.\n"
                        . "\tThe target file already exists.\n"
                        . "\tFile: $fileName\n";
                }
            } catch (Exception $e) {
                return Response::json(['message' => "ERROR: " . $e->getMessage(), 'code' => -1]);
            }
        }

        return Response::json(['message' => $retMessage, 'code' => 1]);
    }


    /**
     * Validate xml data from the client and return the result message.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateXml(Request $request)
    {
        $content = $request->input('xml');

        $retMessage = $this->xsdValidate($content);

        return Response::json(['message' => $retMessage, 'code' => 1]);
    }


    /*
    *  helper function 
    */
    function exception_error_handler($errno, $errstr, $errfile, $errline ) {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }


    /*
     *  helper function 
     */
    function xsdValidate($content) {
        $retVal = '';

        $eh = set_error_handler(array($this, "exception_error_handler"));

        try {
            if(empty($content)) {
                throw new \Exception("Empty xml");
            }
            
            libxml_use_internal_errors(true);
            
            $dom = new \DOMDocument();
            $dom->encoding = "UTF-8";
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            
            if(!$dom->loadXML($content)) {
                throw new \Exception( "Document is not well formed.\n" );
            }
            
            // xsd validation
            if(@($dom->schemaValidate("../resources/tools/sbl/sbl_converter/sbl.xsd"))) {
                $retVal .=  "Document is valid!\n";
            } else {
                $error_str = "Document is not valid!\n";
                $errors = libxml_get_errors();
                foreach($errors as $error) {
                    //$error_str .= sprintf("\t%s\t\t--- File: %s, line: %s, column: %s, level: %s, code: %s\n",
                    $error_str .= sprintf("\t%s\t\t--- line: %s, column: %s, level: %s, code: %s\n",
                                    $error->message,
                                    //$error->file,
                                    $error->line,
                                    $error->column,
                                    $error->level,
                                    $error->code
                                    );
                }
                $retVal .= $error_str;
            }
            
        }
        catch(\Exception $e) {
            $retVal .=  'Error: ' .  $e->getMessage() . "\n";
        }

        if ($eh) {
            set_error_handler($eh);
        }
        return $retVal;
	}
}
