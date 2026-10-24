<?php
class Reservation extends Model
{
    // action => [new status, statuses it is allowed from]
    private const RULES = [
        'confirm' => ['confirmed', ['pending']],
        'reject'  => ['rejected',  ['pending']],
        'cancel'  => ['cancelled', ['pending', 'confirmed']],
    ];

    // Returns an error message, or null when the reservation was saved
    public function create(int $userId, int $slotId, string $note): ?string
    {
        if (strlen($note) > 255) {
            return 'Note is too long (max 255 characters).';
        }

        $this->db->beginTransaction();

        // Lock the slot row so two users cannot take the last place together
        $stmt = $this->db->prepare(
            'SELECT *, (TIMESTAMP(slot_date, start_time) > NOW()) AS is_future
             FROM time_slots WHERE id = ? FOR UPDATE'
        );
        $stmt->execute([$slotId]);
        $slot = $stmt->fetch();

        $error = null;
        if (!$slot) {
            $error = 'Time slot not found.';
        } elseif (!$slot['is_future']) {
            $error = 'You cannot reserve a time slot in the past.';
        } else {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) AS taken, SUM(user_id = ?) AS mine
                 FROM reservations
                 WHERE slot_id = ? AND status IN ('pending','confirmed')"
            );
            $stmt->execute([$userId, $slotId]);
            $count = $stmt->fetch();

            if ($count['mine'] > 0) {
                $error = 'You already reserved this time slot.';
            } elseif ($count['taken'] >= $slot['capacity']) {
                $error = 'This time slot is full.';
            }
        }

        if ($error) {
            $this->db->rollBack();
            return $error;
        }

        $stmt = $this->db->prepare('INSERT INTO reservations (user_id, slot_id, note) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $slotId, $note !== '' ? $note : null]);
        $this->db->commit();
        return null;
    }

    public function forUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, f.name AS facility, s.slot_date, s.start_time, s.end_time,
                    (TIMESTAMP(s.slot_date, s.start_time) > NOW()) AS is_future
             FROM reservations r
             JOIN time_slots s ON s.id = r.slot_id
             JOIN facilities f ON f.id = s.facility_id
             WHERE r.user_id = ?
             ORDER BY s.slot_date DESC, s.start_time DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Owner only, active reservations only, before the slot starts
    public function cancelByUser(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE reservations r
             JOIN time_slots s ON s.id = r.slot_id
             SET r.status = 'cancelled'
             WHERE r.id = ? AND r.user_id = ?
               AND r.status IN ('pending','confirmed')
               AND TIMESTAMP(s.slot_date, s.start_time) > NOW()"
        );
        $stmt->execute([$id, $userId]);
        return $stmt->rowCount() > 0;
    }

    // Admin list with search (name/email) and status filter
    public function search(string $q = '', string $status = ''): array
    {
        $sql = "SELECT r.*, u.name, u.email, f.name AS facility, s.slot_date, s.start_time, s.end_time
                FROM reservations r
                JOIN users u ON u.id = r.user_id
                JOIN time_slots s ON s.id = r.slot_id
                JOIN facilities f ON f.id = s.facility_id
                WHERE (u.name LIKE ? OR u.email LIKE ?)";
        $params = ["%$q%", "%$q%"];

        if (in_array($status, ['pending', 'confirmed', 'rejected', 'cancelled'], true)) {
            $sql .= ' AND r.status = ?';
            $params[] = $status;
        }
        $sql .= ' ORDER BY s.slot_date DESC, s.start_time DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Admin: confirm / reject / cancel, only if the business rules allow it
    public function changeStatus(int $id, string $action): bool
    {
        if (!isset(self::RULES[$action])) {
            return false;
        }
        [$newStatus, $allowedFrom] = self::RULES[$action];
        $marks = implode(',', array_fill(0, count($allowedFrom), '?'));

        $stmt = $this->db->prepare("UPDATE reservations SET status = ? WHERE id = ? AND status IN ($marks)");
        $stmt->execute(array_merge([$newStatus, $id], $allowedFrom));
        return $stmt->rowCount() > 0;
    }

    // Which admin buttons to show for a given status
    public static function actionsFor(string $status): array
    {
        $actions = [];
        foreach (self::RULES as $action => [, $allowedFrom]) {
            if (in_array($status, $allowedFrom, true)) {
                $actions[] = $action;
            }
        }
        return $actions;
    }

    public function countsByStatus(): array
    {
        $counts = ['pending' => 0, 'confirmed' => 0, 'rejected' => 0, 'cancelled' => 0];
        foreach ($this->db->query('SELECT status, COUNT(*) AS total FROM reservations GROUP BY status') as $row) {
            $counts[$row['status']] = (int) $row['total'];
        }
        return $counts;
    }
}
