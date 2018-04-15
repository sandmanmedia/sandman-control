<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.isis
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.0
 */

defined('_JEXEC') or die;
require_once('lib/sandmancontrol.class.php');
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
global $sctrl;
$sctrl = SandmanControl::getInstance();

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$lang = JFactory::getLanguage();
$this->language = $doc->language;
$this->direction = $doc->direction;
$input = $app->input;
$user = JFactory::getUser();

$session = JFactory::getSession();

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
$doc->addScriptVersion('templates/' . $this->template . '/js/template.js');

// Add Stylesheets
$doc->addStyleSheetVersion('templates/' . $this->template . '/css/template' . ($this->direction == 'rtl' ? '-rtl' : '') . '.css');
$doc->addStyleSheetVersion('templates/' . $this->template . '/css/main.css');

// Load specific language related CSS
$file = 'language/' . $lang->getTag() . '/' . $lang->getTag() . '.css';
if (is_file($file))
{
	$doc->addStyleSheetVersion($file);
}

//Load user custom css file if it exists
$file = 'templates/' . $$this->template . '/css/custom.css';
if (is_file($file))
{
	$doc->addStyleSheetVersion($file);
}

// Detecting Active Variables
$option = $input->get('option', '');
$view = $input->get('view', '');
$layout = $input->get('layout', '');
$task = $input->get('task', '');
$itemid = $input->get('Itemid', '');
$sitename = $app->getCfg('sitename');

$cpanel = ($option === 'com_cpanel');

$showSubmenu = false;
$this->submenumodules = JModuleHelper::getModules('submenu');
foreach ($this->submenumodules as $submenumodule)
{
	$output = JModuleHelper::renderModule($submenumodule);
	if (strlen($output))
	{
		$showSubmenu = true;
		break;
	}
}

// Logo file
if ($this->params->get('logoFile'))
{
	$logo = JUri::root() . $this->params->get('logoFile');
}
else
{
	$logo = $this->baseurl . '/templates/' . $this->template . '/images/logo.png';
}

// Template Parameters
$displayHeader = $this->params->get('displayHeader', '1');
$statusFixed = $this->params->get('statusFixed', '1');
$stickyToolbar = $this->params->get('stickyToolbar', '1');

$body_text_color 	= $this->params->get('body_text_color');
$admin_title_text 	= $this->params->get('admin_title_text');
$header_text_color 	= $this->params->get('header_text_color');
$header_link_color 	= $this->params->get('header_link_color');
$header_shadow_color= $this->params->get('header_shadow_color');
$header_bg_color 	= $this->params->get('header_bg_color');
$active_bg_color 	= $this->params->get('active_bg_color');
$active_text_color 	= $this->params->get('active_text_color');
$body_link_color 	= $this->params->get('body_link_color');
$tab_bg_color 		= $this->params->get('tab_bg_color');
$tab_text_color 	= $this->params->get('tab_text_color');
$hover_bg_color 	= $this->params->get('hover_bg_color');
$hover_text_color 	= $this->params->get('hover_text_color');
$special_bg_color 	= $this->params->get('special_bg_color');
$special_text_color = $this->params->get('special_text_color');
$showhelp = $this->params->get('showhelp');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<jdoc:include type="head" />
	<style></style>
	<!-- Template color -->
	<?php if ($this->params->get('templateColor')) : ?>
		<style type="text/css">
			.navbar-inner, .navbar-inverse .navbar-inner, .dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover, .navbar-inverse .nav li.dropdown.open > .dropdown-toggle, .navbar-inverse .nav li.dropdown.active > .dropdown-toggle, .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle, #status.status-top {
				background: <?php echo $this->params->get('templateColor'); ?>;
			}
			.navbar-inner, .navbar-inverse .nav li.dropdown.open > .dropdown-toggle, .navbar-inverse .nav li.dropdown.active > .dropdown-toggle, .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle {
				-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
				-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
				box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			}
		</style>
	<?php endif; ?>

	<!-- Template header color -->
	<?php if ($this->params->get('headerColor')) : ?>
		<style type="text/css">
			.header {
				background: <?php echo $this->params->get('headerColor'); ?>;
				background: transparent;
			}
		</style>
	<?php endif; ?>

	<!-- Sidebar background color -->
	<?php if ($this->params->get('sidebarColor')) : ?>
		<style type="text/css">
			.nav-list > .active > a, .nav-list > .active > a:hover {
				background: <?php echo $this->params->get('sidebarColor'); ?>;
			}
		</style>
	<?php endif; ?>

	<!--[if lt IE 9]>
	<script src="../media/jui/js/html5.js"></script>
	<![endif]-->

	<style>
		#sc-header {
			background-color: <?php echo $header_bg_color; ?>;
			border-bottom: 10px solid <?php echo $active_bg_color; ?>;
		}
		.nav-tabs > li > a:hover, .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover { background-color: <?php echo $active_bg_color; ?>; }
		.nav-list > .active > a, .nav-list > .active > a:hover, .nav-list > .active > a:focus { background-color: <?php echo $active_bg_color; ?>; }
		.nav-tabs > li > a:hover, .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover { color: <?php echo $active_text_color; ?>; }
		a { color: <?php echo $body_link_color; ?>; }

		#menu > li, .nav-tabs > li > a { background-color: <?php echo $tab_bg_color; ?>; }
		#menu > li a, .nav-tabs > li > a { color: <?php echo $tab_text_color; ?>; }

		#menu > li:hover > a, .sc-status > li:hover > a, #menu .dropdown-menu li > a:hover,
		#menu .dropdown-submenu:hover > a, .sc-status .dropdown-menu li > a:hover {
			background-color: <?php echo $hover_bg_color; ?>;
			color: <?php echo $hover_text_color; ?>;
		}

		.sc-status .logout { background-color: <?php echo $special_bg_color; ?>; }
		.sc-status .logout a { color: <?php echo $special_text_color; ?>; }
		.admin-title {
			color: <?php echo $header_link_color; ?>;
			font-weight: normal;
			float: left;
			margin-left: 20px;
			margin-top: 20px;
			text-shadow: 1px 1px 1px <?php echo $header_shadow_color; ?>;
		}
		body { color: <?php echo $body_text_color; ?>; }
		.sc-logo { float: left; }
		.clear { clear: both; }
		.header { background-color: transparent; background-image: none; }

		.userinfo .user-name { color: <?php echo $header_link_color; ?>; }
		.userinfo p { color: <?php echo $header_text_color; ?>; }
		/*
		<?php// if($showhelp): ?> #menu > li:last-child { display: none; } <?php //endif; ?>
		*/

	</style>
