<?php defined('ABSPATH') or die();
/*
Plugin Name: AMarkets Affiliate Links
Description: Fix affiliate links.
Version: 4
*/


add_action('get_header', function() {
    ob_start('amarkets_affiliate_links_fix');
});
add_action('wp_footer', function() {
    ob_end_flush();
}, 999);

add_filter('the_content', 'amarkets_affiliate_links_fix');


function amarkets_affiliate_links_fix($html) {
    $domains = array(
        'aforex.ru',
        'amarkets.org',
        'amarkets.biz',
        'amarkets.info',
        );
    $replace_to = 'https://afinance.pro/fx';

    $domains_string = implode('|', $domains);
    $domains_regex_string = '('. str_replace('.', '\\.', $domains_string).')';

    // Matches links like https://www.amarkets.org/news/g/promocode
    $pattern = array(
        '@',
        '(http:|https:|)',
        '//',
        '(www\.)?',
        $domains_regex_string,
        '/?',
        '[^<>"\'\\s]*?',
        '(?=',
            '("|\'|\\s|/g/|\\?g=|\\#g=|[<>])',
        ')',
        '@',
        );
    $pattern_regex = implode('', $pattern);

    $html = preg_replace($pattern_regex, $replace_to, $html);
    return $html;
}
