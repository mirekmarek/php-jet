<?php
namespace JetApplicationModule\JetExample\Articles;

use Jet\DataModel_Helper;

$article = new Article();

DataModel_Helper::create(get_class($article));