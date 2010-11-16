<?php
/**
 * Class to create and manage a Zip file.
 *
 * Inspired by CreateZipFile by Rochak Chauhan  www.rochakchauhan.com (http://www.phpclasses.org/browse/package/2322.html)
 * and
 * http://www.pkware.com/documents/casestudies/APPNOTE.TXT Zip file specification.
 *
 * @author A. Grandt
 * @see Distributed under "General Public License"
 * @version 1.0
 */
class Zip {
	private $zipMemoryThreshold = 1048576; // Autocreate tempfile if the zip data exceeds 1048576 bytes (1 MB)
	private $endOfCentralDirectory = "\x50\x4b\x05\x06\x00\x00\x00\x00"; //end of Central directory record
	private $localFileHeader = "\x50\x4b\x03\x04"; // Local file header signature
	private $centralFileHeader = "\x50\x4b\x01\x02"; // Central file header signature

	private $zipData = null;
	private $zipFile = null;
	private $zipComment = null;
	private $cdRec = array(); // central directory
	private $offset = 0;
	private $isFinalized = false;

	private $streamChunkSize = 65536;
	private $streamFilePath = null;
	private $streamTimeStamp = null;
	private $streamComment = null;
	private $streamFile = null;
	private $streamData = null;
	private $streamFileLength = 0;	
	/**
	 * Constructor.
	 *
	 * @param $useZipFile boolean. Write temp zip data to tempFile? Default false
	 */
	function __construct($useZipFile = false) {
		if ($useZipFile) {
			$this->zipFile = tmpfile();
		} else {
			$this->zipData = "";
		}
	}

	function __destruct() {
		if (!is_null($this->zipFile)) {
			fclose($this->zipFile);
		}
		$this->zipData= null;
	}

	/**
	 * Set Zip archive comment.
	 *
	 * @param string $newComment New comment. null to clear.
	 */
	public function setComment($newComment = null) {
		$this->zipComment = $newComment;
	}

	/**
	 * Set zip file to write zip data to.
	 * This will cause all present and future data written to this class to be written to this file.
	 * This can be used at any time, even after the Zip Archive have been finalized. Any previous file will be closed.
	 * Warning: If the given file already exists, it will be overwritten.
	 *
	 * @param string $fileName
	 */
	public function setZipFile($fileName) {
		if (file_exists($fileName)) {
			unlink ($fileName);
		}
		$fd=fopen($fileName, "x+b");
		if (!is_null($this->zipFile)) {
			rewind($this->zipFile);
			while(!feof($this->zipFile)) {
			    fwrite($fd, fread($this->zipFile, $this->streamChunkSize));
			} 
			
			fclose($this->zipFile);
		} else {
			fwrite($fd, $this->zipData);
			$this->zipData = null;
		}
		$this->zipFile = $fd;
	}

	/**
	 * Add an empty directory entry to the zip archive.
	 * Basically this is only used if an empty directory is added.
	 *
	 * @param string $directoryPath  Directory Path and name to be added to the archive.
	 * @param int    $timestamp      (Optional) Timestamp for the added directory, if omitted or set to 0, the current time will be used.
	 * @param string $fileComment    (Optional) Comment to be added to the archive for this directory. To use fileComment, timestamp must be given.
	 */
	public function addDirectory($directoryPath, $timestamp = 0, $fileComment = null) {
		if ($this->isFinalized) {
			return;
		}
		$this->buildZipEntry($directoryPath, $fileComment, "\x00\x00", "\x00\x00", $timestamp, "\x00\x00\x00\x00", 0, 0, 16);
	}

