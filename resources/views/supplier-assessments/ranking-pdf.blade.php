<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ranking Supplier</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #4F46E5;
        }
        .header h1 {
            font-size: 22px;
            color: #1F2937;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            color: #6B7280;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 9px;
            color: #6B7280;
        }
        
        /* Podium Section */
        .podium {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            text-align: center;
        }
        .podium-item {
            flex: 1;
            padding: 15px;
            margin: 0 5px;
            border-radius: 8px;
        }
        .podium-item.gold {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border: 2px solid #F59E0B;
        }
        .podium-item.silver {
            background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%);
            border: 2px solid #9CA3AF;
        }
        .podium-item.bronze {
            background: linear-gradient(135deg, #FED7AA 0%, #FDBA74 100%);
            border: 2px solid #F97316;
        }
        .podium-item .medal {
            font-size: 32px;
            margin-bottom: 5px;
        }
        .podium-item .name {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .podium-item .score {
            font-size: 18px;
            font-weight: bold;
            color: #1F2937;
        }
        .podium-item .rank {
            font-size: 9px;
            color: #6B7280;
            margin-top: 3px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
        }
        thead {
            background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
            color: white;
        }
        th {
            padding: 8px 4px;
            text-align: center;
            font-weight: 600;
            font-size: 8px;
            border: 1px solid #E5E7EB;
        }
        td {
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #E5E7EB;
        }
        tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        tbody tr:hover {
            background-color: #F3F4F6;
        }
        .rank-cell {
            font-weight: bold;
            font-size: 10px;
        }
        .rank-1 { color: #F59E0B; }
        .rank-2 { color: #6B7280; }
        .rank-3 { color: #F97316; }
        .supplier-name {
            text-align: left;
            font-weight: 600;
        }
        .supplier-code {
            color: #6B7280;
            font-size: 8px;
        }
        .score-raw {
            color: #1F2937;
        }
        .score-weighted {
            color: #4F46E5;
            font-weight: 600;
        }
        .total-score {
            font-weight: bold;
            color: #059669;
            font-size: 10px;
        }

        /* Legend */
        .legend {
            margin-top: 15px;
            padding: 10px;
            background: #F9FAFB;
            border-radius: 6px;
            font-size: 8px;
        }
        .legend h3 {
            font-size: 10px;
            margin-bottom: 5px;
            color: #1F2937;
        }
        .legend p {
            margin: 3px 0;
            color: #6B7280;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 8px;
            color: #9CA3AF;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>LAPORAN RANKING SUPPLIER</h1>
        <p>Sistem Pendukung Keputusan dengan Metode AHP</p>
    </div>

    <!-- Meta Info -->
    <div class="meta-info">
        <div>
            <strong>Tanggal Cetak:</strong> {{ date('d F Y, H:i') }} WIB
        </div>
        <div>
            <strong>Total Supplier:</strong> {{ count($rankings) }}
        </div>
        <div>
            <strong>Total Kriteria:</strong> {{ count($criterias) }}
        </div>
    </div>

   <!-- Podium (Top 3) -->
@if(count($rankings) >= 3)
<div class="podium">
    <!-- Rank 2 -->
    <div class="podium-item silver">
        <div style="width: 70px; height: 70px; margin: 0 auto 15px; background: linear-gradient(135deg, #E5E7EB 0%, #D1D5DB 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid #9CA3AF; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <span style="font-size: 36px; font-weight: bold; color: #374151;">#2</span>
        </div>
        <div class="name">{{ $rankings[1]['supplier']->name }}</div>
        <div class="score">{{ number_format($rankings[1]['percentage'], 2) }}%</div>
        <div class="rank">Peringkat Kedua</div>
    </div>

    <!-- Rank 1 -->
    <div class="podium-item gold">
        <div style="width: 80px; height: 80px; margin: 0 auto 15px; background: linear-gradient(135deg, #FDE68A 0%, #FCD34D 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 5px solid #F59E0B; box-shadow: 0 6px 8px rgba(0,0,0,0.15);">
            <span style="font-size: 42px; font-weight: bold; color: #92400E;">#1</span>
        </div>
        <div class="name">{{ $rankings[0]['supplier']->name }}</div>
        <div class="score">{{ number_format($rankings[0]['percentage'], 2) }}%</div>
        <div class="rank">JUARA I</div>
    </div>

    <!-- Rank 3 -->
    <div class="podium-item bronze">
        <div style="width: 70px; height: 70px; margin: 0 auto 15px; background: linear-gradient(135deg, #FED7AA 0%, #FDBA74 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid #F97316; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <span style="font-size: 36px; font-weight: bold; color: #7C2D12;">#3</span>
        </div>
        <div class="name">{{ $rankings[2]['supplier']->name }}</div>
        <div class="score">{{ number_format($rankings[2]['percentage'], 2) }}%</div>
        <div class="rank">Peringkat Ketiga</div>
    </div>
</div>
@endif

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">Rank</th>
                <th rowspan="2" style="width: 20%;">Supplier</th>

                @foreach($criterias as $criteria)
                <th colspan="2">
                    {{ $criteria->code }}<br>
                    <span style="font-size: 7px; font-weight: normal;">
                        (W: {{ number_format($criteria->weight, 4) }})
                    </span>
                </th>
                @endforeach

                <th rowspan="2" style="width: 10%;">Total</th>
            </tr>
            <tr>
                @foreach($criterias as $criteria)
                <th style="font-size: 7px;">Raw</th>
                <th style="font-size: 7px;">Weighted</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach($rankings as $ranking)
            <tr>
                <!-- Rank -->
                <td class="rank-cell rank-{{ min($ranking['rank'], 3) }}">
                    {{ $ranking['rank'] }}
                </td>

                <!-- Supplier -->
                <td class="supplier-name">
                    {{ $ranking['supplier']->name }}
                    <div class="supplier-code">{{ $ranking['supplier']->code }}</div>
                </td>

                <!-- Criteria Scores -->
                @foreach($criterias as $criteria)
                    @php $criteriaScore = $ranking['criteria_scores'][$criteria->id] ?? null; @endphp
                    @if($criteriaScore)
                        <td class="score-raw">{{ number_format($criteriaScore['raw_score'], 1) }}</td>
                        <td class="score-weighted">{{ number_format($criteriaScore['weighted_score'], 4) }}</td>
                    @else
                        <td>-</td>
                        <td>-</td>
                    @endif
                @endforeach

                <!-- Total -->
                <td class="total-score">
                    {{ number_format($ranking['percentage'], 2) }}%
                    <div style="font-size: 7px; color: #6B7280;">
                        ({{ number_format($ranking['total_score'], 4) }})
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Legend -->
    <div class="legend">
        <h3>Keterangan:</h3>
        <p><strong>Raw:</strong> Nilai mentah hasil penilaian (0-100)</p>
        <p><strong>Weighted:</strong> Nilai setelah dikali bobot kriteria</p>
        <p><strong>Total:</strong> Jumlah semua nilai tertimbang, ditampilkan dalam persentase</p>
        <p><strong>W (Weight):</strong> Bobot kriteria hasil perhitungan AHP</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh Sistem Pendukung Keputusan Pemilihan Supplier</p>
        <p>© {{ date('Y') }} - All Rights Reserved</p>
    </div>

</body>
</html>