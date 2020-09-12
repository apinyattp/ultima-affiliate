<?php

class Head {
    private $_asset_path = 'asset/';

    private $_css = array(
        'paper/assets/css/bootstrap.min.css',
        'jquery-ui-1.12.1/jquery-ui.css',
        'paper/assets/css/paper-dashboard.css',
        'datepicker/css/bootstrap-datepicker3.standalone.min.css',
        'dropzone-5.7.0/dist/dropzone.css',
        'css/custom.css'
    );
    private $_css_media = array();

    private $_js = array(
        'paper/assets/js/core/jquery.min.js',
        'paper/assets/js/core/popper.min.js',
        'paper/assets/js/core/bootstrap.min.js',
        'paper/assets/js/plugins/perfect-scrollbar.jquery.min.js',
        'paper/assets/js/plugins/moment.min.js',
        'paper/assets/js/paper-dashboard.min.js',
        'ckeditor5/21.0.0/classic/ckeditor.js',
        'jquery-ui-1.12.1/jquery-ui.min.js',
        'datepicker/js/bootstrap-datepicker.min.js',
        'sweetalert2@9.17.1/dist/sweetalert2.all.min.js',
        'dropzone-5.7.0/dist/dropzone.js',
        'js/custom.js',
    );

    private $_html = array();
    private $_title = 'Ulyima Life Affiliate';
    private $_top_title = '';
    private $_description = '';
    private $_keyword = '';
    private $_image_src = '';
    private $_head_script = '';
    private $_a_language_disable = array();
    private $_a_language_link = array();
    private $_menu = '';
    private $_back_url = FALSE;
    private $_breadcrumb = array();
    private $_a_var = array();
    private $_pagemap = array();

    /**
     *  @desc constructor
     * */
    function __construct() {

    }

    function param_get() {
        $param = array();
        foreach (array('css_path', 'css', 'css_media', 'js_path', 'js', 'html', 'title', 'description', 'keyword', 'image_src', 'image_banner', 'head_script', 'a_language_disable', 'a_language_link') as $key) {
            $param[$key] = $this->{"_".$key};
        }
        return $param;
    }

    function param_load($param) {
        foreach (array('css_path', 'css', 'css_media', 'js_path', 'js', 'html', 'title', 'description', 'keyword', 'image_src', 'image_banner', 'head_script', 'a_language_disable', 'a_language_link') as $key) {
            $this->{"_".$key} = $param[$key];
        }
        return TRUE;
    }

    /**
     *  @desc add new css file
     *  @param string - css file
     *  @param string - media type
     *  @return void
     * */
    function css_add($css, $media='screen') {
        $this->_css[] = $css;
        $this->_css_media[$css] = $media;
    }

    /**
     *  @desc remove and set up a css files
     *  @param array - css file
     *  @return void
     * */
    function css_set($css) {
        $this->_css = $css;
        $this->_css_media = array();
    }

    /**
     *  @desc return css
     *  @param boolean - return as rendered HTML
     *  @return array/string
     * */
    function css_get($render=TRUE) {
        if (!$render) return $this->_css;
        $html = '';
        foreach($this->_css as $css) {
            if (strstr($css, 'http://') || strstr($css, 'https://') || strpos($css, '//') === 0){
                $css_url = $css;
            }else{
                $css_url = base_url() . $this->_asset_path . $css;
            }
            $media = 'screen';
            if (isset($this->_css_media[$css])) {
                $media = $this->_css_media[$css];
            }
            $html .= '<link href="' . $css_url . '" rel="stylesheet" type="text/css" media="' . $media . '" />'."\n";
        }
        return $html;
    }

    /**
     *  @desc add new js file
     *  @param string - js file
     *  @return void
     * */
    function js_add($js) {
        $this->_js[] = $js;
    }

    /**
     *  @desc remove and set up a js files
     *  @param array - js file
     *  @return void
     * */
    function js_set($js) {
        $this->_js = $js;
    }