	/**
	 * Add a file to the archive at the specified location and file name.
	 *
	 * @param string $data        File data.
	 * @param string $filePath    Filepath and name to be used in the archive.
	 * @param int    $timestamp   (Optional) Timestamp for the added file, if omitted or set to 0, the current time will be used.
	 * @param string $fileComment (Optional) Comment to be added to the archive for this file. To use fileComment, timestamp must be given.
	 */
	public function addFile($data, $filePath, $timestamp = 0, $fileComment = null)   {
		if ($this->isFinalized) {
			return;
		}

		$gzType = "\x08\x00"; // Compression type 8 = deflate
		$gpFlags = "\x02\x00"; // General Purpose bit flags for compression type 8 it is: 0=Normal, 1=Maximum, 2=Fast, 3=super fast compression.
		$dataLength = strlen($data);
		$fileCRC32 = pack("V", crc32($data));

		$gzData = gzcompress($data);
		$gzData = substr( substr($gzData, 0, strlen($gzData) - 4), 2); // gzcompress adds a 2 byte header and 4 byte CRC we can't use.
		// The 2 byte header does contain useful data, though in this case the 2 parameters we'd be interrested in will always be 8 for compression type, and 2 for General purpose flag.
		$gzLength = strlen($gzData);

		if ($gzLength >= $dataLength) {
			$gzLength = $dataLength;
			$gzData = $data;
			$gzType = "\x00\x00"; // Compression type 0 = stored
			$gpFlags = "\x00\x00"; // Compression type 0 = stored
		}

		if (is_null($this->zipFile) && ($this->offset + $gzLength) > $this->zipMemoryThreshold) {
			$this->zipFile = tmpfile();
			fwrite($this->zipFile, $this->zipData);
			$this->zipData = null;
		}

		$this->buildZipEntry($filePath, $fileComment, $gpFlags, $gzType, $timestamp, $fileCRC32, $gzLength, $dataLength, 32);
		if (is_null($this->zipFile)) {
			$this->zipData .= $gzData;
		} else {
			fwrite($this->zipFile, $gzData);
		}
	}

	/**
	 * Add a file to the archive at the specified location and file name.
	 *
	 * @param string $dataFile    File name/path.
	 * @param string $filePath    Filepath and name to be used in the archive.
	 * @param int    $timestamp   (Optional) Timestamp for the added file, if omitted or set to 0, the current time will be used.
	 * @param string $fileComment (Optional) Comment to be added to the archive for this file. To use fileComment, timestamp must be given.
	 */
	public function addLargeFile($dataFile, $filePath, $timestamp = 0, $fileComment = null)   {
		if ($this->isFinalized) {
			return;
		}
	
		$this->openStream($filePath, $timestamp, $fileComment);

		$fh = fopen($dataFile, "rb");
		while(!feof($fh)) {
		    $this->addStreamData(fread($fh, $this->streamChunkSize));
		} 
		fclose($fh);

		$this->closeStream();
	}

	/**
	 * Create a stream to be used for large entries.
	 *
	 * @param string $filePath    Filepath and name to be used in the archive.
	 * @param int    $timestamp   (Optional) Timestamp for the added file, if omitted or set to 0, the current time will be used.
	 * @param string $fileComment (Optional) Comment to be added to the archive for this file. To use fileComment, timestamp must be given.
	 */
	public function openStream($filePath, $timestamp = 0, $fileComment = null)   {
		if ($this->isFinalized) {
			return;
		}

		if (is_null($this->zipFile)) {
			$this->zipFile = tmpfile();
			fwrite($this->zipFile, $this->zipData);
			$this->zipData = null;
		}
		
		if (strlen($this->streamFilePath) > 0) {
			closeStream();
		}
		$this->streamFile = tempnam(sys_get_temp_dir(), 'Zip');
		$this->streamData = gzopen($this->streamFile, "w9");
		$this->streamFilePath = $filePath;
		$this->streamTimestamp = $timestamp;
		$this->streamFileComment = $fileComment;
		$this->streamFileLength = 0;
	}
	
	public function addStreamData($data) {
		$length = gzwrite($this->streamData, $data, strlen($data));
		if ($length != strlen($data)) {
			print "<p>Length mismatch</p>\n";
		}
		$this->streamFileLength += $length;
		return $length;
	}
	
