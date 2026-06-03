<?php

namespace App\Http\Controllers\Frontend\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use SdarmDL\BookLinkFinder\BookLinkFinder;


class MyDOMDocument {
    private $_delegate;
    private $_validationErrors;
   
    public function __construct (\DOMDocument $pDocument) {
        $this->_delegate = $pDocument;
        $this->_validationErrors = array();
    }
    public function __call ($pMethodName, $pArgs) {
        if ($pMethodName == "validate") {
            $eh = set_error_handler(array($this, "onDtdError"));
            $rv = $this->_delegate->validate();
            if ($eh) {
                set_error_handler($eh);
            }
            return $rv;
        } else if ($pMethodName == "transformToXML") {
            $eh = set_error_handler(array($this, "onError"));
            $rv = $this->_delegate->validate();
            if ($eh) {
                set_error_handler($eh);
            }
            return $rv;
        }
        else {
            $eh = set_error_handler(array($this, "onError"));
            $rv = call_user_func_array(array($this->_delegate, $pMethodName), $pArgs);
            if ($eh) {
                set_error_handler($eh);
            }
            return $rv;
        }
    }
    public function __get ($pMemberName) {
        if ($pMemberName == "errors") {
            return $this->_validationErrors;
        }
        else if ($pMemberName == "xmlError") {
            $this->xmlError();
            return $this->_validationErrors;
        }
        else {
            return $this->_delegate->$pMemberName;
        }
    }
    public function __set ($pMemberName, $pValue) {
        $this->_delegate->$pMemberName = $pValue;
    }
    public function onDtdError ($pNo, $pString, $pFile = null, $pLine = null, $pContext = null) {
        $this->_validationErrors[] = $pString; //preg_replace("/^[^:]+: ?/", "", $pString);
    }
    public function onError ($pNo, $pString, $pFile = null, $pLine = null, $pContext = null) {
        $this->_validationErrors[] = $pString; 
    }
    public function xmlError () {
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
            $this->_validationErrors[] = $error_str;
        }
    }
}

