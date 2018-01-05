<?php
define('LOG_FILE', '/var/www/html/phoe721.com/project/rtorrent/log/rtorrent.log');

class debugger {
	// Debug: 0 - False, 1 - True
	private $debug = 0;


	public function info($message) {
		if ($this->debug) $this->logger($message);
	}

	public function error($message) {
		$this->logger($message);
	}

	public function logger($message) {
		$timestring = date('Y-m-d h:i:s', strtotime('now'));
		$message = $timestring . ' - ' . $message . PHP_EOL;
		$file = fopen(LOG_FILE, 'a+');
		if ($file) {
			fwrite($file, $message);
		} else {
			fwrite($file, $timestring . ' - ' . "[ERROR] Unable to open file!");
		}
		fclose($file);
	}
}
?>
