<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Latihan;
use App\Models\KategoriLatihan;
use App\Models\JenisLatihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LatihanController extends Controller
{
    public function index()
    {
        $id = Auth::user()->id;

        $latihan = Latihan::select('latihan.*', 'kategori_latihan.kategori as nama_kategori', 'jenis_latihan.jenis as nama_jenis')
            ->leftJoin('kategori_latihan', 'latihan.kategori', '=', 'kategori_latihan.id')
            ->leftJoin('jenis_latihan', 'latihan.jenis', '=', 'jenis_latihan.id')
            ->where('latihan.idkakitangan', $id)
            ->orderBy('latihan.mula', 'desc')
            ->get();

        // Calculate total days per year
        $hari = Latihan::select(DB::raw('YEAR(mula) as tahun'), DB::raw('SUM(tempoh) as jumlahhari'))
            ->where('idkakitangan', $id)
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->get();

        return view('kakitangan.latihan.index', compact('latihan', 'hari'));
    }

    public function create()
    {
        $kategori = KategoriLatihan::orderBy('kategori', 'asc')->get();
        $jenis = JenisLatihan::orderBy('jenis', 'asc')->get();

        return view('kakitangan.latihan.create', compact('kategori', 'jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tajuk' => 'required|string',
            'kategori' => 'required|integer',
            'jenis' => 'required|integer',
            'mula' => 'required|date',
            'tamat' => 'required|date|after_or_equal:mula',
            'tempoh' => 'required|integer',
            'tempat' => 'required|string',
            'penganjur' => 'required|string',
        ]);

        Latihan::create([
            'idkakitangan' => Auth::user()->id,
            'tajuk' => $request->tajuk,
            'kategori' => $request->kategori,
            'jenis' => $request->jenis,
            'mula' => $request->mula,
            'tamat' => $request->tamat,
            'tempoh' => $request->tempoh,
            'tempat' => $request->tempat,
            'penganjur' => $request->penganjur,
            'kemaskini' => now(),
        ]);

        return redirect()->route('latihan.index')->with('success', 'Latihan berjaya ditambah.');
    }

    public function destroy($id)
    {
        $latihan = Latihan::findOrFail($id);

        // Ensure user can only delete their own records
        if ($latihan->idkakitangan != Auth::user()->id) {
            return redirect()->route('latihan.index')->with('error', 'Anda tidak dibenarkan memadam rekod ini.');
        }

        $latihan->delete();

        return redirect()->route('latihan.index')->with('success', 'Latihan berjaya dipadam.');
    }
}
