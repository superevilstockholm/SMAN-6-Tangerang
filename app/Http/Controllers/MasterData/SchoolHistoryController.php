<?php

namespace App\Http\Controllers\MasterData;

use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

// Models
use App\Models\MasterData\SchoolHistory;

class SchoolHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View | RedirectResponse
    {
        try {
            $limit = $request->query('limit', 10);
            $query = SchoolHistory::query()->orderBy('created_at', 'desc');

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
                    if ($start_year && $end_year) {
                        $query->where(function($q) use ($start_year, $end_year) {
                            $q->where(function($inner) use ($start_year, $end_year) {
                                $inner->where('start_year', '<=', $end_year)
                                    ->where(function($sub) use ($start_year) {
                                        $sub->where('end_year', '>=', $start_year)
                                            ->orWhereNull('end_year');
                                    });
                            });
                        });
                    } else {
                        if ($start_year) {
                            $query->where(function($q) use ($start_year) {
                                $q->where('end_year', '>=', $start_year)
                                ->orWhereNull('end_year');
                            });
                        }
                        if ($end_year) {
                            $query->where('start_year', '<=', $end_year);
                        }
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
            return redirect()->route('dashboard.admin.master-data.school-histories.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.admin.master-data.school-history.create', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.school-histories.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                'start_year' => 'required|numeric',
                'end_year' => 'nullable|numeric',
            ], [
                'title.required' => 'Kolom title wajib di isi.',
                'title.string' => 'Format title tidak sesuai.',
                'title.max' => 'Panjang title maksimal :max karakter.',
                'description.required' => 'Kolom description wajib di isi.',
                'description.string' => 'Format description tidak sesuai.',
                'image.image' => 'Format gambar tidak sesuai.',
                'image.mimes' => 'Format gambar tidak diperbolehkan.',
                'image.max' => 'Ukuran gambar maksimal :max KB.',
                'start_year.required' => 'Kolom start year wajib di isi.',
                'start_year.numeric' => 'Format start year tidak sesuai.',
                'end_year.numeric' => 'Format end year tidak sesuai.',
            ]);
            if ($request->hasFile('image')) {
                $validated['image_path'] = $request
                    ->file('image')
                    ->store('school-history', 'public');
            }
            unset($validated['image']);
            SchoolHistory::create($validated);
            return redirect()->route('dashboard.admin.master-data.school-histories.index')->with('success', 'School History created successfully.');
        } catch (Throwable $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolHistory $schoolHistory): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.admin.master-data.school-history.show', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'school_history' => $schoolHistory
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.school-histories.index')->withErrors($e->getMessage());
        }
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
