<?php

// no direct access
defined('_JEXEC') or die('Restricted index access');
//define('_COOKIENAME', 'mc-redirect');

class SCCore
{

    var $document;
    var $language;
    var $session;
    var $basePath;
    var $adminPath;
    var $baseUrl;
    var $currentUrl;
    var $templateUrl;
    var $templateUrlAbsolute;
    var $templatePath;
    var $templateName;
    var $user;
    var $toolbar;
    var $toolbar_output;
    var $help;
    var $actions;
    var $first;
    var $bodytags;
    var $updateUrl;
    var $updateSlug;
    var $params;
    var $browser;
    var $_browser_params = array();

    function __construct()
    {
        // // require_once('rtbrowser.class.php');
        // // //$this->checkRedirect();
        // // $this->browser = new RTBrowser();

        // // some more init
        // $this->basePath = JPATH_ROOT;
        // $this->adminPath = $this->basePath . DS . 'administrator';
        // $this->templateName = 'rt_missioncontrol';
        // $this->templatePath = $this->adminPath . DS . 'templates' . DS . $this->templateName;
        // $this->templateUrl = $this->baseUrl . 'templates/' . $this->templateName;
        // $this->templateUrlAbsolute = JURI::root(true) . '/administrator/' .$this->templateUrl;


        // // Set the main class vars to match the call
        // JHTML::_('behavior.mootools');
        // $doc = JFactory::getDocument();
        // $this->document = $doc;
        // $this->user = JFactory::getUser();
        // $this->language = $doc->language;
        // $this->direction = $doc->direction;
        // $this->session = JFactory::getSession();
        // $this->baseUrl = JURI::root(true) . "/";
        // $uri = JURI::getInstance();
        // $this->currentUrl = $uri->toString();
        $this->params = $this->getTemplateParams();
        // $this->$params = JFactory::getApplication()->getTemplate(true)->params;
    }

    function initRenderer()
    {
        $this->_initToolbar();
        $this->_injectClasses();
    }

    /* not needed */
    // function getTemplateParams() {

    //     require_once(dirname(__FILE__) . "/rtparameter.php");

    //     $app = JFactory::getApplication();
    //     $template =  $app->getTemplate(true);
    //     $params = new RTParameter( $template->params, $this->templatePath);

    //     return $params;
    // }

     function getTemplateParams() {

        $app = JFactory::getApplication();
        $template =  $app->getTemplate(true);
        $params = $template->params;

        return $params;
    }

    /* ------ Stylesheet Funcitons  ----------- */

    function addStyle($filename = '', $timestamp = '')
    {
        if (is_array($filename)) return RTCore::addStyles($filename);

        return $this->_parseBrowserFromName($filename, 'css', $timestamp);
    }

    function addStyles($styles = array())
    {
        foreach ($styles as $style) RTCore::addStyle($style);
    }

    function addInlineStyle($css = '')
    {
        $doc =& $this->document;
        return $doc->addStyleDeclaration($css);
    }

    /* ------ Script Funcitons  ----------- */

    function addScript($filename = '', $timestamp = '')
    {
        if (is_array($filename)) return RTCore::addScripts($filename,$timestamp);

        return $this->_parseBrowserFromName(RTCore::getMooScriptVersion($filename), 'js', $timestamp);
    }

    function addScripts($scripts = array(), $timestamp = '')
    {
        foreach ($scripts as $script) RTCore::addScript($script, $timestamp);
    }


    function addInlineScript($js = '')
    {
        $doc =& $this->document;
        return $doc->addScriptDeclaration($js);
    }

    function getMooScriptVersion($filename) {
        global $moo_override;

        //return str_replace('.js','-mt1.2.js',$filename);
        return $filename;
    }

    function addOverrideStyles()
    {
        $option = JRequest::getString('option',null);

        $override = 'extras.css.php';

        $override_file = $this->templatePath . DS . 'overrides' . DS . $option . DS . $override;
        $override_url = $this->templateUrl . '/overrides/' . $option . '/' . $override;

        jimport('joomla.filesystem.file');
        if (JFile::exists($override_file)) {
            $this->document->addStylesheet($override_url);
        }

    }

    function processAjax()
    {
        if (JRequest::getString('process') == 'ajax' && JRequest::getString('model')) {

            $model = $this->getAjaxModel(JRequest::getString('model'));
            if ($model === false) die();
            include_once($model);
            exit;
        } else {
            return true;
        }
        return false;
    }

    function getAjaxModel($model_name)
    {

        $model_path = $this->templatePath . DS . 'ajax-models' . DS . $model_name . '.php';

        if (file_exists($model_path)) {
            return $model_path;
        } else {
            return false;
        }
    }

    function _injectClasses()
    {

        $option = JRequest::getCmd('option');
        $task = JRequest::getString('task');
        $view = JRequest::getString('view');
        $layout = JRequest::getString('layout');

        $buffer = $this->document->getBuffer('component');

        jimport('joomla.filesystem.file');
        $override_replace = $this->templatePath . DS . 'overrides' . DS . $option . DS . 'manipulations.php';


        // include any phpquery manipulations that might exist
        if (JFile::exists($override_replace)) {
            include($override_replace);

        }

    }

