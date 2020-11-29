<?php
namespace JetStudio;

require 'application/init.php';

//TODO: wizard!
//TODO: alespoň 3 šablony pro něj

Application::setCurrentPart( 'module_wizard' );
Application::handleAction();

ModuleWizards::handleAction();

Application::output( Application::getView()->render('main') );
Application::renderLayout();
