<?php

$nano = new Phar(__DIR__ . '/../../Nano.phar',0,'Nano.phar');
$nano->buildFromDirectory(__DIR__ . '/../library');
//$nano->setStub($nano->createDefaultStub('cli/index.php', 'www/index.php'));