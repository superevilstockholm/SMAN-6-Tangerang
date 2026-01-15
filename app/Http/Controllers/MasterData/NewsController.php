<?php

namespace App\Http\Controllers\MasterData;

use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

// Models
use App\Models\MasterData\News;

// Enums
use App\Enums\RoleEnum;
use App\Enums\NewsStatusEnum;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View | RedirectResponse
    {
        try {
            News::autoPublishScheduled();
            $limit = $request->query('limit', 10);
            $query = News::query()->orderBy('created_at', 'desc')->with('user');
            if ($request->user()->role === RoleEnum::TEACHER) {
                $query->where('user_id', $request->user()->id);
            }
            // Search
            $baseAllowedTypes = ['title', 'slug', 'content', 'published_at', 'date'];
            $allowed_types = $request->user()->role === RoleEnum::TEACHER
                ? $baseAllowedTypes
                : array_merge($baseAllowedTypes, ['user_name']);
            $type = $request->query('type');
            if ($type && in_array($type, $allowed_types)) {
                if (in_array($type, ['date', 'published_at'])) {
                    $startDate = $request->query('start_date');
                    $endDate   = $request->query('end_date');
                    $dateField = $type === 'published_at'
                        ? 'published_at'
                        : 'created_at';
                    if ($startDate) {
                        $query->where($dateField, '>=', Carbon::parse($startDate)->startOfDay());
                    }
                    if ($endDate) {
                        $query->where($dateField, '<=', Carbon::parse($endDate)->endOfDay());
                    }
                } else {
                    $search = $request->query('search');
                    if (!empty($search)) {
                        if ($type === 'user_name') {
                            $query->whereHas('user', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                        } else {
                            $query->where($type, 'like', '%' . $search . '%');
                        }
                    }
                }
            }
            $news = $limit === 'all'
                ? $query->get()
                : $query->paginate((int) $limit)
                    ->appends($request->except('page'));
            return view('pages.dashboard.' . $request->user()->role->value . '.master-data.news.index', [
                'meta' => [
                    'sidebarItems' => $request->user()->role === RoleEnum::TEACHER ? teacherSidebarItems() : adminSidebarItems(),
                ],
                'news' => $news,
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.' . $request->user()->role->value . '.master-data.news.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.' . $request->user()->role->value . '.master-data.news.create', [
                'meta' => [
                    'sidebarItems' => $request->user()->role === RoleEnum::TEACHER ? teacherSidebarItems() : adminSidebarItems(),
                ]
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.' . $request->user()->role->value . '.master-data.news.index')->with('error', $e->getMessage());
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
                'slug' => 'nullable|string|max:255|unique:news,slug',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                'content' => 'required|string',
                'status' => ['required', Rule::enum(NewsStatusEnum::class)],
                'published_at' => 'nullable|date',
            ]);
            if ($request->hasFile('cover_image')) {
                $validated['cover_image'] = $request
                    ->file('cover_image')
                    ->store('news', 'public');
            }
            $validated['user_id'] = $request->user()->id;
            switch ($validated['status']) {
                case NewsStatusEnum::DRAFT:
                    // Draft: published_at must be null
                    $validated['published_at'] = null;
                    break;
                case NewsStatusEnum::PUBLISHED:
                    // Published: published_at is now
                    $validated['published_at'] = now();
                    break;
                case NewsStatusEnum::SCHEDULED:
                    // Scheduled: published_at must be future
                    if (
                        empty($validated['published_at']) ||
                        Carbon::parse($validated['published_at'])->lte(now())
                    ) {
                        return back()
                            ->withErrors('Tanggal Publikasi harus lebih besar dari sekarang.')
                            ->withInput();
                    }
                    break;
            }
            News::create($validated);
            return redirect()
                ->route('dashboard.' . $request->user()->role->value . '.master-data.news.index')
                ->with('success', 'News created successfully.');
        } catch (Throwable $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, News $news): View | RedirectResponse
    {
        try {
            if ($request->user()->role === RoleEnum::TEACHER && $news->user_id !== $request->user()->id) {
                return redirect()->route('dashboard.' . $request->user()->role->value . '.master-data.news.index')->with('error', 'News not found.');
            }
            return view('pages.dashboard.' . $request->user()->role->value . '.master-data.news.show', [
                'meta' => [
                    'sidebarItems' => $request->user()->role === RoleEnum::TEACHER ? teacherSidebarItems() : adminSidebarItems(),
                ],
                'news' => $news->load('user'),
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.' . $request->user()->role->value . '.master-data.news.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, News $news): View | RedirectResponse
    {
        try {
            if (
                $request->user()->role === RoleEnum::TEACHER &&
                $news->user_id !== $request->user()->id
            ) {
                abort(403);
            }
            return view('pages.dashboard.' . $request->user()->role->value . '.master-data.news.edit', [
                'meta' => [
                    'sidebarItems' => $request->user()->role === RoleEnum::TEACHER ? teacherSidebarItems() : adminSidebarItems(),
                ],
                'news' => $news,
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.' . $request->user()->role->value . '.master-data.news.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news): RedirectResponse
    {
        try {
            if (
                $request->user()->role === RoleEnum::TEACHER &&
                $news->user_id !== $request->user()->id
            ) {
                abort(403);
            }
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => [
                    'nullable',
                    'string',
                    'max:255',
                    Rule::unique('news', 'slug')->ignore($news->id),
                ],
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                'content' => 'required|string',
                'status' => ['required', Rule::enum(NewsStatusEnum::class)],
                'published_at' => 'nullable|date',
            ]);
            if ($request->hasFile('cover_image')) {
                if ($news->cover_image) {
                    Storage::disk('public')->delete($news->cover_image);
                }
                $validated['cover_image'] = $request
                    ->file('cover_image')
                    ->store('news', 'public');
            }
            switch ($validated['status']) {
                case NewsStatusEnum::DRAFT:
                    $validated['published_at'] = null;
                    break;
                case NewsStatusEnum::PUBLISHED:
                    if (!$news->published_at) {
                        $validated['published_at'] = now();
                    }
                    break;
                case NewsStatusEnum::SCHEDULED:
                    if (
                        empty($validated['published_at']) ||
                        Carbon::parse($validated['published_at'])->lte(now())
                    ) {
                        return back()->withErrors('Tanggal Publikasi harus lebih besar dari sekarang.')->withInput();
                    }
                    break;
            }
            $news->update($validated);
            return redirect()->route('dashboard.' . $request->user()->role->value . '.master-data.news.index')->with('success', 'News updated successfully.');
        } catch (Throwable $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, News $news): RedirectResponse
    {
        try {
            if ($news->cover_image) {
                Storage::disk('public')->delete($news->cover_image);
            }
            $news->delete();
            return redirect()->route('dashboard.' . $request->user()->role->value . '.master-data.news.index')->with('success', 'News deleted successfully.');
        } catch (Throwable $e) {
            return redirect()->route('dashboard.' . $request->user()->role->value . '.master-data.news.index')->with('error', $e->getMessage());
        }
    }
}
