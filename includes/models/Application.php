<?php
/**
 * IT Hub Zavidovici - Application Model
 */

class Application
{
    /**
     * Get all applications
     */
    public static function all($programId = null)
    {
        $sql = "SELECT a.*, p.title as program_title
                FROM applications_2 a
                LEFT JOIN programs p ON a.program_id = p.id";
        $params = [];

        if ($programId) {
            $sql .= " WHERE a.program_id = ?";
            $params[] = $programId;
        }

        $sql .= " ORDER BY a.created_at DESC";

        return db()->fetchAll($sql, $params);
    }

    /**
     * Find application by ID
     */
    public static function find($id)
    {
        return db()->fetch(
            "SELECT a.*, p.title as program_title
             FROM applications_2 a
             LEFT JOIN programs p ON a.program_id = p.id
             WHERE a.id = ?",
            [$id],
        );
    }

    /**
     * Create new application
     */
    public static function create($data)
    {
        return db()->insert("applications_2", $data);
    }

    /**
     * Update application
     */
    public static function update($id, $data)
    {
        return db()->update("applications_2", $data, "id = ?", [$id]);
    }

    /**
     * Delete application
     */
    public static function delete($id)
    {
        return db()->delete("applications_2", "id = ?", [$id]);
    }

    /**
     * Update status
     */
    public static function updateStatus($id, $status)
    {
        return db()->update("applications_2", ["status" => $status], "id = ?", [
            $id,
        ]);
    }

    /**
     * Update admin notes
     */
    public static function updateNotes($id, $notes)
    {
        return db()->update(
            "applications_2",
            ["admin_notes" => $notes],
            "id = ?",
            [$id],
        );
    }

    /**
     * Count applications
     */
    public static function count($programId = null, $status = null)
    {
        $sql = "SELECT COUNT(*) as count FROM applications_2 WHERE 1=1";
        $params = [];

        if ($programId) {
            $sql .= " AND program_id = ?";
            $params[] = $programId;
        }

        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        $result = db()->fetch($sql, $params);
        return $result ? (int) $result["count"] : 0;
    }

    /**
     * Get applications by status
     */
    public static function getByStatus($status, $programId = null)
    {
        $sql = "SELECT a.*, p.title as program_title
                FROM applications_2 a
                LEFT JOIN programs p ON a.program_id = p.id
                WHERE a.status = ?";
        $params = [$status];

        if ($programId) {
            $sql .= " AND a.program_id = ?";
            $params[] = $programId;
        }

        $sql .= " ORDER BY a.created_at DESC";

        return db()->fetchAll($sql, $params);
    }

    /**
     * Get available statuses
     */
    public static function getStatuses()
    {
        return [
            "nova" => "Nova",
            "pregledana" => "Pregledana",
            "pozvana" => "Pozvana na razgovor",
            "odbijena" => "Odbijena",
            "primljena" => "Primljena",
        ];
    }

    /**
     * Get status label
     */
    public static function getStatusLabel($status)
    {
        $statuses = self::getStatuses();
        return $statuses[$status] ?? $status;
    }

    /**
     * Get status color class
     */
    public static function getStatusColor($status)
    {
        $colors = [
            "nova" => "blue",
            "pregledana" => "yellow",
            "pozvana" => "purple",
            "odbijena" => "red",
            "primljena" => "green",
        ];
        return $colors[$status] ?? "gray";
    }

    /**
     * Get programs with applications
     */
    public static function getProgramsWithApplications()
    {
        return db()->fetchAll(
            "SELECT p.id, p.title, COUNT(a.id) as application_count
             FROM programs p
             INNER JOIN applications_2 a ON p.id = a.program_id
             GROUP BY p.id, p.title
             ORDER BY p.title",
        );
    }
}