    function _initToolbar()
    {

        $bar = JToolBar::getInstance('toolbar');

        $buttons = $bar->getItems();
        $buffer = $this->document->getBuffer();

        //Toolbar is empty, attempt to see if it's a koowa toolbar
        //if it's not, then continue like always - TODO: find generic solution
        if(!$buttons && isset($buffer['modules']['toolbar'])) {
            $this->toolbar_output = $buffer['modules']['toolbar'];

            //new help button
            $helpbar = new JToolbar('help');
            $this->help = $helpbar;
            return;
        }

        $newbar = array();
        $newhelp = array();
        $actions = array();
        $first = array();
        foreach ($buttons as $button) {

            if (strtolower($button[0]) == 'help') {
                $newhelp[] = $button;
            } elseif (isset($button[1]) && (strtolower($button[1]) == 'unarchive' or
                      strtolower($button[1]) == 'archive' or
                      strtolower($button[1]) == 'publish' or
                      strtolower($button[1]) == 'unpublish' or
                      strtolower($button[1]) == 'move' or
                      strtolower($button[1]) == 'copy' or
                      strtolower($button[1]) == 'trash' or
                      strtolower($button[1]) == 'delete' or
                      strtolower($button[1]) == 'tag')) {
                $actions[] = $button;
            } else if (isset($button[1]) && (strtolower($button[1]) == 'new' or
                       strtolower($button[1]) == 'apply' or
                       strtolower($button[1]) == 'save')) {
                $first[] = $button;
            } else {
                $newbar[] = $button;
            }
        }
        //create new toolbar object
        $toolbar = new JToolbar('toolbar');
        $toolbar->setToolBar($newbar);

        $toolbar->setButtonPath($bar->getButtonPath());
        $toolbar->_actions = $actions;
        $toolbar->_first = $first;
        $this->toolbar = $toolbar;
        $this->actions = $actions;
        $this->first = $first;

        //new help button
        $helpbar = new JToolbar('help');
        $helpbar->setToolbar($newhelp);

        $this->help = $helpbar;
        $this->toolbar_output = $this->toolbar->render('toolbar');

    }

    function _addListItem($item, $class = null, $link = null, $badge=null)
    {
        if ($item == '___') {$item = '';$class="divider";}
        if ($link != null) $item = '<a href="' . $link . '">' . $item . '</a>';

        if ($badge) $item .= $badge;
        if ($class == null) return $item;

        $chunk = array();
        $chunk[0] = $item;
        $chunk[1] = $class;
        return $chunk;

    }

