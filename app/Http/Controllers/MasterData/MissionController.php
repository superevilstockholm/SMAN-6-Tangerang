<?php

namespace App\Http\Controllers\MasterData;

use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

// Models
use App\Models\MasterData\Mission;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View | RedirectResponse
    {
        try {
            $limit = $request->query('limit', 10);
            $query = Mission::query()
                ->orderBy('item_order', 'desc');
            // Search
            $allowed_types = ['content', 'date'];
            $type = $request->query('type');
            if ($type && in_array($type, $allowed_types)) {
                if ($type === 'date') {
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
                } else {
                    $search = $request->query('search');
                    if ($search) {
                        $query->where($type, 'like', '%' . $search . '%');
                    }
                }
            }
            $missions = $limit === 'all'
                ? $query->get()
                : $query->paginate((int) $limit)
                    ->appends($request->except('page'));
            return view('pages.dashboard.admin.master-data.missions.index', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'missions' => $missions,
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.missions.index')->withErrors($e->getMessage());
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
    public function show(Mission $mission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mission $mission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mission $mission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mission $mission)
    {
        //
    }
}
