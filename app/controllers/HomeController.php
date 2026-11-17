<?php

class HomeController extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    public function index()
    {
        $itemModel = $this->model('Item');
        $categoryModel = $this->model('Category');

        $data = [
            'title'      => 'RuangBaca - Perpustakaan Digital',
            'categories' => $categoryModel->getAll(), // sesuaikan dengan nama method di Category.php
            'books'      => $itemModel->getAll()      // sesuaikan dengan nama method di Item.php
        ];

        // Arahkan ke view halaman depan/dashboard katalog
        $this->view('dashboard/index', $data, false); // false agar tidak memakai header/footer Bootstrap lama
    }
}

