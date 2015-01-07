<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 17.10.13
 * Time: 13:06
 * To change this template use File | Settings | File Templates.
 */
class FilesComponent extends CApplicationComponent  {

	/**
	 *
	 * Save file from stdin
	 * @param string $file - desctination file
	 * @return bool
	 */
	public static function saveFromSTDIN($file) {
		$input = fopen("php://input", "r");
		$temp = fopen($file, "wb+");
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);
		fclose($temp);
		return (file_exists($file) && is_file($file));
	}

	/**
	 *
	 * Recursive deletion of $file in $path
	 *
	 * @param string $path
	 * @param string $file
	 *
	 * @return void
	 */
	public static function delFile($path, $file) {
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
				self::delFile($path . $items[$i], $file);
			}
		}
	}

	/**
	 *
	 * Delete directory in folder
	 *
	 * @param string $path
	 * @param string $dir
	 *
	 * @return void
	 */
	public static function delDirInPath($path, $dir) {
		$items	= scandir($path);
		$count	= sizeof($items);
		$path = self::formatPath($path);
		for ($i = 0; $i < $count; $i++) {
			if ($items[$i] == '.' || $items[$i] == '..') {
				continue;
			}
			if (is_dir($path . $items[$i])) {
				if ($items[$i] == $dir) {
					self::deldir($path . $items[$i]);
				} else {
					self::delDirInPath($path . $items[$i], $dir);
				}
			}
		}
	}

	/**
	 *
	 * Get file extension
	 *
	 * @param string $file
	 */
	public static function getFileExt($file) {
		if (empty($file)) {
			return '';
		}
		return Core_Functions_String::substr($file, Core_Functions_String::strrpos($file, '.') + 1, Core_Functions_String::strlen($file) - 1);
	}

	/**
	 *
	 * Format path
	 * add backslash '/' in the end
	 *
	 * @param string $path
	 * @return string
	 */
	public static function formatPath($path) {
		if (substr($path, -1) !== '/') {
			$path .= '/';
		}
		return $path;
	}

	public static function deldir($dir){
		if (!is_dir($dir)) {
			return;
		}
		$handle = opendir($dir);
		while ($item = readdir($handle)) {
			if ($item == '.' || $item == '..') {
				continue;
			}
			if (is_dir($dir . '/' . $item)) {
				self::deldir($dir . '/' . $item);
			} else {
				unlink($dir . '/' . $item);
			}
		}
		closedir($handle);
		rmdir($dir);
	}

	/**
	 *
	 * Send downloader header for $file
	 * send header and output file and exit
	 *
	 * @param string $file - file path
	 * @param string $contentType
	 */
	public static function downloadHeader($file, $contentType = 'application/octet-stream') {
		if (!file_exists($file)) {
			return false;
		}
		header('Content-Description: File Transfer');
		header('Content-Type: ' . $contentType);
		header('Content-Disposition: attachment; filename=' . (basename($file)));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		readfile($file);
		exit;
	}

	public static function downloadHeaderConfigure($file, $headers = array()) {
		$headers = array_merge(array(
			'Content-Description' => 'File Transfer',
			'Content-Type' => 'application/octet-stream',
			'Content-Disposition' => 'attachment; filename=' . (basename($file)),
			'Content-Transfer-Encoding' => 'binary',
			'Expires' => 0,
			'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
			'Pragma' => 'public',
			'Content-Length' => filesize($file)
		), $headers);
		foreach ($headers as $header => $hvalue) {
			header(sprintf('%s: %s', $header, $hvalue));
		}
		readfile($file);
		exit;
	}

	/**
	 *
	 * Like analog downloadHeader
	 * except no file output reading
	 *
	 * @see Core_Functions_Files::downloadHeader
	 * @param string $fileName
	 * @param int $contentLength
	 * @param string $contentType
	 */
	public static function downloadHeaderSent($fileName, $contentLength, $contentType = 'application/octet-stream') {
		header('Content-Description: File Transfer');
		header('Content-Type: ' . $contentType);
		header('Content-Disposition: attachment; filename=' . $fileName);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . $contentLength);
	}

	public static function dirtoutf8($dir){
		if (!is_dir($dir)) return;
		$current_dir = @opendir($dir);
		while($entryname = @readdir($current_dir))
			if (@is_dir($dir.'/'.$entryname) && ($entryname != '.') && ($entryname!='..') )
				dirtoutf8($dir.'/'.$entryname);
			elseif ($entryname != ('.') && ($entryname!='..') )
				echo($dir.'/'.$entryname);
		@closedir($current_dir);
		@rmdir($dir);
	}

	public static function _dir_copy($oldname, $newname)
	{
		if(is_file($oldname)){
			$perms = fileperms($oldname);
			return copy($oldname, $newname) && chmod($newname, $perms);
		}
		elseif(is_dir($oldname)){
			return dir_copy($oldname, $newname);
		}
		else{
			//die("Cannot copy file: $oldname");
		}
	}

	public static function dir_copy($oldname, $newname)
	{
		if(!is_dir($oldname)) {
			return false;
		}
		if(!is_dir($newname)) @mkdir($newname, 0777);
		$dir = opendir($oldname);
		$res = true;
		while($file = readdir($dir)){
			if($file == "." || $file == "..") continue;
			$res = self::dir_copy("$oldname/$file", "$newname/$file");
			if ($res !== true) {
				echox("$oldname/$file");
				break;
			}
		}
		closedir($dir);
		return $res;
	}

	public static function getPerms($mode) {
		/* Determine Type */
		if( $mode & 0x1000 )
			$type='p'; /* FIFO pipe */
		else if( $mode & 0x2000 )
			$type='c'; /* Character special */
		else if( $mode & 0x4000 )
			$type='d'; /* Directory */
		else if( $mode & 0x6000 )
			$type='b'; /* Block special */
		else if( $mode & 0x8000 )
			$type='-'; /* Regular */
		else if( $mode & 0xA000 )
			$type='l'; /* Symbolic Link */
		else if( $mode & 0xC000 )
			$type='s'; /* Socket */
		else
			$type='u'; /* UNKNOWN */
		/* Determine permissions */
		$owner["read"]	= ($mode & 00400) ? 'r' : '-';
		$owner["write"]   = ($mode & 00200) ? 'w' : '-';
		$owner["execute"] = ($mode & 00100) ? 'x' : '-';
		$group["read"]	= ($mode & 00040) ? 'r' : '-';
		$group["write"]   = ($mode & 00020) ? 'w' : '-';
		$group["execute"] = ($mode & 00010) ? 'x' : '-';
		$world["read"]	= ($mode & 00004) ? 'r' : '-';
		$world["write"]   = ($mode & 00002) ? 'w' : '-';
		$world["execute"] = ($mode & 00001) ? 'x' : '-';
		/* Adjust for SUID, SGID and sticky bit */
		if( $mode & 0x800 )
			$owner["execute"] = ($owner['execute']=='x') ? 's' : 'S';
		if( $mode & 0x400 )
			$group["execute"] = ($group['execute']=='x') ? 's' : 'S';
		if( $mode & 0x200 )
			$world["execute"] = ($world['execute']=='x') ? 't' : 'T';

		return array(
			'type' => $type,
			'u' => array(
				'perms' => sprintf("%1s%1s%1s", $owner['read'], $owner['write'], $owner['execute']),
				'r' => $owner['read'],
				'w' => $owner['write'],
				'x' => $owner['execute']
			),
			'g' => array(
				'perms' => sprintf("%1s%1s%1s", $group['read'], $group['write'], $group['execute']),
				'r' => $group['read'],
				'w' => $group['write'],
				'x' => $group['execute']
			),
			'o' => array(
				'perms' => sprintf("%1s%1s%1s", $world['read'], $world['write'], $world['execute']),
				'r' => $world['read'],
				'w' => $world['write'],
				'x' => $world['execute']
			)
		);
	}

	public static function get_filescount($path) {
		$count = 0;
		$current_dir = @opendir($path);
		while($entryname = @readdir($current_dir)) {
			if ((!is_dir($path.'/'.$entryname)) && $entryname != ('.') && ($entryname!='..') ) {
				$count++;
			}
		}
		@closedir($current_dir);
		return $count;
	}

	/**
	 *
	 * Recursivly Create directory
	 *
	 *
	 * @param string $dir
	 */
	public static function makedir($dir) {
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
						if(!mkdir($path))
							return false;
					}
				}
			}
		}
		return true;
	}

	public static function dirsize($dir) {
		static $size = 0;
		if (!is_dir($dir)) return;
		$current_dir = @opendir($dir);
		while($entryname = @readdir($current_dir))
			if (@is_dir($dir.'/'.$entryname) && ($entryname != '.') && ($entryname!='..') )
				dirsize($dir.'/'.$entryname);
			elseif ($entryname != ('.') && ($entryname!='..') )
				$size = $size + filesize($dir.'/'.$entryname);
		@closedir($current_dir);
		return $size;
	}

	public static function unique_filename($file_name) {
		$mark = microtime();
		$mark = substr($mark, 11, 11).substr($mark, 2, 6);
		$ext = strrchr($file_name, '.');
		return $mark.(($ext === false)?'':$ext);
	}

	/**
	 *
	 * Check if file exists on remote server
	 *
	 * @param string $fileUrl - url
	 */
	public static function checkFileExistingOnServer($fileUrl) {
		$headers = get_headers($fileUrl);
		if ($headers == false) {
			return false;
		}
		return strstr($headers[0], '200');
	}
}