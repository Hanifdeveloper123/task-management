<?php

namespace App\Http\Controllers;


class PenggunaController extends AdminBaseController
{
    public function __construct()
    {
        $this->title = "User";
        $this->path = "user/index";

        
    }
    
    // public function getUsers()
    // {
    //     try {
    //         $data = $this->userManagementService->getData($request);
    //         $result = new RoleManagementListResource($data);
    //         return $this->respond($result);
    //     } catch (\Exception $e) {
    //         return $this->exceptionError($e->getMessage());
    //     }
    // }

}
