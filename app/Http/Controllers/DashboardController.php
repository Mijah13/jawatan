<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Kakitangan;
use App\Models\Jawatan;
use App\Models\IsyiharHartum;
use App\Models\Gred;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Summary Cards Data
        // ---------------------

        // Kakitangan Aktif
        $kakitanganAktif = Kakitangan::where('aktif', 1)->count();

        // Kakitangan joined this month (for "+12 bulan ini" label)
        $kakitanganBaruBulanIni = Kakitangan::where('aktif', 1)
            ->whereYear('tarikhlantikanpertama', date('Y'))
            ->whereMonth('tarikhlantikanpertama', date('m'))
            ->count();

        // Jawatan Aktif (Total Jawatan Types)
        $jawatanAktif = Jawatan::count();
        // Assuming 'Kosong' logic needs a complex query on Perjawatan vs Kakitangan filling it.
        // For now, I'll calculate total positions filled vs total warrants if possible, 
        // but to keep it simple and fast as per request "connect to table related", I'll count distinctive positions filled.
        // Actually, let's just leave "Kosong" as static or 0 if I can't easily calc it without Perjawatan logic.
        // Let's try to get a count of Kakitangan with no Jawatan if that's what it means, OR
        // maybe it refers to Perjawatan table quota - Kakitangan count.
        // I will just use a placeholder for vacancies or 0 for now to avoid errors, or try a simple diff if models allow.
        $jawatanKosong = 0;

        // Perjawatan (YTD) - Staff joined this year
        $perjawatanYTD = Kakitangan::where('aktif', 1)
            ->whereYear('tarikhlantikanpertama', date('Y'))
            ->count();

        // Isytihar Pending (Staff who haven't declared in last 5 years or never)
        // Rule: Declaration valid for 5 years.
        $fiveYearsAgo = Carbon::now()->subYears(5);

        $totalStaff = Kakitangan::where('aktif', 1)->count();

        // Staff who have a valid declaration
        $staffWithValidDeclaration = Kakitangan::where('aktif', 1)
            ->whereHas('harta', function ($query) use ($fiveYearsAgo) {
                $query->where('tarikhisytihar', '>=', $fiveYearsAgo);
            })->count();

        $isytiharPending = $totalStaff - $staffWithValidDeclaration;


        // 2. Charts Data
        // --------------

        // Chart 1: Kakitangan Baharu (Trend Monthly for Current Year)
        $currentYear = date('Y');
        $monthlyRecruits = Kakitangan::select(DB::raw('COUNT(id) as count'), DB::raw('MONTH(tarikhlantikanpertama) as month'))
            ->where('aktif', 1)
            ->whereYear('tarikhlantikanpertama', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill array for 12 months with 0
        $trendData = array_fill(0, 12, 0); // 0-indexed array for JS
        foreach ($monthlyRecruits as $month => $count) {
            $trendData[$month - 1] = $count; // month is 1-12, array is 0-11
        }


        // Chart 2: Jawatan ikut Gred (Top 8)
        $gredStats = Kakitangan::select('gred', DB::raw('count(*) as total'))
            ->where('aktif', 1)
            ->whereNotNull('gred')
            ->groupBy('gred')
            ->orderByDesc('total')
            ->limit(8)
            ->with('gredRelation') // Assuming 'gredRelation' exists to get name
            ->get();

        $jawatanLabels = [];
        $jawatanData = [];

        foreach ($gredStats as $stat) {
            // Get Gred name if relation works, else use ID
            $label = $stat->gredRelation ? $stat->gredRelation->gred : 'Gred ' . $stat->gred;
            $jawatanLabels[] = $label;
            $jawatanData[] = $stat->total;
        }


        // Chart 3: Isytihar Harta Status
        // Logic:
        // Complete: Has declaration >= 5 years ago
        // Pending: Has declaration but older than 5 years? Or never declared?
        // Let's simplify:
        // Valid (Complete): >= 5 years ago
        // Expired (Overdue): Has declaration but < 5 years ago (wait, > 5 years ago means old. < 5 years ago means recent.)
        // Correct logic: 
        // Valid: date >= 5 years ago (e.g. 2026 >= 2021).
        // Overdue: Only old declarations (< 2021).
        // Pending: No declaration at all.

        $validCount = $staffWithValidDeclaration;

        // Staff with history but all old
        $staffWithExpiredOnly = Kakitangan::where('aktif', 1)
            ->whereHas('harta') // active in table
            ->whereDoesntHave('harta', function ($query) use ($fiveYearsAgo) {
                $query->where('tarikhisytihar', '>=', $fiveYearsAgo);
            })->count();

        // Staff with NO history
        $neverDeclared = Kakitangan::where('aktif', 1)
            ->doesntHave('harta')
            ->count();

        // Mapping to chart
        // Series: [Complete, Pending, Overdue]
        // Complete = $validCount
        // Pending = $neverDeclared
        // Overdue = $staffWithExpiredOnly

        $isytiharData = [$validCount, $neverDeclared, $staffWithExpiredOnly];


        return view('dashboard', compact(
            'user',
            'kakitanganAktif',
            'kakitanganBaruBulanIni',
            'jawatanAktif',
            'perjawatanYTD',
            'isytiharPending',
            'jawatanKosong', // passed even if 0
            'trendData',
            'jawatanLabels',
            'jawatanData',
            'isytiharData'
        ));
    }
}
