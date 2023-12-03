<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TugasResource;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendMailJob;
use Illuminate\Support\Facades\Validator;


class TugasController extends Controller
{
    public function index()
    {
     $task = Tugas::latest()->paginate(5);

        //return collection of posts as a resource
        return new TugasResource(true, 'List Data Tugas', $task);
        }

        public function store(Request $request)
    {
        // dd($request);
        //define validation rules
        $tugasInput = $request->only(['judul','deskripsi','user_id','status','deadline']);
        // dd($tugasInput['judul']);
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'deskripsi' =>  'required',
            'user_id' =>  'required',
            'status' =>  'required',
            'deadline' =>  'required'
        ]);
       

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create post
        $task = Tugas::create([
           
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'deadline' => $request->deadline
        ]);

        $email=DB::table('users')->where('id',$tugasInput['user_id'])->select('email')->value('email');
        // dd($email);
        $data = [
            "name"=>"Task Man App",
            "email"=>$email,
            "body"=>$tugasInput['judul']."<br>".$tugasInput['deskripsi']."<br>".$tugasInput['deadline']
        ];

        dispatch(new SendMailJob($data));

        //return response
        return new TugasResource(true, 'Data Tugas Berhasil Ditambahkan!', $task);
    }

    public function show($id)
    {
        //find post by ID
        $task = Tugas::find($id);

        //return single post as a resource
        return new TugasResource(true, 'Detail Data Tugas!', $task);
    }

    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'deskripsi' => 'required',
            'status' => 'required',
            'user_id' => 'required',
            'deadline' => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $task= Tugas::find($id);

        //check if image is not empty
        {

            //update post without image
            $task->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'status' => $request->status,
                'user_id' => $request->user_id,
                'deadline' => $request->deadline
            ]);
        }

        //return response
        return new TugasResource(true, 'Data Tugas Berhasil Diubah!', $task);
    }

    public function destroy($id)
    {

        //find post by ID
        $task = Tugas::find($id);

        //delete image

        //delete post
        $task->delete();

        //return response
        return new TugasResource(true, 'Data Tugas Berhasil Dihapus!', null);
    }
}