</head>

<body class="admin <?php echo $option . ' view-' . $view . ' layout-' . $layout . ' task-' . $task . ' itemid-' . $itemid; ?>" <?php if ($stickyToolbar) : ?>data-spy="scroll" data-target=".subhead" data-offset="87"<?php endif; ?>>

<!-- HEADER -->

<div id="sc-header" style="line-height: 1;">
	<div class="sc-container" style="line-height: 1;">
		<div style="display: inline-block;">
			<img src="<?php echo $logo; ?>" class="sc-logo" />
			<h1 class="admin-title" style="margin-bottom: 18px;"><?php echo $admin_title_text; ?></h1>

		<div class="clear"></div>




		<div id="sc-nav">
			<?php $sctrl->displayMenu(); ?>
			<div class="clear"></div>
		</div> <!-- end #sc-nav -->
		</div>

		<div id="sc-status-container">
			<?php $sctrl->displayStatus(); ?>
 			<!-- <ul>
				<li><a href="#">View Site</a></li>

			</ul> -->
			<?php $sctrl->displayUserInfo(); ?>
			<div class="clear"></div>
		</div> <!-- end #sc-status -->







	</div>
	<div class="clear"></div>
</div>
<?php if(0): ?>

<!-- Top Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<?php if ($this->params->get('admin_menus') != '0') : ?>
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
			<?php endif; ?>

			<a class="admin-logo" href="<?php echo $this->baseurl; ?>"><span class="icon-joomla"></span></a>

			<a class="brand hidden-desktop hidden-tablet" href="<?php echo JUri::root(); ?>" title="<?php echo JText::sprintf('TPL_SANDMANCONTROL_PREVIEW', $sitename); ?>" target="_blank"><?php echo JHtml::_('string.truncate', $sitename, 14, false, false); ?>
				<span class="icon-out-2 small"></span></a>

			<div<?php echo ($this->params->get('admin_menus') != '0') ? ' class="nav-collapse"' : ''; ?>>
				<!--<___jdoc:include type="modules" name="menu" style="none" />-->
				<?php // $sctrl->displayMenu(); ?>
				<ul class="nav nav-user<?php echo ($this->direction == 'rtl') ? ' pull-left' : ' pull-right'; ?>">
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="icon-cog"></span>
							<b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li>
								<span>
									<span class="icon-user"></span>
									<strong><?php echo $user->name; ?></strong>
								</span>
							</li>
							<li class="divider"></li>
							<li class="">
								<a href="index.php?option=com_admin&task=profile.edit&id=<?php echo $user->id; ?>"><?php echo JText::_('TPL_SANDMANCONTROL_EDIT_ACCOUNT'); ?></a>
							</li>
							<li class="divider"></li>
							<li class="">
								<a href="<?php echo JRoute::_('index.php?option=com_login&task=logout&' . JSession::getFormToken() . '=1'); ?>"><?php echo JText::_('TPL_SANDMANCONTROL_LOGOUT'); ?></a>
							</li>
						</ul>
					</li>
				</ul>
				<a class="brand visible-desktop visible-tablet" href="<?php echo JUri::root(); ?>" title="<?php echo JText::sprintf('TPL_SANDMANCONTROL_PREVIEW', $sitename); ?>" target="_blank"><?php echo JHtml::_('string.truncate', $sitename, 14, false, false); ?>
					<span class="icon-out-2 small"></span></a>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
