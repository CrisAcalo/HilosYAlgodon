<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Model\Configuraciones;
use App\Model\Role;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Rules\AlphaSpace;

class adminController extends Controller
{
    public function administrador()
    {
        return view('admin.adminPrincipal');
    }


    public function index()
    {
        if (!Auth::user()->rolValidation(['Admin'])){
            abort(403,'Unauthorized action.');
        }
        $users = User::paginate(10);
        return view('admin.users.userManager', compact('users'));
    }

    public function show($id)
    {
        if(!Configuraciones::where('id',1)->first()){
            $newConfiguracion = new Configuraciones();
            $newConfiguracion->save();
        }
        
        if (!Auth::user()->rolValidation(['Admin'])){
            abort(403,'Unauthorized action.');
        }
        $user = User::where('id', decrypt($id))->first();
        if ($user) {
            $roles = Role::all();
            $assignatedRoles = Role::whereJsonContains('assignation', strval($user->id))->get();
            $configuraciones = Configuraciones::where('id',1)->first();
            return view('admin.users.profile', compact('user', 'roles', 'assignatedRoles','configuraciones'));
        } else {
            abort(404);
        }
    }

    public function store(Request $data)
    {
        if (!Auth::user()->rolValidation(['Admin'])){
            abort(403,'Unauthorized action.');
        }
        $validatedData = $data->validate([
            'name' => ['required', 'max:255', new AlphaSpace],
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = new User();
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = Hash::make($data->password);
        $user->save();

        return back()->with('success', 'El usuario ha sido registrado con éxito 😉');
    }

    public function update(Request $data, $id)
    {
        if (!Auth::user()->rolValidation(['Admin'])){
            abort(403,'Unauthorized action.');
        }
        $validatedData = $data->validate([
            'name' => ['required', 'max:255', new AlphaSpace],
            'email' => 'unique:users|max:255'
        ]);

        $user = User::find(decrypt($id));

        if ($user && $data) {

            $user->name = $data->name;
            if ($data->email) {
                $user->email = $data->email;
            }
            $user->save();
            return redirect()->route('admin.users.show', encrypt($user->id))->with('success', 'El usuario se ha actualizado exitosamente');
        } else {
            return back()->with('warning', 'No se han encontrado datos a o para actualizar');
        }
    }

    public function updateUserRoles(Request $data, $id)
    {
        if (!Auth::user()->rolValidation(['Admin'])){
            abort(403,'Unauthorized action.');
        }
        $user = User::find(decrypt($id));
        $allRoles = Role::all();

        if ($data && $user) {
            $rolesToassign = $data->except(['_token', '_method']);
            $selectedIdRoles = [];
            foreach ($rolesToassign as $checkbox => $value) {
                $parts = explode('_', $value);
                $roleId = $parts[1];
                $selectedIdRoles[] = $roleId;
            }

            foreach ($allRoles as $rol) {
                if (!in_array($rol->id, $selectedIdRoles)) {
                    $asignationToModify = json_decode($rol->assignation, true);
                    $idArray[] = strval(decrypt($id));
                    if ($asignationToModify) {
                        $asignationToModify = array_diff($asignationToModify, $idArray);
                        $rol->assignation = json_encode(array_values($asignationToModify));
                        $rol->save();
                    }else{
                        $asignationToModify = [];
                        $asignationToModify = array_diff($asignationToModify, $idArray);
                        $rol->assignation = json_encode(array_values($asignationToModify));
                        $rol->save();
                    }
                }
            }

            foreach ($selectedIdRoles as $rolId) {

                $role = Role::where('id', $rolId)->first();
                $roleToModify = json_decode($role->assignation, true);
                $roleToModify[] = strval(decrypt($id));
                $roleToModify = array_unique($roleToModify);
                $role->assignation = json_encode(array_values($roleToModify));
                $role->save();
            }

            return redirect()->route('admin.users.show', encrypt($user->id))->with(['success' => 'La actualización de los roles se ha ejecutado exitosamente']);
        } else {
            return back()->with('warning', 'No se han encontrado datos a o para actualizar');
        }
    }

    public function destroy($id)
    {
        if (!Auth::user()->rolValidation(['Admin'])){
            abort(403,'Unauthorized action.');
        }
        if (Auth::user()->id == decrypt($id)) {
            return back()->with('warning', 'Bro la vida es bella, no te elimines');
        }

        $user = User::find(decrypt($id));

        if ($user) {
            $allRoles = Role::all();
            foreach ($allRoles as $rol) {

                $asignationToModify = json_decode($rol->assignation, true);

                if (in_array($user->id, $asignationToModify)) {
                    $idArray[] = strval($user->id);
                    $asignationToModify = array_diff($asignationToModify, $idArray);
                    $rol->assignation = json_encode(array_values($asignationToModify));
                    $rol->save();
                }
            }
            $user->delete();
            return back()->with('success', 'El usuario se ha eliminado exitosamente');
        } else {
            return back()->with('warning', 'No se han encontrado datos a eliminar');
        }
    }
}
