<?php

use mako\application\Application;

//--------------------------------------------------------------------------
// Setup Z-Ray extension
//--------------------------------------------------------------------------

$zrayExtension = new ZRayExtension('Mako');

$zrayExtension->setMetadata(['logo' => __DIR__ . DIRECTORY_SEPARATOR . 'logo.png']);

//--------------------------------------------------------------------------
// Enable extension after Application::run has been executed
//--------------------------------------------------------------------------

$zrayExtension->setEnabledAfter('mako\application\web\Application::run');

//--------------------------------------------------------------------------
// Add config panel
//--------------------------------------------------------------------------

$zrayExtension->traceFunction('mako\http\Response::send', function(){}, function($context, &$storage)
{
	$storage['configuration'] = [Application::instance()->getConfig()->getLoadedConfiguration()];
});

//--------------------------------------------------------------------------
// Add session panel
//--------------------------------------------------------------------------

$zrayExtension->traceFunction('mako\session\Session::start', function(){}, function($context, &$storage) use ($zrayExtension)
{
	$zrayExtension->traceFunction('mako\session\Session::__destruct', function($context, &$storage)
	{
		$storage['session'] = [Application::instance()->getContainer()->get('session')->getData()];
	}, function(){});
});