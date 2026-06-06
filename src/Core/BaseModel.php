<?php

declare(strict_types=1);

namespace Src\Core;

abstract class BaseModel
{
    protected Database $db;
    protected string $table = '';
    protected array  $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(): array
    {
        return $this->db
            ->query("SELECT * FROM {$this->table} ORDER BY id DESC")
            ->fetchAll();
    }

    public function find(int $id): ?array
    {
        $result = $this->db
            ->query("SELECT * FROM {$this->table} WHERE id = ?", [$id])
            ->fetch();
        return $result ?: null;
    }

    public function findBy(string $column, mixed $value): ?array
    {
        $result = $this->db
            ->query(
                "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1",
                [$value]
            )
            ->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $filtered     = $this->filterFillable($data);
        $columns      = implode(', ', array_keys($filtered));
        $placeholders = implode(', ', array_fill(0, count($filtered), '?'));

        $this->db->query(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})",
            array_values($filtered)
        );

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $filtered = $this->filterFillable($data);
        if (empty($filtered)) return false;

        $setClause = implode(', ', array_map(fn($c) => "{$c} = ?", array_keys($filtered)));
        $params    = [...array_values($filtered), $id];

        return $this->db
            ->query("UPDATE {$this->table} SET {$setClause} WHERE id = ?", $params)
            ->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        return $this->db
            ->query("DELETE FROM {$this->table} WHERE id = ?", [$id])
            ->rowCount() > 0;
    }

    private function filterFillable(array $data): array
    {
        return array_filter(
            $data,
            fn($key) => in_array($key, $this->fillable, true),
            ARRAY_FILTER_USE_KEY
        );
    }
}