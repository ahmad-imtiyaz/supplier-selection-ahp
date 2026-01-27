<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Criteria;
use App\Models\SupplierTrackRecord;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SupplierTrackRecordController extends Controller
{
    /**
     * Display a listing of suppliers with track records
     * 🆕 FIXED: Added year filter support
     */
    public function index(Request $request)
    {
        // Get year from request or use current year
        $selectedYear = $request->input('year', date('Y'));

        // Generate available years (current year ± 2 years)
        $currentYear = date('Y');
        $availableYears = range($currentYear - 2, $currentYear + 2);

        $suppliers = Supplier::where('is_active', true)
            ->with(['trackRecords' => function($query) use ($selectedYear) {
                $query->where('year', $selectedYear);
            }])
            ->get()
            ->map(function($supplier) use ($selectedYear) {
                // Hitung statistik completion untuk tahun yang dipilih
                $activeCriteria = Criteria::active()->count();
                $totalMonths = 12; // 12 bulan dalam setahun
                $totalExpected = $activeCriteria * $totalMonths;

                // Hitung track records yang sudah diisi untuk tahun yang dipilih
                $completed = $supplier->trackRecords
                    ->filter(function($record) {
                        return $record->hasContent();
                    })
                    ->count();

                $supplier->completion_stats = [
                    'total' => $totalExpected,
                    'completed' => $completed,
                    'percentage' => $totalExpected > 0 ? ($completed / $totalExpected) * 100 : 0,
                ];

                return $supplier;
            });

        return view('track-records.index', compact('suppliers', 'selectedYear', 'availableYears'));
    }

    /**
     * Display the specified supplier's track records
     */
    public function show(Request $request, Supplier $supplier)
    {
        // Get year from request or use current year
        $year = $request->input('year', date('Y'));

        // Check if show all is requested
        $showAll = $request->input('show_all', false);

        // Generate available years (current year ± 2 years)
        $currentYear = date('Y');
        $availableYears = range($currentYear - 2, $currentYear + 2);

        // Get all active criteria
        $criterias = Criteria::active()->get();

        // Get months (1-12)
        $months = range(1, 12);

        // Get all track records for this supplier and year
        $records = SupplierTrackRecord::where('supplier_id', $supplier->id)
            ->where('year', $year)
            ->with('criteria')
            ->get();

        // Organize track records by criteria and month
        $trackRecords = [];
        foreach ($criterias as $criteria) {
            $trackRecords[$criteria->id] = [];
            foreach ($months as $month) {
                $record = $records->first(function ($r) use ($criteria, $month) {
                    return $r->criteria_id == $criteria->id && $r->month == $month;
                });
                $trackRecords[$criteria->id][$month] = $record;
            }
        }

        // Calculate completion stats
        $totalExpected = $criterias->count() * 12; // criteria count × 12 months
        $completed = $records->filter(function ($record) {
            return $record->hasContent();
        })->count();

        $completion = [
            'total' => $totalExpected,
            'completed' => $completed,
            'percentage' => $totalExpected > 0 ? ($completed / $totalExpected) * 100 : 0,
        ];

        // Get activity logs for this supplier's track records
        $activitiesQuery = ActivityLog::where('model', 'SupplierTrackRecord')
            ->whereIn('model_id', function($query) use ($supplier) {
                $query->select('id')
                    ->from('supplier_track_records')
                    ->where('supplier_id', $supplier->id);
            })
            ->with('user')
            ->latest();

        // If show all, get all records, otherwise paginate
        if ($showAll) {
            $activities = $activitiesQuery->get();
            // Convert to paginator-like object for blade compatibility
            $activities = new \Illuminate\Pagination\LengthAwarePaginator(
                $activities,
                $activities->count(),
                $activities->count(),
                1
            );
        } else {
            $activities = $activitiesQuery->paginate(15);
        }

        // Calculate activity statistics
        $activityStats = [
            'total' => ActivityLog::where('model', 'SupplierTrackRecord')
                ->whereIn('model_id', function($query) use ($supplier) {
                    $query->select('id')
                        ->from('supplier_track_records')
                        ->where('supplier_id', $supplier->id);
                })
                ->count(),
            'create' => ActivityLog::where('model', 'SupplierTrackRecord')
                ->where('action', 'create')
                ->whereIn('model_id', function($query) use ($supplier) {
                    $query->select('id')
                        ->from('supplier_track_records')
                        ->where('supplier_id', $supplier->id);
                })
                ->count(),
            'update' => ActivityLog::where('model', 'SupplierTrackRecord')
                ->where('action', 'update')
                ->whereIn('model_id', function($query) use ($supplier) {
                    $query->select('id')
                        ->from('supplier_track_records')
                        ->where('supplier_id', $supplier->id);
                })
                ->count(),
            'delete' => ActivityLog::where('model', 'SupplierTrackRecord')
                ->where('action', 'delete')
                ->whereIn('model_id', function($query) use ($supplier) {
                    $query->select('id')
                        ->from('supplier_track_records')
                        ->where('supplier_id', $supplier->id);
                })
                ->count(),
        ];

        return view('track-records.show', compact(
            'supplier',
            'year',
            'availableYears',
            'criterias',
            'months',
            'trackRecords',
            'completion',
            'activities',
            'activityStats'
        ));
    }

    /**
     * Show the form for editing a specific track record
     */
    public function edit(Request $request, Supplier $supplier)
    {
        $criteriaId = $request->input('criteria');
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', 1);

        $criteria = Criteria::findOrFail($criteriaId);

        // Get or create track record
        $trackRecord = SupplierTrackRecord::firstOrNew([
            'supplier_id' => $supplier->id,
            'criteria_id' => $criteriaId,
            'year' => $year,
            'month' => $month,
        ]);

        // Month names in Indonesian
        $monthName = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        return view('track-records.edit', compact(
            'supplier',
            'criteria',
            'trackRecord',
            'year',
            'month',
            'monthName'
        ));
    }

    /**
     * Update a specific track record
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'criteria_id' => 'required|exists:criteria,id',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'description' => 'nullable|string|max:2000',
            'recommended_score' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Get old values for logging
            $trackRecord = SupplierTrackRecord::where([
                'supplier_id' => $supplier->id,
                'criteria_id' => $request->criteria_id,
                'year' => $request->year,
                'month' => $request->month,
            ])->first();

            $oldValues = $trackRecord ? $trackRecord->only(['description', 'recommended_score', 'notes']) : null;
            $isNew = !$trackRecord;

            // Update or create
            $trackRecord = SupplierTrackRecord::updateOrCreate(
                [
                    'supplier_id' => $supplier->id,
                    'criteria_id' => $request->criteria_id,
                    'year' => $request->year,
                    'month' => $request->month,
                ],
                [
                    'description' => $request->description,
                    'recommended_score' => $request->recommended_score,
                    'notes' => $request->notes,
                ]
            );

            // Log activity
            $criteria = Criteria::find($request->criteria_id);
            $monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $isNew ? 'create' : 'update',
                'model' => 'SupplierTrackRecord',
                'model_id' => $trackRecord->id,
                'description' => ($isNew ? 'Menambahkan' : 'Mengubah') . " track record {$supplier->name} - {$criteria->name} ({$monthNames[$request->month]} {$request->year})",
                'old_values' => $oldValues,
                'new_values' => $trackRecord->only(['description', 'recommended_score', 'notes']),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()
                ->route('track-records.show', ['supplier' => $supplier, 'year' => $request->year])
                ->with('success', 'Track record berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui track record: ' . $e->getMessage());
        }
    }

    /**
     * Initialize track records for a supplier with all active criteria
     */
    public function initialize(Request $request, Supplier $supplier)
    {
        $year = $request->input('year', date('Y'));

        try {
            DB::beginTransaction();

            $criterias = Criteria::active()->get();
            $months = range(1, 12);
            $createdCount = 0;

            foreach ($criterias as $criteria) {
                foreach ($months as $month) {
                    $created = SupplierTrackRecord::firstOrCreate(
                        [
                            'supplier_id' => $supplier->id,
                            'criteria_id' => $criteria->id,
                            'year' => $year,
                            'month' => $month,
                        ],
                        [
                            'description' => null,
                            'recommended_score' => null,
                            'notes' => null,
                        ]
                    );

                    if ($created->wasRecentlyCreated) {
                        $createdCount++;
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('track-records.show', ['supplier' => $supplier, 'year' => $year])
                ->with('success', "Track record tahun {$year} berhasil diinisialisasi. {$createdCount} slot baru dibuat.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menginisialisasi track record: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified track record
     */
    public function destroy(SupplierTrackRecord $trackRecord)
    {
        try {
            DB::beginTransaction();

            // Get data for logging before delete
            $trackRecord->load(['supplier', 'criteria']);
            $monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete',
                'model' => 'SupplierTrackRecord',
                'model_id' => $trackRecord->id,
                'description' => "Menghapus track record {$trackRecord->supplier->name} - {$trackRecord->criteria->name} ({$monthNames[$trackRecord->month]} {$trackRecord->year})",
                'old_values' => $trackRecord->only(['description', 'recommended_score', 'notes']),
                'new_values' => null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $trackRecord->delete();

            DB::commit();

            return back()->with('success', 'Track record berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus track record: ' . $e->getMessage());
        }
    }

    /**
     * Reset all track records for a supplier
     */
    public function reset(Request $request, Supplier $supplier)
    {
        $year = $request->input('year', date('Y'));

        try {
            DB::beginTransaction();

            $deletedCount = SupplierTrackRecord::where('supplier_id', $supplier->id)
                ->where('year', $year)
                ->delete();

            DB::commit();

            return redirect()
                ->route('track-records.show', ['supplier' => $supplier, 'year' => $year])
                ->with('success', "Semua track record tahun {$year} berhasil direset. {$deletedCount} data dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal mereset track record: ' . $e->getMessage());
        }
    }

    /**
     * Clear all activity logs for a specific supplier's track records
     */
    public function clearActivities(Supplier $supplier)
    {
        try {
            DB::beginTransaction();

            // Get all track record IDs for this supplier
            $trackRecordIds = SupplierTrackRecord::where('supplier_id', $supplier->id)
                ->pluck('id')
                ->toArray();

            // Delete all activity logs for these track records
            $deletedCount = ActivityLog::where('model', 'SupplierTrackRecord')
                ->whereIn('model_id', $trackRecordIds)
                ->delete();

            DB::commit();

            return redirect()
                ->route('track-records.show', $supplier)
                ->with('success', "Berhasil menghapus {$deletedCount} riwayat aktivitas.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus riwayat aktivitas: ' . $e->getMessage());
        }
    }
}
