<?php
/** 
 * Images optimizer and lightner for Responsivizer mobile template
 * @package RESPONSIVIZER::LIGHTIMAGES::plugins::system
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.folder');

/**
 * Observer class notified on events
 *
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::LIGHTIMAGES::plugins::system
 * @since 2.0
 */
class plgSystemLightImages extends JPlugin {
	function onAfterRender() {
		// Avoid unuseful operations if not enabled
		if (!$this->isEnabled) {
			return false;
		}

		// App instance
		$app = JFactory::getApplication();

		// Ensure valid execution of plugin optimization
		$document = JFactory::getDocument();
		$template = $app->getTemplate();
		if ($document->getType() !== 'html' || $app->input->get('tmpl') === 'component' || $template !== $this->params->get('template')) {
			return false;
		}

		if ($app->input->get("task") == "edit" || $app->isAdmin()) {
			return false;
		}

		// Ensure valid cache folder exists
		if (!JFolder::exists(JPATH_CACHE . '/com_responsivizer')) {
			JFolder::create(JPATH_CACHE . '/com_responsivizer');
		}

		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML(JResponse::getBody());
		libxml_clear_errors();
		
		$nodes = $doc->getElementsByTagName('img');

		foreach ($nodes as $node) {
			$this->processImageNodes($node);
		}
		JResponse::setBody($doc->saveHTML());
	}

	function processImageNodes(&$node) {
		$quality = $this->params->get('img_quality', 75);
		$pngQuality = 10 - (int) ($quality / 10); // Inverted as level of compression
		$resizeFactorSwitcher = $this->params->get('img_resizing_switcher', false);
		$resizeFactor = $this->params->get('img_resizing', 75);
		$resizeMinWidth = $this->params->get('img_resizing_minwidth', 1280);

		$cache_path = JPATH_CACHE . '/com_responsivizer';
		$cache_path_http = JURI::root() . "cache/com_responsivizer";

		// Get what we want
		if ($node->nodeType == XML_ELEMENT_NODE && $node->hasAttributes()) {
			if ($node->getAttribute('height') && strpos($node->getAttribute('height'), 'px')) {
				$heightExplicitFromNode = (int) $node->getAttribute("height");
			}

			if ($node->getAttribute('width') && strpos($node->getAttribute('width'), 'px')) {
				$widthExplicitFromNode = (int) $node->getAttribute("width");
			}

			if ($node->getAttribute("src") != "")
				$src = $node->getAttribute("src");
		}

		if (!isset($src)) {
			return false;
		}

		// Need to remove encoding
		$imagePath = urldecode($src);

		$urlparts = parse_url($imagePath);
		// Dose this URL contain a host name?
		if (!empty($urlparts["host"])) {
			// is it local?
			if (substr($imagePath, 0, strlen(JURI::root())) == JURI::root()) {
				// This is a local url
				// Remove the URL
				$imagePath = substr($imagePath, strlen(JURI::root()));
			}
			return true;
		}

		if ($imagePath[0] == "/") {
			$root = JURI::base(true);
			if (substr($imagePath, 0, strlen($root)) == $root) {
				$imagePath = dirname($_SERVER["SCRIPT_FILENAME"]) . substr($imagePath, strlen($root));
			}
		}

		if (realpath($imagePath) === false) {
			return false;
		}

		$imagePath = realpath($imagePath);
		$path_parts = pathinfo($src);

		switch (isset($path_parts['extension']) ? strtolower($path_parts['extension']) : null) {
		case 'jpeg':
		case 'jpg':
		case 'swf':
		case 'psd':
		case 'bmp':
		case 'tiff':
		case 'jpc':
		case 'jp2':
		case 'jpf':
		case 'jb2':
		case 'swc':
		case 'aiff':
		case 'wbmp':
		case 'xbm':
			$new_ext = 'jpeg';
			break;
		case 'gif':
		//!! GD dosent support resizing animated gifs
			$support_gif = (bool) $this->params->get('img_support_gif', 1);
			if ($support_gif) {
				$new_ext = 'png';
			} else {
				return false;
			}

			break;
		case 'png':
			$new_ext = 'png';
			break;
		default:
			$new_ext = 'png';
			$pref = $imagePath;
			break;
		}

		// Skip images for excluded extensions
		if (in_array($path_parts['extension'], $this->excludedExts)) {
			return false;
		}

		$filename = sha1($src) . "." . $new_ext;
		$full_path_filename = $cache_path . "/" . $filename;

		// If cache file exists don't process anymore
		if (@is_file($full_path_filename) && @is_file($imagePath) && @filemtime($full_path_filename) > @filemtime($imagePath)) {
			// Files that are 0bytes, mean that they sould be ignored.
			if (filesize($full_path_filename) == 0) {
				return true;
			}

			$url = $cache_path_http . "/" . $filename;
		} else {
			list($image, $image_file_size) = $this->fetchImageData($imagePath);
			if ($image === false) {
				return false;
			}

			$widthOriginal = imagesx($image);
			$heightOriginal = imagesy($image);

			if (!isset($heightExplicitFromNode) || !isset($widthExplicitFromNode)) {
				$imageWidth = $widthOriginal;
				$imageHeight = $heightOriginal;
			} else {
				$imageWidth = $widthExplicitFromNode;
				$imageHeight = $heightExplicitFromNode;
			}

			// Override $imageWidth and $imageHeight if factor percentage is enabled
			if ($resizeFactorSwitcher && $imageWidth >= $resizeMinWidth) {
				if ($new_ext == 'png') {
					// Force always 50% for png images to avoid greater sizes
					$resizeFactor = 50;
				}
				$imageWidth = intval($imageWidth * $resizeFactor / 100);
				$imageHeight = intval($imageHeight * $resizeFactor / 100);
			}

			$result = @imagecreatetruecolor($imageWidth, $imageHeight);
			if ($result == false)
				return false;

			if ($new_ext == 'png') {
				imagealphablending($result, false);
				$transparent = imagecolorallocatealpha($result, 0, 0, 0, 127);
				imagefill($result, 0, 0, $transparent);
				imagesavealpha($result, true);
				imagealphablending($result, true);
			}

			$sample = @imagecopyresampled($result, $image, 0, 0, 0, 0, $imageWidth, $imageHeight, $widthOriginal, $heightOriginal);

			if ($sample == false)
				return false;

			switch ($new_ext) {
			case 'jpeg':
				$save = @imagejpeg($result, $full_path_filename, $quality);
				break;
			case 'png':
				$save = @imagepng($result, $full_path_filename, $pngQuality);
				break;
			}

			if ($save == false) {
				return false;
			}

			@imagedestroy($image);
			@imagedestroy($result);

			// Make sure we are really creating a smaller image!
			if (filesize($full_path_filename) >= $image_file_size) {
				// Files that are 0bytes, mean that they sould be ignored.
				unlink($full_path_filename);
				return true;
			}

			$url = $cache_path_http . "/" . $filename;
		}

		// set the new image location
		$node->setAttribute("src", $url);

		return true;
	}

	function fetchImageData($file) {
		$data = file_get_contents($file);

		// could not open image?
		if ($data === false) {
			return array(false, strlen($data));
		}

		$img = imagecreatefromstring($data);

		return array($img, strlen($data));
	}

	public function __construct(&$subject) {
		parent::__construct($subject);
		$this->params = JComponentHelper::getParams('com_responsivizer');
		$this->isEnabled = $this->params->get('plugin_lightimgs_status', false);
		$this->excludedExts = $this->params->get('img_exts_excluded', array());
	}
}

?>
