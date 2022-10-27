<?php


function dir_to_array($dir)
{

  if (!is_dir($dir)) {
    return null;
  }

  $data = [];

  foreach (new DirectoryIterator($dir) as $f) {
    if ($f->isDot()) {
      continue;
    }

    $path = $f->getPathname();
    $ext = pathinfo($f, PATHINFO_EXTENSION);

    if ($f->isFile()) {
      $data[] = [
        "path" => $path,
        'NAME' => $f->getFilename(),
        'extension' => $ext,
        'date' => $f->getMTime(),
        'size' => $f->getSize(),

      ];
    } else {
      $files = dir_to_array($path);

      $data[] = [
        $path  => $files,
        'name' => $f->getFilename(),
        'type' => $f->getType(),
        'date' => $f->getMTime(),
        'size' => $f->getSize(),


      ];
    }
  }

  return $data;
}


function dir_to_json($dir)
{

  $data = dir_to_array($dir);
  $json_pretty = json_encode($data, JSON_PRETTY_PRINT);
  echo "<pre>" . $json_pretty . "<pre/>";
}


echo dir_to_json('folder');
