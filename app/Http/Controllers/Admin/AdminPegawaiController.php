<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Pegawai;
use App\Models\User;


class AdminPegawaiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = User::with(['pegawai'])->paginate(7);

        return view('Admin.DataPegawai.DataPegawai', compact('user'));
    }

    public function show($id)
    {
        $pegawai = User::with(['pegawai'])->findOrFail($id);

        return view('Admin.DataPegawai.ShowPegawai', compact('pegawai') );
    }

    public function create()
    {        
        return view('Admin.DataPegawai.CreatePegawai');
    }

    public function store(Request $request)
    {
            $request->validate([
                'nama'                  => 'required',
                'email'                 => 'required',
                'password'              => 'required',
                'jeniskelamin'          => 'required',
                'telepon'               => 'required',
                'alamat'                => 'required',
                'NIK'                   => 'required',
                'NIP'                   => 'required',
                'divisi'                => 'required',
                'status'                => 'required',
            ]);

            // $request->validate([
            //     'foto' => 'mimes:jpg,jpeg,png',
            // ]);

            $pegawai = new Pegawai;

            // if ($files = $request->file('foto')) {
            //     $destinationPath = 'datapegawai/';
            //     $file = $request->file('foto');
            //     // upload path  
    
            //     $profileImage = basename($request->file('foto')->getClientOriginalName(), '.' . $request->file('foto')->getClientOriginalExtension()) . "." .
            //         $files->getClientOriginalExtension();
            //     $pathImg = $file->storeAs('', $profileImage);
            //     $files->move($destinationPath, $profileImage);
            //     $pegawai->Foto = $pathImg;
            // }

            $pegawai->NomorTelepon      = $request->telepon;
            $pegawai->Alamat            = $request->alamat;
            $pegawai->NIK               = $request->NIK;
            $pegawai->NIP               = $request->NIP;
            $pegawai->JenisKelamin      = $request->jeniskelamin;
            $pegawai->Divisi            = $request->divisi;
            $pegawai->save();

            $user = new User;

            $user->name       = $request->nama;
            $user->email      = $request->email;
            $user->password   = Hash::make($request->nama);
            if($request->status == 'admin'){
                $user->is_Admin      = 0;
            }
            else if($request->status == 'pegawai'){
                $user->is_Admin      = 1;
            }
            else if($request->status == 'SDM'){
                $user->is_Admin      = 2;
            }
            $user->id_pegawai      = $pegawai->id;
            $user->save();

            return redirect(route('register'));
        
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $user = User::with(['pegawai'])
            ->where('name', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%')
            ->paginate(7);

        return view('Admin.DataPegawai.DataPegawai', compact('user'));
    }
}
