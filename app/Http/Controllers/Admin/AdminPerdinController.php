<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Perjalanan;

class AdminPerdinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $perjalanan = Perjalanan::with(['user'])->paginate(7);

        return view('Admin.DataPerdin.DataPerdin', compact('perjalanan'));
    }

    public function detail($id)
    {
        $perjalanan = Perjalanan::with(['user'])->findOrFail($id);

        return view('Admin.DataPerdin.DetailPerdin', compact('perjalanan') );
    }

    public function searchperdin(Request $request)
    {
        $searchperdin = $request->get('searchperdin');
        $perjalanan = Perjalanan::where('kotaasal', 'like', '%'.$searchperdin.'%')
            ->orWhere('kotatujuan', 'like', '%'.$searchperdin.'%')
            ->orWhere('Status', 'like', '%'.$searchperdin.'%')
            ->orWhere('durasi', 'like', '%'.$searchperdin.'%')
            ->orWhere('keterangan', 'like', '%'.$searchperdin.'%')
            ->orWhereHas('user', function ($query) use ($searchperdin) {
                $query->where('name', 'like', '%'.$searchperdin.'%');
            })
            ->paginate(7);

        return view('Admin.DataPerdin.DataPerdin', compact('perjalanan'));
    }
}