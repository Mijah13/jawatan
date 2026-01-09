<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <p class="text-lg font-semibold">Selamat datang, {{ $user->nama }}!</p>
                        <p class="text-sm text-gray-600">No. MyKad: <strong>{{ $user->mykad }}</strong></p>
                    </div>
                    {{-- Header Section --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Analytics</p>
                            <h3 class="text-2xl font-bold text-gray-800">Ringkasan & Trend</h3>
                            <p class="text-sm text-gray-500">Preview UI sahaja (dummy data).</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                            <select
                                class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option>2025</option>
                                <option>2024</option>
                            </select>
                            <select
                                class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option>Semua Unit</option>
                            </select>
                            <select
                                class="rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                <option>Semua Status</option>
                            </select>
                            <button
                                class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Tapis</button>
                            <button
                                class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-50">Export</button>
                        </div>
                    </div>

                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Card 1 -->
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 shadow-sm">
                            <p class="text-sm font-bold text-gray-600 mb-1">Kakitangan Aktif</p>
                            <p class="text-3xl font-extrabold text-gray-900">412</p>
                            <p class="text-xs text-green-600 font-medium mt-1">+12 bulan ini</p>
                        </div>
                        <!-- Card 2 -->
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 shadow-sm">
                            <p class="text-sm font-bold text-gray-600 mb-1">Jawatan Aktif</p>
                            <p class="text-3xl font-extrabold text-gray-900">128</p>
                            <p class="text-xs text-gray-500 mt-1">Kosong: 9</p>
                        </div>
                        <!-- Card 3 -->
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 shadow-sm">
                            <p class="text-sm font-bold text-gray-600 mb-1">Perjawatan (YTD)</p>
                            <p class="text-3xl font-extrabold text-gray-900">56</p>
                            <p class="text-xs text-gray-500 mt-1">8 baharu</p>
                        </div>
                        <!-- Card 4 -->
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 shadow-sm">
                            <p class="text-sm font-bold text-gray-600 mb-1">Isytihar Pending</p>
                            <p class="text-3xl font-extrabold text-gray-900">17</p>
                            <p class="text-xs text-red-600 font-medium mt-1">Overdue: 4</p>
                        </div>
                    </div>

                    {{-- Charts Section --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Chart 1: Line Chart -->
                        <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-200">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Trend</p>
                                    <h4 class="text-lg font-bold text-gray-800">Kakitangan baharu (Bulanan)</h4>
                                </div>
                                <span
                                    class="text-xs font-semibold text-gray-500 bg-white px-2 py-1 rounded border">Line</span>
                            </div>
                            <div id="kakitanganChart" style="min-height: 300px;"></div>
                        </div>

                        <!-- Chart 2: Bar Chart -->
                        <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-200">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Breakdown</p>
                                    <h4 class="text-lg font-bold text-gray-800">Jawatan ikut gred (Top 8)</h4>
                                </div>
                                <span
                                    class="text-xs font-semibold text-gray-500 bg-white px-2 py-1 rounded border">Bar</span>
                            </div>
                            <div id="jawatanChart" style="min-height: 300px;"></div>
                        </div>

                        <!-- Chart 3: Doughnut Chart -->
                        <div class="bg-gray-50/50 p-6 rounded-xl border border-gray-200">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status</p>
                                    <h4 class="text-lg font-bold text-gray-800">Isytihar Harta</h4>
                                </div>
                                <span
                                    class="text-xs font-semibold text-gray-500 bg-white px-2 py-1 rounded border">Doughnut</span>
                            </div>
                            <div id="isytiharChart" style="min-height: 300px;"></div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ApexCharts CDN --}}
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

            {{-- Chart Scripts --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {

                    // Chart 1: Kakitangan Baharu (Line Chart)
                    var optionsLine = {
                        series: [{
                            name: "Kakitangan Baharu",
                            data: [3, 5, 4, 6, 2, 8, 6, 4, 7, 3, 2, 5]
                        }],
                        chart: {
                            height: 320,
                            type: 'line',
                            zoom: { enabled: false },
                            toolbar: { show: false },
                            fontFamily: 'Figtree, sans-serif'
                        },
                        dataLabels: { enabled: false },
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                            colors: ['#3b82f6'] // Blue-500
                        },
                        grid: {
                            row: {
                                colors: ['transparent', 'transparent'],
                                opacity: 0.5
                            },
                            borderColor: '#f3f4f6'
                        },
                        xaxis: {
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            min: 0,
                            max: 10,
                            tickAmount: 5
                        }
                    };

                    var chartLine = new ApexCharts(document.querySelector("#kakitanganChart"), optionsLine);
                    chartLine.render();


                    // Chart 2: Jawatan Ikut Gred (Bar Chart)
                    var optionsBar = {
                        series: [{
                            name: 'Jumlah',
                            data: [18, 14, 22, 9, 7, 11, 16, 5]
                        }],
                        chart: {
                            height: 320,
                            type: 'bar',
                            toolbar: { show: false },
                            fontFamily: 'Figtree, sans-serif'
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                columnWidth: '60%',
                                distributed: true, // Cool effect for different colors if wanted, or keep single
                            }
                        },
                        dataLabels: { enabled: false },
                        legend: { show: false },
                        colors: ['#60a5fa', '#60a5fa', '#60a5fa', '#60a5fa', '#60a5fa', '#60a5fa', '#60a5fa', '#60a5fa'], // Blue-400
                        xaxis: {
                            categories: ['N19', 'N22', 'N29', 'N32', 'N41', 'W29', 'W41', 'J29'],
                            labels: {
                                style: { fontSize: '12px' }
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        grid: {
                            borderColor: '#f3f4f6'
                        }
                    };

                    var chartBar = new ApexCharts(document.querySelector("#jawatanChart"), optionsBar);
                    chartBar.render();

                    // Chart 3: Isytihar Harta (Doughnut Chart)
                    var optionsDoughnut = {
                        series: [75, 15, 5], // Complete, Pending, Overdue
                        labels: ['Complete', 'Pending', 'Overdue'],
                        chart: {
                            type: 'donut',
                            height: 320,
                            fontFamily: 'Figtree, sans-serif'
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '75%',
                                    labels: {
                                        show: false
                                    }
                                }
                            }
                        },
                        dataLabels: { enabled: false },
                        colors: ['#0ea5e9', '#ef4444', '#f59e0b'], // Sky-500, Red-500, Amber-500
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            fontSize: '14px',
                            markers: { radius: 12 },
                            itemMargin: { horizontal: 10 }
                        },
                        stroke: { show: false },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return val + " orang";
                                }
                            }
                        }
                    };

                    var chartDoughnut = new ApexCharts(document.querySelector("#isytiharChart"), optionsDoughnut);
                    chartDoughnut.render();
                });
            </script>
        </div>
    </div>
</x-app-layout>