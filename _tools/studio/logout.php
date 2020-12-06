<?php
namespace JetStudio;

use Jet\Http_Headers;

require 'application/init.php';

AccessControl::logout();

Http_Headers::movedTemporary('./');