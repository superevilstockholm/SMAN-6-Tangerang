<?php

namespace App\Http\Controllers\MasterData;

use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

// Models
use App\Models\MasterData\Vision;

class VisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View | RedirectResponse
    {
        try {
            $limit = $request->query('limit', 10);
            $query = Vision::query()
                ->orderBy('is_active', 'desc')
                ->orderBy('created_at', 'desc');
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
            $visions = $limit === 'all'
                ? $query->get()
                : $query->paginate((int) $limit)
                    ->appends($request->except('page'));
            return view('pages.dashboard.admin.master-data.visions.index', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'visions' => $visions,
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.visions.index')->withErrors($e->getMessage());
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
    public function show(Vision $vision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vision $vision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vision $vision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vision $vision): RedirectResponse
    {
        try {
            $vision->delete();
            return redirect()->route('dashboard.admin.master-data.visions.index')->with('success', 'Vision deleted successfully.');
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.visions.index')->withErrors($e->getMessage());
        }
    }
}
