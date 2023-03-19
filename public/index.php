<?php
if(session_status() === PHP_SESSION_NONE)
{
    session_start();
}
session_regenerate_id();

define            ('DS', DIRECTORY_SEPARATOR);
define            ('RACINE', dirname(__DIR__));
require           RACINE.DS.'vendor'.DS.'autoload.php';
$builder          = new \DI\ContainerBuilder();

$builder->addDefinitions(RACINE.DS.'config'.DS.'config.php');
$container = $builder->build();

require_once (RACINE.DS.'lib'.DS.'libs-includes.php');

$container->get(\App\Renderer::class)->render()->isNotExistPage();
