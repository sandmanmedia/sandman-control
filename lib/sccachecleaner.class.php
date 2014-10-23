<?php

class SCCacheClean {

	function clean($ids = array())
	{
		$conf = JFactory::getConfig();

		// setup options with site cachebase
		$options = array(
			'defaultgroup'	=> '',
			'storage' 		=> $conf->get('cache_handler', ''),
			'caching'		=> true,
			'cachebase'		=> $conf->get('cache_path', JPATH_SITE.DS.'cache')
		);

		// clean out site caches
		$cache = JCache::getInstance('', $options);
		$site_caches = array_keys($cache->getAll());
		foreach ($site_caches as $key=>$group) {
			$cache->clean($group);
		}

		// modify options to use admin cachebase
		$options['cachebase'] = JPATH_ADMINISTRATOR.DS.'cache';
		$cache = JCache::getInstance('', $options);
		$admin_caches = array_keys($cache->getAll());
		foreach ($admin_caches as $key=>$group) {
			$cache->clean($group);
		}

	}

	static function getCount() {

			$conf = JFactory::getConfig();
			$count = 0;
			$allCache = array();

			// setup options with site cachebase
			$options = array(
				'defaultgroup'	=> '',
				'storage' 		=> $conf->get('cache_handler', ''),
				'caching'		=> true,
				'cachebase'		=> $conf->get('cache_path', JPATH_SITE.DS.'cache')
			);

			// clean out site caches
			$cache = JCache::getInstance('', $options);
			$site_caches = array_keys($cache->getAll());
			$count = sizeof($site_caches);

			if (file_exists(JPATH_ADMINISTRATOR.DS.'cache')) {

				// modify options to use admin cachebase
				$options['cachebase'] = JPATH_ADMINISTRATOR.DS.'cache';
				$cache = JCache::getInstance('', $options);

				$allCache = $cache->getAll();

				if ($allCache) {
					$admin_caches = array_keys($allCache);
					$count += sizeof($admin_caches);
				}
			}

			return($count);
	}

}
