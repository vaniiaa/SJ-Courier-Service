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
                ['label' => 'Permintaan Pengiriman', 'url' => route('shipments.create.step1')],
                ['label' => 'Daftar Pengiriman', 'url' => route('customer.active')],
                ['label' => 'History Pengiriman', 'url' => route('customer.history')],
            ]],
        ];
    }
}