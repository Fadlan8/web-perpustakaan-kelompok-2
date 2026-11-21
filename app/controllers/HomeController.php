<?php

class HomeController extends Controller
{
    public function __construct()
    {
        $this->requireLogin();
    }

    public function index()
    {
        $itemModel     = $this->model('Item');
        $categoryModel = $this->model('Category');

        $data = [
            'title'      => 'RuangBaca — Perpustakaan Digital',
            'categories' => $categoryModel->all(),
            'books'      => $itemModel->all()
        ];

        $this->view('dashboard/index', $data);
    }
}