<?php
namespace App\Http\Controllers;

use Upload;
use App\Models\File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    private $image_ext = ['jpg', 'jpeg', 'png', 'gif', 'tiff'];
    private $video_ext = ['mp3', 'ogg', 'mpga'];
    private $doc_ext = ['mp4', 'mpeg'];
    private $audio_ext = ['doc', 'docx', 'pdf', 'odt'];

     /**
     * constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handles retriving files by File Type or id
     * @param  string $type  File type
     * @param  integer $id   Id
     * @return object  Files, JSON
     * 
     */
    public function retrieve($type, $id=null)
    {

        $model = new File();

        if(!is_null($id))
        {
            $response = $model::findOrFail(id);

        }else{
            // implement some caching here maybe Redis ? 
            $files = $model::where('type', $type)
                            ->where('user_id', Auth::id())
                            ->orderBy('id', 'desc');
        }

        $response = [ 'data' => $files ];

        return response()->json($response);

    }

   /**
     * Handles uploading new file and stores it to Filesystem 
     * @param  Request $request Request 
     * @return boolean True if success, otherwise - false
     */
        public function store(Request $request)
        {

            $max_file_size = (int)ini_get('upload_max_filesize') * 1000;

            $all_ext = implode(',', $this->allExtensions());
            
            // validate request
            $this->validate($request, [
                'name' => 'required|unique:files',
                'file' => 'required|file|mimes:' . $all_ext . '|max:' . $max_size
            ]);
            
            $file = new File();

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            if(in_array($extension,$this->img_ext))
            {   
                    $type = 'image';
            }elseif(in_array($extension,$this->video_ext))
            {
                   $type = 'video';
            }elseif(in_array($extension,$this->doc_ext))   
            {   
                   $type = 'document';
            }elseif(in_array($extension,$this->audio_ext))
            {
                $type = 'audio';
            }

            if (Storage::putFileAs('/public/' . $this->getUserDir() . '/' . $type . '/', $file, $request['name'] . '.' . $ext)) {
                return $model::create([
                        'name' => $request['name'],
                        'type' => $type,
                        'extension' => $ext,
                        'user_id' => Auth::id()
                    ]);
            }
    
            return response()->json(false);

        }

   /**
     * Handles editing an already uploaded file  
     * @param int $id File id
     * @param Request $request 
     * @return boolean True, False 
     */
    public function editing(int $id,Request $request)
    {

        $file = File::where('id', $id)->where('user_id', Auth::id())->first();
        // return json response if file name and $resquest don't match
        if ($file->name == $request['name']) {
            return response()->json(false);
        }

        $this->validate($request, [
            'name' => 'required|unique:files'
        ]);

        
        $old_filename = '/public/' . $this->getUserDir() . '/' . $file->type . '/' . $file->name . '.' . $file->extension;
        $new_filename = '/public/' . $this->getUserDir() . '/' . $request['type'] . '/' . $request['name'] . '.' . $request['extension'];

        if (Storage::disk('local')->exists($old_filename)) {
            if (Storage::disk('local')->move($old_filename, $new_filename)) {
                $file->name = $request['name'];
                return response()->json($file->save());
            }
        }

        return response()->json(false);

    }

   /**
     * Handles removing an existing file and removing the entry from database
     * @param int $id File id
     * @param Request $request 
     * @return boolean True, False 
     */
    public function remove(int $id,Request $request)
    {

        $file = File::findOrFail($id);

        if (Storage::disk('local')->exists('/public/' . $this->getUserDir() . '/' . $file->type . '/' . $file->name . '.' . $file->extension)) {
            if (Storage::disk('local')->delete('/public/' . $this->getUserDir() . '/' . $file->type . '/' . $file->name . '.' . $file->extension)) {
                return response()->json($file->delete());
            }
        }
        return response()->json(false);

    }

    /**
     * Get all extensions 
     * @return array Extensions of all supported types  
     */

     public function getAllExtensions()
     {
         return array_merge($this->img_ext, $this->video_ext, $this->doc_ext, $this->audio_ext);
     }



    /**
     * Get user directory 
     * @return string User directory 
     */

    public function getUserDir()
    {
        return Auth::user()->name . '_' . Auth::id();
    }

   




}