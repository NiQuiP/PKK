<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->setDefaultNamespace('App\Controllers');

$routes->get('/', 'Auth\Auth::login');
$routes->get('logout', 'Auth\Auth::logout');
$routes->get('kirim-ulang', 'Auth\Auth::kirim_ulang');
$routes->group('/', ['filter' => 'noauth'], function ($routes) {
    $routes->get('login', 'Auth\Auth::login');
    $routes->post('login', 'Auth\Auth::loginProcess');
    $routes->get('register', 'Auth\Auth::register');
    $routes->post('register', 'Auth\Auth::registerProcess');
    $routes->get('verifikasi', 'Auth\Auth::verifikasi');
    $routes->post('verifikasi', 'Auth\Auth::verifikasiProcess');
    $routes->add('forgetpassword', 'Auth\Auth::forget_password');
    $routes->get('resetpassword', 'Auth\Auth::reset_password');
    $routes->post('resetpassword', 'Auth\Auth::reset_passwordProcess');
});

$routes->group('inventory', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Inventory\DashboardController::index');
    $routes->get('data-barang', 'Inventory\DataBarangController::index');
    $routes->get('barang-masuk', 'Inventory\BarangMasukController::index');
    $routes->get('barang-keluar', 'Inventory\BarangKeluarController::index');
    $routes->get('data-peminjam', 'Inventory\DataPinjamanController::index');
    $routes->get('data-pinjaman', 'Inventory\DataPinjamanController::pinjaman');
    $routes->get('permintaan', 'Inventory\PermintaanController::index');
    $routes->get('data-user', 'Inventory\UserController::index');
    $routes->get('data-ruangan', 'Inventory\RuanganController::index');
    $routes->get('setting', 'Inventory\SettingController::index');
    $routes->post('setting', 'Inventory\SettingController::store');
});