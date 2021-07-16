<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

define('WP_MEMORY_LIMIT', '500M');

require_once(dirname(__FILE__) . '/../wp-config.php');
require_once(dirname(__FILE__) . '/../wp-load.php');

class Migrate
{

    private $wpdb;
    private $table_prefix;
    private $options_table_name;
    private $directory = '';
    private $search = '';
    private $replace = '';
    private $counter = 0;
    private $json_counter = 0;
    private $host = '';
    private $path = '';
    private $search_urls = [];
    private $delete_script_directory = false;

    public $json_result = [];
    public $string_result = [];

    public function __construct($search = '', $replace = '', $search_by_host = false)
    {
        global $wpdb;
        global $table_prefix;

        $this->directory = getcwd();
        $this->wpdb = $wpdb;
        $this->table_prefix = $table_prefix;
        $this->options_table_name = $this->table_prefix . 'options';

        if (!$search) {
            if (!($search = $wpdb->get_row("SELECT * FROM {$this->options_table_name} WHERE `option_name` = 'siteurl'"))) {
                die('Site url not found');
            }
        }

        if (!$replace) {
            $replace_host = $_SERVER['HTTP_HOST'];
            $replace_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $script_path = '/' . basename(__DIR__) . '/' . basename(__FILE__);
            $replace_path = str_replace($script_path, '', $replace_path);
            $replace = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $replace_host . $replace_path;
        }

        $this->search = $search->option_value;
        $this->replace = $replace;
        $this->host = parse_url($this->search, PHP_URL_HOST);
        $this->path = parse_url($this->search, PHP_URL_PATH);

        if ($search_by_host) {
            $search_string = $this->host;
        } else {
            $search_string = $this->host . $this->path;
        }

        $this->search_urls = [
            'https://' . $search_string,
            'https://www.' . $search_string,
            'http://' . $search_string,
            'http://www.' . $search_string,
            'www.' . $search_string,
        ];
    }

