<?php

if (!function_exists('getNavigationLinks')) {
    function getNavigationLinks($user) {
        if (!$user) {
            return [
                ['label' => 'Layanan', 'children' => [
                    ['label' => 'Live Tracking', 'url' => url('/#tab-tracking-content')],
                    ['label' => 'Pengiriman', 'url' => route('login')],
                ]],
            ];
        }
        if ($user->isKurir()) {
            return [
                ['label' => 'Dashboard', 'url' => route('kurir.dashboard')],
                ['label' => 'Layanan Kami', 'children' => [
                    ['label' => 'Live Tracking', 'url' => route('kurir.live_tracking')],
                    ['label' => 'Kelola Status', 'url' => route('kurir.kelola_status')],
                    ['label' => 'History Pengiriman', 'url' => route('kurir.history_pengiriman_kurir')],
                ]],
                ['label' => 'Daftar Pengiriman', 'url' => route('kurir.daftar_pengiriman')],
            ];
        }
        // Default: customer
        return [
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Layanan', 'children' => [
                // Arahkan ke dashboard, tempat widget tracking berada
                ['label' => 'Live Tracking', 'url' => route('user.live_tracking')],
                ['label' => 'Permintaan Pengiriman', 'url' => route('user.form_pengiriman')],
                ['label' => 'Daftar Pengiriman', 'url' => route('user.daftar_pengiriman')],
                ['label' => 'History Pengiriman', 'url' => route('user.history')],

            ]],
        ];
    }
}