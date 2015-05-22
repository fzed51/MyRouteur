<?php

echo "dossier d'execution : " . __DIR__ . PHP_EOL ;

function listeDossiers($dossiers) {
	if(!is_array($dossiers)){
		$dossiers=[$dossiers];
	}
    while($dossier = array_shift($dossiers)) {
        $sousDossiers=glob($dossier . '/*', GLOB_ONLYDIR|GLOB_NOSORT);
        if(!$sousDossiers){
			continue;
		}
        $dossiers = array_merge($dossiers, $sousDossiers);
        foreach($sousDossiers as $yieldDir)
            yield $yieldDir;
    }
}

function chercheFichier($pattern, $dossier){
	$result=glob($dossier . '/' . $pattern, GLOB_NOSORT);
	foreach(listeDossiers($dossier) as $dir) {
		$match = glob($dir . '/' . $pattern, GLOB_NOSORT);
		if(!$match){
			continue;
		}
		$result = array_merge($result, $match);
	}
	return $result;
}

function tab2space($tabs){
	return str_replace("\t", '    ', $tabs[1]);
}

function spacemod4($space){
	if (strlen($space[1]) > 4){
		$nb = round(strlen($space[1]) / 4) * 4;
		return str_repeat(' ', $nb);
	} else {
		return $space[1];
	}
}

function cleanLine($line){
	$newLine = rtrim($line);
	$newLine = preg_replace_callback("/^(\s*)/", 'tab2space', $newLine);
	// $newLine = preg_replace_callback("/^([ ]*)/", 'spacemod4', $newLine);
	return $newLine;
}

function cleanFile($fileName){
	$lines = file($fileName, FILE_IGNORE_NEW_LINES);
	$cleaned = false;
	$cleanLines = array();
	foreach($lines as $line){
		$newLine = cleanLine($line);
		array_push($cleanLines, $newLine);
		if((!$cleaned) && strcmp($line, $newLine) <> 0) {
			$cleaned = true;
		}
	}
	if($cleaned){
		echo "{$fileName} est netoye" . PHP_EOL;
		file_put_contents($fileName, implode(PHP_EOL, $cleanLines));
	}
}

$liste = chercheFichier('*.php', __DIR__);
print('Liste des fichier php : ' . PHP_EOL);
print_r($liste);

foreach($liste as $file){
	cleanFile($file);
}

exit(0);