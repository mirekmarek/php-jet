<?php
namespace JetApplicationModule\JetExample\Articles;

use Jet\DataModel_Helper;

$article = new Article();

DataModel_Helper::drop(get_class($article));