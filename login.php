<?php
/**
 * @package     Sandman
 * @subpackage  Templates.sandmancontrol
 *
 * @copyright   Copyright (C) 2014 Sandman Media, Inc. All rights reserved.
 * @license
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$lang = JFactory::getLanguage();

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.tooltip');

// Add Stylesheets
$doc->addStyleSheetVersion('templates/' . $this->template . '/css/template' . ($this->direction == 'rtl' ? '-rtl' : '') . '.css');
$doc->addStyleSheet('templates/' .$this->template. '/css/login.css');

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Load specific language related CSS
$file = 'language/' . $lang->getTag() . '/' . $lang->getTag() . '.css';
if (is_file($file)) :
	$doc->addStyleSheet($file);
endif;

//Load user custom css file if it exists
$file = 'templates/' . $$this->template . '/css/custom.css';
if (is_file($file))
{
	$doc->addStyleSheetVersion($file);
}

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');

// Template Parameters
$header_link_color 	= $this->params->get('header_link_color');
$header_shadow_color= $this->params->get('header_shadow_color');
$header_bg_color = $this->params->get('header_bg_color');
$active_bg_color = $this->params->get('active_bg_color');
$hover_bg_color 	= $this->params->get('hover_bg_color');
$special_bg_color 	= $this->params->get('special_bg_color');
$special_text_color = $this->params->get('special_text_color');
$hover_bg_color 	= $this->params->get('hover_bg_color');

// Logo file
if ($this->params->get('logoFile'))
{
	$logo = JUri::root() . $this->params->get('logoFile');
}
else
{
	$logo = $this->baseurl. '/templates/'. $this->template . '/images/logo.png';
}








?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<jdoc:include type="head" />
	<script type="text/javascript">
		window.addEvent('domready', function ()
		{
			document.getElementById('form-login').username.select();
			document.getElementById('form-login').username.focus();
		});
	</script>
	<!--
	<style type="text/css">
		/* Responsive Styles */
		@media (max-width: 480px) {
			.view-login .container {
				margin-top: -170px;
			}
			.btn {
				font-size: 13px;
				padding: 4px 10px 4px;
			}
		}
		<?php // Check if debug is on ?>
		<?php if ($app->get('debug_lang', 1) || $app->get('debug', 1)) : ?>
			.view-login .container {
				position: static;
				margin-top: 20px;
				margin-left: auto;
				margin-right: auto;
			}
			.view-login .navbar-fixed-bottom {
				display: none;
			}
		<?php endif; ?>
	</style>
	-->
	<!--[if lt IE 9]>
		<script src="../media/jui/js/html5.js"></script>
	<![endif]-->

	<style>
		#sc-login #sc-header {
			background-color: <?php echo $header_bg_color; ?>;
			border-bottom: 10px solid <?php echo $active_bg_color; ?>;
		}

		#sc-login-form fieldset .control-group .btn.btn-primary.btn-large:hover {
		    background: none repeat scroll 0 0 <?php echo $hover_bg_color; ?>;
		    border: medium none;
		    padding: 7px 27px;
		}

		#sc-header .sc-status .frontend { background-color: <?php echo $special_bg_color; ?>; }
		#sc-header .sc-status .frontend a { color: <?php echo $special_text_color; ?>; }
		#sc-header .sc-status a:hover { background-color: <?php echo $hover_bg_color; ?>; }
		#sc-login #sc-logo .admin-title {
			color: <?php echo $header_link_color; ?>;
			font-weight: normal;
			text-shadow: 1px 1px 1px <?php echo $header_shadow_color; ?>;
		}
	</style>
</head>

<body id="sc-login" class="site">
	<header id="sc-header">
		<div id="sc-status">
			<ul class="sc-status"><li class="frontend"><a href="<?php JUri::root(); ?>" target="_blank">Frontend</a></li></ul>
		</div>
		<div id="sc-logo">
			<img src="<?php echo $logo; ?>" class="sc-logo" />
			<h1 class="admin-title">Administrator Login</h1>
		</div>
	</header>
	<div id="sc-login-form">
		<jdoc:include type="component" />
	</div>
	<!-- Container -->
	<div class="container">
		<div id="content">
			<!-- Begin Content -->
			<!--
			<div id="element-box" class="login well">
				<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/images/joomla.png" alt="Joomla!" />
				<hr />
				<jdoc:include type="message" />
				<jdoc:include type="component" />
			</div>
		-->
			<noscript>
				<?php echo JText::_('JGLOBAL_WARNJAVASCRIPT') ?>
			</noscript>
			<!-- End Content -->
		</div>
	</div>
	<!--
	<div class="navbar navbar-fixed-bottom hidden-phone">
		<p class="pull-right">
			&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
		</p>
		<a class="login-joomla" href="http://www.joomla.org" target="_blank" class="hasTooltip" title="<?php echo JHtml::tooltipText('TPL_ISIS_ISFREESOFTWARE');?>">Joomla!&#174;</a>
		<a href="<?php echo JUri::root(); ?>" target="_blank" class="pull-left"><i class="icon-share icon-white"></i> <?php echo JText::_('COM_LOGIN_RETURN_TO_SITE_HOME_PAGE') ?></a>
	</div>-->
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
