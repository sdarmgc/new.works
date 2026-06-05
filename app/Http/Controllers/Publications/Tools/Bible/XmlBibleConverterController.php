<?php

namespace App\Http\Controllers\Publications\Tools\Bible;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

class XmlBibleConverterController extends Controller
{

    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('publications.tools.bible.xml_bible_converter', []);
    }

    /**
     * Upload xml data from the client.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $content = $request->input('content');
        $overwrite =  $request->input('overwrite') == 'true' ? true : false;
        // $db_update =  $request->input('db-update') == 'true' ? true : false;

        $dom = new \DOMDocument();
		$dom->encoding = "UTF-8";
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		
		if(!$dom->loadXML($content)) {
			throw new \Exception( "\tDocument is not well formed.\n" );
		}

		$bibleVirsion = $dom->getElementsByTagName("book")->item(0)->getAttribute("xml:base");

        if(empty($bibleVirsion)) {
            return Response::json(['message' => "Bible version name is missing. (ex: 'xml:base=\"KJV\"')"]);
        }
        else {  // store data
            $retMessage = '';
            try {
                $retMessage = $this->xsdValidate($content);
                if (strpos($retMessage, "not valid!") !== false) {
                    return Response::json(['message' => $retMessage, 'code' => 1]);
                }
        
                $fileName = realpath("../..") . "/dl.sdarm.org/contents/sb/xml/{$bibleVirsion}.xml";
        
                if ($overwrite || !file_exists($fileName)) {
                    if (file_exists($fileName)) {
                        $retMessage .= "\tThe target file already exists and has been overwritten!\n";
                    }
                    $dom->save($fileName);
                    $retMessage .= "\tDocument is saved.\n\tFile: $fileName\n";
                }
                else {
                    $retMessage .= "\tDocument is not saved.\n"
                        . "\tThe target file already exists.\n"
                        . "\tFile: $fileName\n";
                }
            } catch (\Exception $e) {
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
        $content = $request->input('content');
        
        if (empty($content)) {
            return Response::json(['message' => 'Content is empty. Not thing to validate.', 'code' => -1]);
        }

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
            if(@($dom->schemaValidate("../resources/tools/bible/bible.xsd"))) {
                $retVal .=  "\tDocument is valid!\n";
            } else {
                $error_str = "\tERROR: \n\tDocument is not valid!\n";
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
