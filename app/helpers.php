<?php

if (!function_exists('getNavigationLinks')) {
    function getNavigationLinks($user) {
        if (!$user) {
            return [
                ['label' => 'Layanan', 'children' => [
                    ['label' => 'Live Tracking', 'url' => route('login')],
                    ['label' => 'Pengiriman', 'url' => route('login')],
                ]],
            ];
        }
        if ($user->isAdmin()) {
            return [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Manajemen User', 'url' => route('admin.users')],
            ];
        }
        if ($user->isKurir()) {
            return [
                ['label' => 'Dashboard', 'url' => route('kurir.dashboard')],
                ['label' => 'Live Tracking', 'url' => route('kurir.live_tracking')],
            ];
        }
        // Default: customer
        return [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Layanan', 'children' => [
                ['label' => 'Live Tracking', 'url' => route('login')],
                ['label' => 'Pengiriman', 'url' => route('login')],
            ]],
        ];
    }
}