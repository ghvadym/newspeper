<?php

namespace inc;
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

class DataBase
{
    public function __construct()
    {
        $this->createTable();
    }

    private function createTable(): void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'subscribers';

        $query = "CREATE TABLE $tableName (
            `id` int(11) NOT NULL auto_increment, 
            `first_name` varchar(100)  NOT NULL ,
            `last_name` varchar(100)  NOT NULL,
            `email` varchar(100)  NOT NULL,
            `subject` varchar(100)  NOT NULL,
            `message` longtext NOT NULL, 
             PRIMARY KEY(`id`))";

        maybe_create_table($tableName, $query);
    }

    static function checkEmailExist($email)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'subscribers';
        $query = "SELECT `email` FROM $tableName WHERE `email` = '$email'";
        return $wpdb->get_results($query);
    }

    static function insertData($data)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'subscribers';
        $wpdb->insert($tableName, $data);
    }

    static function getAllEntries(int $limit = 10, int $offset = 0)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'subscribers';
        $query = "SELECT * FROM $tableName LIMIT $limit OFFSET $offset";
        return $wpdb->get_results($query);
    }

    static function getMaxNumRows()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'subscribers';
        $query = "SELECT COUNT(*) FROM $tableName";
        return array_column($wpdb->get_results($query, ARRAY_A), "COUNT(*)");
    }

    static function getEntryColumnById($id, $key)
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'subscribers';
        $query = "SELECT $key FROM $tableName WHERE id = $id";
        return $wpdb->get_results($query);
    }

}
