<?php

namespace App\Http\Controllers\Frontend\Publications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Storage;
use App\Models\Publications\PublicationManuscript;

class OldManuscriptController extends Controller
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
        if (auth()->user()->hasRole("administrator") || auth()->user()->hasRole("pab"))
            $results = PublicationManuscript::whereNotNull('name')->orderBy('category', 'asc')->get();
        else
            $results = PublicationManuscript::where('active', 1)->orderBy('category', 'asc')->get();
        $files = array();
        foreach ($results as $row) {
            $files[] = $row;
        }

        // board notice contents
        $notice = Storage::disk('local')->get("publications/manuscripts/board_notice.html");

        // board message contents
        $message = Storage::disk('local')->get("publications/manuscripts/board_message.html");

        return view('frontend.publications.manuscripts.manuscripts_old', ["notice" => $notice, "message" => $message, "files" => (object)$files]);
    }

    /**
     * Show the form for creating a new resource with given category.
     *
     * @return \Illuminate\Http\Response
     */
    public function createNew($category)
    {
        if (empty($category))
            $category = "Manuscript";

        $manuscript = array(
            'id' => "0",
            'category' => $category,
            'name' => "",
            'file_translate' => "",
            'file_view_1' => "",
            'file_view_2' => "",
            'file_thumbnail' => "",
            'file_download' => "",
            'image_download' => "",
            'view_class' => "none",
            'active' => "",
        );

        $title = "Add New Manuscript";
        $form_url = "frontend.publications.manuscripts.store.old";
        $method = 'post';

        return view(
            'frontend.publications.manuscripts.edit_old',
            ["manuscript" => (object)$manuscript, "title" => $title, "form_url" => $form_url, 'method' => $method]
        );
    }

    /**
     * Store a newly created or edited resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->middleware('permission:view backend');

        $this->validate($request, [
            'name' => 'required|max:255',
            //'file_download' => 'required',
        ]);

        $isNew = true;
        if ($request->id == 0)
            $manuscript = new Manuscript;
        else {
            $manuscript = PublicationManuscript::find($request->id);
            if (!$manuscript)
                return redirect()->route('frontend.publications.manuscripts.index.old')
                    ->withFlashSuccess(trans('Error: Invalid index. Data was not stored properly.'));
            $isNew = false;
        }

        $manuscript->category = $request->category;
        $manuscript->name = $request->name;

        foreach (array('file_translate', 'file_view_1', 'file_view_2', 'file_thumbnail', 'file_download', 'image_download') as $field) {
            if ($request->id > 0 && !empty($request->input($field.'_delete'))) {
	            Storage::delete('/public/publications/manuscripts/old/'.$manuscript->$field);
	            $manuscript->$field = "";
	        }
            if ($request->hasFile($field)) {
                $manuscript->$field = $request->file($field)->getClientOriginalName();
                $request->file($field)->move(storage_path() . '/app/public/publications/manuscripts/old/', $manuscript->$field);
            }
        }

        $manuscript->view_class = $request->view_class;
        $manuscript->active = empty($request->active) ? 0 : 1;
        $manuscript->save();

        if ($isNew) {
            return redirect()->route('frontend.publications.manuscripts.index.old')
                ->withFlashSuccess(trans('New ' . '"' . $manuscript->category . '" ' . $request->name . '" added.'));
        }
        else {
            return redirect()->route('frontend.publications.manuscripts.index.old')
                ->withFlashSuccess(trans('"' . $manuscript->category . '" ' . $request->name . '" updated.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->middleware('permission:view backend');

        $manuscript = PublicationManuscript::where('id', $id)->get()->first();

        $title = "Edit Manuscript (" . $manuscript->category . ")";
        $form_url = "frontend.publications.manuscripts.store.old";
        $method = 'put';

        return view(
            'frontend.publications.manuscripts.edit_old',
            ["manuscript" => (object)$manuscript, "title" => $title, "form_url" => $form_url, 'method' => $method]
        );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->middleware('permission:view backend');

        $manuscript = PublicationManuscript::find($id);
        $category = $manuscript->category;
        $name = $manuscript->name;

        if (!empty($manuscript->file_translate)) {
            Storage::delete('/public/publications/manuscripts/old/' . $manuscript->file_translate);
        }
        if (!empty($manuscript->file_view_1)) {
            Storage::delete('/public/publications/manuscripts/old/' . $manuscript->file_view_1);
        }
        if (!empty($manuscript->file_view_2)) {
            Storage::delete('/public/publications/manuscripts/old/' . $manuscript->file_view_2);
        }
        if (!empty($manuscript->file_thumbnail)) {
            Storage::delete('/public/publications/manuscripts/old/' . $manuscript->file_thumbnail);
        }
        if (!empty($manuscript->file_download)) {
            Storage::delete('/public/publications/manuscripts/old/' . $manuscript->file_download);
        }
        if (!empty($manuscript->image_download)) {
            Storage::delete('/public/publications/manuscripts/old/' . $manuscript->image_download);
        }

        $manuscript->delete();

        // $manuscript->destroy($id);
        return redirect()->route('frontend.publications.manuscripts.index.old')
            ->withFlashSuccess(trans($category . ' "' . $name . '" is deleted.'));
    }


    /**
     * update board message.
     *
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    public function updateMessage(Request $request)
    {
        $this->middleware('permission:view backend');

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
        $this->middleware('permission:view backend');

        $message = $request->input('editor');
        if (empty($message)) {
            $message = '';
        }

        Storage::disk('local')->put("publications/manuscripts/board_notice.html", $message);
        return response()->json($message, 200);
    }
}