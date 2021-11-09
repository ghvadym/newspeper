<?php
/**
 * Created by PhpStorm.
 * User: bvn-564
 * Date: 6/10/21
 * Time: 11:39 AM
 */

namespace Helpers;

require_once('vendor/autoload.php');

use ErrorException;
use Stichoza\GoogleTranslate\GoogleTranslate;
use DOMDocument;
use RecursiveIteratorIterator;
use Helpers\RecursiveDOMIterator;


class Functions extends GoogleTranslate
{

    public function checkLength($str): bool
    {
        return strlen($str) > 3000;
    }

    public function strConcat($str, $word): string
    {
        return $str . ' ' . $word;
    }

    public function true_duplicate_post_as_draft($translatedFields, $id, $lang)
    {
        global $wpdb;

        $post_id = $id;
        $post = get_post($post_id);
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;

        if (isset($post) && $post != null) {
            $args = [
                'comment_status' => $post->comment_status,
                'ping_status' => $post->ping_status,
                'post_author' => $new_post_author,
                'post_content' => $translatedFields['content'],
                'post_excerpt' => $post->post_excerpt,
                'post_name' => $post->post_name,
                'post_parent' => $post->post_parent,
                'post_password' => $post->post_password,
                'post_status' => 'draft',
                'post_title' => $translatedFields['title'],
                'post_type' => $post->post_type,
                'to_ping' => $post->to_ping,
                'menu_order' => $post->menu_order,
            ];

            $new_post_id = wp_insert_post($args);
            $taxonomies = get_object_taxonomies($post->post_type);

            foreach ($taxonomies as $taxonomy) {

                if ($taxonomy !== 'language' && $taxonomy !== 'post_translations') {

                    $newLangTerms = [];
                    $post_terms1 = wp_get_object_terms($post_id, $taxonomy);

                    foreach ($post_terms1 as $term) {

                        $newLangTerm = pll_get_term($term->term_id, $lang);
                        $getTermByNewLangTerm = get_term($newLangTerm);
                        array_push($newLangTerms, $getTermByNewLangTerm->slug);
                    }

                    wp_set_object_terms($new_post_id, $newLangTerms, $taxonomy, false);

                } else {

                    $post_terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'slugs']);
                    wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);

                }

            }

            $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");

            if (count($post_meta_infos) != 0) {

                $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                foreach ($post_meta_infos as $meta_info) {

                    $meta_key = $meta_info->meta_key;

                    foreach ($translatedFields as $key => $value) {

                        if ($meta_key === $key) {
                            $meta_value = $value;
                            break;

                        } else {
                            if ($this->getTypeOfAcf($meta_key) === 'post_object') {
                                $meta_value = pll_get_post_translations($meta_info->meta_value) ? pll_get_post_translations($meta_info->meta_value)[$lang] : '';
                            }else {
                                $meta_value = addslashes($meta_info->meta_value);
                            }
                        }

                    }

                    $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                }

                $sql_query .= implode(" UNION ALL ", $sql_query_sel);
                $wpdb->query($sql_query);

            }


            pll_set_post_language($new_post_id, $lang);
            pll_save_post_translations([pll_get_post_language($id) => $post_id, $lang => $new_post_id]);

            return $new_post_id;

        } else {

            wp_die('Ошибка создания поста');

        }

    }

    /**
     * @throws ErrorException
     */
    public function translateStr($str, $currentLang, $goalLang)
    {
        $currentLang = $currentLang === 'ua' ? 'uk' : $currentLang;
        $goalLang = $goalLang === 'ua' ? 'uk' : $goalLang;
        $tr = new GoogleTranslate();

        $space = '';

        if ($str && $str[0] == " ") {
            $space = ' ';
        }

        $newStr = '';

        if ($this->checkLength($str)) {
            $results = [];
            $words = explode(' ', $str);
            $result = '';
            foreach ($words as $word) {
                $line = $this->strConcat($result, $word);
                if ($this->checkLength($line)) {
                    $results[] = $result;
                    $result = '';
                }
                $result = $this->strConcat($result, $word);
            }
            if ($result) {
                $results[] = $result;
            }

            foreach ($results as $result) {
                $newStr = $this->strConcat($newStr, $space . $tr->setSource($currentLang)->setTarget($goalLang)->translate($result));
            }
        } else {
            $newStr = $space . $tr->setSource($currentLang)->setTarget($goalLang)->translate($str);
        }

        return $newStr;

    }

    public function getAttr(&$node)
    {
        $attrs = '';

        foreach ($node->attributes as $attr) {

            $attrs .= $attr->name . '= "' . $attr->nodeValue . '" ';

        }

        return $attrs;
    }

    public function recursiveParser($node, &$str, $currentLang, $goalLang)
    {

        foreach ($node->childNodes as $childNode) {

            if ($childNode->childNodes[0]) {

                $str .= ' ' . '<' . $childNode->tagName . ' ' . $this->getAttr($childNode) . '>';

                $this->recursiveParser($childNode, $str, $currentLang, $goalLang);

                $str .= '</' . $childNode->tagName . '>' . ' ';

                if ($childNode->tagName === 'em') {
                    $str .= '<br><br>';
                }

            } else {

                $find = '/(\.[a-z])((?![.,?!;:()]*(\s|$))[^\s]){2,}/';
                preg_match_all($find, $childNode->nodeValue, $matches);

                if ($matches[0]) {

                    $str .= $this->translateStr($childNode->nodeValue, $currentLang, $goalLang);

                } else {

                    if ($childNode->nodeName !== '#text') {

                        $str .= '<br><' . $childNode->tagName . ' ' . $this->getAttr($childNode) . '>' . $childNode->nodeValue . '</' . $childNode->tagName . '><br>';

                    } else {

                        $str .= $this->translateStr($childNode->nodeValue, $currentLang, $goalLang);
                    }
                }
            }
        }
    }

    public function getTypeOfAcf($key)
    {
        global $wpdb;
        $sqlQuery = "SELECT post_content FROM `wp_posts` WHERE `post_excerpt` LIKE '$key'";
        $result = $wpdb->get_row($sqlQuery, ARRAY_A);
        $getArray = isset($result['post_content']) ? unserialize($result['post_content']) : '';

        return $getArray['type'];
    }

    public function flexiParser($content, $currentLang, $goalLang)
    {

        $content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
        $dom = new DOMDocument;
        $dom->loadHTML($content);


        $iterator = new RecursiveIteratorIterator(
            new RecursiveDOMIterator($dom),
            RecursiveIteratorIterator::SELF_FIRST);

        $str = '';
        foreach ($iterator as $node) {

            if ($node->nodeName === '#text' && $node->parentNode->tagName === 'body') {
                $str .= $this->translateStr($node->nodeValue, $currentLang, $goalLang) . ' ';
            }

            if ($node->nodeType === XML_ELEMENT_NODE && ($node->nodeName !== 'html' && $node->nodeName !== 'body' && $node->parentNode->nodeName === 'body')) {

                if ($node->childNodes[0]) {

                    if ($node->tagName === 'em') {
                        $str .= '<p>';
                    }

                    $str .= '<' . $node->tagName . ' ' . $this->getAttr($node) . '>';

                    $this->recursiveParser($node, $str, $currentLang, $goalLang);

                    $str .= '</' . $node->tagName . '>';

                    if ($node->tagName === 'em') {
                        $str .= '</p>';
                    }

                } else {
                    $str .= '<br><' . $node->tagName . ' ' . $this->getAttr($node) . '>' . $this->translateStr($node->nodeValue, $currentLang,
                            $goalLang) . '</' . $node->tagName . '><br>';
                }
            }
        }

        $find = '/[^\r\n]*/';
        $newStr = '';
        preg_match_all($find, $str, $matches);

        foreach ($matches[0] as $match) {

            if ($match !== '') {

                $newStr .= '<p>' . $match . '</p>';
                $newStr = str_replace('[/ caption','[/caption',$newStr);
            }

        }

        return $newStr;
    }

}
