<?php

namespace App\Http\Controllers\MasterData;

use Throwable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Models
use App\Models\MasterData\SchoolHistory;

class SchoolHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->query('limit', 10);
            $query = SchoolHistory::query();

            // Search
            $allowed_types = ['title', 'description', 'history_year', 'created_date'];
            $type = $request->query('type');
            if ($type && in_array($type, $allowed_types)) {
                if ($type === 'created_date') {
                    $start_date = $request->query('start_date');
                    $end_date = $request->query('end_date');
                    if ($start_date) {
                        $start_date = Carbon::parse($start_date)->startOfDay();
                        $query->where('created_at', '>=', $start_date);
                    }
                    if ($end_date) {
                        $end_date = Carbon::parse($end_date)->endOfDay();
                        $query->where('created_at', '<=', $end_date);
                    }
                } if ($type === 'history_year') {
                    $start_year = $request->query('start_year');
                    $end_year = $request->query('end_year');
                    if ($start_year) {
                        $query->where('start_year', '>=', $start_year);
                    }
                    if ($end_year) {
                        $query->where(function($q) use ($end_year) {
                            $q->where('end_year', '<=', $end_year)
                                ->orWhereNull('end_year');
                        });
                    }
                } else {
                    $search = $request->query('search');
                    if ($search) {
                        $query->where($type, 'like', '%' . $search . '%');
                    }
                }
            }

            $school_histories = $limit === 'all'
                ? $query->get()
                : $query->paginate((int) $limit)
                    ->appends($request->except('page'));

            return view('pages.dashboard.admin.master-data.school-history.index', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'school_histories' => $school_histories,
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.school-history.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolHistory $schoolHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolHistory $schoolHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolHistory $schoolHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolHistory $schoolHistory)
    {
        //
    }
}
