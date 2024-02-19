<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Role;
use App\User;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public function roles()
    {
        $roles = Role::all();
        return view('admin.roles.rolesManager', compact('roles'));
    }

    public function newRole(Request $data)
    {
        $validatedData = $data->validate([
            'name' => 'required'
        ]);

        $validation = Role::where('name', $data->name)->first();

        if (!$validation) {
            $newRequirement = new Role();
            $newRequirement->name = $data->name;
            $newRequirement->save();
            return back()->with(['success' => 'El rol se registró con éxito']);
        } else {
            return back()->with(['danger' => 'Ese rol ya existe :D']);
        }
    }

    public function showRole($rol)
    {
        $rol = decrypt($rol);
        $users = User::all();

        return view('admin.roles.roleData', compact('rol', 'users'));
    }

    public function updateRole(Request $data, $id)
    {
        $validatedData = $data->validate([
            'name' => 'required|max:255'
        ]);

        $role = Role::where('id', decrypt($id))->first();
        
        if ($data && $role) {
            $usersToassign = $data->except(['_token', '_method','name']);
            $selectedUsers = [];

            foreach ($usersToassign as $checkbox => $value) {

                $parts = explode('_', $value);
                $userId = $parts[1];
                $selectedUsers[] = $userId;
            }
            
            $role->assignation = json_encode(array_values($selectedUsers));
            $role->name = $data->name;
            $role->save();

            return redirect()->route('admin.roles.role.show', encrypt($role))->with(['success' => 'La actualización del rol se ha ejecutado exitosamente']);
            
        }else{
            return back()->with(['danger' => '🚨 No se ha encontrado información a o para modificar']);
        }
    }

    public function deleteRole($id){
        $role = Role::where('id',decrypt($id))->first();
        if($role){
            $role->delete();
            return back()->with(['success' => '✅ El rol se ha eliminado exitosamente']);
        }else{
            return back()->with(['danger' => '🚨 No se ha encontrado información a eliminar']);
        }
    }
}
