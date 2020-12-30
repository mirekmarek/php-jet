<?php
namespace JetStudio;

require 'application/init.php';

//TODO: update templates

Application::setCurrentPart( 'module_wizard' );
Application::handleAction();

ModuleWizards::handleAction();

Application::output( Application::getView()->render('main') );
Application::renderLayout();
