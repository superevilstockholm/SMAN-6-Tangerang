<?php

use Illuminate\Support\Str;

if (!function_exists('adminSidebarItems')) {
    function adminSidebarItems(): array
    {
        $items = [
            'main' => [
                ['label' => 'dashboard', 'icon' => 'ti ti-dashboard', 'route' => 'dashboard.admin.index'],
            ],
            'master data' => [
                ['label' => 'users', 'icon' => 'ti ti-users-group', 'route' => 'dashboard.admin.master-data.users.index'],
                ['label' => 'school Histories', 'icon' => 'ti ti-book', 'route' => 'dashboard.admin.master-data.school-histories.index'],
                ['label' => 'visions', 'icon' => 'ti ti-eye', 'route' => 'dashboard.admin.master-data.visions.index'],
                ['label' => 'missions', 'icon' => 'ti ti-target', 'route' => 'dashboard.admin.master-data.missions.index'],
                ['label' => 'teachers', 'icon' => 'ti ti-school', 'route' => 'dashboard.admin.master-data.teachers.index'],
                ['label' => 'news', 'icon' => 'ti ti-news', 'route' => 'dashboard.admin.master-data.news.index'],
            ],
            'settings' => [
                ['label' => 'activity logs', 'icon' => 'ti ti-history', 'route' => 'dashboard.admin.settings.activity-logs.index'],
            ],
        ];
        foreach ($items as $group => &$groupItems) {
            foreach ($groupItems as &$item) {
                if ($item['route'] === 'dashboard.admin.index') {
                    $item['active_pattern'] = $item['route'];
                } else {
                    $item['active_pattern'] = Str::replaceLast('.index', '.*', $item['route']);
                }
            }
        }
        return $items;
    }
}

if (!function_exists('teacherSidebarItems')) {
    function teacherSidebarItems(): array
    {
        $items = [
            'main' => [
                ['label' => 'dashboard', 'icon' => 'ti ti-dashboard', 'route' => 'dashboard.teacher.index'],
            ],
            'master data' => [
                ['label' => 'news', 'icon' => 'ti ti-news', 'route' => 'dashboard.teacher.master-data.news.index'],
            ],
        ];
        foreach ($items as $group => &$groupItems) {
            foreach ($groupItems as &$item) {
                if ($item['route'] === 'dashboard.teacher.index') {
                    $item['active_pattern'] = $item['route'];
                } else {
                    $item['active_pattern'] = Str::replaceLast('.index', '.*', $item['route']);
                }
            }
        }
        return $items;
    }
}

if (!function_exists('studentSidebarItems')) {
    function studentSidebarItems(): array
    {
        return [
            'main' => [
                ['label' => 'dashboard', 'icon' => 'ti ti-dashboard', 'route' => 'dashboard.student.index'],
            ],
        ];
    }
}
