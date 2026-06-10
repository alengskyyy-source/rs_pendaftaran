<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

session_start();

$GLOBALS['CI_INSTANCE'] = NULL;

function &get_instance()
{
    return $GLOBALS['CI_INSTANCE'];
}

function site_url($uri = '')
{
    $CI =& get_instance();
    $base = isset($CI->config['base_url']) ? $CI->config['base_url'] : '';
    $index = isset($CI->config['index_page']) ? $CI->config['index_page'] : 'index.php';
    $uri = trim($uri, '/');
    return rtrim($base, '/') . '/' . trim($index, '/') . ($uri !== '' ? '/' . $uri : '');
}

function base_url($uri = '')
{
    $CI =& get_instance();
    $base = isset($CI->config['base_url']) ? $CI->config['base_url'] : '';
    return rtrim($base, '/') . '/' . ltrim($uri, '/');
}

function redirect($uri = '')
{
    header('Location: ' . site_url($uri));
    exit;
}

function show_404()
{
    http_response_code(404);
    echo '<h1>404 Not Found</h1>';
    exit;
}

function html_escape($str)
{
    return htmlspecialchars((string) $str, ENT_QUOTES, 'UTF-8');
}

function set_value($field, $default = '')
{
    return isset($_POST[$field]) ? html_escape($_POST[$field]) : html_escape($default);
}

function set_select($field, $value)
{
    return (isset($_POST[$field]) && (string) $_POST[$field] === (string) $value) ? 'selected="selected"' : '';
}

function validation_errors($prefix = '', $suffix = '')
{
    $CI =& get_instance();
    if (!isset($CI->form_validation)) {
        return '';
    }
    return $CI->form_validation->error_string($prefix, $suffix);
}

class CI_Input
{
    public function post($key = NULL, $xss_clean = FALSE)
    {
        if ($key === NULL) {
            return $_POST;
        }
        if (!isset($_POST[$key])) {
            return NULL;
        }
        $value = $_POST[$key];
        if (is_array($value)) {
            return $value;
        }
        $value = trim($value);
        if ($xss_clean === TRUE) {
            $value = strip_tags($value);
        }
        return $value;
    }
}

class CI_Session
{
    public function userdata($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : NULL;
    }

    public function set_userdata($data, $value = NULL)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $_SESSION[$k] = $v;
            }
        } else {
            $_SESSION[$data] = $value;
        }
    }

    public function set_flashdata($key, $value)
    {
        $_SESSION['__flashdata'][$key] = $value;
    }

    public function flashdata($key)
    {
        if (!isset($_SESSION['__flashdata'][$key])) {
            return NULL;
        }
        return $_SESSION['__flashdata'][$key];
    }

    public function sess_destroy()
    {
        $_SESSION = array();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}

class CI_Form_validation
{
    private $rules = array();
    private $errors = array();
    private $messages = array();

    public function set_rules($field, $label, $rules)
    {
        $this->rules[] = array('field' => $field, 'label' => $label, 'rules' => explode('|', $rules));
    }

    public function set_message($rule, $message)
    {
        $this->messages[$rule] = $message;
    }

    private function message($rule, $label, $param = '')
    {
        $message = isset($this->messages[$rule]) ? $this->messages[$rule] : $label . ' tidak valid.';
        return str_replace(array('{field}', '{param}'), array($label, $param), $message);
    }

    public function run()
    {
        $this->errors = array();
        $CI =& get_instance();

        foreach ($this->rules as $item) {
            $field = $item['field'];
            $label = $item['label'];
            $value = isset($_POST[$field]) ? trim($_POST[$field]) : '';

            foreach ($item['rules'] as $rule) {
                if ($rule === 'trim') {
                    $_POST[$field] = trim($value);
                    continue;
                }

                if ($rule === 'required' && $value === '') {
                    $this->errors[] = $this->message('required', $label);
                    break;
                }

                if (strpos($rule, 'min_length[') === 0) {
                    $min = (int) str_replace(array('min_length[', ']'), '', $rule);
                    if ($value !== '' && strlen($value) < $min) {
                        $this->errors[] = $this->message('min_length', $label, $min);
                        break;
                    }
                }

                if (strpos($rule, 'matches[') === 0) {
                    $other = str_replace(array('matches[', ']'), '', $rule);
                    $other_value = isset($_POST[$other]) ? $_POST[$other] : '';
                    if ($value !== $other_value) {
                        $this->errors[] = $this->message('matches', $label);
                        break;
                    }
                }

                if (strpos($rule, 'is_unique[') === 0) {
                    $target = str_replace(array('is_unique[', ']'), '', $rule);
                    $parts = explode('.', $target);
                    if (count($parts) === 2) {
                        $table = $parts[0];
                        $column = $parts[1];
                        $query = $CI->db->where($column, $value)->get($table);
                        if ($query->num_rows() > 0) {
                            $this->errors[] = $this->message('is_unique', $label);
                            break;
                        }
                    }
                }
            }
        }

        return count($this->errors) === 0;
    }

