<?php
namespace JetStudio;

require 'application/init.php';

Application::setCurrentPart( 'data_model' );
Application::handleAction();
Application::output( Application::getView()->render('main') );
Application::renderLayout();
