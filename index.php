<?php

$cut_date = (isset($_GET['cut_date'])) ? $_GET['cut_date'] : false;
$cut_date_end = (isset($_GET['cut_date_end'])) ? $_GET['cut_date_end'] : false;
$depth_search = (isset($_GET['depth_search'])) ? $_GET['depth_search'] : true;
$folder = (isset($_GET['folder'])) ? $_GET['folder'] : 'folder';


function dir_to_array($dir)
{
  global $cut_date, $cut_date_end, $depth_search;


  if (!is_dir($dir)) {
    return null;
  }

  $data = [];

  $dirIterator = new DirectoryIterator($dir);

  foreach ($dirIterator as $f) {
    if ($f->isDot()) {
      continue;
    }

    $path = $f->getPathname();
    $ext = pathinfo($f, PATHINFO_EXTENSION);
    $time = $f->getMTime();
    if ($f->isFile()) {
      $data[] = [
        'name' => $f->getFilename(),
        'type' => $f->getType(),
        'extension' => $ext,
        'size' => $f->getSize(),
        'last_modified_t' => $time,
        'last_modified' => date("D. F jS, Y - h:ia", $time),
        'cut_date' => date("D. F jS, Y - h:ia", $cut_date),
        'cut_date_end' => date("D. F jS, Y - h:ia", $cut_date_end),

      ];
    } else {

      $data[] = [
        'name' => $f->getFilename(),
        'type' => "dir",
        'size' => $f->getSize(),
        'last_modified_t' => $time,
        'last_modified' => date("D. F jS, Y - h:ia", $time),
        'cut_date' => date("D. F jS, Y - h:ia", $cut_date),
        'cut_date_end' => date("D. F jS, Y - h:ia", $cut_date_end),
        'subs' => ($depth_search) ? dir_to_array($path) : null
      ];
    }
  }


  return $data;
}




function dir_to_json($dir)
{

  $data = dir_to_array($dir);

  $json_pretty = json_encode($data, JSON_PRETTY_PRINT);

  echo "<pre>" . $json_pretty . "</pre>";
}


echo dir_to_json($folder);