	/**
	 * Close the current stream.
	 */
	public function closeStream() {
		if ($this->isFinalized || strlen($this->streamFilePath) == 0) {
			return;
		}
		
		fflush($this->streamData);
		gzclose($this->streamData);
		
		$gzType = "\x08\x00"; // Compression type 8 = deflate
		$gpFlags = "\x02\x00"; // General Purpose bit flags for compression type 8 it is: 0=Normal, 1=Maximum, 2=Fast, 3=super fast compression.

		$file_handle = fopen($this->streamFile, "rb");
		$stats = fstat($file_handle);
		$eof = $stats['size'];
		
		fseek($file_handle, $eof-8);
		$fileCRC32 = fread($file_handle, 4);
		$dataLength = $this->streamFileLength;//$gzl[1];

		$gzLength = $eof-10;
		$eof -= 9;
		
		fseek($file_handle, 10);

		$this->buildZipEntry($this->streamFilePath, $this->streamFileComment, $gpFlags, $gzType, $this->streamTimestamp, $fileCRC32, $gzLength, $dataLength, 32);
		while(!feof($file_handle)) {
		    fwrite($this->zipFile, fread($file_handle, $this->streamChunkSize));
		} 
		
		unlink($this->streamFile);
		$this->streamFile = null;
		$this->streamData = null;
		$this->streamFilePath = null;
		$this->streamTimestamp = null;
		$this->streamFileComment = null;
		$this->streamFileLength = 0;
	}

	/**
	 * Close the archive.
	 * A closed archive can no longer have new files added to it.
	 */
	public function finalize() {
		if(!$this->isFinalized) {
			if (strlen($this->streamFilePath) > 0) {
				$this->closeStream();
			}
			$cd = implode("", $this->cdRec);
			
			$cdRec = $cd . $this->endOfCentralDirectory
					. pack("v", sizeof($this->cdRec))
					. pack("v", sizeof($this->cdRec))
					. pack("V", strlen($cd))
					. pack("V", $this->offset); 
			if (!is_null($this->zipComment)) {
				$cdRec .= pack("v", strlen($this->zipComment)) . $this->zipComment;
			} else {
				$cdRec .= "\x00\x00";
			}
					
			if (is_null($this->zipFile)) {
				$this->zipData .= $cdRec;
			} else {
				fwrite($this->zipFile, $cdRec);
				fflush($this->zipFile);
			}
			$this->isFinalized = true;
			$cd = null;
			$this->cdRec = null;
		}
	}

	/**
	 * Get the handle ressource for the archive zip file.
	 * If the zip haven't been finalized yet, this will cause it to become finalized
	 *
	 * @return zip file handle
	 */
	public function getZipFile() {
		if(!$this->isFinalized) {
			$this->finalize();
		}
		if (is_null($this->zipFile)) {
			$this->zipFile = tmpfile();
			fwrite($this->zipFile, $this->zipData);
			$this->zipData = null;
		}
		rewind($this->zipFile);
		return $this->zipFile;
	}

	/**
	 * Get the zip file contents
	 * If the zip haven't been finalized yet, this will cause it to become finalized
	 *
	 * @return zip data
	 */
	public function getZipData() {
		if(!$this->isFinalized) {
			$this->finalize();
		}
		if (is_null($this->zipFile)) {
			return $this->zipData;
		} else {
			rewind($this->zipFile);
			$filestat = fstat($this->zipFile);
			return fread($this->zipFile, $filestat['size']);
		}
	}

	/**
	 * Send the archive as a zip download
	 *
	 * @param String $fileName The name of the Zip archive, ie. "archive.zip".
	 * @return void
	 */
	function sendZip($fileName) {
		if(!$this->isFinalized) {
			$this->finalize();
		}

		if (!headers_sent($headerFile, $headerLine) or die("<p><strong>Error:</strong> Unable to send file $fileName. HTML Headers have already been sent from <strong>$headerFile</strong> in line <strong>$headerLine</strong></p>")) {
			if (ob_get_contents() === false or die("\n<p><strong>Error:</strong> Unable to send file <strong>$fileName.epub</strong>. Output buffer contains the following text (typically warnings or errors):<br>" . ob_get_contents() . "</p>")) {
				if (ini_get('zlib.output_compression')) {
					ini_set('zlib.output_compression', 'Off');
				}

				header('Pragma: public');
				header("Last-Modified: " . date($this->headerDateFormat, $this->date));
				header("Expires: 0");
				header("Accept-Ranges: bytes");
				header("Connection: close");
				header("Content-Type: application/zip");
				header('Content-Disposition: attachment; filename="' . $fileName . '";' );
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ". $this->getArchiveSize());

				if (is_null($this->zipFile)) {
					echo $this->zipData;
				} else {
					rewind($this->zipFile);

					while(!feof($this->zipFile)) {
					    echo fread($this->zipFile, $this->streamChunkSize);
					} 
				}
			}
		}
	}
	
