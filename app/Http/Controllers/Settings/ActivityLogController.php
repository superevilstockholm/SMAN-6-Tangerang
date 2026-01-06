<?php

namespace App\Http\Controllers\Settings;

use Throwable;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

// Models
use App\Models\Settings\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View | RedirectResponse
    {
        try {
            $limit = $request->query('limit', 10);
            $query = ActivityLog::query()->orderBy('created_at', 'desc')->with('user');
            // Search
            $allowed_types = ['method', 'path', 'user_id', 'user_name', 'ip_address', 'user_agent', 'status_code', 'date'];
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
                    if (!empty($search)) {
                        if ($type === 'user_name') {
                            $query->whereHas('user', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                        } else if ($type === 'method') {
                            $query->where('method', $search);
                        } else {
                            $query->where($type, 'like', '%' . $search . '%');
                        }
                    }
                }
            }
            $activityLogs = $limit === 'all'
                ? $query->get()
                : $query->paginate((int) $limit)
                    ->appends($request->except('page'));
            return view('pages.dashboard.admin.settings.activity-log.index', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'activityLogs' => $activityLogs,
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.settings.activity-logs.index')->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityLog $activityLog): View | RedirectResponse
    {
        try {
            return view('pages.dashboard.admin.settings.activity-log.show', [
                'meta' => [
                    'sidebarItems' => adminSidebarItems(),
                ],
                'activity_log' => $activityLog->load('user:name'),
            ]);
        } catch (Throwable $e) {
            return redirect()->route('dashboard.admin.settings.activity-logs.index')->withErrors($e->getMessage());
        }
    }
}
