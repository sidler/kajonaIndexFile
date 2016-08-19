<?php

$strFolder = "";
$strFiles = "";

foreach(scandir(".") as $strOneFile) {

    if($strOneFile == "." || $strOneFile == ".." || $strOneFile == ".DS_Store" || $strOneFile == "Icon") {
        continue;
    }


    $strIcon = "<i class='fa fa-folder-o' aria-hidden='true'></i>";
    if(is_file(__DIR__.$strOneFile)) {
        $strIcon = "<i class='fa fa-file-code-o' aria-hidden='true'></i>";
    }

    if(is_dir(__DIR__."/".$strOneFile)) {

        $strFrontend = "";
        if(is_dir(__DIR__."/".$strOneFile."/core/module_pages") || is_file(__DIR__."/".$strOneFile."/core/module_pages.phar")) {
            $strFrontend= "<a href='".$strOneFile."/index.php'><i class='fa fa-picture-o'></i> index.php</a>";
        }


        $strBackend = "";
        if(is_dir(__DIR__."/".$strOneFile."/core/module_system") || is_file(__DIR__."/".$strOneFile."/core/module_system.phar")) {
            $strBackend= "<a href='".$strOneFile."/index.php?admin=1'><i class='fa fa-sliders'></i> /admin</a>";
        }


        $strInstaller = "";
        if(is_file(__DIR__."/".$strOneFile."/installer.php")) {
            $strInstaller = "<a href='".$strOneFile."/installer.php'><i class='fa fa-plus-square-o'></i> installer.php</a>";
        }


        $strDebug = "";
        if(is_file(__DIR__."/".$strOneFile."/debug.php")) {
            $strDebug = "<a href='".$strOneFile."/debug.php'><i class='fa fa-bug'></i> debug.php</a>";
        }

        $strBranch = "";
        if(is_file(__DIR__."/".$strOneFile."/.git/HEAD")) {
            $strBranch = "<i class='fa fa-code-fork'></i> ".file_get_contents(__DIR__."/".$strOneFile."/.git/HEAD");
        }


        $strFolder .= <<<HTML
    <tr>
      <td><i class='fa fa-folder-o' aria-hidden='true'></i></td>
      <td><a href="{$strOneFile}">{$strOneFile}</a></td>
      <td>{$strBackend}</td>
      <td>{$strFrontend}</td>
      <td>{$strInstaller}</td>
      <td>{$strDebug}</td>
      <td>{$strBranch}</td>
    </tr>
HTML;
    } else {


        $strFiles .= <<<HTML
    <tr>
      <td><i class='fa fa-file-code-o' aria-hidden='true'></i></td>
      <td><a href="{$strOneFile}">{$strOneFile}</a></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
HTML;
    }
}


echo <<<HTML
<!DOCTYPE html>
<html lang="de">

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    
    <title>{$_SERVER['HTTP_HOST']}</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css"  crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"  crossorigin="anonymous">
</head>
<body>

<style type="text/css">
 body {
    font-size: 0.8rem;
 }
 
 
 .table td, .table th {
    padding: 0.25rem;
 }
</style>

<div class="container">

 <div class="row">
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Backend</th>
      <th>Frontend</th>
      <th>Installer</th>
      <th>Debug</th>
      <th>Branch</th>
    </tr>
  </thead>
  <tbody>
    {$strFolder}
    {$strFiles}
  </tbody>
</table>

</div>
</div>
</body>

</html>
HTML;
