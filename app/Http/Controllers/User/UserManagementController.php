<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\AdminBaseController;
use App\Services\User\UserManagementService;
use App\Http\Resources\User\UserManagementListResource;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\SubmitUserResource;

class UserManagementController extends AdminBaseController
{

    public function getUser(){
        $user=User::all();
        return response()->json($user);
    }
    
   
    public function __construct(UserManagementService $userManagementService)
    {
        $this->userManagementService = $userManagementService;
    }

    public function getUserList(Request $request)
    {
        try {
            $data = $this->userManagementService->getData($request);

            $result = new UserManagementListResource($data);
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function createUserPage()
    {
        return Inertia::render($this->source . 'user/create', [
            "title" => 'BattleHR | Setting System Authentication',
            "additional" => [
                'permission_list' => $this->userManagementService->getPermissionList()
            ]
        ]);
    }

    public function editUserPage($id)
    {
        return Inertia::render($this->source . 'user/edit', [
            "title" => 'BattleHR | Setting System Authentication',
            "additional" => [
                'permission_list' => $this->userManagementService->getPermissionList(),
                'user' => $this->userManagementService->findUserById($id),
                'user_has_permissions' => $this->userManagementService->getUserHasPermissions($id)
            ]
        ]);
    }

    public function store(CreateUserRequest $request)
    {
        try {
            $data = $this->userManagementService->storeData($request);

            $result = new SubmitUserResource($data, 'Success Create New User');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function update($id, UpdateUserRequest $request)
    {
        try {
            $data = $this->userManagementService->updateData($id, $request);

            $result = new SubmitUserResource($data, 'Success Update User');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        try {
            $data = $this->userManagementService->deleteData($id);

            $result = new SubmitUserResource($data, 'Success Delete User');
            return $this->respond($result);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage());
        }
    }
}
