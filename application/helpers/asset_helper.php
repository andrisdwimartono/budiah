<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Get asset URL
*
 * @access  public
 * @return  string
*/
if ( ! function_exists('asset_url'))
{
    function asset_url()
    {
        //get an instance of CI so we can access our configuration
        $obj =& get_instance();
        $base_url = $obj->config->item('base_url');
        $asset_root = 'assets/';
        $asset_location = $base_url . $asset_root;
        return $asset_location;
    }
}
/**
 * Get css URL
 *
 * @access  public
 * @return  string
 */
if ( ! function_exists('css_url'))
{
    function css_url()
    {
        $obj = & get_instance();
        $css_url = asset_url() . 'css/';
        return $css_url;
    }
}

/**
 * Get js URL
 *
 * @access  public
 * @return  string
 */
if ( ! function_exists('js_url'))
{
    function js_url()
    {
        $obj = & get_instance();
        $js_url = asset_url() . 'js/';
        return $js_url;
    }
}
/**
 * Get image URL
 *
 * @access  public
 * @return  string
 */
if ( ! function_exists('img_url'))
{
    function img_url()
    {
        $obj = & get_instance();
        $img_url = asset_url() . 'images/';
        return $img_url;
    }
}