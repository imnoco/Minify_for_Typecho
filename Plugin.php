<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 压缩HTML代码
 *
 * @package Minify
 * @author noco
 * @version 1.0.0
 * @link http://seonoco.com
 */
class Minify_Plugin implements Typecho_Plugin_Interface
{
    /* 激活插件方法 */
    public static function activate(){
        Typecho_Plugin::factory('index.php')->begin = array('Minify_Plugin', 'parse');
    }

    /* 禁用插件方法 */
    public static function deactivate(){}

    /* 插件配置方法 */
    public static function config(Typecho_Widget_Helper_Form $form){}

    /* 个人用户的配置方法 */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /* 插件实现方法 */
    public static function parse(){
        ob_start('Minify_Plugin::minify');
    }

    public static function minify($buffer){

        $content=$buffer;

        // 1.标签前后留空格是为了a标签前后的文字不连在一起,但会存在<> text </>
        // 2.不针直接对多余空格缩减，避免对<pre><code>等特殊标签中的内容影响
        $pattern = array(
            "/\s+</",
            "/>\s+/",
            "/>\s+</",
            //"/\s+/",
            "/ \"/",
            "/<p><br[^>]*><\/p>/i"
        );
        $replace = array (
            " <",
            "> ",
            "><",
            //" ",
            "\"",
            ""
        );
        $content = preg_replace($pattern, $replace, $content);

        // 去除注释
        $content = preg_replace("/<!--[^\!]+-->/i", "", $content);

        // 去除schema
        $content = preg_replace("/\sitem\w+\s/i", " ", $content);
        $content = preg_replace("/\sitem\w+\=[\"\'][^\"\']+[\"\']/i", "", $content);

        return $content;
    }
}
