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
    $cols[] = '[' . $repo->name . '](' . $repo->html_url . ')';
    
    // latest stable version
    $cols[] = '[![Latest Stable Version](https://poser.pugx.org/' . $repo->full_name . '/v/stable)](https://packagist.org/packages/' . $repo->full_name . ')';
    
    // latest unstable version
    $cols[] = '[![Latest Unstable Version](https://poser.pugx.org/' . $repo->full_name . '/v/unstable)](https://packagist.org/packages/' . $repo->full_name . ')';
    
    /*
     * MASTER
     */
    // build status
    $cols[] = '[![Build Status](https://secure.travis-ci.org/' . $repo->full_name . '.svg?branch=master)](https://secure.travis-ci.org/' . $repo->full_name . ') ';
    // coverage
    $cols[] = '[![Coverage Status](https://coveralls.io/repos/' . $repo->full_name . '/badge.svg?branch=master)](https://coveralls.io/r/' . $repo->full_name . '?branch=master) ';
    
    /*
     * DEVELOP
     */
    // build status
    $cols[] = '[![Build Status](https://secure.travis-ci.org/' . $repo->full_name . '.svg?branch=develop)](https://secure.travis-ci.org/' . $repo->full_name . ') ';
    // coverage
    $cols[] = '[![Coverage Status](https://coveralls.io/repos/' . $repo->full_name . '/badge.svg?branch=develop)](https://coveralls.io/r/' . $repo->full_name . '?branch=develop) ';
    
    /*
     * general
     */
    $cols[] = $repo->updated_at;
    $cols[] = $repo->created_at;
    
    $rowString = implode(' | ', $cols);
    
    if (strpos($repo->name, 'zend') === 0) {
        $rowsFramework[$repo->name] = $rowString;
    } else {
        $rowsExtra[$repo->name] = $rowString;
    }
}

// @todo find API parameter at github
ksort($rowsFramework);
ksort($rowsExtra);

/*
 * Build the table
 */
$tableHeader = 'Name | Stable version | Unstable version | Master status | Master coverage | Develop status | Develop coverage | Last update | Created' . "\n";
$tableHeader .= '--- | --- | --- | --- | --- | --- | --- | --- | ---' . "\n";

$txt = ' # Zend Framework repositories status page' . "\n\n";

$txt .= ' ## Framework' . "\n\n";
$txt .= $tableHeader;
$txt .= implode("\n", $rowsFramework);

$txt .= "\n\n";

$txt .= ' ## Extra' . "\n\n";
$txt .= $tableHeader;
$txt .= implode("\n", $rowsExtra);

$txt .= "\n\n";

file_put_contents('README.md', $txt);

