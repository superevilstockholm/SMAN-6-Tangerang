<?php

namespace App\Http\Controllers\MasterData;

use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

// Models
use App\Models\User;

// Enums
use App\Enums\RoleEnum;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View | RedirectResponse
    {
        try {
            $limit = $request->query('limit', 10);
            $query = User::query();

            // Search
            $allowed_types = ['name', 'email', 'role', 'date'];
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

            $users = $limit === 'all'
                ? $query->get()
                : $query->paginate((int) $limit)
                    ->appends($request->except('page'));

            return view('pages.dashboard.admin.master-data.user.index', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'users' => $users,
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.users.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.admin.master-data.user.create', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.users.index')->withErrors($e->getMessage());
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
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|min:8|max:255|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()\-_]).{8,255}$/',
                'role' => ['required', Rule::enum(RoleEnum::class)],
                'profile_picture_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            ], [
                'name.required' => 'Kolom nama wajib di isi.',
                'name.string' => 'Format nama tidak sesuai.',
                'name.max' => 'Panjang nama maksimal :max karakter.',
                'email.required' => 'Kolom email wajib di isi.',
                'email.email' => 'Format email tidak sesuai.',
                'email.unique' => 'Email sudah terdaftar.',
                'email.max' => 'Panjang email maksimal :max karakter.',
                'password.required' => 'Kolom password wajib di isi.',
                'password.string' => 'Format password tidak sesuai.',
                'password.min' => 'Panjang password minimal :min karakter.',
                'password.max' => 'Panjang password maksimal :max karakter.',
                'password.regex' => 'Password harus memiliki setidaknya satu huruf besar, satu huruf kecil, satu angka, dan satu karakter khusus.',
                'role.required' => 'Kolom role wajib di isi.',
                'profile_picture_image.image' => 'Format gambar tidak sesuai.',
                'profile_picture_image.mimes' => 'Format gambar tidak diperbolehkan.',
                'profile_picture_image.max' => 'Ukuran gambar maksimal :max KB.',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            if ($request->hasFile('profile_picture_image')) {
                $validated['profile_picture_path'] = $request
                    ->file('profile_picture_image')
                    ->store('profile-pictures', 'public');
            }

            unset($validated['profile_picture_image']);

            User::create($validated);

            return redirect()->route('dashboard.admin.master-data.users.index')->with('success', 'User created successfully.');
        } catch (Throwable $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.admin.master-data.user.show', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'user' => $user
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.users.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.admin.master-data.user.edit', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'user' => $user
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.users.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
                'password' => 'sometimes|nullable|min:8|max:255|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()\-_]).{8,255}$/',
                'role' => ['required', Rule::enum(RoleEnum::class)],
                'profile_picture_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                'delete_profile_picture' => 'nullable|boolean',
            ], [
                'name.required' => 'Kolom nama wajib di isi.',
                'name.string' => 'Format nama tidak sesuai.',
                'name.max' => 'Panjang nama maksimal :max karakter.',
                'email.required' => 'Kolom email wajib di isi.',
                'email.email' => 'Format email tidak sesuai.',
                'email.unique' => 'Email sudah terdaftar.',
                'email.max' => 'Panjang email maksimal :max karakter.',
                'password.min' => 'Panjang password minimal :min karakter.',
                'password.max' => 'Panjang password maksimal :max karakter.',
                'password.regex' => 'Password harus memiliki setidaknya satu huruf besar, satu huruf kecil, satu angka, dan satu karakter khusus.',
                'role.required' => 'Kolom role wajib di isi.',
                'profile_picture_image.image' => 'Format gambar tidak sesuai.',
                'profile_picture_image.mimes' => 'Format gambar tidak diperbolehkan.',
                'profile_picture_image.max' => 'Ukuran gambar maksimal :max KB.',
                'delete_profile_picture.boolean' => 'Format delete_profile_picture tidak sesuai.',
            ]);

            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            if ($request->hasFile('profile_picture_image')) {
                if ($user->profile_picture_path) {
                    Storage::disk('public')->delete($user->profile_picture_path);
                }

                $validated['profile_picture_path'] = $request
                    ->file('profile_picture_image')
                    ->store('profile-pictures', 'public');
            } elseif ($request->boolean('delete_profile_picture')) {
                if ($user->profile_picture_path) {
                    Storage::disk('public')->delete($user->profile_picture_path);
                }

                $validated['profile_picture_path'] = null;
            }

            unset($validated['profile_picture_image'], $validated['delete_profile_picture']);

            $user->update($validated);

            return redirect()->route('dashboard.admin.master-data.users.index')->with('success', 'User updated successfully.');
        } catch (Throwable $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            if ($user->profile_picture_path) {
                Storage::disk('public')->delete($user->profile_picture_path);
            }

            $user->delete();

            return redirect()->route('dashboard.admin.master-data.users.index')->with('success', 'User deleted successfully.');
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.master-data.users.index')->withErrors($e->getMessage());
        }
    }
}
