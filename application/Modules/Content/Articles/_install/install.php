<?php
namespace JetApplicationModule\Content\Articles;

use Jet\DataModel_Helper;

$article = new Article();
$article_localized = new Article_Localized();

DataModel_Helper::create( get_class( $article ) );
DataModel_Helper::create( get_class( $article_localized ) );