<?php

class DashboardController extends HomeController
{
    public function index()
    {
        $role = $_SESSION['user']['role'] ?? 'anggota';

        if (in_array($role, ['admin', 'petugas'])) {
            $itemModel     = $this->model('Item');
            $categoryModel = $this->model('Category');
            $userModel     = $this->model('User');

            $data = [
                'title'        => 'Admin RUANGMP',
                'books'        => $itemModel->all(),
                'categories'   => $categoryModel->all(),
                'users'        => method_exists($userModel, 'all') ? $userModel->all() : [],
                'transactions' => []
            ];

            $this->view('dashboard/admin', $data);
            return;
        }

        parent::index();
    }
}

