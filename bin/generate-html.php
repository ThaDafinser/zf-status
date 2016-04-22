<?php
/**
 * Generate a README.md with all repo informations
 */
$repos = include 'get-repos.php';
$rowsFramework = [];
$rowsExtra = [];

foreach ($repos as $repo) {
    
    $cols = [];
    
    // repo name + url
    $cols['name'] = '<a href="' . $repo->html_url . '">' . $repo->name . '</a>';
    
    // latest stable version
    $cols[] = '<img src="https://poser.pugx.org/' . $repo->full_name . '/v/stable" />';
    $cols[] = '<img src="https://poser.pugx.org/' . $repo->full_name . '/v/unstable" />';
    
    /*
     * MASTER
     */
    $cols[] = '<img src="https://secure.travis-ci.org/' . $repo->full_name . '.svg?branch=master" />';
    $cols[] = '<img src="https://coveralls.io/repos/' . $repo->full_name . '/badge.svg?branch=master" />';
    
    /*
     * DEVELOP
     */
    $cols[] = '<img src="https://secure.travis-ci.org/' . $repo->full_name . '.svg?branch=develop" />';
    $cols[] = '<img src="https://coveralls.io/repos/' . $repo->full_name . '/badge.svg?branch=develop" />';
    
    /*
     * general
     */
    $cols[] = $repo->created_at;
    
    /*
     * build HTML
     */
    $rowString = '<tr>';
    foreach ($cols as $key => $col) {
        
        $class = '';
        if (! is_numeric($key)) {
            $class = 'class="' . $key . '"';
        }
        
        $rowString .= '<td ' . $class . '>' . $col . '</td>';
    }
    $rowString .= '</tr>';
    
    if (strpos($repo->name, 'zend') === 0) {
        $rowsFramework[$repo->name] = $rowString;
    } else {
        $rowsExtra[$repo->name] = $rowString;
    }
}

// @todo find API parameter at github
ksort($rowsFramework);
ksort($rowsExtra);

$header = '<thead><tr>';
$header .= '<th>Name</th>';
$header .= '<th>Stable version</th>';
$header .= '<th>Unstable version</th>';
$header .= '<th>Master status</th>';
$header .= '<th>Master coverage</th>';
$header .= '<th>Develop status</th>';
$header .= '<th>Develop coverage</th>';
$header .= '<th>Create date</th>';

$header .= '</tr></thead>';

$html = '
<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Zend Framework - Status page</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
    
  <body>
    <div class="container">
    
    <div class="section">
        <h1>Zend Framework - Status page</h1>
    </div>
    
    <div class="section">
        <h2>Framework</h2>
        
        <div id="framework">
            <form>
                <div class="input-field">
                  <input class="search" type="search" placeholder="Search for a repository">
                  <i class="material-icons">close</i>
                </div>
            </form>
        
            <table>
                ' . $header . '
                    
                <tbody class="list">
                    ' . implode("\n", $rowsFramework) . '
                </tbody>
            </table>
        </div>
    </div>
                    
    <div class="section">
        <h2>Extra</h2>
                        
        <div id="extra">
            <form>
                <div class="input-field">
                  <input class="search" type="search" placeholder="Search for a repository">
                  <i class="material-icons">close</i>
                </div>
            </form>
        
            <table>
                ' . $header . '
                    
                <tbody class="list">
                    ' . implode("\n", $rowsExtra) . '
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/list.js/1.2.0/list.min.js"></script>
                
    <script>
    var options = {
      valueNames: [ \'name\' ]
    };
    var frameworkList = new List(\'framework\', options);
                        
    var options = {
      valueNames: [ \'name\' ]
    };
    var extraList = new List(\'extra\', options);
    </script>
                    
    </div>
  </body>
</html>
';

file_put_contents('index.html', $html);
