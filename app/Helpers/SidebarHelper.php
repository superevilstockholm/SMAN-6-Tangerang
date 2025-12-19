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
                ['label' => 'School Histories', 'icon' => 'ti ti-book', 'route' => 'dashboard.admin.master-data.school-histories.index'],
            ],
        ];
        foreach ($items as $group => &$groupItems) {
            foreach ($groupItems as &$item) {
                if ($item['route'] === 'dashboard.admin.index' || $item['route'] === 'dashboard.teacher.index') {
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
        return [
            'main' => [
                ['label' => 'dashboard', 'icon' => 'ti ti-dashboard', 'route' => 'dashboard.teacher.index'],
            ],
        ];
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