class ArticleConverterController extends Controller
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
        return view('frontend.tools.article_converter', []);
    }

    /**
     * Upload xml data from the client.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $content = $request->input('content');
        $book =  $request->input('book');
        $year =  $request->input('year');
        $issue =  $request->input('issue');
        $lang =  $request->input('lang');
        $overwrite =  $request->input('overwrite') == 'true' ? true : false;
        $validate =  $request->input('validate') == 'true' ? true : false;
        $db_update =  $request->input('db-update') == 'true' ? true : false;

        if( empty($book) || empty($year) || empty($issue) || empty($lang)) {
            return Response::json(['message' => "Wrong access!"]);
        }
        else {  // store data
            if ( strpos($content, "<?xml ") === false || strlen($content) < 1000 )
                return Response::json(['message' => "ERROR: invalid contents", 'code' => -1]);
            try {
                $dom = new \DOMDocument("1.0", "utf-8");
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $xmlDom = new MyDOMDocument($dom); //, LIBXML_NOEMPTYTAG | LIBXML_NOERROR | LIBXML_NOWARNING);
                if (!$xmlDom->loadXML($content)) {
                    return Response::json(['message' => $xmlDom->errors, 'code' => -1]);
                }
                // if (!$xmlDom->validate()) {  //DTD validation
                //     return Response::json(['message' => $xmlDom->errors, 'code' => -1]);
                // }
                $xsdSchema = "../../dl.sdarm.org/resources/xml/docbook5.xsd";
                if ($validate && !$xmlDom->schemaValidate($xsdSchema)) {
                    return Response::json(['message' => $xmlDom->xmlError, 'code' => -1]);
                }
                else {
                    $fileName = "../../dl.sdarm.org/contents/publications/periodicals/$book/xml/{$book}{$year}_{$issue}_{$lang}.xml";
                    $content = $xmlDom->saveXML();
                    // temporary workaround for name space issue
                    $content = str_replace([' xmlns="http://docbook.org/ns/docbook"', ' xmlns:xlink="http://www.w3.org/1999/xlink"'], ["", ""], $content);
                    if (file_exists($fileName) && !$overwrite) {
                        return Response::json(['message' => "File exist: \n'$fileName'", 'code' => 0]);
                    }
                    else if (file_put_contents($fileName, $content)) {
                        $message = "Uploaded successfuly as : '$fileName'\n";
                        if ($db_update) {
                            include_once("../../dl.sdarm.org/php/publication.php");
                            $message .= update_library("../../dl.sdarm.org/contents/publications/periodicals/$book");
                        }
                        return Response::json(['message' => $message, 'code' => 1]);
                    }
                    else {
                        return Response::json(['message' => "ERROR: File write error", 'code' => -1]);
                    }
                }
            } catch (Exception $e) {
                return Response::json(['message' => "ERROR: " . $e->getMessage(), 'code' => -1]);
            }
        }

        return Response::json(['message' => "Wrong access!", 'code' => -1]);
    }


    /**
     * Validate xml data from the client and return the result message.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateXml(Request $request)
    {
        $content = $request->input('content');
        $book =  $request->input('book');
        $year =  $request->input('year');
        $issue =  $request->input('issue');
        $lang =  $request->input('lang');

        if( empty($book) || empty($year) || empty($issue) || empty($lang)) {
            return Response::json(['message' => "Wrong access!"]);
        }
        else {  // 
            try {
                $dom = new \DOMDocument("1.0", "utf-8");
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $xmlDom = new MyDOMDocument($dom); //, LIBXML_NOEMPTYTAG | LIBXML_NOERROR | LIBXML_NOWARNING);
                if (!$xmlDom->loadXML($content)) {
                    return Response::json(['message' => $xmlDom->errors, 'code' => -1]);
                }
                // if (!$xmlDom->validate()) {  //DTD validation
                //     return Response::json(['message' => $xmlDom->errors, 'code' => -1]);
                // }
                $xsdSchema = "../../dl.sdarm.org/resources/xml/docbook5.xsd";
                if (!$xmlDom->schemaValidate($xsdSchema)) {
                    return Response::json(['message' => $xmlDom->xmlError, 'code' => -1]);
                }
            } catch (Exception $e) {
                return Response::json(['message' => "ERROR: " . $e->getMessage(), 'code' => -1]);
            }
        }

        return Response::json(['message' => "The xml is valid!", 'code' => 1]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function preview($book, $lang, $year, $issue, $article)
    {
        if( empty($book) || empty($year) || empty($issue) || empty($lang)) {
            return Response::json(['message' => "Wrong access!"]);
        }

        $fileName = "../../dl.sdarm.org/contents/publications/periodicals/$book/xml/{$book}{$year}_{$issue}_{$lang}.xml";
        if (!file_exists($fileName)) {
            return Response::json(['message' => "File not exist: \n'$fileName'", 'code' => -1]);
        }
        $xml = file_get_contents($fileName);
        $matches = null;
        $subtitle = '';
        preg_match('/<subtitle>([^<]+)/', $xml, $matches);
        if ($matches) {
            $subtitle = $matches[1];
        }
        include_once("../../dl.sdarm.org/php/publication.php");
        $paths = ["", $book, $lang, $year, $issue, $article];
        $contents = theme_periodical($paths, $xml, "/tools/article-converter/preview/", "https://sdarm.org/files/publications/periodicals/");
        $linkFinder = new BookLinkFinder($lang);
        if ($linkFinder->isAvailable())
            $linkFinder->link_find($contents);

        $paths[5] = '0';
        $toc = theme_periodical($paths, $xml, "/tools/article-converter/preview/", "");
        return view('frontend.tools.article_preview', 
                    ["book" => $book, "lang" => $lang, "year" => $year, "issue" => $issue, "article" => $article
                        , "subtitle" => $subtitle, "toc" => $toc, "contents" => $contents]);
    }

}