<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Role;
use App\Permission;
use App\PermissionRole;
use App\APIError;

class RoleController extends Controller
{
    //
    public function store(Request $request){

        $this->validate($request->all(), [
            'name' => 'required|string|unique:roles',
            'display_name' => 'required|string|unique:roles',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description
        ]);

        $role->permissions()->sync($request->permissions);

        return response()->json($role);
    }

    public function update(Request $request, $id){

        $role = Role::find($id);
        if($role == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("ROLE_NOT_FOUND");
            $notFoundError->setMessage("Role type with id " . $id . " not found");
            return response()->json($notFoundError, 404);
        }

        $this->validate($request->all(), [
            'name' => 'required|string',
            'display_name' => 'required|string',
        ]);

        $role_tmp = Role::whereName($request->name)->first();

        if($role_tmp == $role) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("400");
            $notFoundError->setCode("ROLE_ALREADY_EXISTS");
            $notFoundError->setMessage("Role aleady exists");
            return response()->json($notFoundError, 400);
        }

        $role_tmp = Role::whereName($request->display_name)->first();

        if($role_tmp == $role) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("400");
            $notFoundError->setCode("ROLE_ALREADY_EXISTS");
            $notFoundError->setMessage("Role aleady exists");
            return response()->json($notFoundError, 400);
        }

        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description
        ]);

        $role->permissions()->sync($request->permissions);

        return response()->json($role);
    }

    public function delete($id) {
        $role = Role::find($id);
        if($role == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("ROLE_NOT_FOUND");
            $notFoundError->setMessage("Role type with id " . $id . " not found");
            return response()->json($notFoundError, 404);
        }
        Role::find($id)->delete();
        return response()->json(null);
    }

    public function get(Request $request){
        $limit = $request->limit;
        $s = $request->s;
        $page = $request->page;
        $roles = Role::where('name', 'LIKE', '%'.$s.'%')
                                  ->paginate($limit);
        return response()->json($roles);
    }

    public function getRolesWithPermissions(Request $request){
     
        $roles = Role::all();
        foreach ($roles as $role) {
            $role->permissions;
        }
        return response()->json($roles);
    }

    public function getPermissions(){
        $permissions = Permission::get();
        return response()->json($permissions);
    }

    public function find($id){
        $role = Role::find($id);
        if($role == null) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("404");
            $notFoundError->setCode("ROLE_NOT_FOUND");
            $notFoundError->setMessage("Assignment type with id " . $id . " not found");
            return response()->json($notFoundError, 404);
        }
        $role->permissions;
        return response()->json($role);
    }
}
