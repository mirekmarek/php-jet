<?php
namespace JetApplicationModule\JetExample\Content\Articles;

use Jet\DataModel_Helper;

$article = new Article();
$article_localized = new Article_Localized();

DataModel_Helper::drop( get_class( $article ) );
DataModel_Helper::drop( get_class( $article_localized ) );