<?php
namespace JetStudio;

require 'application/init.php';

Application::setCurrentPart( 'sites' );
Application::handleAction();
Application::output( Application::getView()->render('main') );
Application::renderLayout();
