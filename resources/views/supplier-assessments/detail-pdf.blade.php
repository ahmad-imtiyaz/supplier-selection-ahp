<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Perhitungan AHP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            padding: 25px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4F46E5;
        }
        .header h1 {
            font-size: 24px;
            color: #1F2937;
            margin-bottom: 8px;
        }
        .header p {
            font-size: 12px;
            color: #6B7280;
        }

        .section {
            margin: 20px 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 10px;
            padding: 8px;
            background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
            border-left: 4px solid #4F46E5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border: 1px solid #E5E7EB;
        }
        th {
            background: #4F46E5;
            color: white;
            font-weight: 600;
        }
        tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        .criteria-weights {
            display: table;
            width: 100%;
            margin: 15px 0;
        }
        .criteria-item {
            display: table-row;
        }
        .criteria-item > div {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #E5E7EB;
        }
        .criteria-name {
            font-weight: 600;
            color: #1F2937;
        }
        .criteria-weight {
            text-align: right;
            color: #4F46E5;
            font-weight: bold;
        }

        .formula {
            background: #F9FAFB;
            padding: 12px;
            border-radius: 6px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 10px;
            border-left: 3px solid #4F46E5;
        }

        .note {
            background: #FEF3C7;
            padding: 10px;
            border-radius: 6px;
            border-left: 3px solid #F59E0B;
            margin: 10px 0;
            font-size: 10px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 9px;
            color: #9CA3AF;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>DETAIL PERHITUNGAN AHP</h1>
        <p>Analytical Hierarchy Process - Pemilihan Supplier Terbaik</p>
        <p style="margin-top: 5px; font-size: 10px;">Tanggal: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <!-- 1. Kriteria & Bobot -->
    <div class="section">
        <div class="section-title">1. Kriteria Penilaian & Bobot AHP</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">Kode</th>
                    <th style="width: 50%;">Nama Kriteria</th>
                    <th style="width: 20%;">Bobot (Weight)</th>
                    <th style="width: 20%;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($criterias as $criteria)
                <tr>
                    <td><strong>{{ $criteria->code }}</strong></td>
                    <td style="text-align: left;">{{ $criteria->name }}</td>
                    <td>{{ number_format($criteria->weight, 4) }}</td>
                    <td><strong>{{ number_format($criteria->weight * 100, 2) }}%</strong></td>
                </tr>
                @endforeach
                <tr style="background: #EEF2FF; font-weight: bold;">
                    <td colspan="2">TOTAL</td>
                    <td>{{ number_format($criterias->sum('weight'), 4) }}</td>
                    <td>100%</td>
                </tr>
            </tbody>
        </table>

        <div class="note">
            <strong>Catatan:</strong> Bobot kriteria diperoleh dari perhitungan matriks perbandingan berpasangan menggunakan metode AHP dengan Consistency Ratio (CR) < 0.1
        </div>
    </div>

    <!-- 2. Daftar Supplier -->
    <div class="section">
        <div class="section-title">2. Daftar Supplier yang Dinilai</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th style="width: 20%;">Kode</th>
                    <th style="width: 70%;">Nama Supplier</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rankings as $index => $ranking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $ranking['supplier']->code }}</strong></td>
                    <td style="text-align: left;">{{ $ranking['supplier']->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- 3. Formula Perhitungan -->
    <div class="section">
        <div class="section-title">3. Formula Perhitungan</div>
        
        <p style="margin-bottom: 10px;"><strong>Langkah-langkah perhitungan:</strong></p>
        
        <div style="padding-left: 15px;">
            <p><strong>a. Normalisasi Nilai:</strong></p>
            <div class="formula">
                Normalized Score = Raw Score / 100
            </div>
            
            <p style="margin-top: 15px;"><strong>b. Weighted Score (Nilai Tertimbang):</strong></p>
            <div class="formula">
                Weighted Score = Normalized Score × Criteria Weight
            </div>
            
            <p style="margin-top: 15px;"><strong>c. Total Score (Skor Akhir):</strong></p>
            <div class="formula">
                Total Score = Σ (Weighted Score untuk setiap kriteria)<br>
                Total Score = (C1_weighted + C2_weighted + ... + Cn_weighted)
            </div>
            
            <p style="margin-top: 15px;"><strong>d. Persentase:</strong></p>
            <div class="formula">
                Percentage = Total Score × 100%
            </div>
        </div>
    </div>

    <!-- 4. Detail Perhitungan per Supplier -->
    <div class="section">
        <div class="section-title">4. Detail Perhitungan per Supplier</div>
        
        @foreach($rankings as $ranking)
        <div style="margin: 20px 0; page-break-inside: avoid;">
            <h3 style="background: #F3F4F6; padding: 8px; font-size: 13px; border-left: 4px solid #6366F1;">
                Rank #{{ $ranking['rank'] }} - {{ $ranking['supplier']->name }} ({{ $ranking['supplier']->code }})
            </h3>
            
            <table>
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th>Bobot</th>
                        <th>Nilai Raw</th>
                        <th>Normalized</th>
                        <th>Weighted Score</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criterias as $criteria)
                        @php $criteriaScore = $ranking['criteria_scores'][$criteria->id] ?? null; @endphp
                        <tr>
                            <td style="text-align: left;"><strong>{{ $criteria->code }}</strong> - {{ $criteria->name }}</td>
                            <td>{{ number_format($criteria->weight, 4) }}</td>
                            @if($criteriaScore)
                                <td>{{ number_format($criteriaScore['raw_score'], 2) }}</td>
                                <td>{{ number_format($criteriaScore['normalized_score'], 4) }}</td>
                                <td style="color: #4F46E5; font-weight: bold;">{{ number_format($criteriaScore['weighted_score'], 4) }}</td>
                            @else
                                <td colspan="3" style="color: #DC2626;">Data tidak tersedia</td>
                            @endif
                        </tr>
                    @endforeach
                    <tr style="background: #EEF2FF; font-weight: bold;">
                        <td colspan="4" style="text-align: right;">TOTAL SCORE:</td>
                        <td style="color: #059669; font-size: 12px;">{{ number_format($ranking['total_score'], 4) }}</td>
                    </tr>
                    <tr style="background: #D1FAE5; font-weight: bold;">
                        <td colspan="4" style="text-align: right;">PERSENTASE:</td>
                        <td style="color: #059669; font-size: 12px;">{{ number_format($ranking['percentage'], 2) }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endforeach
    </div>

    <!-- 5. Kesimpulan -->
    <div class="section">
        <div class="section-title">5. Kesimpulan & Rekomendasi</div>
        
        <div style="padding: 15px; background: #F0FDF4; border-radius: 6px; border-left: 4px solid #10B981;">
            <p style="font-size: 12px; margin-bottom: 8px;"><strong>Hasil Ranking:</strong></p>
            <ol style="padding-left: 20px;">
                @foreach(array_slice($rankings, 0, 3) as $ranking)
                <li style="margin: 5px 0;">
                    <strong>{{ $ranking['supplier']->name }}
                        :</strong>
{{ number_format($ranking['percentage'], 2) }}%
(Skor: {{ number_format($ranking['total_score'], 4) }})
</li>
@endforeach
</ol>
        <p style="margin-top: 15px; font-size: 11px;">
            <strong>Rekomendasi:</strong> Berdasarkan perhitungan AHP, supplier 
            <strong style="color: #059669;">{{ $rankings[0]['supplier']->name }}</strong> 
            memiliki skor tertinggi sebesar <strong>{{ number_format($rankings[0]['percentage'], 2) }}%</strong> 
            dan direkomendasikan sebagai pilihan terbaik.
        </p>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p><strong>Metode AHP (Analytical Hierarchy Process)</strong></p>
    <p>Dokumen ini dihasilkan secara otomatis oleh Sistem Pendukung Keputusan</p>
    <p>© {{ date('Y') }} - All Rights Reserved</p>
</div>