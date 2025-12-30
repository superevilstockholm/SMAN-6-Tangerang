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
            return view('pages.dashboard.admin.master-data.vision.index', [
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
    public function create(): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.admin.master-data.vision.create', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ]
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.visions.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string',
                'is_active' => 'nullable|boolean',
            ], [
                'content.required' => 'Kolom content wajib di isi.',
                'content.string' => 'Format content tidak sesuai.',
                'is_active.boolean' => 'Format is_active tidak sesuai.',
            ]);
            if (!empty($validated['is_active'])) {
                Vision::where('is_active', true)->update(['is_active' => false]);
            }
            Vision::create($validated);
            return redirect()->route('dashboard.admin.master-data.visions.index')->with('success', 'Vision created successfully.');
        } catch (Throwable $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vision $vision): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.admin.master-data.vision.show', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'vision' => $vision
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.visions.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vision $vision): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.admin.master-data.vision.edit', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'vision' => $vision
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.visions.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vision $vision): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string',
                'is_active' => 'nullable|boolean',
            ], [
                'content.required' => 'Kolom content wajib di isi.',
                'content.string' => 'Format content tidak sesuai.',
                'is_active.boolean' => 'Format is_active tidak sesuai.',
            ]);
            if (!empty($validated['is_active'])) {
                Vision::where('is_active', true)->update(['is_active' => false]);
            }
            $vision->update($validated);
            return redirect()->route('dashboard.admin.master-data.visions.index')->with('success', 'Vision updated successfully.');
        } catch (Throwable $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
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
