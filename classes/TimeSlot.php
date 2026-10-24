<?php
class TimeSlot extends Model
{
    private const TAKEN = "(SELECT COUNT(*) FROM reservations r
                            WHERE r.slot_id = s.id AND r.status IN ('pending','confirmed'))";

    // Future slots of one date (optionally one facility) with places taken
    public function forDate(string $date, int $userId, ?int $facilityId = null): array
    {
        $sql = "SELECT s.*, f.name AS facility, " . self::TAKEN . " AS taken,
                    (SELECT COUNT(*) FROM reservations r
                      WHERE r.slot_id = s.id AND r.user_id = ?
                        AND r.status IN ('pending','confirmed')) AS mine
                FROM time_slots s
                JOIN facilities f ON f.id = s.facility_id
                WHERE s.slot_date = ? AND TIMESTAMP(s.slot_date, s.start_time) > NOW()";
        $params = [$userId, $date];

        if ($facilityId) {
            $sql .= ' AND s.facility_id = ?';
            $params[] = $facilityId;
        }
        $sql .= ' ORDER BY s.start_time, f.name';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function all(): array
    {
        return $this->db->query(
            "SELECT s.*, f.name AS facility, " . self::TAKEN . " AS taken
             FROM time_slots s JOIN facilities f ON f.id = s.facility_id
             ORDER BY s.slot_date DESC, s.start_time"
        )->fetchAll();
    }

    public function validate(int $facilityId, string $date, string $start, string $end, int $capacity): array
    {
        $errors = [];

        if (!(new Facility())->find($facilityId)) {
            $errors[] = 'Choose a valid facility.';
        }

        $parsed = DateTime::createFromFormat('Y-m-d', $date);
        if (!$parsed || $parsed->format('Y-m-d') !== $date) {
            $errors[] = 'Enter a valid date.';
        } elseif ($date < date('Y-m-d')) {
            $errors[] = 'The date cannot be in the past.';
        }

        if (!preg_match('/^\d{2}:\d{2}$/', $start) || !preg_match('/^\d{2}:\d{2}$/', $end)) {
            $errors[] = 'Enter valid start and end times.';
        } elseif ($end <= $start) {
            $errors[] = 'End time must be after start time.';
        }

        if ($capacity < 1 || $capacity > 500) {
            $errors[] = 'Capacity must be between 1 and 500.';
        }
        return $errors;
    }

    public function create(int $facilityId, string $date, string $start, string $end, int $capacity): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO time_slots (facility_id, slot_date, start_time, end_time, capacity)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$facilityId, $date, $start, $end, $capacity]);
    }

    // Business rule: slots with pending/confirmed reservations are never deleted
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM time_slots WHERE id = ?
             AND NOT EXISTS (SELECT 1 FROM reservations r
                             WHERE r.slot_id = time_slots.id AND r.status IN ('pending','confirmed'))"
        );
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
