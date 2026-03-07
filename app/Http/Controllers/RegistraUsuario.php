<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cob_rol;
use App\Models\Hceespecial;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
use Yajra\Datatables\Facades\Datatables;

class RegistraUsuario extends Controller
{
    //
    public function show()
    {
        if (auth()->user()->hcerol_id === 1) {
            $usuarios = User::whereIn('escobrador', [1])->get();
            return view('showUsuario', [
                'usuarios' => $usuarios,
            ]);
        }
    }

    public function showUsuarioEdit(Request $request)
    {
        $id =  $request->id;
        $roles = Cob_rol::all();
        return view("/usuarioEditView", [
            "usuario" => User::find($id),
            'roles' => $roles,
        ]);
    }

    public function showPerfil()
    {
        return view("/showPerfil", [
            "usuario" => User::find(auth()->user()->id),
        ]);
    }


    public function editPerfil(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'name2' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            ////            'cp' => 'required',
        ];
        $customMessages = [
            'name.required' => 'El campo nombre es obligatorio',
            'name2.required' => 'El campo apellido es obligatorio',
            'email.required' => 'El campo email es obligatorio',
            'email.string' => 'El campo email es obligatorio',
            'email.email' => 'No es una dirección válida',
            'password.required' => 'El campo email es obligatorio',
            'password.string' => 'El campo clave es obligatorio',
            'password.min' => 'El campo clave debe ser mayor a 8 caracteres',
            'password.confirmed' => 'El campo clave no coincide, verifique!',
        ];
        if ($request->password != null) {
            $request->validate($rules, $customMessages);
        }
        $usuario = User::find((auth()->user()->id));
        if ($request->password != null) {
            $usuario->email = $request->email;
            $usuario->password = Hash::make($request->password);
        } else {
            $usuario->email = $request->email;
        }
        $usuario->save();
        return redirect('/')->with('mensaje', "El usuario se ha modificado correctamente");
        //        }
    }

    public function editUsuario(Request $request)
    {
        if ($request->password != null) {
            $rules = [
                'name' => 'required|min:3|max:255',
                'name2' => 'required|min:3|max:255',
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'documento' => 'required|min:7|max:8',
                'hcerol_id' => 'required',
                'cod_sapp' => 'required',
            ];
            $customMessages = [
                'name.required' => 'El campo nombre es obligatorio',
                'name.min'           => 'El campo nombre debe ser más de 3 caracteres',
                'name.max' => 'El campo nombre debe ser igual a 4 caracteres',
                'name2.required' => 'El campo apellido es obligatorio',
                'name2.min'           => 'El campo apellido debe ser más de 3 caracteres',
                'name2.max' => 'El campo apellido debe ser igual a 4 caracteres',
                'email.required' => 'El campo email es obligatorio',
                'email.string' => 'El campo email es obligatorio',
                'email.unique' => 'El campo email ya existe',
                'email.email' => 'No es una dirección válida',
                'password.required' => 'El campo email es obligatorio',
                'password.string' => 'El campo clave es obligatorio',
                'password.min' => 'El campo clave debe ser mayor a 8 caracteres',
                'password.confirmed' => 'El campo clave no coincide, verifique!',
                'documento.required' => 'El campo documento es obligatorio',
                'documento.unique' => 'El campo documento ya existe',
                'documento.min.' => 'El campo  documento debe ser >=7 caracteres',
                'documento.max' => 'El campo documento debe ser <=8 caracteres',
                'hcerol_id.required' => 'El campo rol es obligatorio',
                'cod_sapp.required' => 'El campo número de cobrador es obligatorio',
            ];
        } else {
            $rules = [
                'name' => 'required|min:3|max:255',
                'name2' => 'required|min:3|max:255',
                'email' => ['required', 'string', 'email', 'max:255'],
                'documento' => 'required|min:7|max:8',
                'hcerol_id' => 'required',
                'cod_sapp' => 'required',
            ];
            $customMessages = [
                'name.required' => 'El campo nombre es obligatorio',
                'name.min'           => 'El campo nombre debe ser más de 3 caracteres',
                'name.max' => 'El campo nombre debe ser igual a 4 caracteres',
                'name2.required' => 'El campo apellido es obligatorio',
                'name2.min'           => 'El campo apellido debe ser más de 3 caracteres',
                'name2.max' => 'El campo apellido debe ser igual a 4 caracteres',
                'email.required' => 'El campo email es obligatorio',
                'email.string' => 'El campo email es obligatorio',
                'email.unique' => 'El campo email ya existe',
                'email.email' => 'No es una dirección válida',
                'documento.required' => 'El campo documento es obligatorio',
                'documento.unique' => 'El campo documento ya existe',
                'documento.min.' => 'El campo  documento debe ser >=7 caracteres',
                'documento.max' => 'El campo documento debe ser <=8 caracteres',
                'hcerol_id.required' => 'El campo rol es obligatorio',
                'cod_sapp.required' => 'El campo número de cobrador es obligatorio',
            ];
        }

        $request->validate($rules, $customMessages);
        $usuario = User::find($request->id);
        if ($request->password != null) {
            $usuario->name = $request->name . " " . $request->name2;
            $usuario->name1 = $request->name;
            $usuario->name2 = $request->name2;
            $usuario->email = $request->email;
            $usuario->password = Hash::make($request->password);
            $usuario->documento = $request->documento;
            $usuario->hcerol_id = $request->hcerol_id;
            $usuario->cod_sapp = $request->cod_sapp;
            $usuario->baja = null;
            $usuario->save();
        } else {
            $usuario->name = $request->name . " " . $request->name2;
            $usuario->name1 = $request->name;
            $usuario->name2 = $request->name2;
            $usuario->email = $request->email;
            $usuario->documento = $request->documento;
            $usuario->hcerol_id = $request->hcerol_id;
            $usuario->cod_sapp = $request->cod_sapp;
            $usuario->esadmin = $request->esadmin;
            $usuario->baja = null;
            $usuario->save();
        }
        return redirect('usuarios')->with('mensaje', "El usuario se ha modificado y activado correctamente");
        //        }
    }

    public function showDeleteUpdate(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->password = "Baja";
        $usuario->baja = 1;
        $usuario->save();
        return redirect('usuarios')->with('mensaje', "El usuario se ha desactivado correctamente");
        //        }
    }

    public function showDeleteUpdateBorrar(Request $request)
    {
        $fechactual = strtotime(date('Y-m-d'));
        $usuario = User::find($request->id);
        $nombre = $usuario->name;
        $fechacreate = date("Y-m-d", strtotime($usuario->created_at));
        $fechacreate2 = strtotime($fechacreate);
        if ($fechacreate2 === $fechactual) {
            $usuario->delete();
            return redirect('usuarios')->with('mensaje', "El usuario " . $nombre . " se ha ELIMINADO");
        } else {
            return redirect('usuarios')->with('mensaje', "NO ES POSIBLE ELIMINAR");
        }
        //        }
    }

    public function showUsuarioCreate()
    {
        $roles = Cob_rol::all();
        return view('registraUsuario', [
            'roles' => $roles,
        ]);
    }

    public function createUsuario(Request $request)
    {
        $rules = [
            'name' => 'required|min:3|max:255',
            'name2' => 'required|min:3|max:255',
            'username' => ['required', 'string', 'max:65'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'documento' => 'required|unique:users|min:7|max:8',
            'hcerol_id' => 'required',
            'cod_sapp' => 'required',
        ];
        $customMessages = [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min'           => 'El campo nombre debe ser más de 3 caracteres',
            'name.max' => 'El campo nombre debe ser igual a 4 caracteres',
            'name2.required' => 'El campo apellido es obligatorio',
            'name2.min'           => 'El campo apellido debe ser más de 3 caracteres',
            'name2.max' => 'El campo apellido debe ser igual a 4 caracteres',
            'username.required' => 'El campo nombre de usuario es obligatorio',
            'username.string' => 'El campo nombre usuario es obligatorio',
            'username.max' => 'El campo nombre usuario debe ser menor a 65 caracteres',
            'email.required' => 'El campo email es obligatorio',
            'email.string' => 'El campo email es obligatorio',
            'email.unique' => 'El campo email ya existe',
            'email.email' => 'No es una dirección válida',
            'password.required' => 'El campo email es obligatorio',
            'password.string' => 'El campo clave es obligatorio',
            'password.min' => 'El campo clave debe ser mayor a 8 caracteres',
            'password.confirmed' => 'El campo clave no coincide, verifique!',
            'documento.required' => 'El campo documento es obligatorio',
            'documento.unique' => 'El campo documento ya existe',
            'documento.min.' => 'El campo  documento debe ser >=7 caracteres',
            'documento.max' => 'El campo documento debe ser <=8 caracteres',
            'hcerol_id.required' => 'El campo rol es obligatorio',
            'cod_sapp.required' => 'El campo número de cobrador es obligatorio',
        ];

        $request->validate($rules, $customMessages);
        $usuario = new User();
        $usuario->name = $request->name . " " . $request->name2;
        $usuario->name1 = $request->name;
        $usuario->name2 = $request->name2;
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->documento = $request->documento;
        $usuario->hcerol_id = $request->hcerol_id;
        $usuario->cod_sapp = $request->cod_sapp;
        $usuario->esadmin = $request->esadmin;
        $usuario->escobrador = 1;
        $usuario->save();
        return redirect('/usuarios')->with('mensaje', 'Se ha creado correctamente el nuevo usuario');
    }
}
