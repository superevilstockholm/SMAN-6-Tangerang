<?php

namespace App\Http\Controllers\MasterData;

use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

// Models
use App\Models\MasterData\Mission;

class MissionController extends Controller
{
    private function normalizeOrder(): void
    {
        $missions = Mission::orderBy('item_order')
            ->orderBy('id')
            ->get(['id']);
        DB::transaction(function () use ($missions) {
            foreach ($missions as $index => $mission) {
                Mission::where('id', $mission->id)
                    ->update([
                        'item_order' => $index + 1,
                    ]);
            }
        });
    }

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
        $total = Mission::count();
        $orders = range(1, $total + 1);
        return view('pages.dashboard.admin.master-data.missions.create', [
            'meta' => [
                'sidebarItems' => adminSidebarItems(),
            ],
            'orders' => $orders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string',
                'item_order' => 'required|integer|min:1',
            ]);
            DB::transaction(function () use ($validated) {
                Mission::where('item_order', '>=', $validated['item_order'])
                    ->increment('item_order');
                Mission::create($validated);
                $this->normalizeOrder();
            });
            return redirect()
                ->route('dashboard.admin.master-data.missions.index')
                ->with('success', 'Mission berhasil ditambahkan');
        } catch (Throwable $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
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
