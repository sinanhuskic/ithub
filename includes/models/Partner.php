<?php
/**
 * IT Hub Zavidovići - Partner Model
 */

class Partner
{
    /**
     * Get all partners
     */
    public static function all($activeOnly = false)
    {
        $sql = "SELECT * FROM partners";
        if ($activeOnly) {
            $sql .= " WHERE active = 1";
        }
        $sql .= " ORDER BY sort_order ASC, id ASC";

        return db()->fetchAll($sql);
    }

    /**
     * Find partner by ID
     */
    public static function find($id)
    {
        return db()->fetch("SELECT * FROM partners WHERE id = ?", [$id]);
    }

    /**
     * Create new partner
     */
    public static function create($data)
    {
        return db()->insert("partners", [
            "name" => $data["name"],
            "logo_path" => $data["logo_path"],
            "website_url" => $data["website_url"] ?? "",
            "sort_order" => $data["sort_order"] ?? 0,
            "active" => $data["active"] ?? 1,
        ]);
    }

    /**
     * Update partner
     */
    public static function update($id, $data)
    {
        $updateData = [];

        if (isset($data["name"])) {
            $updateData["name"] = $data["name"];
        }
        if (isset($data["logo_path"])) {
            $updateData["logo_path"] = $data["logo_path"];
        }
        if (isset($data["website_url"])) {
            $updateData["website_url"] = $data["website_url"];
        }
        if (isset($data["sort_order"])) {
            $updateData["sort_order"] = $data["sort_order"];
        }
        if (isset($data["active"])) {
            $updateData["active"] = $data["active"] ? 1 : 0;
        }

        return db()->update("partners", $updateData, "id = ?", [$id]);
    }

    /**
     * Delete partner
     */
    public static function delete($id)
    {
        $partner = self::find($id);
        if ($partner && strpos($partner["logo_path"], "uploads/") === 0) {
            // Delete physical file only if it's in uploads folder
            $basePath = dirname(__DIR__, 2) . "/public/";
            if (file_exists($basePath . $partner["logo_path"])) {
                unlink($basePath . $partner["logo_path"]);
            }
        }
        return db()->delete("partners", "id = ?", [$id]);
    }

    /**
     * Toggle active status
     */
    public static function toggleActive($id)
    {
        $partner = self::find($id);
        if ($partner) {
            $newStatus = $partner["active"] ? 0 : 1;
            return db()->update(
                "partners",
                ["active" => $newStatus],
                "id = ?",
                [$id],
            );
        }
        return false;
    }

    /**
     * Count partners
     */
    public static function count($activeOnly = false)
    {
        if ($activeOnly) {
            return db()->count("partners", "active = 1");
        }
        return db()->count("partners");
    }

    /**
     * Get max sort_order
     */
    public static function getMaxSortOrder()
    {
        $result = db()->fetch(
            "SELECT MAX(sort_order) as max_order FROM partners",
        );
        return $result ? (int) $result["max_order"] : 0;
    }

    /**
     * Upload logo
     */
    public static function uploadLogo($file)
    {
        $uploadDir = dirname(__DIR__, 2) . "/public/uploads/partners/";

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowedExtensions = ["jpg", "jpeg", "png", "gif", "webp", "svg"];

        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception(
                "Nedozvoljen tip fajla. Dozvoljeni: " .
                    implode(", ", $allowedExtensions),
            );
        }

        $filename =
            "partner-" .
            time() .
            "-" .
            bin2hex(random_bytes(4)) .
            "." .
            $extension;
        $filePath = $uploadDir . $filename;

        // Move uploaded file
        if (!move_uploaded_file($file["tmp_name"], $filePath)) {
            throw new Exception("Greška pri uploadu logotipa.");
        }

        return "uploads/partners/" . $filename;
    }
}
