<?php
class Facility extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM facilities ORDER BY name')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM facilities WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    // $d = name, short_description, description, image, opening_hours, guidelines
    public function validate(array $d): array
    {
        $errors = [];

        if ($d['name'] === '' || strlen($d['name']) > 100) {
            $errors[] = 'Name is required (max 100 characters).';
        }
        if ($d['short_description'] === '' || strlen($d['short_description']) > 255) {
            $errors[] = 'Short description is required (max 255 characters).';
        }
        if ($d['description'] === '') {
            $errors[] = 'Description is required.';
        }
        if ($d['image'] !== '' && !preg_match('/^[\w\-]+\.(jpg|jpeg|png|webp|svg)$/i', $d['image'])) {
            $errors[] = 'Image must be a file name like pool.jpg (inside assets/images).';
        }
        if (strlen($d['opening_hours']) > 100) {
            $errors[] = 'Opening hours are too long.';
        }
        return $errors;
    }

    public function create(array $d): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO facilities (name, short_description, description, image, opening_hours, guidelines)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute($this->values($d));
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->db->prepare(
            'UPDATE facilities
             SET name = ?, short_description = ?, description = ?, image = ?, opening_hours = ?, guidelines = ?
             WHERE id = ?'
        );
        $stmt->execute(array_merge($this->values($d), [$id]));
    }

    private function values(array $d): array
    {
        return [
            $d['name'],
            $d['short_description'],
            $d['description'],
            $d['image'] !== '' ? $d['image'] : 'placeholder.svg',
            $d['opening_hours'],
            $d['guidelines'],
        ];
    }

    // Guidelines are stored one per line
    public static function guidelineList(?string $text): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", (string) $text))));
    }

    // Image URL, falls back to the placeholder if the file is missing
    public static function imageUrl(?string $file): string
    {
        if (!$file || !is_file(ROOT . '/assets/images/' . $file)) {
            $file = 'placeholder.svg';
        }
        return asset('images/' . $file);
    }
}