    public function __destruct()
    {
        if ($this->delete_script_directory) {
            $this->deleteDirectory($this->directory);
        }
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function getReplace()
    {
        return $this->replace;
    }

    public function getCounter()
    {
        return $this->counter;
    }

    public function getJsonCounter()
    {
        return $this->json_counter;
    }

    public function updateDatabase($update_options = true)
    {
        $this->delete_script_directory = true;

        $result = [
            'json' => 0,
            'string' => 0,
            'queries' => '',
        ];

        foreach ($this->json_result as $k => $val) {
            foreach ($val as $field) {
                $table_name = $k;
                $id_name = $field['idName'];
                $id_value = $field['idValue'];
                $field_name = $field['fieldName'];
                $field_value = $field['new'];

                $json_input_cnt = $this->wpdb->update($table_name, array("$field_name" => $field_value), array("$id_name" => $id_value));
                $result['json'] += $json_input_cnt;
            }
        }

        if ($this->string_result) {
            foreach ($this->string_result as $table_name => $value) {
                $total = 0;

                if (!$update_options && $table_name === $this->options_table_name) {
                    continue;
                }

                $result['queries'] .= "\n<strong>{$table_name}</strong>\n";

                foreach ($value as $field_name => $val) {
                    $id_name = '';
                    $ids = [];

                    if (is_array($val) && !empty($val)) {
                        reset($val);
                        $id_name = key($val);
                        $ids = $val[$id_name];
                    }

                    if ($id_name && is_array($ids) && !empty($ids)) {
                        $cnt = count($ids);
                        $total += $cnt;

                        $result['queries'] .= "    <strong>{$field_name}</strong>:{$cnt}\n";

                        $ids_str = implode(',', $ids);
                        $when_sql = '';
                        foreach ($this->search_urls as $search_str) {
                            $when_sql .= " WHEN {$field_name} LIKE '%{$search_str}%' THEN REPLACE({$field_name}, '{$search_str}', '{$this->replace}')";
                        }
                        $sql = "UPDATE {$table_name} SET {$field_name} = CASE {$when_sql} ELSE {$field_name} END WHERE {$id_name} IN ($ids_str)";

                        $result['queries'] .= "    <strong>Query:</strong>{$sql}\n";

                        $string_input_cnt = $this->wpdb->query($sql);
                        $result['string'] += $string_input_cnt;
                    }
                }
            }
        }

        return $result;
    }

    public function run()
    {
        if ($this->search === $this->replace) {
            die("<h2>Migration canceled! Replaced url and current site url identical: {$this->replace}</h2>");
        }

        $showTables = $this->getTables();

        foreach ($showTables as $table_data) {
            $field = "Tables_in_" . strtolower(DB_NAME);
            $table_name = $table_data->{$field};
            $tableFields = $this->getFieldsByTableName($table_name);

            foreach ($tableFields as $tableField) {
                $this->getTableDataForField($table_name, $tableField);
            }
        }
    }

    public function getTables()
    {
        return $this->wpdb->get_results('SHOW TABLES');
    }

    public function getFieldsByTableName($table_name)
    {
        return $this->wpdb->get_results('SHOW FIELDS FROM ' . $table_name);
    }

    private function getTableDataForField($tableName, $tableField)
    {
        $field = $tableField->Field;
        $type = $tableField->Type;

        if (!$this->checkType($type)) {
            return false;
        }

        $column_str = 'CONVERT(`' . $field . '` USING utf8)';
        $str = '';
        foreach ($this->search_urls as $key => $url) {
            if (!$key) {
                $str = '\'%' . $url . '%\'';
            } else {
                $str .= ' OR ' . $column_str . ' LIKE \'%' . $url . '%\'';
            }
        }
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE ' . $column_str . ' LIKE ' . $str;

        $results = $this->wpdb->get_results($sql);
        $row_count = count($results);

        if (!$row_count) {
            return false;
        }

        foreach ($results as $result) {
            $keys = (Array)$result;
            $key_id = key($keys);
            $key_value = $keys[$key_id];

            $isJson = false;

            $new = $this->getReplaceFieldValue($result->{$field}, $isJson);
            if ($isJson) {
                $this->json_result[$tableName][] = [
                    'idName' => $key_id,
                    'idValue' => $key_value,
                    'fieldName' => $field,
                    'old' => $result->{$field},
                    'new' => $new,
                ];

                $this->json_counter++;
            } else {
                $this->string_result[$tableName][$field][$key_id][] = $key_value;
            }

            $this->counter++;
        }

        return true;
    }

    private function _replaceString(&$str)
    {
        if (is_string($str)) {
            $str = str_replace($this->search_urls, $this->replace, $str);
        }
        return $str;
    }

    private function getReplaceFieldValue($field, &$isJson)
    {

        $json = maybe_unserialize($field);

        if ($json && is_array($json)) {
            $isJson = true;
            if (array_walk_recursive($json, [$this, '_replaceString'])) {
                return maybe_serialize($json);
            }
        }

        return $this->_replaceString($field);
    }

    private function checkType($type)
    {
        switch (true) {
            case stristr(strtolower($type), 'char'):
                $hasType = true;
                break;
            case stristr(strtolower($type), 'text'):
                $hasType = true;
                break;
            case stristr(strtolower($type), 'blob'):
                $hasType = true;
                break;
            case stristr(strtolower($type), 'pri'):
                $hasType = false;
                break;
            default:
                $hasType = false;
                break;
        }

        return $hasType;
    }

    private function deleteDirectory($directory)
    {
        if (!is_writable($directory)) {
            echo "<pre style='font-weight:bold;border:3px solid red;'>";
            echo "Unable to delete file or folder: {$directory}";
            echo "</pre>";
            die();
        }

        $files = array_diff(scandir($directory), array('.', '..'));
        foreach ($files as $file) {
            if (is_dir("$directory/$file")) {
                $this->deleteDirectory("$directory/$file");
            } else {
                unlink("$directory/$file");
            }
        }

        return rmdir($directory);
    }

}

$time_start = microtime(true);

$is_update = (isset($_GET['update'])) ? $_GET['update'] : false;

$migration = new Migrate();
$migration->run();

if ($is_update) {
    $update_results = $migration->updateDatabase();
}

$search = $migration->getSearch();
$replace = $migration->getReplace();
$total_fields_count = $migration->getCounter();
$total_json_count = $migration->getJsonCounter();

$time_end = microtime(true);
$execution_time = intval($time_end - $time_start);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body class="container-fluid">
<h2 class="text-left">Replacing <strong><?= $search ?></strong> with <strong><?= $replace ?></strong></h2>

<h3>Database name: <?= DB_NAME ?></h3>
<h3>Search results:</h3>
<span>Strings found: <?= $total_fields_count ?></span><br>
<span>JSON found: <?= $total_json_count ?></span><br>

<?php
if ($is_update && isset($update_results)) {
    echo "<h3>Update results:</h3>";
    echo "<span>Strings updated: {$update_results['string']}</span><br>";
    echo "<span>JSON updated: {$update_results['json']}</span><br>";
}
echo "<br><span>Execution time: {$execution_time} sec.</span><br>";

if ($is_update && isset($update_results)) {
    echo "<pre>";
    echo $update_results['queries'];
    echo "</pre>";
}
?>

<?php
if (!$is_update) {
    echo '<a href="?update=true" class="btn btn-danger" style="margin: 15px 0;" role="button">Update</a>';
}
?>

</body>
</html>