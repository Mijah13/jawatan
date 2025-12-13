<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratPengesahan;
use App\Models\SuratPengesahanNombor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SuratPengesahanController extends Controller
{
    public function index()
    {
        $id = Auth::user()->id;
        $surat = SuratPengesahan::where('idkakitangan', $id)
            ->orderBy('kemaskini', 'desc')
            ->get();

        return view('kakitangan.surat.index', compact('surat'));
    }

    public function create()
    {
        $negeri = DB::table('negeri')->orderBy('nama', 'asc')->get();
        $fail = SuratPengesahanNombor::first(); // Assuming single record as per legacy code

        return view('kakitangan.surat.create', compact('negeri', 'fail'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penerima' => 'required|string',
            'alamat1' => 'required|string',
            'alamat2' => 'nullable|string',
            'poskod' => 'required|numeric',
            'bandar' => 'required|string',
            'negeri' => 'required|integer',
            'fail' => 'required|string',
        ]);

        SuratPengesahan::create([
            'idkakitangan' => Auth::user()->id,
            'kepada' => $request->penerima,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2,
            'poskod' => $request->poskod,
            'bandar' => $request->bandar,
            'negeri' => $request->negeri,
            'fail' => $request->fail,
            'tarikhmohon' => now(),
            'status' => 0, // Default status
        ]);

        return redirect()->route('surat.index')->with('success', 'Permohonan berjaya dihantar.');
    }
}
