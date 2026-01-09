<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="utf-8">
    <title>e-Jawatan - Surat Akuan Perubatan</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
        }

        h3 {
            text-decoration: underline;
        }

        /* Page break class for printing */
        .breakhere {
            page-break-before: always;
        }

        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            table {
                width: 100%;
            }
        }

        .page-container {
            width: 900px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="page-container">

        <!-- Add a Print button for convenience -->
        <div class="no-print" style="text-align: right; padding: 10px;">
            <button onclick="window.print()" style="padding: 5px 10px; cursor: pointer;">Cetak Surat</button>
        </div>

        <!-- Section 1: Surat Pengesahan Diri -->
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td colspan="2"><br><br><br></td> <!-- Spacers as per legacy -->
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="75%" align="right">Rujukan Fail</td>
                            <td width="3%" align="center">:</td>
                            <td width="22%" nowrap="nowrap">CIAST 500-2/19/1</td>
                        </tr>
                        <tr>
                            <td align="right">Tarikh</td>
                            <td align="center">:</td>
                            <td>{{ date('d/m/Y') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>PENGARAH / PEGAWAI PERUBATAN <br />
                        <strong>{{ strtoupper($surat->hospital) }}</strong>
                    </p>
                    <p>Tuan,</p>
                    <p><strong><u>SURAT PENGESAHAN DIRI DAN PERAKUAN PEGAWAI</u></strong></p>
                    <p>Dengan ini disahkan bahawa penama dibawah adalah seorang pegawai Kerajaan di Pejabat ini.</p>
                </td>
            </tr>
            <tr>
                <td width="7%">&nbsp;</td>
                <td>Nama Pegawai: <strong>{{ $surat->namakakitangan }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>No. K/P: <strong>{{ $surat->mykad }}</strong>, Gred gaji: <strong>{{ $surat->gred }}</strong>, Gaji
                    Pokok RM <strong>{{ $surat->gaji_pokok }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>Jawatan: <strong>{{ $surat->jawatan }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>Kelayakan kelas wad: <strong>{{ $surat->kelayakan }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>Alamat Pejabat: <strong>Pusat Latihan Pengajar Dan Kemahiran Lanjutan (CIAST), Jalan Petani 19/1,
                        Seksyen 19, 40900 Shah Alam, Selangor</strong></td>
            </tr>
            <tr>
                <td colspan="2">2. Pegawai berkenaan / isteri / suami / ibubapa / anak pegawai berkenaan seperti
                    butir-butir dibawah memerlukan rawatan.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>Nama: <strong>{{ $surat->hubungan == 'DIRI SENDIRI' ? '' : $surat->pesakit }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>Perhubungan Keluarga:
                    <strong>{{ $surat->hubungan == 'DIRI SENDIRI' ? '' : $surat->hubungan }}</strong></td>
            </tr>
            <tr>
                <td colspan="2">3. Jabatan ini bersetuju akan memotong gaji dari pegawai bagi menjelaskan bil hospital
                    untuk rawatan berkenaan.</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>

            <tr>
                <td colspan="2"><strong><i>"MALAYSIA MADANI"</i></strong></td>
            </tr>
            <tr>
                <td colspan="2"><strong><i>"BERKHIDMAT UNTUK NEGARA"</i></strong></td>
            </tr>
            <tr>
                <td colspan="2"><strong><i>{{ $moto ? $moto->moto : '' }}</i></strong></td>
            </tr>

            <tr>
                <td colspan="2"><br><strong>Saya Yang Menjalankan Amanah,</strong></td>
            </tr>
            <tr>
                <td colspan="2"><br><br><br></td>
            </tr>
            <tr>
                <td colspan="2">(Tandatangan Ketua Jabatan)</td>
            </tr>

            <tr>
                <td colspan="2">
                    Nama : <strong>{{ $pelulus ? $pelulus->nama : '' }}</strong><br />
                    Jawatan : <strong>{{ $pelulus ? $pelulus->jawatan : '' }}</strong><br />
                    No. Telefon : 03-55438200
                </td>
            </tr>
        </table>

        <!-- Page Break for Second Section -->
        <div class="breakhere"></div>
        <br>

        <!-- Section 2: Perakuan Pegawai -->
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
            <tr>
                <td colspan="5">
                    <br><br><br>
                    <p><strong>PERAKUAN PEGAWAI MEMBENARKAN POTONGAN GAJI BAGI MENJELASKAN BAYARAN BIL HOSPITAL ATAS
                            RAWATAN YANG DITERIMA</strong></p>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    Saya <strong>{{ $surat->namakakitangan }}</strong> yang sekarang menerima gaji pokok sebanyak
                    <strong>RM {{ $surat->gaji_pokok }}</strong> di Kementerian / Jabatan <strong>Pusat Latihan Pengajar
                        Dan Kemahiran Lanjutan (CIAST)</strong> bertanggungjawab menjelaskan bayaran yang dituntut dan
                    dengan ini membenarkan dan memberi kuasa kepada Ketua Jabatan memotong gaji saya bagi menjelaskan
                    bayaran Hospital yang dikenakan kerana rawatan diri saya / ahli keluarga / ibu-bapa saya seperti
                    maklumat berikut:
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="4">
                    Nama Pegawai:<strong> {{ $surat->pesakit }}</strong><br />
                    No. Gaji: <strong>{{ $surat->no_gaji }}</strong><br />
                    Perhubungan keluarga: <strong>{{ $surat->hubungan }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5">(Tandatangan Pegawai)</td>
            </tr>
            <tr>
                <td colspan="5">No. K/Pengenalan: <strong>{{ $surat->mykad }}</strong></td>
            </tr>
            <tr>
                <td valign="top">Catatan: </td>
                <td colspan="4" valign="top">
                    <p>Butir-butir di atas hendaklah diisi dengan lengkap<br />
                        * Tempoh laku surat ini ialah tiga (3) bulan dari dari tarikh di atas.<br />
                        ** Potong mana-mana yang berkenaan</p>
                </td>
            </tr>
            <tr valign="top">
                <td>s.k</td>
                <td colspan="4">
                    <p>Unit Urusan Gaji (Alamat):</p>
                    <p>Unit Kewangan, Bahagian Khidmat Pengurusan<br />
                        Pusat Latihan Pengajar Dan Kemahiran Lanjutan (CIAST)<br />
                        Jalan Petani 19/1, Seksyen 19<br />
                        40900 Shah Alam Selangor </p>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="4" valign="top">Fail: Pegawai:</td>
            </tr>
        </table>
    </div>
</body>

</html>