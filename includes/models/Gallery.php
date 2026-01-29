<?php
/**
 * IT Hub Zavidovići - Gallery Model
 */

class Gallery
{
    /**
     * Get all gallery images
     */
    public static function all($activeOnly = false)
    {
        $sql = "SELECT * FROM gallery";
        if ($activeOnly) {
            $sql .= " WHERE active = 1";
        }
        $sql .= " ORDER BY sort_order ASC, id DESC";

        return db()->fetchAll($sql);
    }

    /**
     * Find image by ID
     */
    public static function find($id)
    {
        return db()->fetch("SELECT * FROM gallery WHERE id = ?", [$id]);
    }

    /**
     * Create new image entry
     */
    public static function create($data)
    {
        return db()->insert("gallery", [
            "image_path" => $data["image_path"],
            "thumb_path" => $data["thumb_path"] ?? $data["image_path"],
            "alt_text" => $data["alt_text"] ?? "",
            "size_class" => $data["size_class"] ?? "normal",
            "sort_order" => $data["sort_order"] ?? 0,
            "active" => $data["active"] ?? 1,
        ]);
    }

    /**
     * Update image
     */
    public static function update($id, $data)
    {
        $updateData = [];

        if (isset($data["image_path"])) {
            $updateData["image_path"] = $data["image_path"];
        }
        if (isset($data["thumb_path"])) {
            $updateData["thumb_path"] = $data["thumb_path"];
        }
        if (isset($data["alt_text"])) {
            $updateData["alt_text"] = $data["alt_text"];
        }
        if (isset($data["size_class"])) {
            $updateData["size_class"] = $data["size_class"];
        }
        if (isset($data["sort_order"])) {
            $updateData["sort_order"] = $data["sort_order"];
        }
        if (isset($data["active"])) {
            $updateData["active"] = $data["active"] ? 1 : 0;
        }

        return db()->update("gallery", $updateData, "id = ?", [$id]);
    }

    /**
     * Delete image
     */
    public static function delete($id)
    {
        $image = self::find($id);
        if ($image) {
            // Delete physical files
            $basePath = dirname(__DIR__, 2) . "/public/";
            if (file_exists($basePath . $image["image_path"])) {
                unlink($basePath . $image["image_path"]);
            }
            if (
                $image["thumb_path"] &&
                file_exists($basePath . $image["thumb_path"])
            ) {
                unlink($basePath . $image["thumb_path"]);
            }
        }
        return db()->delete("gallery", "id = ?", [$id]);
    }

    /**
     * Toggle active status
     */
    public static function toggleActive($id)
    {
        $image = self::find($id);
        if ($image) {
            $newStatus = $image["active"] ? 0 : 1;
            return db()->update("gallery", ["active" => $newStatus], "id = ?", [
                $id,
            ]);
        }
        return false;
    }

    /**
     * Count images
     */
    public static function count($activeOnly = false)
    {
        if ($activeOnly) {
            return db()->count("gallery", "active = 1");
        }
        return db()->count("gallery");
    }

    /**
     * Upload and process image
     */
    public static function uploadImage(
        $file,
        $altText = "",
        $sizeClass = "normal",
    ) {
        $uploadDir = dirname(__DIR__, 2) . "/public/uploads/gallery/";
        $thumbDir = $uploadDir . "thumbs/";

        // Create directories if they don't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        if (!is_dir($thumbDir)) {
            mkdir($thumbDir, 0755, true);
        }

        // Generate unique filename
        $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowedExtensions = ["jpg", "jpeg", "png", "gif", "webp"];

        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception(
                "Nedozvoljen tip fajla. Dozvoljeni: " .
                    implode(", ", $allowedExtensions),
            );
        }

        $filename =
            "gallery-" .
            time() .
            "-" .
            bin2hex(random_bytes(4)) .
            "." .
            $extension;
        $imagePath = $uploadDir . $filename;
        $thumbPath = $thumbDir . "thumb-" . $filename;

        // Move uploaded file
        if (!move_uploaded_file($file["tmp_name"], $imagePath)) {
            throw new Exception("Greška pri uploadu slike.");
        }

        // Create thumbnail
        self::createThumbnail($imagePath, $thumbPath, 400, 300);

        // Get max sort_order to add new image at the end
        $maxOrder = self::getMaxSortOrder();

        // Save to database
        return self::create([
            "image_path" => "uploads/gallery/" . $filename,
            "thumb_path" => "uploads/gallery/thumbs/thumb-" . $filename,
            "alt_text" => $altText,
            "size_class" => $sizeClass,
            "sort_order" => $maxOrder + 1,
            "active" => 1,
        ]);
    }

    /**
     * Get max sort_order
     */
    public static function getMaxSortOrder()
    {
        $result = db()->fetch(
            "SELECT MAX(sort_order) as max_order FROM gallery",
        );
        return $result ? (int) $result["max_order"] : 0;
    }

    /**
     * Create thumbnail
     */
    private static function createThumbnail(
        $source,
        $destination,
        $maxWidth,
        $maxHeight,
    ) {
        $imageInfo = getimagesize($source);
        if (!$imageInfo) {
            // Just copy the file if we can't process it
            copy($source, $destination);
            return;
        }

        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mimeType = $imageInfo["mime"];

        // Calculate new dimensions
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        $newWidth = (int) ($originalWidth * $ratio);
        $newHeight = (int) ($originalHeight * $ratio);

        // Create image from source
        switch ($mimeType) {
            case "image/jpeg":
                $sourceImage = imagecreatefromjpeg($source);
                break;
            case "image/png":
                $sourceImage = imagecreatefrompng($source);
                break;
            case "image/gif":
                $sourceImage = imagecreatefromgif($source);
                break;
            case "image/webp":
                $sourceImage = imagecreatefromwebp($source);
                break;
            default:
                copy($source, $destination);
                return;
        }

        // Create thumbnail
        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG and GIF
        if ($mimeType === "image/png" || $mimeType === "image/gif") {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
            imagefill($thumbnail, 0, 0, $transparent);
        }

        // Resize
        imagecopyresampled(
            $thumbnail,
            $sourceImage,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $originalWidth,
            $originalHeight,
        );

        // Save thumbnail
        switch ($mimeType) {
            case "image/jpeg":
                imagejpeg($thumbnail, $destination, 85);
                break;
            case "image/png":
                imagepng($thumbnail, $destination, 8);
                break;
            case "image/gif":
                imagegif($thumbnail, $destination);
                break;
            case "image/webp":
                imagewebp($thumbnail, $destination, 85);
                break;
        }

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($thumbnail);
    }
}
