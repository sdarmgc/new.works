<?php

namespace App\Http\Controllers\Publications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Storage;
use Exception;
use App\Models\Publications\Manuscript;
use App\Models\Publications\ManuscriptItem;

class ManuscriptController extends Controller
{

    public $pNameList = [ '' => 'Select a Publication',
                        'SBL' => 'Sabbath Bible Lessons', // SBL
                        'LBS' => 'Lecciones Biblicas Sabaticas', 
                        'RH' => 'Reformation Herald, Week of Prayer', 
                        'OTH' => 'Other'];
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole("administrator") || auth()->user()->hasRole("pab"))
            $manuscript = Manuscript::whereNotNull('name')->orderBy('sort', 'desc')->orderBy('id', 'desc')->get();
        else
            $manuscript = Manuscript::where('active', 1)->orderBy('sort', 'desc')->orderBy('id', 'desc')->get();

        // foreach ($manuscript as $pub) {
        //     echo $pub->name;
        //     foreach ($pub->files as $items) {
        //         echo $items->description;
        //     }
        // }
            
        // board notice contents
        $notice = Storage::get("publications/manuscripts/board_notice.html");

        // board message contents
        $message = Storage::get("publications/manuscripts/board_message.html");

        return view('publications.manuscripts.manuscripts', [
            "notice" => $notice, 
            "message" => $message, 
            "manuscript" => (object)$manuscript
            ]);
    }

    /**
     * Show the form for creating a new manuscript.
     *
     * @return \Illuminate\Http\Response
     */
    public function createManuscript()
    {
        $last = Manuscript::latest()->first();
        
        $manuscript = array(
            'id' => "0",
            'category' => "manuscript",
            'name' => "",
            'view_class' => "none",
            'active' => "",
            'sort' => ($last ? $last->id + 1 : 0)
        );

        $title = "Add New Manuscript";
        $form_url = "publications.manuscripts.storeManuscript";
        $method = 'post';

        $pNameList = $this->pNameList;
        $pName = ' ';

        $yearList = [];
        $thisYear = date('Y');
        for ($index = 0; $index < 10; $index++) {
            $year = $thisYear + $index;
            $yearList[$year] = $year;
        }
        $year = $thisYear;
        $issue = 1;

        return view(
            'publications.manuscripts.edit_manuscript', [
                "manuscript" => (object)$manuscript, 
                "title" => $title, 
                "form_url" => $form_url, 
                'method' => $method,
                'pNameList' => $pNameList,
                'yearList' => $yearList,
                'pName' => $pName,
                'year' => $year,
                'issue' => $issue
            ]
        );
    }
    
    public function createItem($manuscript_id)
    {
        $manuscriptName = Manuscript::where('id', $manuscript_id)->first()->name;
    
        $last = ManuscriptItem::where('manuscript_id', $manuscript_id)->latest()->first();
        $item = array(
            'id' => "0",
            'manuscript_id' => $manuscript_id,
            'type' => 1,
            'name' => "",
            'description' => "",
            'url' => "",
            'size' => 0,
            'sort' => ($last ? $last->id + 1 : 0)
        );

        $title = "Add Manuscript Item";
        $form_url = "publications.manuscripts.storeItem";
        $method = 'post';

        return view(
            'publications.manuscripts.edit_item', [
                'manuscriptName' => $manuscriptName,
                "item" => (object)$item, 
                "title" => $title, 
                "form_url" => $form_url, 
                'method' => $method]
        );
    }

    /**
     * Store a newly created or edited resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeManuscript(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'max:255'], 
        ]);

        $isNew = true;
        if ($request->id == 0)
            $manuscript = new Manuscript;
        else {
            $manuscript = Manuscript::find($request->id);
            if (!$manuscript)
                return redirect()->route('publications.manuscripts.index')
                    ->banner(trans('Error: Invalid index. Data was not stored properly.'));
            $isNew = false;
        }

        $manuscript->category = $request->category;
        $manuscript->name = $validated['name'];
        $manuscript->view_class = $request->view_class;
        $manuscript->active = empty($request->active) ? 0 : 1;
        $manuscript->sort = $request->sort;
        $manuscript->save();

        if ($isNew) {
            return redirect()->route('publications.manuscripts.index')
                ->banner(trans('New ' . '"' . $manuscript->category . '" ' . $validated['name'] . '" added.'));
        }
        else {
            return redirect()->route('publications.manuscripts.index')
                ->banner(trans('"' . $manuscript->category . '" ' . $validated['name'] . '" updated.'));
        }
    }

    public function storeItem(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            //'file_download' => 'required',
        ]);

        $isNew = true;
        if ($request->id == 0) {
            $item = new ManuscriptItem;
            $item->manuscript_id = $request->manuscript_id;
        }
        else {
            $item = ManuscriptItem::find($request->id);
            if (!$item)
                return redirect()->route('publications.manuscripts.index')
                    ->banner(trans('Error: Invalid index. Data was not stored properly.'));
            $isNew = false;
        }

        $item->type = $request->type;
        $item->name = $validated['name'];
        $item->description = $request->description;
        
        // file upload or URL
        if ($request->type == 2) {
            $item->url = $request->url;
            $item->size = 0;
        }
        else if (!empty($request->has('filepath'))) {
            if (!Storage::disk('public')->exists($request->filepath))
                throw new Exception('File upload error!');
            $storagePath = 'publications/manuscripts/';
            $item->url = $request->original;
            $item->size = round(Storage::disk('public')->size($request->filepath) / 1024, 0) ;
            Storage::disk('public')->move($request->filepath, $storagePath . $request->original);
        }
        $item->sort = $request->sort;
        $item->save();

        if ($isNew) {
            return redirect()->route('publications.manuscripts.index')
                ->banner(trans('New ' . '"' . $request->name . '" added.'));
        }
        else {
            return redirect()->route('publications.manuscripts.index')
                ->banner(trans('"' . $request->name . '" updated.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editManuscript($id)
    {
        $manuscript = Manuscript::where('id', $id)->get()->first();

        $title = "Edit Manuscript";
        $form_url = "publications.manuscripts.storeManuscript";
        $method = 'put';

        $pNameList = $this->pNameList;

        $pName = '';
        foreach ($pNameList as $key => $value) {
            if (strpos($manuscript->name, $value) !== false) {
                $pName = $key;
                break;
            }
        }

        $yearList = [];
        $thisYear = date('Y');
        for ($index = 0; $index < 10; $index++) {
            $year = $thisYear + $index;
            $yearList[$year] = $year;
        }
        $year = preg_match("/20\d\d/", $manuscript['name'], $match) ? $match[0] : $thisYear;
        $issue = preg_match("/[\/\-](\d)/", $manuscript['name'], $match) ? $match[1] : 1;

        return view(
            'publications.manuscripts.edit_manuscript', [
                "manuscript" => (object)$manuscript, 
                "title" => $title, 
                "form_url" => $form_url, 
                'method' => $method,
                'pNameList' => $pNameList,
                'yearList' => $yearList,
                'pName' => $pName,
                'year' => $year,
                'issue' => $issue
            ]
        );
    }
    
    public function editItem($id)
    {
        $item = ManuscriptItem::find($id);
        $manuscriptName = Manuscript::where('id', $item->manuscript_id)->first()->name;

        $title = "Edit Manuscript Item";
        $form_url = "publications.manuscripts.storeItem";
        $method = 'put';

        return view(
            'publications.manuscripts.edit_item', [
                'manuscriptName' => $manuscriptName,
                "item" => (object)$item, 
                "title" => $title, 
                "form_url" => $form_url, 
                'method' => $method
            ]
        );
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyManuscript($id)
    {
        $manuscript = Manuscript::find($id);
        $category = $manuscript->category;
        $name = $manuscript->name;

        $manuscript = Manuscript::where('id', $id)->get()->first();

        foreach ($manuscript as $item) {
            if (!is_bool($item)) {
                $this->destroyItem($item->id);
            }
        }

        $manuscript->delete();

        // $manuscript->destroy($id);
        return redirect()->route('publications.manuscripts.index')
            ->banner(trans($category . ' "' . $name . '" is deleted.'));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyItem($id)
    {
        $item = ManuscriptItem::find($id);
        $name = $item->name;

        if ($item->type != 2) {
            Storage::disk('public')->delete('publications/manuscripts/' . $item->url);
        }

        $item->delete();

        return redirect()->route('publications.manuscripts.index')
            ->banner(trans("$name" . " is deleted."));
    }


    /**
     * update board message.
     *
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    public function updateMessage(Request $request)
    {
        $message = $request->input('editor');

        if ($message != "") {
            Storage::disk('local')->put("publications/manuscripts/board_message.html", $message);
            return response()->json($message, 200);
        }
        return response()->json("error", 520);
    }


    /**
     * update board notice.
     *
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    public function updateNotice(Request $request)
    {
        $message = $request->input('editor');
        if (empty($message)) {
            $message = '';
        }

        Storage::disk('local')->put("publications/manuscripts/board_notice.html", $message);
        return response()->json($message, 200);
    }
}
