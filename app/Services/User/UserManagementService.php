<?php

namespace App\Services\User;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use App\Events\User\UserCreated;
use App\Events\User\UserUpdated;



class UserManagementService
{

 

    public function getData($request)
    {
        $search = $request->search;

        // Get company
        $query = User::query();
        // Filter By Params
        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('id', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%');
        });
        return $query->paginate(10);
    }

    public function getPermissionList()
    {
        $permissions = Permission::get(['id', 'name', 'guard_name', 'label', 'group', 'sub_group'])->groupBy(['group', 'sub_group']);

        return $permissions;
    }

    public function findUserById($id)
    {
        $user = User::findOrFail($id);
        return $user;
    }

    public function getUserHasPermissions($id)
    {
        $user = User::findOrFail($id);
        return $user->getAllPermissions()->pluck('id');
    }

    public function storeData($request)
    {
        // Create a role first 
        $userInput = $request->only(['name','email','password']);
        $user = User::create([
            'name' => $userInput['name'],
            'email' => $userInput['email'],
            'password' => $userInput['password']
        ]);
        $user->assignRole('user');
   
        $permissions = collect($request->permissions)->where('status', true)->all();
        $user->syncPermissions($permissions);

    
        $new_permissions = Permission::whereIn('id', collect($permissions)->pluck('id'))->get()->pluck('name','email','password');
        event(new UserCreated($user, $new_permissions));
        
        return true;
    }

    public function updateData($id, $request)
    {
     
        $user = $this->findUserById($id);
        $user->update([
            'name' => $request->name ,
            'email' => $request->email,
        ]);
        $old_permissions = $user->getAllPermissions()->pluck('name','email','password');

        // Sync New Permissions
        $permissions = collect($request->permissions)->where('status', true)->all();
        $user->syncPermissions($permissions);

        // Call the created role event
        $new_permissions = Permission::whereIn('id', collect($permissions)->pluck('id'))->get()->pluck('name','email','password');
        event(new UserUpdated($user, $new_permissions, $old_permissions));

        return true;
    }

    public function deleteData($id)
    {
        $user = $this->findUserById($id);
        $user->delete();

        return $user;
    }
    
}