</nav>
<?php endif; ?>
<!-- Header -->
<?php if ($displayHeader) : ?>
	<header class="header">
		<div class="container-logo">
			<!--<img src="<?php echo $logo; ?>" class="logo" />-->
			<jdoc:include type="modules" name="toolbar" style="no" />
		</div>
		<div class="container-title">
			<jdoc:include type="modules" name="title" />
		</div>
	</header>
<?php endif; ?>
<?php if ((!$statusFixed) && ($this->countModules('status'))) : ?>
	<!-- Begin Status Module -->
	<div id="status" class="navbar status-top hidden-phone">
		<div class="btn-toolbar">
			<jdoc:include type="modules" name="status" style="no" />
		</div>
		<div class="clearfix"></div>
	</div>
	<!-- End Status Module -->
<?php endif; ?>
<?php if (!$cpanel) : ?>
	<!-- Subheader -->
	<!--
	<a class="btn btn-subhead" data-toggle="collapse" data-target=".subhead-collapse"><?php echo JText::_('TPL_SANDMANCONTROL_TOOLBAR'); ?>
		<i class="icon-wrench"></i></a>
	<div class="subhead-collapse collapse">
		<div class="subhead">
			<div class="container-fluid">
				<div id="container-collapse" class="container-collapse"></div>
				<div class="row-fluid">
					<div class="span12">
						<jdoc:include type="modules" name="toolbar" style="no" />
					</div>
				</div>
			</div>
		</div>
	</div>
	-->
<?php else : ?>
	<div style="margin-bottom: 20px"></div>
<?php endif; ?>
<!-- container-fluid -->
<div class="container-fluid container-main">
	<section id="content">
		<!-- Begin Content -->
		<jdoc:include type="modules" name="top" style="xhtml" />
		<div class="row-fluid">
			<?php if ($showSubmenu) : ?>
			<div class="span2">
				<jdoc:include type="modules" name="submenu" style="none" />
			</div>
			<div class="span10">
				<?php else : ?>
				<div class="span12">
					<?php endif; ?>
					<jdoc:include type="message" />
					<?php
					// Show the page title here if the header is hidden
					if (!$displayHeader) : ?>
						<h1 class="content-title"><?php echo JHtml::_('string.truncate', $app->JComponentTitle, 0, false, false); ?></h1>
					<?php endif; ?>
					<jdoc:include type="component" />
				</div>
			</div>
			<?php if ($this->countModules('bottom')) : ?>
				<jdoc:include type="modules" name="bottom" style="xhtml" />
			<?php endif; ?>
			<!-- End Content -->
	</section>

	<?php if (!$this->countModules('status') || (!$statusFixed && $this->countModules('status'))) : ?>
		<footer class="footer">
			<p align="center">
				<jdoc:include type="modules" name="footer" style="no" />
				&copy; <?php echo $sitename; ?> <?php echo date('Y'); ?></p>
		</footer>
	<?php endif; ?>
</div>
<?php if (($statusFixed) && ($this->countModules('status'))) : ?>
	<!-- Begin Status Module -->
	<div id="status" class="navbar navbar-fixed-bottom hidden-phone">
		<div class="btn-toolbar">
			<div class="btn-group pull-right">
				<p>
					<jdoc:include type="modules" name="footer" style="no" />
					&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
				</p>

			</div>
			<jdoc:include type="modules" name="status" style="no" />
		</div>
	</div>
	<!-- End Status Module -->
<?php endif; ?>
<jdoc:include type="modules" name="debug" style="none" />
<?php if ($stickyToolbar) : ?>
	<script>
		(function($)
		{
			// fix sub nav on scroll
			var $win = $(window)
				, $nav = $('.subhead')
				, navTop = $('.subhead').length && $('.subhead').offset().top - <?php if ($displayHeader || !$statusFixed) : ?>40<?php else:?>20<?php endif;?>
				, isFixed = 0

			processScroll()

			// hack sad times - holdover until rewrite for 2.1
			$nav.on('click', function()
			{
				if (!isFixed) {
					setTimeout(function()
					{
						$win.scrollTop($win.scrollTop() - 47)
					}, 10)
				}
			})

			$win.on('scroll', processScroll)

			function processScroll()
			{
				var i, scrollTop = $win.scrollTop()
				if (scrollTop >= navTop && !isFixed) {
					isFixed = 1
					$nav.addClass('subhead-fixed')
				} else if (scrollTop <= navTop && isFixed) {
					isFixed = 0
					$nav.removeClass('subhead-fixed')
				}
			}
		})(jQuery);
	</script>
	<script src="templates/sandmancontrol/js/main.js"></script>
<?php endif; ?>
</body>
</html>
