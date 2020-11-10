<?php
namespace JetStudio;

require 'application/init.php';

Application::setCurrentPart( 'menus' );
Application::handleAction();
Application::output( Application::getView()->render('main') );
Application::renderLayout();
