<?php
/**
 * @package setup
 */
$proceed = false;
$writable = is_writable(MODX_SETUP_PATH . 'includes/config.core.php');
$writableError = false;
$config_key = isset($_POST['config_key']) && !empty($_POST['config_key']) ? $_POST['config_key'] : MODX_CONFIG_KEY;
if (!empty($_POST['proceed'])) {
    if ($config_key !== MODX_CONFIG_KEY) {
        if ($writable) {
            $content = file_get_contents(MODX_SETUP_PATH . 'includes/config.core.php');
            $pattern = "/define\s*\(\s*'MODX_CONFIG_KEY'\s*,.*\);/";
            $replacement = "define ('MODX_CONFIG_KEY', '{$_POST['config_key']}');";
            $content = preg_replace($pattern, $replacement, $content);
            file_put_contents(MODX_SETUP_PATH . 'includes/config.core.php', $content);
            $proceed = true;
        } else {
            $writableError = true;
        }
    } else {
        $proceed = true;
    }
    if ($proceed) {
        $this->proceed('options');
    }
}

$this->parser->assign('config_key', $config_key);
$this->parser->assign('writableError', $writableError);

return $this->parser->fetch('welcome.tpl');