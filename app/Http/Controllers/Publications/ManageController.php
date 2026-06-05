<?php

namespace App\Http\Controllers\Publications;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
// use Storage;
use SdarmDL\BookUpdator;

class ManageController extends Controller
{

    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Update publication(periodical) XML DB
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePubDb()
    {
        $messageTitle = "Update SDARM publication DB";

        $updator = new BookUpdator();
        $messageBody = $updator->updateLibrary();
        $messageBody = str_replace("\n", "<br />", $messageBody);

        return view('message', ["messageTitle" => $messageTitle, "messageBody" => $messageBody]);
    }


    /**
     * Update publication(periodical) XML DB
     *
     * @return \Illuminate\Http\Response
     */
    public function rebuildPubDb()
    {
        $messageTitle = "Rebuild SDARM publication DB";
        $messageBody = BookUpdator::rebuildPubDb();
        $messageBody = str_replace("\n", "<br />", $messageBody);

        return view('message', ["messageTitle" => $messageTitle, "messageBody" => $messageBody]);
    }


    /**
     * Update publication(periodical) XML DB
     *
     * @return \Illuminate\Http\Response
     */
    public function rebuildSbDb()
    {
        $messageTitle = "Rebuild Study Bible DB(XML DB)";
        $messageBody = BookUpdator::rebuildSbDb();
        $messageBody = str_replace("\n", "<br />", $messageBody);
        return view('message', ["messageTitle" => $messageTitle, "messageBody" => $messageBody]);
    }
}