	public function getArchiveSize() {
		if (is_null($this->zipFile)) {
			return strlen($this->zipData);
		}
		$filestat = fstat($this->zipFile);
		return $filestat['size'];
	}

	/**
	 * Calculate the 2 byte dostime used in the zip entries.
	 *
	 * @param int $timestamp
	 * @return 2-byte encoded DOS Date
	 */
	private function getDosTime($timestamp = 0) {
		$timestamp = (int)$timestamp;
		$date = ($timestamp == 0 ? getdate() : getDate($timestamp));
		if ($date["year"] >= 1980) {
			return pack("V", (($date["mday"] + ($date["mon"] << 5) + (($date["year"]-1980) << 9)) << 16) |
				(($date["seconds"] >> 1) + ($date["minutes"] << 5) + ($date["hours"] << 11)));
		}
		return "\x00\x00\x00\x00";
	}

	/**
	 * Build the Zip file structures
	 * 
	 * @param unknown_type $filePath
	 * @param unknown_type $fileComment
	 * @param unknown_type $gpFlags
	 * @param unknown_type $gzType
	 * @param unknown_type $timestamp
	 * @param unknown_type $fileCRC32
	 * @param unknown_type $gzLength
	 * @param unknown_type $dataLength
	 * @param integer $extFileAttr 16 for directories, 32 for files.
	 */
	private function buildZipEntry($filePath, $fileComment, $gpFlags, $gzType, $timestamp, $fileCRC32, $gzLength, $dataLength, $extFileAttr) {
		$filePath = str_replace("\\", "/", $filePath);
		$fileCommentLength = (is_null($fileComment) ? 0 : strlen($fileComment));
		$dosTime = $this->getDosTime($timestamp);
		
		$zipEntry  = $this->localFileHeader;
		$zipEntry .= "\x14\x00"; // Version needed to extract
		$zipEntry .= $gpFlags . $gzType . $dosTime. $fileCRC32;
		$zipEntry .= pack("VV", $gzLength, $dataLength);
		$zipEntry .= pack("v", strlen($filePath) ); // File name length
		$zipEntry .= "\x00\x00"; // Extra field length
		$zipEntry .= $filePath; // FileName . Extra field

		if (is_null($this->zipFile)) {
			$this->zipData .= $zipEntry;
		} else {
			fwrite($this->zipFile, $zipEntry);
		}
				
		$cdEntry  = $this->centralFileHeader;
		$cdEntry .= "\x00\x00"; // Made By Version
		$cdEntry .= "\x14\x00"; // Version Needed to extract
		$cdEntry .= $gpFlags . $gzType . $dosTime. $fileCRC32;
		$cdEntry .= pack("VV", $gzLength, $dataLength);
		$cdEntry .= pack("v", strlen($filePath)); // Filename length
		$cdEntry .= "\x00\x00"; // Extra field length
		$cdEntry .= pack("v", $fileCommentLength); // File comment length
		$cdEntry .= "\x00\x00"; // Disk number start
		$cdEntry .= "\x00\x00"; // internal file attributes
		$cdEntry .= pack("V", $extFileAttr ); // External file attributes
		$cdEntry .= pack("V", $this->offset ); // Relative offset of local header
		$cdEntry .= $filePath; // FileName . Extra field
		if (!is_null($fileComment)) {
			$cdEntry .= $fileComment; // Comment
		}

		$this->cdRec[] = $cdEntry;
		$this->offset += strlen($zipEntry) + $gzLength;
	}
}
?>