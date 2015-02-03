<?php

class RestifyVersion {

	public static function getVersionInfoFromRequestParams($params = array()) {
		if (!isset($params['prefix']) || $params['prefix'] !== 'api') {
			return false;
		}

		if (!isset($params['version'])) {
			return false;
		}

		$version    	 = $params['version'];
		$action     	 = explode('_', $params['action']);
		$action     	 = $action[count($action) - 1];
		$apiAction  	 = "api_{$version}_{$action}";
		$downgradeAction = self::getActionDowngrade($apiAction);

		return array(
			'version' 		   => $version,
			'versionNumber'    => self::extractVersionNumber($version),
			'action'  		   => $action,
			'prefixedAction'   => $apiAction,
			'downgradeAction'  => $downgradeAction
		);
	}

	public static function getActionDowngrade($action) {
		$actionArray = explode('_', $action);
		// if action is api_index return itself
		if (!isset($actionArray[2])) {
			return $action;
		}

		$version       = $actionArray[1];
		$versionNumber = self::extractVersionNumber($version);
		$actionName	   = $actionArray[2];

		if ($versionNumber <= 2) {
			return "api_{$actionName}";
		}

		$downgradeVersion = $versionNumber - 1;
		return "api_v{$downgradeVersion}_{$actionName}";
	}

	public static function extractVersionNumber($version) {
		$match = null;
		preg_match('/[0-9]+/', $version, $match);

		return (int) $match[0];
	}
}
