<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/28/12
 * Time: 11:09 AM
 * To change this template use File | Settings | File Templates.
 */
class FileHelper extends CFileHelper {

	/**
	 *
	 * Format path
	 * add backslash '/' in the end of path if needed
	 *
	 * @param string $path
	 * @return string formatted path
	 */
	public static function formatPath($path) {
		if (substr($path, -1) !== '/') {
			$path .= '/';
		}
		return $path;
	}

	/**
	 *
	 * Recursively deletion of $file in $path
	 *
	 * @param string $path source folder
	 * @param string $file file to delete in source folder
	 *
	 * @return void
	 */
	public static function deleteFile($path, $file) {
		if (!is_dir($path)) {
			return;
		}
		$items	= scandir($path);
		$count	= sizeof($items);
		$path = self::formatPath($path);
		if (file_exists($path . $file)) {
			unlink($path . $file);
		}
		for ($i = 0; $i < $count; $i++) {
			if ($items[$i] == '.' || $items[$i] == '..') {
				continue;
			}
			if (is_dir($path . $items[$i])) {
				self::deleteFile($path . $items[$i], $file);
			}
		}
	}

	/**
	 *
	 * Delete directory in folder
	 *
	 * Find $directory in $folder and delete it, recursively searching and deleteion
	 *
	 * @param string $folder folder when will look directory for delete
	 * @param string $directory search condition
	 *
	 * @return void
	 */
	public static function deleteDirectoryInFolder($folder, $directory) {
		$items	= scandir($folder);
		$count	= sizeof($items);
		$folder = self::formatPath($folder);
		for ($i = 0; $i < $count; $i++) {
			if ($items[$i] == '.' || $items[$i] == '..') {
				continue;
			}
			if (is_dir($folder . $items[$i])) {
				if ($items[$i] == $directory) {
					self::deleteDirectory($folder . $items[$i]);
				} else {
					self::deleteDirectoryInFolder($folder . $items[$i], $directory);
				}
			}
		}
	}

	/**
	 * Delete directory and all files in current directory
	 *
	 * @param string $dir directory to delete
	 */
	public static function deleteDirectory($dir){
		if (!is_dir($dir)) {
			return;
		}
		$handle = opendir($dir);
		while ($item = readdir($handle)) {
			if ($item == '.' || $item == '..') {
				continue;
			}
			if (is_dir($dir . '/' . $item)) {
				self::deleteDirectory($dir . '/' . $item);
			} else {
				unlink($dir . '/' . $item);
			}
		}
		closedir($handle);
		rmdir($dir);
	}

	/**
	 *
	 * Recursivly Create directory
	 *
	 *
	 * @param string $dir
	 */
	public static function makedir($dir) {
		if (is_dir($dir)) {
			return;
		}
		$path = '/';
		$dir_arr = explode('/', $dir);
		if (sizeof($dir_arr) > 0) {
			foreach ($dir_arr as $part) {
				if (strlen($part) > 0) {
					if (isset($part[1]) && $part[1] == ':') {
						$path = $part . '/';
					}
					else {
						$path .= $part . '/';
					}
					if (!file_exists($path)) {
						mkdir($path);
					}
				}
			}
		}
	}
}
