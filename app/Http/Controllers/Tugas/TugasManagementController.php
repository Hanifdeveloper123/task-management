<?php

namespace App\Http\Controllers\Tugas;

use Inertia\Inertia;
use App\Models\Tugas;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminBaseController;
use App\Services\Tugas\TugasManagementService;
use App\Http\Resources\Tugas\TugasManagementListResource;
use App\Http\Requests\Tugas\CreateTugasRequest;
use App\Http\Requests\Tugas\UpdateTugasRequest;
use App\Http\Resources\Tugas\SubmitTugasResource;


class TugasManagementController extends AdminBaseController
{

    


    public function getTugas(){
        $tugas=Tugas::all();
        return response()->json($tugas);
    }

    public function __construct(TugasManagementService $tugasManagementService)
    {
        $this->tugasManagementService = $tugasManagementService;
    }

    public function getTugasList(Request $request)
    {
        try {
            $data = $this->tugasManagementService->getData($request);

            $result = new TugasManagementListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createTugasPage()
    {
        return Inertia::render($this->source . 'tugas/create', [
            "title" => 'BattleHR | Setting System Authentication',
            "additional" => [
                'permission_list' => $this->tugasManagementService->getPermissionList()
            ]
        ]);
    }

    public function editTugasPage($id)
    {
        return Inertia::render($this->source . 'tugas/edit', [
            "title" => 'BattleHR | Setting System Authentication',
            "additional" => [
                'tugas' => $this->tugasManagementService->findTugasById($id),
            ]
        ]);
    }

    public function store(CreateTugasRequest $request)
    {
        try {
            $data = $this->tugasManagementService->storeData($request);

            $result = new SubmitTugasResource($data, 'Success Create New Tugas');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function update($id, UpdateTugasRequest $request)
    {
        try {
            $data = $this->tugasManagementService->updateData($id, $request);

            $result = new SubmitTugasResource($data, 'Success Update Tugas');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $data = $this->tugasManagementService->deleteData($id);

            $result = new SubmitTugasResource($data, 'Success Delete Tugas');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    
}
