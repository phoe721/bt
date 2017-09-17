<?php
##### Initialization #####
require_once("torrentClient.php");
$client = new torrentClient;
$uploadDir = $client->getUploadDir();
$result = array();
##### Initialization #####

##### Panel Control #####
if (isset($_POST["start"])) {
	if ($client->start()) {
		$result['status'] = "Rtorrent started";
	} else {
		$result['status'] = "Rtorrent already started";
	}
	echo json_encode($result);
} else if (isset($_POST["stop"])) {
	if ($client->stop()) {
		$result['status'] = "Rtorrent stopped";
	} else {
		$result['status'] = "Rtorrent not started";
	}
	echo json_encode($result);
} else if (isset($_POST["reload"])) {
	if ($client->check()) {
		if ($client->countTorrentFile() >= 1) {
			display();
		} else {
			$result['status'] = "No torrent file(s) found";
		}
	} else {
		$result['status'] = "Rtorrent not started";
	}
	echo json_encode($result);
}
##### Panel Control #####

##### Upload Control #####
if (isset($_FILES["file"])) {
	if ($_FILES["file"]["error"] > 0) {
		if ($_FILES["file"]["error"] == 4) {
			$result['error'] = "Please select a torrent file";
		} else {
			$result['error'] = "Error: " . $_FILES["file"]["error"];
		}
	} else { 
	  $fileName = str_replace(" ", "_", $_FILES["file"]["name"]);
	  $tmpPath = $_FILES["file"]["tmp_name"];
	  $filePath = $uploadDir . "/" . $fileName;
	  $fileType = $_FILES["file"]["type"];
	  
	  if ($client->checkTorrentExt($fileName) || $client->checkFileType($fileType)) {	
		  if (file_exists($filePath)) {
			$result['error'] = "$fileName already exists!";
		  } else {
			move_uploaded_file($tmpPath, $filePath);
			chmod($filePath, 0777);
			if (!$client->validateTorrentFile($filePath)) {
				$result['error'] = "Invalid torrent file!";
			} else {
				$result['message'] = "Uploaded: $fileName!";
			}
		  }
	  } else {
		$result['error'] = "Invalid file type!";
	  } 
	}

	echo json_encode($result);
}
##### Upload Control #####

##### Torrent Control #####
if (isset($_POST["torrentHash"])) {
	$torrentHash = $_POST["torrentHash"];
	if (isset($_POST["startTorrent"])) {
		$result['message'] = $client->startTorrent($torrentHash);
	} else if (isset($_POST["stopTorrent"])) {
		$result['message'] = $client->stopTorrent($torrentHash);
	} else if (isset($_POST["deleteTorrent"])) {
		$result['message'] = $client->deleteTorrent($torrentHash);
	} else if (isset($_POST["purgeTorrent"])) {
		$result['message'] = $client->purgeTorrent($torrentHash);
	}

	echo json_encode($result);
}
##### Torrent Control #####

##### Function #####
function display() {
	global $client, $result;
	$torrentHash = "";
	$torrentActive = $client->listActiveTorrent(); 
	$torrentInactive = $client->listInactiveTorrent();
	if (!empty($torrentActive)) {
		$torrentInfo = $torrentActive . "\n" . $torrentInactive;
	} else {
		$torrentInfo = $torrentInactive;
	}
	$lines = explode("\n", $torrentInfo);
	for ($i = 0; $i < count($lines); $i++) {
		if (!empty($lines[$i])) {
			$chunk = explode("\t", $lines[$i]);
			$result['display'][$i]['torrentHash'] = trim($chunk[0]);
			$result['display'][$i]['fileName'] = (strlen($chunk[1]) > 30) ? substr($chunk[1], 0, 30) : $chunk[1];
			$result['display'][$i]['uploadSize'] = trim($chunk[2]);
			$result['display'][$i]['downloadSize'] = trim($chunk[3]);
			$result['display'][$i]['size'] = trim($chunk[4]);
			$result['display'][$i]['percent'] = round(trim($chunk[5]), 2);
			$result['display'][$i]['ratio'] = round(trim($chunk[6]), 2);
			$result['display'][$i]['status'] = trim($chunk[7]);
		}
	}
}
##### Function #####
?>
