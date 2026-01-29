<?php
/**
 * IT Hub Zavidovići - Program Model
 */

class Program
{
    /**
     * Get all programs
     */
    public static function all($activeOnly = false)
    {
        $sql = "SELECT * FROM programs";
        if ($activeOnly) {
            $sql .= " WHERE active = 1";
        }
        $sql .= " ORDER BY sort_order ASC, id ASC";

        return db()->fetchAll($sql);
    }

    /**
     * Find program by ID
     */
    public static function find($id)
    {
        return db()->fetch("SELECT * FROM programs WHERE id = ?", [$id]);
    }

    /**
     * Create new program
     */
    public static function create($data)
    {
        $insertData = self::prepareData($data);
        return db()->insert("programs", $insertData);
    }

    /**
     * Update program
     */
    public static function update($id, $data)
    {
        $updateData = self::prepareData($data);
        return db()->update("programs", $updateData, "id = ?", [$id]);
    }

    /**
     * Delete program
     */
    public static function delete($id)
    {
        return db()->delete("programs", "id = ?", [$id]);
    }

    /**
     * Toggle active status
     */
    public static function toggleActive($id)
    {
        $program = self::find($id);
        if ($program) {
            $newStatus = $program["active"] ? 0 : 1;
            return db()->update(
                "programs",
                ["active" => $newStatus],
                "id = ?",
                [$id],
            );
        }
        return false;
    }

    /**
     * Update sort order
     */
    public static function updateOrder($id, $order)
    {
        return db()->update("programs", ["sort_order" => $order], "id = ?", [
            $id,
        ]);
    }

    /**
     * Prepare data for insert/update
     */
    private static function prepareData($data)
    {
        $prepared = [];

        $fields = [
            "title",
            "description",
            "duration",
            "level",
            "icon",
            "period",
            "format",
            "participants",
            "status",
            "full_description",
            "requirements",
            "sort_order",
        ];

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $prepared[$field] = $data[$field];
            }
        }

        // Boolean fields
        if (isset($data["featured"])) {
            $prepared["featured"] = $data["featured"] ? 1 : 0;
        }
        if (isset($data["active"])) {
            $prepared["active"] = $data["active"] ? 1 : 0;
        }

        // JSON fields
        if (isset($data["technologies"])) {
            $prepared["technologies"] = is_array($data["technologies"])
                ? json_encode($data["technologies"])
                : $data["technologies"];
        }
        if (isset($data["highlights"])) {
            $prepared["highlights"] = is_array($data["highlights"])
                ? json_encode(array_filter($data["highlights"]))
                : $data["highlights"];
        }

        return $prepared;
    }

    /**
     * Get program with decoded JSON fields
     */
    public static function findWithDecoded($id)
    {
        $program = self::find($id);
        if ($program) {
            $program["technologies"] =
                json_decode($program["technologies"] ?? "[]", true) ?: [];
            $program["highlights"] =
                json_decode($program["highlights"] ?? "[]", true) ?: [];
        }
        return $program;
    }

    /**
     * Get all programs with decoded JSON fields
     */
    public static function allWithDecoded($activeOnly = false)
    {
        $programs = self::all($activeOnly);
        foreach ($programs as &$program) {
            $program["technologies"] =
                json_decode($program["technologies"] ?? "[]", true) ?: [];
            $program["highlights"] =
                json_decode($program["highlights"] ?? "[]", true) ?: [];
        }
        return $programs;
    }

    /**
     * Count programs
     */
    public static function count($activeOnly = false)
    {
        if ($activeOnly) {
            return db()->count("programs", "active = 1");
        }
        return db()->count("programs");
    }

    /**
     * Get available technologies
     */
    public static function getAvailableTechnologies()
    {
        return [
            // Web Development
            "html5" => "HTML5",
            "css3" => "CSS3",
            "javascript" => "JavaScript",
            "react" => "React",
            "nextjs" => "Next.js",
            "nodejs" => "Node.js",
            "python" => "Python",
            "php" => "PHP",
            "laravel" => "Laravel",
            "mysql" => "MySQL",
            "git" => "Git",
            // AI
            "claude" => "Claude AI",
            // Office / Windows
            "windows8" => "Windows",
            "word" => "MS Word",
            "excel" => "MS Excel",
            "powerpoint" => "PowerPoint",
            // 3D
            "blender" => "Blender",
            "bambu" => "Bambu Lab",
        ];
    }

    /**
     * Get available statuses
     */
    public static function getStatuses()
    {
        return [
            "Završen" => "Završen",
            "Uskoro" => "Uskoro",
            "U pripremi" => "U pripremi",
            "Prijave u toku" => "Prijave u toku",
        ];
    }

    /**
     * Get available levels
     */
    public static function getLevels()
    {
        return [
            "Početnik" => "Početnik",
            "Srednji" => "Srednji",
            "Napredni" => "Napredni",
        ];
    }

    /**
     * Get available icons
     */
    public static function getIcons()
    {
        return [
            "code" => "Programiranje",
            "brain" => "AI / Vještačka inteligencija",
            "file-text" => "Dokumenti / Office",
            "box" => "3D / Modeliranje",
            "palette" => "Dizajn",
            "camera" => "Fotografija / Video",
            "globe" => "Web",
            "smartphone" => "Mobilne aplikacije",
            "database" => "Baze podataka",
            "shield" => "Sigurnost",
        ];
    }
}
