<?php

namespace App\Http\Controllers\MasterData;

use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

// Models
use App\Models\MasterData\Teacher;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View | RedirectResponse
    {
        try {
            $limit = $request->query('limit', 10);
            $query = Teacher::query()->with('user')->orderBy('created_at', 'desc');
            // Search
            $allowed_types = ['name', 'nip', 'dob', 'date', 'email'];
            $type = $request->query('type');
            if ($type && in_array($type, $allowed_types)) {
                if ($type === 'date' || $type === 'dob') {
                    $start_date = $request->query('start_date');
                    $end_date = $request->query('end_date');
                    $dateColumn = $type === 'dob' ? 'dob' : 'created_at';
                    if ($start_date) {
                        $query->where($dateColumn, '>=', Carbon::parse($start_date)->startOfDay());
                    }
                    if ($end_date) {
                        $query->where($dateColumn, '<=', Carbon::parse($end_date)->endOfDay());
                    }
                } else {
                    $search = $request->query('search');
                    if ($type === 'email') {
                        if ($search) {
                            $query->whereHas('user', function ($q) use ($search) {
                                $q->where('email', 'like', '%' . $search . '%');
                            });
                        }
                    } else {
                        if ($search) {
                            $query->where($type, 'like', '%' . $search . '%');
                        }
                    }
                }
            }
            $teachers = $limit === 'all'
                ? $query->get()
                : $query->paginate((int) $limit)
                    ->appends($request->except('page'));
            return view('pages.dashboard.admin.master-data.teacher.index', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'teachers' => $teachers,
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.teachers.index')->withErrors($e->getMessage());
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
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
    }
}
