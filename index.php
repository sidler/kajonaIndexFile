<?php


$arrFiles = scandir(".");
$arrFiles = array_diff($arrFiles, array(".", "..", ".DS_Store"));

uasort($arrFiles, function($strFile1, $strFile2) {
    if(is_dir(__DIR__."/".$strFile1) && is_dir(__DIR__."/".$strFile2))
        return strcmp($strFile1, $strFile2);

    if(is_file(__DIR__."/".$strFile1) && is_file(__DIR__."/".$strFile2))
        return strcmp($strFile1, $strFile2);

    if(is_file(__DIR__."/".$strFile1) && is_dir(__DIR__."/".$strFile2))
        return 1;
    else
        return -1;

});


$strPhpVersion = phpversion();
$strHostname = $_SERVER['HTTP_HOST'];
$strWebserver = $_SERVER['SERVER_SOFTWARE'];



if(!empty($_GET["clearcache"])) {
    $strDir = realpath(__DIR__."/".($_GET["clearcache"])."/project/temp/cache");
    if(strpos($strDir, __DIR__) === 0 && is_dir($strDir)) {
        delDirrec($strDir);
    }
    header("Location: ".$_SERVER["PHP_SELF"]);
    return;
}

function delDirrec($strDir) {
    $arrFiles = array_diff(scandir($strDir), array('.','..'));
    foreach ($arrFiles as $strFile) {
        (is_dir("$strDir/$strFile")) ? delDirrec("$strDir/$strFile") : unlink("$strDir/$strFile");
    }
    return rmdir($strDir);
}



$strRows = "";
$strButtons = "";
$strPrevChar = "";
$bitFiles = false;
foreach($arrFiles as $strOneFile) {

    $strIcon = "<i class='fa fa-folder-o' aria-hidden='true'></i>";
    if(is_file(__DIR__."/".$strOneFile)) {
        $strIcon = "<i class='fa fa-file-code-o' aria-hidden='true'></i>";

        if(!$bitFiles) {
            $bitFiles = true;
            $strButtons .= "<a role='button' class='btn btn-secondary' href='#files'>files</a>";
            $strRows .= <<<HTML
                <tr>
                  <td class="font-weight-bold" colspan="8"><a name='files'></a>Files</td>
                </tr>
HTML;
        }

    } else {
        if($strPrevChar != $strOneFile[0]) {
            $strPrevChar = $strOneFile[0];
            $strButtons .= "<a role='button' class='btn btn-secondary' href='#{$strPrevChar}'>{$strPrevChar}</a>";
            $strRows .= <<<HTML
                <tr>
                  <td class="font-weight-bold" colspan="8"><a name='{$strPrevChar}'></a>{$strPrevChar}</td>
                </tr>
HTML;
        }
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

    $arrBranches = [];
    if(is_file(__DIR__."/".$strOneFile."/.git/HEAD")) {
        $arrBranches[] = "<i class='fa fa-code-fork'></i> ".file_get_contents(__DIR__."/".$strOneFile."/.git/HEAD");
    }
    if(is_file(__DIR__."/".$strOneFile."/.git/modules/core/HEAD")) {
        $arrBranches[] = "<i class='fa fa-code-fork'></i> ".file_get_contents(__DIR__."/".$strOneFile."/.git/modules/core/HEAD")." core";
    }
    if(is_file(__DIR__."/".$strOneFile."/core/.git/HEAD")) {
        $arrBranches[] = "<i class='fa fa-code-fork'></i> ".file_get_contents(__DIR__."/".$strOneFile."/core/.git/HEAD")." core";
    }
    if(is_file(__DIR__."/".$strOneFile."/.git/modules/core_agp/HEAD")) {
        $arrBranches[] = "<i class='fa fa-code-fork'></i> ".file_get_contents(__DIR__."/".$strOneFile."/.git/modules/core_agp/HEAD")." core_agp";
    }

    $strBranch = str_replace("ref: refs/heads/", "", implode("<br />", $arrBranches));


    $arrActions = [];
    if(is_dir(__DIR__."/".$strOneFile."/project/temp/cache")) {
        $arrActions[] = "<a href='".$_SERVER['PHP_SELF']."?clearcache=".urlencode($strOneFile)."'><i class='fa fa-trash-o'></i> Delete /project/temp/cache</a>";
    }

    $strActions = implode("<br />", $arrActions);

    $strRows .= <<<HTML
    <tr>
      <td></td>
      <td>{$strIcon}</td>
      <td><a href="{$strOneFile}">{$strOneFile}</a></td>
      <td>{$strBackend}</td>
      <td>{$strInstaller}</td>
      <td>{$strDebug}</td>
      <td>{$strBranch}</td>
      <td>{$strActions}</td>
    </tr>
HTML;
}





echo <<<HTML
<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{$strHostname}</title>
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
 .btn-group {
 padding: 1rem 0;
 }
</style>

<div class="container">
<div class="row text-xs-center">
  <div class="btn-group btn-group-sm" role="group" aria-label="First group">
    {$strButtons}
  </div>
    
</div>

<div class="row">
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th></th>
      <th>#</th>
      <th>Name</th>
      <th>Backend</th>
      <th>Installer</th>
      <th>Debug</th>
      <th>Branch</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    {$strRows}
  </tbody>
</table>
</div>
<div class="row">
<pre>
<code>PHP Version: {$strPhpVersion}
Hostname: {$strHostname}
Webserver: {$strWebserver}</code>
</pre>
</div>
</div>
</body>
</html>
HTML;
