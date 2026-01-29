<?php
/**
 * IT Hub Zavidovići - Setting Model
 */

class Setting
{
    private static $cache = [];

    /**
     * Get a setting value
     */
    public static function get($key, $default = null)
    {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $setting = db()->fetch(
            "SELECT setting_value FROM settings WHERE setting_key = ?",
            [$key]
        );

        $value = $setting ? $setting['setting_value'] : $default;
        self::$cache[$key] = $value;

        return $value;
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $group = 'general')
    {
        $existing = db()->fetch(
            "SELECT id FROM settings WHERE setting_key = ?",
            [$key]
        );

        if ($existing) {
            db()->update(
                'settings',
                ['setting_value' => $value],
                'setting_key = ?',
                [$key]
            );
        } else {
            db()->insert('settings', [
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_group' => $group,
            ]);
        }

        self::$cache[$key] = $value;
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        $settings = db()->fetchAll(
            "SELECT setting_key, setting_value FROM settings WHERE setting_group = ?",
            [$group]
        );

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }

        return $result;
    }

    /**
     * Update multiple settings
     */
    public static function updateMultiple($settings, $group = 'general')
    {
        foreach ($settings as $key => $value) {
            self::set($key, $value, $group);
        }
    }

    /**
     * Get contact information
     */
    public static function getContact()
    {
        return self::getByGroup('contact');
    }

    /**
     * Get about information
     */
    public static function getAbout()
    {
        return self::getByGroup('about');
    }

    /**
     * Get statistics
     */
    public static function getStats()
    {
        $settings = self::getByGroup('stats');

        return [
            [
                'current' => (int)($settings['stat_1_current'] ?? 0),
                'planned' => (int)($settings['stat_1_planned'] ?? 0),
                'label' => $settings['stat_1_label'] ?? 'Sati edukacije',
            ],
            [
                'current' => (int)($settings['stat_2_current'] ?? 0),
                'planned' => (int)($settings['stat_2_planned'] ?? 0),
                'label' => $settings['stat_2_label'] ?? 'Polaznika',
            ],
            [
                'current' => (int)($settings['stat_3_current'] ?? 0),
                'planned' => (int)($settings['stat_3_planned'] ?? 0),
                'label' => $settings['stat_3_label'] ?? 'Programa',
            ],
            [
                'current' => (int)($settings['stat_4_current'] ?? 0),
                'planned' => (int)($settings['stat_4_planned'] ?? 0),
                'label' => $settings['stat_4_label'] ?? 'Aktivnosti',
            ],
        ];
    }

    /**
     * Get about features
     */
    public static function getAboutFeatures()
    {
        $settings = self::getByGroup('about');

        return [
            [
                'title' => $settings['about_feature_1_title'] ?? '',
                'description' => $settings['about_feature_1_desc'] ?? '',
            ],
            [
                'title' => $settings['about_feature_2_title'] ?? '',
                'description' => $settings['about_feature_2_desc'] ?? '',
            ],
            [
                'title' => $settings['about_feature_3_title'] ?? '',
                'description' => $settings['about_feature_3_desc'] ?? '',
            ],
            [
                'title' => $settings['about_feature_4_title'] ?? '',
                'description' => $settings['about_feature_4_desc'] ?? '',
            ],
        ];
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        self::$cache = [];
    }
}