    function _listify($list, $class = null)
    {

        if (isset($class)) $output = '<ul class="' . $class . '">';
        else $output = '<ul>';

        foreach ($list as $item) {
            if (is_array($item)) {
                $value = $item[0];
                $iclass = $item[1];
            } else {
                $value = $item;
                $iclass = null;
            }
            if (isset($iclass)) $output .= '<li class="' . $iclass . '">' . $value . '</li>';
            else $output .= '<li>' . $value . '</li>';
        }
        $output .= '</ul>';

        return $output;

    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    function _getGravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
         // Added to setect whether to use HTTP or HTTPS:
        $mode = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $url = ($mode == 'https') ? $mode.'://secure.gravatar.com/avatar/' : $mode.'://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
            $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    function _parseBrowserFromName($filename, $type = 'css', $timestamp = '')
    {

        $ext = substr($filename, strrpos($filename, '.'));
        $filename = substr($filename, 0, strrpos($filename, '.'));
        if ($timestamp != '') $timestamp = '?'.$timestamp;

        if (!preg_match("/^http(s?):/", $filename)) $filename = $this->templateUrl . '/' . $type . '/' . $filename;
        else return true;

        $checks = $this->browser->_checks;

        // Add RTL if enabled
        if ($this->document->direction == 'rtl') $checks[] = '-rtl';

        foreach ($checks as $check) {

            if (file_exists($this->adminPath . DS . $filename . $check . $ext)) {
                if ($type == 'js') $this->document->addScript($filename . $check . $ext . $timestamp);
                else $this->document->addStylesheet($filename . $check . $ext . $timestamp);
            }
        }

        return true;
    }

    function _getTools()
    {
        $user = JFactory::getUser();
        $canCheckin = $user->authorise('core.admin', 'com_checkin');
        $canCache = $user->authorise('core.manage', 'com_cache');

            $tools = array();

            // if can check-in
            // if ($this->params->get('enableQuickCheckin',0)) {
            //     $tools[] = $this->_addListItem(JText::_('MC_QUICK_CHECKIN'), 'qci', '#', '<span class="badge number">'.SCCheckin::getCheckouts(true).'</span>');
            // }
            if ($canCheckin) {
                $tools[] = $this->_addListItem(JText::_('Checkin Manager'), 'checkin', 'index.php?option=com_checkin');
                $tools[] = $this->_addListItem('___');
            }

            if ($canCache) {
                require_once('sccachecleaner.class.php');
                //$tools[] = $this->_addListItem(JText::_('Quick-Cache-Clean'), 'qcc', '#', '<span class="badge number">'.SCCacheClean::getCount().'</span>');

                $tools[] = $this->_addListItem(JText::_('Cache Manager'), 'config', 'index.php?option=com_cache');
                $tools[] = $this->_addListItem(JText::_('Purge Expired Cache'), 'config', 'index.php?option=com_cache&view=purge');
                $tools[] = $this->_addListItem('___');
            }

            // System Information
            $tools[] = $this->_addListItem(JText::_('System Information'), 'sysinfo', 'index.php?option=com_admin&view=sysinfo');
            return $this->_listify($tools, 'dropdown-menu');
    }

    function _renderEditor()
    {
        
    }

    // function _getTools()
    // {

    //     $user = JFactory::getUser();

    //     // cache some acl checks
    //     $canCheckin = $user->authorise('core.admin', 'com_checkin');
    //     $canCache = $user->authorise('core.manage', 'com_cache');
    //     $canAdmin = $user->authorise('core.admin');

    //     if ($canCheckin || $canCache || $canAdmin) {
    //         $tools = array();

    //         if ($canCheckin) {
    //             //index.php?process=ajax&model=quickcheckin
    //             require_once('rtcheckin.class.php');
    //             if ($this->params->get('enableQuickCheckin',0)) {
    //                 $tools[] = $this->_addListItem(JText::_('MC_QUICK_CHECKIN'), 'qci', '#', '<span class="badge number">'.RTCheckin::getCheckouts(true).'</span>');
    //             }
    //             $tools[] = $this->_addListItem(JText::_('MC_CHECKIN_MANAGER'), 'checkin', 'index.php?option=com_checkin');
    //             $tools[] = $this->_addListItem('___');
    //         }
    //         if ($canCache) {
    //             // index.php?process=ajax&model=quickcachecleaner
    //             require_once('rtcachecleaner.class.php');
    //             if ($this->params->get('enableQuickCacheClean',1)) {
    //                 $tools[] = $this->_addListItem(JText::_('MC_QUICK_CACHE_CLEAN'), 'qcc', '#', '<span class="badge number">'.RTCacheClean::getCount().'</span>');
    //             }
    //             $tools[] = $this->_addListItem(JText::_('MC_CACHE_MANAGER'), 'config', 'index.php?option=com_cache');
    //             $tools[] = $this->_addListItem(JText::_('MC_PURGE_EXPIRED_CACHE'), 'config', 'index.php?option=com_cache&view=purge');
    //             $tools[] = $this->_addListItem('___');

    //         }
    //         if ($canAdmin) {
    //             $tools[] = $this->_addListItem(JText::_('MC_SYS_INFO'), 'sysinfo', 'index.php?option=com_admin&view=sysinfo');
    //         }
    //         return $this->_listify($tools, 'mc-dropdown');
    //     }
    //     return false;

    // }

    /**
     * @return
     */
    function _getTemplateName()
    {
        $cid = JRequest::getVar('cid');
        if (is_array($cid))
            return $cid[0];
        else
            return null;
    }

    function _getAdminTemplate()
    {
        global $mainframe, $option;
        $template = null;
        $task = JRequest::getCmd('task');
        $client =& JApplicationHelper::getClientInfo(JRequest::getVar('client', '0', '', 'int'));

        if ($option == 'com_templates' && $task == 'edit' && $client->id == 1 && array_key_exists('cid', $_REQUEST)) {
            $template = $_REQUEST['cid'][0];
        }
        else {
            $template = $mainframe->getTemplate();
        }

        return $template;
    }

    function _getCurrentAdminTemplate()
    {
        $app = JFactory::getApplication('admin');
        return $app->getTemplate();
    }

    function _getCurrentSiteTemplate()
    {

        $db = JFactory::getDBO();
        $db->setQuery('select template from #__template_styles where client_id = 0 and home = 1');
        $template = $db->loadResult();

        return $template;
    }

    function _isGantrySiteTemplate()
    {

        $libPath = $this->basePath . DS . 'templates' . DS . $this->_getCurrentSiteTemplate() . DS . 'lib' . DS . 'gantry' . DS . 'gantry.php';

        if (file_exists($libPath)) return true;
        else return false;

    }

    function _isGantryTemplate()
    {

        $cid = JRequest::getVar('cid');
        if (is_array($cid)) {

            $libPath = $this->basePath . DS . 'templates' . DS . $cid[0] . DS . 'lib' . DS . 'gantry' . DS . 'gantry.php';

            if (file_exists($libPath)) return true;

        }
        return false;
    }


    function _getCurrentPageURL()
    {
        $isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
        $port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
        $port = ($port) ? ':' . $_SERVER["SERVER_PORT"] : '';
        $url = ($isHTTPS ? 'https://' : 'http://') . $_SERVER["SERVER_NAME"] . $port . $_SERVER["REQUEST_URI"];
        return $url;
    }


}
