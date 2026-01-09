<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratPengesahan;
use App\Models\SuratPengesahanNombor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// No explicit model needed if using DB facade for everything, but keeping imports clean.

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

    public function edit($id)
    {
        $surat = SuratPengesahan::findOrFail($id);
        $negeri = DB::table('negeri')->orderBy('nama', 'asc')->get();
        $fail = SuratPengesahanNombor::first(); // Assuming single record as per legacy code

        return view('kakitangan.surat.edit', compact('surat', 'negeri', 'fail'));
    }

    public function update(Request $request, $id)
    {
        $surat = SuratPengesahan::findOrFail($id);

        $surat->update([
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

    public function cetak($id)
    {
        // Main Surat Data
        $suratData = DB::table('surat_pengesahan')
            ->select(
                'surat_pengesahan.id as idsurat',
                'surat_pengesahan.idkakitangan',
                'kakitangan.nama as kakitangan',
                'surat_pengesahan.kepada',
                'surat_pengesahan.alamat1',
                'surat_pengesahan.alamat2',
                'surat_pengesahan.poskod',
                'surat_pengesahan.bandar',
                'negeri.nama as negeri',
                'kakitangan.mykad',
                'kakitangan.tarikhlantikanpertama',
                'kakitangan.tarikhpengesahanjawatan',
                'gaji_pokok.gaji_pokok',
                'jawatan.jawatan as jwt',
                'gaji_pokok.no_gaji',
                'surat_pengesahan.fail',
                'surat_pengesahan.tarikh_sah'
            )
            ->leftJoin('negeri', 'negeri.id', '=', 'surat_pengesahan.negeri')
            ->leftJoin('kakitangan', 'kakitangan.id', '=', 'surat_pengesahan.idkakitangan')
            ->leftJoin('gaji_pokok', 'gaji_pokok.idkakitangan', '=', 'surat_pengesahan.idkakitangan')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->where('surat_pengesahan.id', $id)
            ->first();

        if (!$suratData) {
            abort(404, 'Surat not found');
        }

        $idstaff = $suratData->idkakitangan;

        // Taraf Perkhidmatan
        $taraf = DB::table('kakitangan')
            ->join('taraf_perkhidmatan', 'taraf_perkhidmatan.id', '=', 'kakitangan.taraf_perkhidmatan')
            ->where('kakitangan.id', $idstaff)
            ->value('taraf_perkhidmatan.taraf');

        // Elaun
        $elaun = DB::table('elaun_dapat')
            ->leftJoin('elaun', 'elaun.id', '=', 'elaun_dapat.elaun')
            ->where('elaun_dapat.idkakitangan', $idstaff)
            ->select('elaun.nama', 'elaun_dapat.nilai')
            ->get();

        // Moto (Assuming latest year or just the first one as per legacy logic)
        $moto = DB::table('moto_hari_pekerja')
            ->orderBy('tahun', 'asc')
            ->first();

        // Pengesah (Pelulus)
        $pengesah = DB::table('surat_pengesahan_pelulus')
            ->leftJoin('kakitangan', 'kakitangan.id', '=', 'surat_pengesahan_pelulus.idpelulus')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->select('surat_pengesahan_pelulus.id', 'surat_pengesahan_pelulus.idpelulus', 'kakitangan.nama', 'jawatan.jawatan')
            ->orderBy('surat_pengesahan_pelulus.tarikh', 'desc')
            ->first();

        return view('kakitangan.surat.surat_pengesahan', compact('suratData', 'taraf', 'elaun', 'moto', 'pengesah'));
    }
}
