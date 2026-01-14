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

    public function senarai(Request $request)
    {
        // Get distinct years from latihan table
        $tahun_list = Latihan::selectRaw('DISTINCT YEAR(mula) as tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Get selected year from request, default to latest year
        $selected_tahun = $request->input('tahun', $tahun_list->first());

        // Fetch training records for selected year
        $rows = [];
        if ($selected_tahun) {
            $rows = Latihan::select(
                'latihan.id',
                'latihan.tajuk',
                'latihan.mula',
                'latihan.tamat',
                'latihan.tempoh',
                'latihan.tempat',
                'latihan.penganjur',
                'kategori_latihan.kategori as kategori_latihan',
                'jenis_latihan.jenis as jenis_latihan',
                'kakitangan.nama',
                'organisasi.kod'
            )
                ->leftJoin('kategori_latihan', 'kategori_latihan.id', '=', 'latihan.kategori')
                ->leftJoin('jenis_latihan', 'jenis_latihan.id', '=', 'latihan.jenis')
                ->leftJoin('kakitangan', 'kakitangan.id', '=', 'latihan.idkakitangan')
                ->leftJoin('organisasi', 'organisasi.id', '=', 'kakitangan.penempatanoperasi')
                ->whereRaw('YEAR(latihan.mula) = ?', [$selected_tahun])
                ->orderBy('kakitangan.nama', 'asc')
                ->get();
        }

        // SELECT latihan.id, latihan.tajuk, latihan.mula, latihan.tamat, latihan.tempoh, latihan.tempat, kategori_latihan.kategori, jenis_latihan.jenis, kakitangan.nama, organisasi.kod, latihan.penganjur FROM latihan LEFT JOIN kategori_latihan ON kategori_latihan.id = latihan.kategori LEFT JOIN jenis_latihan ON jenis_latihan.id = latihan.jenis LEFT JOIN kakitangan ON kakitangan.id = latihan.idkakitangan LEFT JOIN organisasi ON organisasi.id = kakitangan.penempatanoperasi WHERE YEAR(latihan.mula) = %s ORDER BY kakitangan.nama", GetSQLValueString($x_Recordset1, "date"));

        return view('latihan.senarai', compact('tahun_list', 'selected_tahun', 'rows'));
    }
    public function laporan(Request $request)
    {
        // Get distinct years from latihan table
        $tahun_list = Latihan::selectRaw('DISTINCT YEAR(mula) as tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Get selected year from request, default to latest year
        $selected_tahun = $request->input('tahun', $tahun_list->first());

        // Fetch training records for selected year
        $rows = [];
        if ($selected_tahun) {
            DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

            $rows = DB::table('latihan_jumlah_hari_v2')
                ->select(
                    'latihan_jumlah_hari_v2.tahun',
                    'latihan_jumlah_hari_v2.nama',
                    'latihan_jumlah_hari_v2.mykad',
                    'latihan_jumlah_hari_v2.jumlah_hari',
                    'organisasi.program',
                    'organisasi.kod'
                )
                ->leftJoin('kakitangan', 'kakitangan.mykad', '=', 'latihan_jumlah_hari_v2.mykad')
                ->leftJoin('organisasi', 'organisasi.id', '=', 'kakitangan.penempatanoperasi')
                ->where('latihan_jumlah_hari_v2.tahun', $selected_tahun)
                ->orderBy('latihan_jumlah_hari_v2.nama', 'asc')
                ->get();
        }

        // SELECT latihan.id, latihan.tajuk, latihan.mula, latihan.tamat, latihan.tempoh, latihan.tempat, kategori_latihan.kategori, jenis_latihan.jenis, kakitangan.nama, organisasi.kod, latihan.penganjur FROM latihan LEFT JOIN kategori_latihan ON kategori_latihan.id = latihan.kategori LEFT JOIN jenis_latihan ON jenis_latihan.id = latihan.jenis LEFT JOIN kakitangan ON kakitangan.id = latihan.idkakitangan LEFT JOIN organisasi ON organisasi.id = kakitangan.penempatanoperasi WHERE YEAR(latihan.mula) = %s ORDER BY kakitangan.nama", GetSQLValueString($x_Recordset1, "date"));

        return view('latihan.laporan', compact('tahun_list', 'selected_tahun', 'rows'));
    }

    public function edit($id)
    {
        $kategori = KategoriLatihan::orderBy('kategori', 'asc')->get();
        $jenis = JenisLatihan::orderBy('jenis', 'asc')->get();

        $latihan = Latihan::findOrFail($id);

        return view('kakitangan.latihan.edit', compact('kategori', 'jenis', 'latihan'));

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tajuk' => 'string',
            'kategori' => 'integer',
            'jenis' => 'integer',
            'mula' => 'date',
            'tamat' => 'date|after_or_equal:mula',
            'tempoh' => 'integer',
            'tempat' => 'string',
            'penganjur' => 'string',
        ]);

        $latihan = Latihan::findOrFail($id);

        $latihan->update([
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

        return redirect()->route('latihan.index')->with('success', 'Latihan berjaya diubah.');
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
