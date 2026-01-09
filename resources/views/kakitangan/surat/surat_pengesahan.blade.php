<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="utf-8">
    <title>e-Jawatan - Surat Pengesahan</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
        }

        h3 {
            text-decoration: underline;
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

        <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                </td>
                <td align="right" valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td width="56%" rowspan="2">&nbsp;</td>
                <td width="17%" align="right" valign="top">Rujukan kami</td>
                <td width="1%" valign="top">:</td>
                <td width="26%" valign="top">{{ $suratData->fail }}</td>
            </tr>
            <tr>
                <td align="right" valign="top">Tarikh</td>
                <td width="1%" valign="top">:</td>
                <td width="26%" valign="top">{{ $suratData->tarikh_sah }}</td>
            </tr>
            <tr>
                <td>
                    <strong>
                        {{ $suratData->kepada }}<br />
                        {{ $suratData->alamat1 }} {{ $suratData->alamat2 }}<br />
                        {{ $suratData->poskod }} {{ $suratData->bandar }}<br />
                        {{ $suratData->negeri }}
                    </strong>
                </td>
                <td align="right" valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td>(Nama &amp; Alamat Agensi)</td>
                <td align="right" valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
                <td valign="top">&nbsp;</td>
            </tr>
        </table>

        <p>Tuan,</p>
        <p><strong>PER : SURAT PENGESAHAN DAN BUTIR-BUTIR PERKHIDMATAN</strong></p>
        <p>Dengan ini disahkan bahawa penama di bawah adalah kakitangan Pusat Latihan Pengajar dan Kemahiran Lanjutan
            (CIAST), Jabatan Pembangunan Kemahiran, Kementerian Sumber Manusia.</p>
        <p>2. Berikut adalah butir-butir perkhidmatan beliau:-</p>

        <table width="800" border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td width="9">&nbsp;</td>
                <td width="186" valign="top">Nama</td>
                <td width="12" valign="top">-</td>
                <td colspan="2" valign="top"><strong>{{ $suratData->kakitangan }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td valign="top">No. K/P</td>
                <td valign="top">-</td>
                <td colspan="2" valign="top"><strong>{{ $suratData->mykad }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td valign="top">No. Gaji</td>
                <td valign="top">-</td>
                <td colspan="2" valign="top"><strong>{{ $suratData->no_gaji }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td valign="top">Tarikh Mula Berkhidmat</td>
                <td valign="top">-</td>
                <td colspan="2" valign="top"><strong>{{ $suratData->tarikhlantikanpertama }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td valign="top">Tarikh Disahkan Jawatan</td>
                <td valign="top">-</td>
                <td colspan="2" valign="top"><strong>{{ $suratData->tarikhpengesahanjawatan }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td valign="top">Jawatan Sekarang</td>
                <td valign="top">-</td>
                <td colspan="2" valign="top"><strong>{{ $suratData->jwt }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td valign="top">Taraf Perkhidmatan</td>
                <td valign="top">-</td>
                <td colspan="2" valign="top"><strong>{{ $taraf }}</strong></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td valign="top">Gaji Pokok</td>
                <td valign="top">-</td>
                <td colspan="2" valign="top"><strong>RM {{ number_format($suratData->gaji_pokok, 2, '.', '') }}</strong>
                </td>
            </tr>

            @php $jumlahelaun = 0; @endphp
            @foreach($elaun as $el)
                <tr>
                    <td>&nbsp;</td>
                    <td valign="top">{{ $el->nama }}</td>
                    <td valign="top">-</td>
                    <td colspan="2" valign="top">
                        <strong>RM {{ number_format($el->nilai, 2, '.', '') }}</strong>
                        @php $jumlahelaun += $el->nilai; @endphp
                    </td>
                </tr>
            @endforeach

            <tr>
                <td>&nbsp;</td>
                <td align="right" valign="top">JUMLAH</td>
                <td valign="top">-</td>
                <td colspan="2" valign="top">
                    <strong>RM {{ number_format($jumlahelaun + $suratData->gaji_pokok, 2, '.', '') }}</strong>
                </td>
            </tr>
        </table>

        <p>Sekian, terima kasih.</p>
        <p>
            <strong><i>"MALAYSIA MADANI"</i></strong><br>
            <strong><i>"BERKHIDMAT UNTUK NEGARA"</i></strong><br>
            @if($moto)
                <strong><i>{{ $moto->moto }}</i></strong>
            @endif
        </p>

        <p><strong>Saya yang menjalankan amanah,</strong></p>
        <p>&nbsp;</p>

        @if($pengesah && $pengesah->idpelulus != $suratData->idkakitangan)
            <p>
                <strong>({{ $pengesah->nama }})</strong><br />
                {{ $pengesah->jawatan }}
            </p>
        @else
            <!-- Fallback if no specific approver or self-approval issue? Legacy handled this else block by closing </p> which is weird HTML structure. 
                     It seems legacy `else echo '</p>';` meant the name/jawatan block is skipped.
                     Then "b.p. Pengarah..." follows.
                -->
        @endif

        <p>
            b.p. Pengarah<br />
            CIAST<br />
            Shah Alam, Selangor
        </p>

    </div>
</body>

</html>