    /**
     *  @desc return js
     *  @param boolean - return as rendered HTML
     *  @return array/string
     * */
    function js_get($render=TRUE) {
        if (!$render) return $this->_js;
        $html = '';
        foreach ($this->_js as $js) {
            if (strstr($js, 'http://') || strstr($js, 'https://') || strpos($js, '//') === 0){
                $js_url = $js;
            }else{
                $js_url = base_url() . $this->_asset_path . $js;
            }
            $html .= '<script src="' . $js_url . '" type="text/javascript"></script>'."\n";
        }
        return $html;
    }

    /**
     *  @desc set header title
     *  @param string - title
     *  @return void
     * */
    function title($title=FALSE) {
        if($title !== FALSE) $this->_title = $title;
        return $this->_title;
    }

    function top_title($title=FALSE) {
        if($title !== FALSE) $this->_top_title = $title;
        return $this->_top_title;
    }

    /**
     *  @desc get/set header description
     *  @param string - description
     *  @return void
     * */
    function description($description=FALSE) {
        if($description !== FALSE) $this->_description = $description;
        return $this->_description;
    }

    /**
     *  @desc get/set header keyword
     *  @param string - keyword
     *  @return void
     * */
    function keyword($keyword=FALSE) {
        if($keyword !== FALSE) $this->_keyword = $keyword;
        return $this->_keyword;
    }

    /**
     *  @desc get/set header image src
     *  @param string - image src
     *  @return void
     * */
    function image_src($image_src=FALSE) {
        if($image_src !== FALSE) $this->_image_src = $image_src;
        return $this->_image_src;
    }

    /**
     *  @desc get/set image banner
     *  @param string - image src
     *  @return void
     * */
    function image_banner($image_banner=FALSE) {
        if($image_banner !== FALSE) $this->_image_banner = $image_banner;
        return $this->_image_banner;
    }

    /**
     * @desc add header script
     * @return void 
     */
    function script_add($script) {
        $this->_head_script .= $script . "\r\n";
    }

    /**
     * @desc get header script
     * @return string 
     */
    function script_get() {
        return $this->_head_script;
    }

    /**
     * @desc add header html
     * @param string
     * @return void 
     */
    function html_add($html) {
        $this->_html[] = $html;
    }

    /**
     * @desc get header html
     * @return string 
     */
    function html_get() {
        return implode("\r\n", $this->_html);
    }

    /**
     * @desc disable language
     * @param array/int 
     * @return void
     */
    function language_disable_add($language) {
        if (!is_array($language)) $language = array($language);
        if (in_array(lang_get(), $language)) {
            show_404();
            exit;
        }
        $this->_a_language_disable = array_merge($this->_a_language_disable, $language);
    }

    /**
     * @desc get disabled language
     * @return array
     */
    function language_disable_get() {
        return $this->_a_language_disable;
    }

    /**
     * @desc set language link 
     * @param int language id 
     * @param string url
     * @return void
     */
    function language_link_set($lang, $url) {
        $this->_a_language_link[$lang] = $url;
    }

    /**
     * @desc get language link
     * @return array
     */
    function language_link_get() {
        return $this->_a_language_link;
    }

    function menu_select($menu=FALSE){
        if($menu !== FALSE) $this->_menu = $menu;
        return $this->_menu;
    }
    
    function var_add($var_name, $value){
        $this->_a_var[$var_name] = $value;
    }
    
    function var_get($var_name, $default=FALSE){
        if(!isset($this->_a_var[$var_name])) return $default;
        return $this->_a_var[$var_name];
    }
    
    function back_url($url=FALSE){
        if($url !== FALSE) $this->_back_url = $url;
        return $this->_back_url;
    }
    
    function breadcrumb($a_breadcrumb=FALSE){
        if($a_breadcrumb !== FALSE) $this->_breadcrumb = $a_breadcrumb;
        return $this->_breadcrumb;
    }
    
    function pagemap_set($data_object, $name, $value){
        $this->_pagemap[$data_object][$name] = $value;
    }
    
    function pagemap_get(){
        return $this->_pagemap;
    }
}
