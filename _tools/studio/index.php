<?php
namespace JetStudio;

require 'application/init.php';

Application::setCurrentPart( 'welcome' );
Application::handleAction();
Application::output( Application::getView()->render('main') );
Application::renderLayout();
