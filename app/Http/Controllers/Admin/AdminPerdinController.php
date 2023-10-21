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

    public function search(Request $request)
    {
        $search = $request->get('search');
        $perjalanan = Perjalanan::where('kotaasal', 'like', '%'.$search.'%')
            ->orWhere('kotatujuan', 'like', '%'.$search.'%')
            ->orWhere('Status', 'like', '%'.$search.'%')
            ->orWhere('durasi', 'like', '%'.$search.'%')
            ->orWhere('keterangan', 'like', '%'.$search.'%')
            ->orWhereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->paginate(7);

        return view('Admin.DataPerdin.DataPerdin', compact('perjalanan'));
    }
}