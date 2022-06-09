<?php

// walk back 3 levels to repo root directory
chdir(dirname(dirname(dirname(__DIR__))));
echo "cwd: " . getcwd() . "\n";

if(!file_exists('composer.json')) {
  echo "composer.json not found\n";
  exit(1);
}

$composer_json_raw = file_get_contents('composer.json');
if(! $composer_json_contents = json_decode($composer_json_raw, true)) {
  echo "failed to parse composer.json, stopping.\n";
  exit(1);
}

// print_r($composer_json_contents);

if(!isset($composer_json_contents['require'])) {
  echo "composer.json does not have a 'require' section, stopping.\n";
  exit(1);
}

if(!isset($composer_json_contents['require']['drush/drush'])) {
  echo "composer.json does not have a 'require' section for drush/drush, stopping.\n";
  exit(1);
}

if($composer_json_contents['require']['drush/drush'] != '^11 || ^12') {
  echo "customer has already modified drush version, stopping.\n";
  exit(1);
}

echo "updating drush/drush to version 12\n";
$composer_json_contents['require']['drush/drush'] = '^12.0';

echo "writing composer.json\n";
$composer_json_raw = json_encode($composer_json_contents, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_UNESCAPED_UNICODE);
file_put_contents('composer.json', $composer_json_raw);

echo "✅ Success!\n";
