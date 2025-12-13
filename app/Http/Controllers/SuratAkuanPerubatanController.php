<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratAkuanPerubatan;
use App\Models\KelayakanWad;
use App\Models\Keluarga;
use Illuminate\Support\Facades\Auth;

class SuratAkuanPerubatanController extends Controller
{
    public function index()
    {
        $id = Auth::user()->id;
        $surat = SuratAkuanPerubatan::select('surat_akuan_perubatan.*', 'keluarga.nama as nama_pesakit', 'kelayakan_wad.kelayakan')
            ->leftJoin('keluarga', 'surat_akuan_perubatan.pesakit', '=', 'keluarga.id')
            ->leftJoin('kelayakan_wad', 'surat_akuan_perubatan.wad', '=', 'kelayakan_wad.id')
            ->where('surat_akuan_perubatan.idkakitangan', $id)
            ->orderBy('surat_akuan_perubatan.kemaskini', 'desc')
            ->get();

        return view('kakitangan.surat_akuan.index', compact('surat'));
    }

    public function create()
    {
        $id = Auth::user()->id;
        $pesakit = Keluarga::where('idkakitangan', $id)->orderBy('nama', 'asc')->get();
        $wad = KelayakanWad::orderBy('gred', 'asc')->get();

        return view('kakitangan.surat_akuan.create', compact('pesakit', 'wad'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hospital' => 'required|string',
            'pesakit' => 'required|integer',
            'wad' => 'required|integer',
        ]);

        SuratAkuanPerubatan::create([
            'idkakitangan' => Auth::user()->id,
            'hospital' => $request->hospital,
            'pesakit' => $request->pesakit,
            'wad' => $request->wad,
            'kemaskini' => now(),
        ]);

        return redirect()->route('surat_akuan.index')->with('success', 'Permohonan berjaya dihantar.');
    }
}