    public function error_string($prefix = '', $suffix = '')
    {
        if (empty($this->errors)) {
            return '';
        }
        return $prefix . implode('<br>', $this->errors) . $suffix;
    }
}

class CI_DB_result
{
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function row()
    {
        if (!$this->result) {
            return NULL;
        }
        return $this->result->fetch_object();
    }

    public function result()
    {
        $data = array();
        if (!$this->result) {
            return $data;
        }
        while ($row = $this->result->fetch_object()) {
            $data[] = $row;
        }
        return $data;
    }

    public function num_rows()
    {
        if (!$this->result) {
            return 0;
        }
        return $this->result->num_rows;
    }
}

class CI_DB
{
    private $mysqli;
    private $select = '*';
    private $from = '';
    private $joins = array();
    private $wheres = array();
    private $orders = array();
    private $trans_failed = FALSE;

    public function __construct($config)
    {
        $this->mysqli = new mysqli($config['hostname'], $config['username'], $config['password'], $config['database']);
        if ($this->mysqli->connect_errno) {
            die('Database connection failed: ' . $this->mysqli->connect_error);
        }
        $charset = isset($config['char_set']) ? $config['char_set'] : 'utf8mb4';
        $this->mysqli->set_charset($charset);
    }

    private function reset_query()
    {
        $this->select = '*';
        $this->from = '';
        $this->joins = array();
        $this->wheres = array();
        $this->orders = array();
    }

    private function esc($value)
    {
        if ($value === NULL) {
            return 'NULL';
        }
        return "'" . $this->mysqli->real_escape_string($value) . "'";
    }

    public function select($select, $escape = NULL)
    {
        $this->select = $select;
        return $this;
    }

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    public function join($table, $condition, $type = '')
    {
        $type = trim(strtoupper($type));
        if ($type !== '') {
            $type .= ' ';
        }
        $this->joins[] = $type . 'JOIN ' . $table . ' ON ' . $condition;
        return $this;
    }

    public function where($key, $value)
    {
        $operators = array(' !=', ' >=', ' <=', ' >', ' <', ' LIKE');
        $has_operator = FALSE;
        foreach ($operators as $op) {
            if (substr($key, -strlen($op)) === $op) {
                $has_operator = TRUE;
                break;
            }
        }
        $this->wheres[] = $key . ($has_operator ? ' ' : ' = ') . $this->esc($value);
        return $this;
    }

    public function order_by($field, $direction = 'ASC')
    {
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $this->orders[] = $field . ' ' . $direction;
        return $this;
    }

    public function get($table = '')
    {
        if ($table !== '') {
            $this->from = $table;
        }

        $sql = 'SELECT ' . $this->select . ' FROM ' . $this->from;
        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }
        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        if (!empty($this->orders)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }

