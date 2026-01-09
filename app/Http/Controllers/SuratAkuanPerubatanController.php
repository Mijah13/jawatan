<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratAkuanPerubatan;
use App\Models\KelayakanWad;
use App\Models\Keluarga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function edit($id)
    {
        $idkakitangan = Auth::user()->id;
        $surat = SuratAkuanPerubatan::select('surat_akuan_perubatan.*', 'keluarga.nama as nama_pesakit', 'kelayakan_wad.kelayakan')
            ->leftJoin('keluarga', 'surat_akuan_perubatan.pesakit', '=', 'keluarga.id')
            ->leftJoin('kelayakan_wad', 'surat_akuan_perubatan.wad', '=', 'kelayakan_wad.id')
            ->where('surat_akuan_perubatan.id', $id)
            ->orderBy('surat_akuan_perubatan.kemaskini', 'desc')
            ->first();
        $pesakit = Keluarga::where('idkakitangan', $idkakitangan)->orderBy('nama', 'asc')->get();
        $wad = KelayakanWad::orderBy('gred', 'asc')->get();

        return view('kakitangan.surat_akuan.edit', compact('surat', 'pesakit', 'wad'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hospital' => 'string',
            'pesakit' => 'integer',
            'wad' => 'integer',
        ]);

        SuratAkuanPerubatan::where('id', $id)->update([
            'hospital' => $request->hospital,
            'pesakit' => $request->pesakit,
            'wad' => $request->wad,
            'kemaskini' => now(),
        ]);

        return redirect()->route('surat_akuan.index')->with('success', 'Surat akuan perubatan berjaya diemaskini.');
    }

    public function destroy($id)
    {
        SuratAkuanPerubatan::where('id', $id)->delete();

        return redirect()->route('surat_akuan.index')->with('success', 'Surat akuan perubatan berjaya dihapus.');
    }

    public function cetak($id)
    {
        $surat = DB::table('surat_akuan_perubatan')
            ->select(
                'surat_akuan_perubatan.id',
                'surat_akuan_perubatan.hospital',
                'kelayakan_wad.kelayakan',
                'kakitangan.nama as namakakitangan',
                'kakitangan.mykad',
                'gred.gred',
                'gaji_pokok.gaji_pokok',
                'gaji_pokok.no_gaji',
                'jawatan.jawatan',
                'keluarga.nama as pesakit',
                'hubungan.hubungan'
            )
            ->leftJoin('kelayakan_wad', 'kelayakan_wad.id', '=', 'surat_akuan_perubatan.wad')
            ->leftJoin('kakitangan', 'kakitangan.id', '=', 'surat_akuan_perubatan.idkakitangan')
            ->leftJoin('gred', 'gred.id', '=', 'kakitangan.gred')
            ->leftJoin('gaji_pokok', 'gaji_pokok.idkakitangan', '=', 'surat_akuan_perubatan.idkakitangan')
            ->leftJoin('jawatan', 'kakitangan.jawatan', '=', 'jawatan.id')
            ->leftJoin('keluarga', 'keluarga.id', '=', 'surat_akuan_perubatan.pesakit')
            ->leftJoin('hubungan', 'hubungan.id', '=', 'keluarga.hubungan')
            ->where('surat_akuan_perubatan.id', $id)
            ->first();

        if (!$surat) {
            abort(404, 'Surat not found');
        }

        // Moto
        $moto = DB::table('moto_hari_pekerja')->orderBy('tahun', 'asc')->first();

        // Pelulus
        $pelulus = DB::table('surat_akuan_perubatan_pelulus')
            ->select('kakitangan.nama', 'jawatan.jawatan')
            ->leftJoin('kakitangan', 'kakitangan.id', '=', 'surat_akuan_perubatan_pelulus.idkakitangan')
            ->leftJoin('jawatan', 'jawatan.id', '=', 'kakitangan.jawatan')
            ->orderBy('surat_akuan_perubatan_pelulus.tarikh', 'desc')
            ->first();

        return view('kakitangan.surat_akuan.surat_akuan', compact('surat', 'moto', 'pelulus'));
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
