<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Permission;
use App\APIError;
use App\User;

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

        if($role_tmp != null && $role_tmp != $role) {
            $notFoundError = new APIError;
            $notFoundError->setStatus("400");
            $notFoundError->setCode("ROLE_ALREADY_EXISTS");
            $notFoundError->setMessage("Role aleady exists");
            return response()->json($notFoundError, 400);
        }

        $role_tmp = Role::whereName($request->display_name)->first();

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


    public function syncAbilities(Request $req, $id) {
        $req->validate([
            'roles' => 'required|json',
            'permissions' => 'required|json'
        ]);
        $user = User::find($id);
        abort_if($user == null, 404, "User not found !");
        $roles = json_decode($req->roles);
        $permissions = json_decode($req->permissions);
        abort_unless(is_array($roles) && is_array($permissions), 400, "Roles and permissions must be both json array of id");

        foreach ($roles as $roleId) {
            abort_if(Role::find($roleId) == null, 404, "Role of id $roleId not found !");
        }

        foreach ($permissions as $permissionId) {
            abort_if(Permission::find($permissionId) == null, 404, "Permission of id $permissionId not found !");
        }

        $user->syncPermissions([]);
        $user->syncRoles($roles);
        $user->syncPermissionsWithoutDetaching($permissions);
        $data = [
            'roles' => $user->roles,
            'permissions' => $user->allPermissions()
        ];

        return response()->json($data);
    }
}
