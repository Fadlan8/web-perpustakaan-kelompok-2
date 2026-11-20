<?php

class Item extends Model
{
    use SoftDeletes;
    protected string $table = 'items';

    public function all(): array
    {
        return $this->db->query(
            "SELECT i.*, c.nama_kategori, c.nama_kategori AS category_name FROM items i LEFT JOIN categories c ON i.category_id = c.id WHERE i.deleted_at IS NULL ORDER BY i.id DESC"
        )->fetchAll();
    }
    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare("SELECT i.*, c.nama_kategori, c.nama_kategori AS category_name FROM items i LEFT JOIN categories c ON i.category_id = c.id WHERE i.deleted_at IS NULL AND (i.kode_buku LIKE ? OR i.judul LIKE ? OR i.penulis LIKE ? OR i.isbn LIKE ?) ORDER BY i.id DESC");
        $keyword = '%' . $keyword . '%';
        $stmt->execute([$keyword, $keyword, $keyword, $keyword]);
        return $stmt->fetchAll();
    }

    public function find(int $id, bool $withTrashed = false)
    {
        $sql = "SELECT i.*, c.nama_kategori, c.nama_kategori AS category_name
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.id
                WHERE i.id = ?";
        $params = [$id];
        if (!$withTrashed) {
            $sql .= " AND i.deleted_at IS NULL";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function create(array $data): bool
    {
        return $this->db->prepare(
            "INSERT INTO items (kode_buku, isbn, judul, penulis, penerbit, tahun_terbit, category_id, stok_total, stok_tersedia, deskripsi, cover)
             VALUES (:kode_buku, :isbn, :judul, :penulis, :penerbit, :tahun_terbit, :category_id, :stok_total, :stok_tersedia, :deskripsi, :cover)"
        )->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        return $this->db->prepare(
            "UPDATE items SET kode_buku = :kode_buku, isbn = :isbn, judul = :judul, penulis = :penulis, penerbit = :penerbit, tahun_terbit = :tahun_terbit, category_id = :category_id, 
                stok_total = :stok_total, stok_tersedia = :stok_tersedia, deskripsi = :deskripsi, cover = :cover WHERE id = :id"
        )->execute($data);
    }

    public function generateCode(): string
    {
        $max = $this->db->query(
            "SELECT MAX(CAST(SUBSTRING(kode_buku, 4) AS UNSIGNED)) FROM items WHERE code LIKE 'BKU%'"
        )->fetchColumn();
        return 'BKU' . str_pad((string) (((int) $max) + 1), 4, '0', STR_PAD_LEFT);
    }

    public function paginate(int $limit = 10, int $page = 1, string $search = '', string $categoryId = '', bool $trashed = false): array
    {
        $offset = ($page - 1) * $limit;
        $where = [$trashed ? "i.deleted_at IS NOT NULL" : "i.deleted_at IS NULL"];
        $params = [];

        if ($search !== '') {
            $where[] = "(i.kode_buku LIKE :search OR i.judul LIKE :search OR i.penulis LIKE :search OR c.nama_kategori LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        if ($categoryId !== '') {
            $where[] = "i.category_id = :category_id";
            $params['category_id'] = (int) $categoryId;
        }
         $whereSql = 'WHERE ' . implode(' AND ', $where);

        $count = $this->db->prepare("SELECT COUNT(*) FROM items i LEFT JOIN categories c ON i.category_id = c.id {$whereSql}");
        $count->execute($params);
        $total = (int) $count->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT i.*, c.nama_kategori, c.nama_kategori AS category_name FROM items i LEFT JOIN categories c ON i.category_id = c.id
             {$whereSql} ORDER BY i.id DESC LIMIT {$limit} OFFSET {$offset}"
        );
        $stmt->execute($params);

        return [
            'data' => $stmt->fetchAll(),
            'current_page' => $page,
            'total_pages' => (int) ceil($total / max($limit, 1)),
            'total_data' => $total,
        ];
    }

    public function totalItems(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM items WHERE deleted_at IS NULL")->fetchColumn();
    }

    public function totalStock(): int
    {
        return (int) $this->db->query("SELECT COALESCE(SUM(stok_tersedia), 0) FROM items WHERE deleted_at IS NULL")->fetchColumn();
    }
    public function lowStockCount(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM items WHERE stok_tersedia <= 5 AND deleted_at IS NULL")->fetchColumn();
    }

    public function lowStockItems(): array
    {
        return $this->db->query(
            "SELECT i.*, c.nama_kategori, c.nama_kategori AS category_name FROM items i LEFT JOIN categories c ON i.category_id = c.id
             WHERE i.stok_tersedia <= 5 AND i.deleted_at IS NULL ORDER BY i.stok_tersedia ASC LIMIT 10"
        )->fetchAll();
    }
    public function report(array $filters = []): array
    {
        $where = ["i.deleted_at IS NULL"];
        $params = [];
        if (!empty($filters['search'])) {
            $where[] = "(i.kode_buku LIKE :search OR i.judul LIKE :search OR i.penulisLIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }
        if (!empty($filters['category'])) {
            $where[] = "i.category_id = :category_id";
            $params['category_id'] = (int) $filters['category'];
        }
        if (!empty($filters['low_stock'])) {
            $where[] = "i.stok_tersedia <= :threshold";
            $params['threshold'] = (int) ($filters['threshold'] ?? 5);
        }
        $sql = "SELECT i.kode_buku, i.isbn, i.judul, i.penulis, i.penerbit, i.tahun_terbit, c.nama_kategori AS category_name, i.stok_total, i.stok_tersedia,
                FROM items i
                LEFT JOIN categories c ON i.category_id = c.id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY i.judul ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function topStock(int $limit = 5): array
    {
        $sql = "SELECT judul, stok_tersedia
            FROM items
            WHERE deleted_at IS NULL
            ORDER BY stok_tersedia DESC
            LIMIT {$limit}";
        return $this->db->query($sql)->fetchAll();
    }
}
