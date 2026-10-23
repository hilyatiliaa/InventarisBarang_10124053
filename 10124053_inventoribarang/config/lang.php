
<?php

$bahasa = $_SESSION['bahasa'] ?? 'id';

if (!in_array($bahasa, ['id', 'en'], true)) {
    $bahasa = 'id';
}

$teks = [
    'id' => [
        // Nama aplikasi
        'app_name' => 'Inventaris Barang',

        // Navigasi
        'dashboard' => 'Dashboard',
        'items' => 'Data Barang',
        'logout' => 'Logout',
        'language' => 'Bahasa',

        // Login
        'login' => 'Login',
        'username' => 'Username',
        'password' => 'Password',
        'login_message' => 'Silakan login untuk melanjutkan.',
        'login_required' => 'Username dan password wajib diisi.',
        'invalid_login' => 'Username atau password salah.',

        // Dashboard
        'welcome' => 'Selamat datang',
        'total_items' => 'Total Barang',
        'total_stock' => 'Total Stok',
        'welcome_title' => 'Selamat Datang',
        'welcome_message' => 'Gunakan menu Data Barang untuk melihat, menambah, mengubah, dan menghapus data inventaris.',
        'add_item' => 'Tambah Barang',
        'item_list_title' => 'Daftar Barang',
        'item_list_description' => 'Informasi barang inventaris yang tersimpan.',
        'search_placeholder' => 'Cari kode, nama, atau kategori barang...',
        'search' => 'Cari',
        'reset' => 'Reset',
        'number' => 'No.',
        'item_not_found' => 'Barang tidak ditemukan.',

        // Data barang
        'item_code' => 'Kode Barang',
        'item_name' => 'Nama Barang',
        'category' => 'Kategori',
        'stock' => 'Total Stok',
        'storage_rack' => 'Rak Penyimpanan',
        'action' => 'Aksi',
        'edit' => 'Edit',
        'delete' => 'Hapus',
        'save' => 'Simpan',
        'save_changes' => 'Simpan Perubahan',
        'cancel' => 'Batal',
        'no_items' => 'Belum ada data barang.',

        // Tambah dan edit barang
        'edit_item' => 'Edit Barang',
        'add_item_title' => 'Tambah Barang',
        'add_item_description' => 'Masukkan informasi barang baru.',
        'edit_item_description' => 'Perbarui informasi barang yang dipilih.',

        // Pilihan kategori
        'select_category' => 'Pilih kategori',
        'stationery' => 'ATK',
        'electronics' => 'Elektronik',
        'cleaning' => 'Perlengkapan Kebersihan',
        'furniture' => 'Perabot',
        'other' => 'Lainnya',

        // Validasi dan pesan
        'required_fields' => 'Semua kolom wajib diisi.',
        'invalid_stock' => 'Total stok harus berupa bilangan bulat minimal 0.',
        'invalid_category' => 'Kategori barang tidak valid.',
        'duplicate_code' => 'Kode barang sudah digunakan. Gunakan kode lain.',
        'save_error' => 'Data gagal disimpan. Silakan coba kembali.',
        'invalid_request' => 'Permintaan tidak valid. Silakan muat ulang halaman.',
        'confirm_delete' => 'Yakin ingin menghapus barang ini?',

        // Pengaturan bahasa
        'language_settings' => 'Pengaturan Bahasa',
        'choose_language' => 'Pilih bahasa yang ingin digunakan.',
        'save_language' => 'Simpan Bahasa',
        'language_saved' => 'Pilihan bahasa berhasil disimpan.',

        // Pengguna
        'admin' => 'Administrator'
    ],

    'en' => [
        // Application name
        'app_name' => 'Inventory Management',

        // Navigation
        'dashboard' => 'Dashboard',
        'items' => 'Items',
        'logout' => 'Logout',
        'language' => 'Language',

        // Login
        'login' => 'Login',
        'username' => 'Username',
        'password' => 'Password',
        'login_message' => 'Please log in to continue.',
        'login_required' => 'Username and password are required.',
        'invalid_login' => 'Incorrect username or password.',

        // Dashboard
        'welcome' => 'Welcome',
        'total_items' => 'Total Items',
        'total_stock' => 'Total Stock',
        'welcome_title' => 'Welcome',
        'welcome_message' => 'Use the Items menu to view, add, edit, and delete inventory records.',
        'add_item' => 'Add Item',
        'item_list_title' => 'Item List',
        'item_list_description' => 'Saved inventory item information.',
        'search_placeholder' => 'Search by item code, name, or category...',
        'search' => 'Search',
        'reset' => 'Reset',
        'number' => 'No.',
        'item_not_found' => 'No matching items found.',

        // Item data
        'item_code' => 'Item Code',
        'item_name' => 'Item Name',
        'category' => 'Category',
        'stock' => 'Total Stock',
        'storage_rack' => 'Storage Rack',
        'action' => 'Actions',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'save' => 'Save',
        'save_changes' => 'Save Changes',
        'cancel' => 'Cancel',
        'no_items' => 'No inventory items found.',

        // Add and edit items
        'edit_item' => 'Edit Item',
        'add_item_title' => 'Add Item',
        'add_item_description' => 'Enter the new item information.',
        'edit_item_description' => 'Update the selected item information.',

        // Category options
        'select_category' => 'Select category',
        'stationery' => 'Stationery',
        'electronics' => 'Electronics',
        'cleaning' => 'Cleaning Supplies',
        'furniture' => 'Furniture',
        'other' => 'Other',

        // Validation and messages
        'required_fields' => 'All fields are required.',
        'invalid_stock' => 'Stock must be a non-negative whole number.',
        'invalid_category' => 'Invalid item category.',
        'duplicate_code' => 'Item code already exists. Use another code.',
        'save_error' => 'Failed to save data. Please try again.',
        'invalid_request' => 'Invalid request. Please reload the page.',
        'confirm_delete' => 'Are you sure you want to delete this item?',

        // Language settings
        'language_settings' => 'Language Settings',
        'choose_language' => 'Choose your preferred language.',
        'save_language' => 'Save Language',
        'language_saved' => 'Language preference saved successfully.',

        // User
        'admin' => 'Administrator'
    ]
];

/**
 * Mengambil teks berdasarkan bahasa yang sedang digunakan.
 */
function lang($key)
{
    global $teks, $bahasa;

    return $teks[$bahasa][$key]
        ?? $teks['id'][$key]
        ?? $key;
}
