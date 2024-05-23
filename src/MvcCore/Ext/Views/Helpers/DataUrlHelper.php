<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Views\Helpers;

/**
 * Responsibility - get any file content by given relative or absolute path in data URL format: `data:image/png;base64,iVBOR..`.
 * - Path could be relative from currently rendered view,
 *   relative from application root or absolute path to file.
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/Data_URIs
 * @method static \MvcCore\Ext\Views\Helpers\DataUrlHelper GetInstance()
 */
class DataUrlHelper implements \MvcCore\Ext\Views\Helpers\IHelper {

	/**
	 * MvcCore Extension - View Helper - Assets - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.0.0';

	/**
	 * Currently rendered view instance.
	 * @var \MvcCore\View|NULL
	 */
	protected $view = NULL;

	/**
	 * Application root path on hard drive.
	 * Example: `"C:/www/my/development/directory/www"`
	 * @var string|NULL
	 */
	protected $appRoot = NULL;

	/**
	 * Create view helper instance, every time new instance.
	 * @static
	 * @return \MvcCore\Ext\Views\Helpers\DataUrlHelper
	 */
	public static function & GetInstance () {
		$instance = new static;
		return $instance;
	}

	/**
	 * Set currently rendered view instance every time this helper
	 * is called and the rendered view instance is changed.
	 * This method sets these protected object references:
	 * - `DataUrlHelper::$view`		as `\MvcCore\View|\MvcCore\IView`
	 * - `DataUrlHelper::$request`	as `\MvcCore\Request|\MvcCore\IRequest`
	 * @param \MvcCore\View $view
	 * @return \MvcCore\Ext\Views\Helpers\DataUrlHelper
	 */
	public function SetView (\MvcCore\IView $view) {
		$this->view = $view;
		$this->appRoot = $view->GetController()->GetRequest()->GetAppRoot();
		return $this;
	}

	/**
	 * Return any file content by given relative or absolute path in data url.
	 * Path could be relative from currently rendered view,
	 * relative from application root or absolute path to file.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/Data_URIs
	 * @param string $relativeOrAbsolutePath
	 * @throws \InvalidArgumentException If file not found by given path in any searched place.
	 * @return string Data URL value like: `data:image/png;base64,iVBORw0KGgoAAAANSUhEUgA...`
	 */
	public function DataUrl ($relativeOrAbsolutePath) {
		$currentDirFullPath = $this->view->GetCurrentViewDirectory();
		$searchedPaths = [];
		// first - try to find file relatively from currently rendered view
		array_unshift($searchedPaths, $currentDirFullPath . '/' . $relativeOrAbsolutePath);
		if (!file_exists($searchedPaths[0])) {
			// second - try to find file relatively from application root
			array_unshift($searchedPaths, $this->appRoot . '/' . ltrim($relativeOrAbsolutePath, '/'));
			if (!file_exists($searchedPaths[0])) {
				// third - try to find file absolutely
				array_unshift($searchedPaths, $relativeOrAbsolutePath);
				if (!file_exists($searchedPaths[0])) 
					// throw an error at last
					throw new \InvalidArgumentException(
						"[".get_class()."] File not found in paths: '".implode("', '", array_reverse($searchedPaths))."'."
					);
			}
		}
		// get last searched path
		$fileFullPath = $searchedPaths[0];
		// Read image path, convert to base64 encoding
		$imageData = base64_encode(file_get_contents($fileFullPath));
		// Format the image SRC:  data:{mime};base64,{data};
		$result = 'data: '.mime_content_type($fileFullPath).';base64,'.$imageData;
		return $result;
	}
}
