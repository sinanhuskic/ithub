<?php
/**
 * IT Hub Zavidovići - User Model
 */

class User
{
    /**
     * Find user by email
     */
    public static function findByEmail($email)
    {
        return db()->fetch(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
    }

    /**
     * Find user by ID
     */
    public static function find($id)
    {
        return db()->fetch(
            "SELECT * FROM users WHERE id = ?",
            [$id]
        );
    }

    /**
     * Verify password
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Hash password
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Update user
     */
    public static function update($id, $data)
    {
        return db()->update('users', $data, 'id = ?', [$id]);
    }

    /**
     * Get all users
     */
    public static function all()
    {
        return db()->fetchAll("SELECT id, name, email, created_at FROM users ORDER BY id");
    }
}
