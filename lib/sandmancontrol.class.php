<?php
require_once('sccore.class.php');

class SandmanControl extends SCCore {
	private static $instance;
	public static function getInstance(){
        if (!self::$instance)
        {
            self::$instance = new SandmanControl();
        }
        return self::$instance;
    }

    function displayMenu() {
   	    require_once('menu'.DS.'menu.php' );
	}

    function displayStatus() {
        $output = array();

        $output[] = array($this->_renderEditorSelect(), 'mdropdown quickedit');

        $output[] = $this->_addListItem("<a href=\"".JUri::root()."\" target=\"_blank\">".JText::_('View Site')."</a>","action");
        // require_once('menu'.DS.'menu.php' );
        $tools = $this->_getTools();
        $output[] = array('<a href="#" id="ToolsToggle"><span class="select-active">'.JTEXT::_('System Tools').'</span><span class="select-arrow">&#x25BE;</span></a>'.$tools, 'dropdown');
        

        $logoutLink = JRoute::_('index.php?option=com_login&task=logout&'. JSession::getFormToken() .'=1');
        $output[] = $this->_addListItem("<a href=\"".$logoutLink."\">".JText::_('TPL_SANDMANCONTROL_LOGOUT')."</a>","logout");


        echo $this->_listify($output,'sc-status');
    }

    function displayUserInfo() {

        $db         = JFactory::getDBO();
        $user       = JFactory::getUser();
        $task       = JRequest::getString('task');

        $lastvisit = JHTML::_('date', $user->lastvisitDate, 'Y-m-d H:i:s');

        $output = '';
        $output .= '<div class="userinfo-container">';

        if($this->params->get('enable_gravatar')) 
            $gravatar = $this->_getGravatar($user->email,46);
        else 
            $gravatar = $this->_getGravatar('',46);

            // $gravatar = $this->_getGravatar($user->email,46);
            $output .= '<div class="gravatar"><img src="'.$gravatar.'" alt="gravatar" /></div>';

        $output .= '<div class="userinfo">';
        // $output .= '<b>'.$user->name . '</b>';
        $output .= '<b class="user-name">'.$user->name . '</b>';
        // $output .= '<span class="mc-button">'.$edit_link.JTEXT::_('MC_EDIT_BUTTON').'</a></span>';
        // $output .= '<span class="mc-messages">'.$messages . '</span>';
        $output .= '<p class="last-visit">last visit: ' . $lastvisit . '</p>';
        $output .= '</div>';
        $output .= '<div class="clear"></div>';
        $output .= '<div class="session_expire">';
        $output .= '<div class="session_tip"></div>';
        $output .= '<div class="session_progress"></div>';
        $output .= '</div> <!-- end session_expire -->';
        $output .= '</div>';
        $output .= '<div class="clear"></div>';

        echo $output;

    }



    function _getEditors() {

        $dbo = JFactory::getDBO();
        $query = 'SELECT element, name '.
            'FROM #__extensions '.
            'WHERE type = "plugin" '.
            'AND folder = "editors" '.
            'AND enabled = 1 '.
            'ORDER BY ordering, name';
        $dbo->setQuery($query);
        $editors = $dbo->loadObjectList();

        return $editors;
    }

    function _renderEditorSelect() {

        $conf = JFactory::getConfig();
        $myEditor = $conf->get('editor');
        $user = JFactory::getUser();


        $output = '<li class="dropdown" id="editor" data-user="'.$user->id.'">';
        $output .= '<a href="#" ><span class="select-active">';

        foreach ($this->_getEditors() as $editor) {
            if ($myEditor == $editor->element) {
                $output .= 'Editor - '.ucfirst($editor->element); 
                break;
            }
        }

        $output .='</span><span class="select-arrow">▾</span></a>';

        $output .='<ul class="dropdown-menu" id="editor-select">';

        foreach ($this->_getEditors() as $editor) {
            $output .= '<li class="checkin"><a href="#" data-editor="'.$editor->element.'">Editor - '.ucfirst($editor->element).'</a></li>';
        }

        $output .='</ul>';
        $output .='</li>';

        return $output;
    }
}