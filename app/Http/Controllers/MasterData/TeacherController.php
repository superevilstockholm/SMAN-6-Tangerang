<?php

namespace App\Http\Controllers\MasterData;

use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

// Enums
use App\Enums\RoleEnum;

// Models
use App\Models\User;
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
    public function create(): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.admin.master-data.teacher.create', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.teachers.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'nip' => 'required|string|unique:teachers,nip|size:18',
                'dob' => 'required|date',
                'with_user' => 'nullable|boolean',
                'email' => 'required_if:with_user,1|email|unique:users,email',
                'password' => 'required_if:with_user,1|min:8|max:255|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()\-_]).{8,255}$/',
                'profile_picture_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            ]);
            DB::transaction(function () use ($validated, $request) {
                $userId = null;
                if ($request->boolean('with_user')) {
                    $profilePath = null;
                    if ($request->hasFile('profile_picture_image')) {
                        $profilePath = $request
                            ->file('profile_picture_image')
                            ->store('profile-pictures', 'public');
                    }
                    $user = User::create([
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'role' => RoleEnum::TEACHER,
                        'password' => Hash::make($validated['password']),
                        'profile_picture_path' => $profilePath,
                    ]);
                    $userId = $user->id;
                }
                Teacher::create([
                    'name' => $validated['name'],
                    'nip' => $validated['nip'],
                    'dob' => $validated['dob'],
                    'user_id' => $userId,
                ]);
            });
            return redirect()->route('dashboard.admin.master-data.teachers.index')->with('success', 'Teacher created successfully.');
        } catch (Throwable $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
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
    public function destroy(Teacher $teacher): RedirectResponse
    {
        try {
            DB::transaction(function () use ($teacher) {
                if ($teacher->user?->profile_picture_path) {
                    Storage::disk('public')->delete($teacher->user->profile_picture_path);
                }
                $teacher->user?->delete();
                $teacher->delete();
            });
            return redirect()->route('dashboard.admin.master-data.teachers.index')->with('success', 'Teacher deleted successfully.');
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.teachers.index')->withErrors($e->getMessage());
        }
    }
}
