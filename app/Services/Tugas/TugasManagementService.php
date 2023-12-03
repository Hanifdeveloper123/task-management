<?php

namespace App\Services\Tugas;

use App\Models\Tugas;
use Spatie\Permission\Models\Permission;
use App\Events\Tugas\TugasCreated;
use App\Events\Tugas\TugasUpdated;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendMailJob;


class TugasManagementService
{

    public function index ()
    {
     $data=DB::table('tugas')->leftJoin('users','tugas.user_id','=','users.id')->select('tugas.*','users.name')->paginate(20);
    }

    public function getData($request)
    {
        $search = $request->search;

        if(auth()->user()->roles->pluck('name')[0]=="super admin"){
            if($search==""){
                $query=Tugas::select('tugas.*','users.name')->leftJoin('users','tugas.user_id','=','users.id');
            }else{
                $query=Tugas::select('tugas.*','users.name')->leftJoin('users','tugas.user_id','=','users.id')
                    ->where('tugas.id','like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhere('user_id', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('deadline', 'like', '%' . $search . '%');
            }
        }else{
            if($search==""){
                $query=Tugas::select('tugas.*','users.name')->where('tugas.user_id',auth()->user()->id)->leftJoin('users','tugas.user_id','=','users.id');
            }else{
                $query=Tugas::select('tugas.*','users.name')->leftJoin('users','tugas.user_id','=','users.id')
                    ->where('tugas.user_id',auth()->user()->id)
                    ->where('tugas.id','like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhere('user_id', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('deadline', 'like', '%' . $search . '%');
            }
        }
        
        return $query->paginate(10);
    }

    public function getPermissionList()
    {
        $permissions = Permission::get(['id', 'name', 'guard_name', 'label', 'group', 'sub_group'])->groupBy(['group', 'sub_group']);

        return $permissions;
    }

    public function getTugasHasPermissions($id)
    {
        $tugas = Tugas::findOrFail($id);
        return $tugas->getAllPermissions()->pluck('id');
    }

    public function findTugasById($id)
    {
        $tugas = Tugas::findOrFail($id);
        return $tugas;
    }


    public function storeData($request)
    {

        $tugasInput = $request->only(['judul','deskripsi','user_id','status','deadline']);
        $tugas = Tugas::create([
            'judul' => $tugasInput['judul'],
            'deskripsi' => $tugasInput['deskripsi'],
            'user_id' => $tugasInput['user_id'],
            'status' => $tugasInput['status'],
            'deadline' => $tugasInput['deadline']
        ]);

        $email=DB::table('users')->where('id',$tugasInput['user_id'])->select('email')->value('email');

        $data = [
            "name"=>"Task Man App",
            "email"=>$email,
            "body"=>$tugasInput['judul']."<br>".$tugasInput['deskripsi']."<br>".$tugasInput['deadline']
        ];

        dispatch(new SendMailJob($data));

        return true;
    }

    public function updateData($id, $request)
    {
     
        $tugas = $this->findTugasById($id);
        $tugas->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'user_id' => $request->user_id,
            'deadline' => $request->deadline,
        ]);
        
        return true;
    }

    public function deleteData($id)
    {
        $tugas = $this->findTugasById($id);
        $tugas->delete();

        return $tugas;
    }
}