        $res = $this->mysqli->query($sql);
        if (!$res) {
            die('Query error: ' . $this->mysqli->error . '<br>SQL: ' . html_escape($sql));
        }
        $this->reset_query();
        return new CI_DB_result($res);
    }

    public function insert($table, $data)
    {
        $fields = array();
        $values = array();
        foreach ($data as $k => $v) {
            $fields[] = $k;
            $values[] = $this->esc($v);
        }
        $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
        $ok = $this->mysqli->query($sql);
        if (!$ok) {
            $this->trans_failed = TRUE;
            die('Insert error: ' . $this->mysqli->error . '<br>SQL: ' . html_escape($sql));
        }
        return $ok;
    }

    public function update($table, $data)
    {
        $sets = array();
        foreach ($data as $k => $v) {
            $sets[] = $k . ' = ' . $this->esc($v);
        }
        $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets);
        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $ok = $this->mysqli->query($sql);
        if (!$ok) {
            $this->trans_failed = TRUE;
            die('Update error: ' . $this->mysqli->error . '<br>SQL: ' . html_escape($sql));
        }
        $this->reset_query();
        return $ok;
    }

    public function delete($table)
    {
        $sql = 'DELETE FROM ' . $table;
        if (!empty($this->wheres)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        $ok = $this->mysqli->query($sql);
        if (!$ok) {
            $this->trans_failed = TRUE;
            die('Delete error: ' . $this->mysqli->error . '<br>SQL: ' . html_escape($sql));
        }
        $this->reset_query();
        return $ok;
    }

    public function insert_id()
    {
        return $this->mysqli->insert_id;
    }

    public function trans_begin()
    {
        $this->trans_failed = FALSE;
        $this->mysqli->begin_transaction();
    }

    public function trans_status()
    {
        return !$this->trans_failed;
    }

    public function trans_rollback()
    {
        $this->mysqli->rollback();
    }

    public function trans_commit()
    {
        $this->mysqli->commit();
    }
}

class CI_Loader
{
    public function __get($key)
    {
        $CI =& get_instance();
        return $CI->$key;
    }

    public function model($model)
    {
        $CI =& get_instance();
        $file = APPPATH . 'models' . DIRECTORY_SEPARATOR . $model . '.php';
        if (!file_exists($file)) {
            die('Model not found: ' . html_escape($model));
        }
        require_once $file;
        $CI->$model = new $model();
    }

    public function view($view, $data = array())
    {
        $file = APPPATH . 'views' . DIRECTORY_SEPARATOR . $view . '.php';
        if (!file_exists($file)) {
            die('View not found: ' . html_escape($view));
        }
        extract($data);
        include $file;
    }
}

class CI_Model
{
    public function __construct() {}

    public function __get($key)
    {
        $CI =& get_instance();
        return $CI->$key;
    }
}

class CI_Controller
{
    public $load;
    public $db;
    public $input;
    public $session;
    public $form_validation;
    public $config = array();

    public function __construct()
    {
        $GLOBALS['CI_INSTANCE'] = $this;
        $this->load_config();
        $this->input = new CI_Input();
        $this->session = new CI_Session();
        $this->form_validation = new CI_Form_validation();
        $this->load = new CI_Loader();

        $db = array();
        require APPPATH . 'config' . DIRECTORY_SEPARATOR . 'database.php';
        $this->db = new CI_DB($db['default']);
    }

    private function load_config()
    {
        $config = array();
        require APPPATH . 'config' . DIRECTORY_SEPARATOR . 'config.php';
        $this->config = $config;
    }
}

function _parse_uri_segments()
{
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
    $base = str_replace('\\', '/', dirname($script));
    $path = parse_url($uri, PHP_URL_PATH);

    if ($base !== '/' && strpos($path, $base) === 0) {
        $path = substr($path, strlen($base));
    }
    $path = ltrim($path, '/');

    if (strpos($path, 'index.php') === 0) {
        $path = substr($path, strlen('index.php'));
    }
    $path = trim($path, '/');

    if ($path === '') {
        $routes = array();
        require APPPATH . 'config' . DIRECTORY_SEPARATOR . 'routes.php';
        $path = isset($route['default_controller']) ? $route['default_controller'] : 'auth';
    }

    return array_values(array_filter(explode('/', $path), 'strlen'));
}

$segments = _parse_uri_segments();
$controller = isset($segments[0]) ? ucfirst(strtolower($segments[0])) : 'Auth';
$method = isset($segments[1]) ? $segments[1] : 'index';
$params = array_slice($segments, 2);

$file = APPPATH . 'controllers' . DIRECTORY_SEPARATOR . $controller . '.php';
if (!file_exists($file)) {
    show_404();
}

require_once $file;

if (!class_exists($controller)) {
    show_404();
}

$CI = new $controller();

if (!method_exists($CI, $method) || substr($method, 0, 1) === '_') {
    show_404();
}

call_user_func_array(array($CI, $method), $params);
