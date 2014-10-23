<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$document = JFactory::getDocument();

$tmplpath = dirname(__FILE__).DS;
$fullpath_menupath = $tmplpath.($enabled ? 'default_enabled' : 'default_disabled').'.php';

require  ($fullpath_menupath);

// $menu->renderMenu('menu', $enabled ? 'nav ' . $direction : 'nav disabled ' . $direction);
$menu->renderMenu('menu', $enabled ? 'nav '  : 'nav disabled ' );

