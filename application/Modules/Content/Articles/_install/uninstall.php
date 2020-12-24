<?php
namespace JetApplicationModule\Content\Articles;

use Jet\DataModel_Helper;

DataModel_Helper::drop( Article::class );
DataModel_Helper::drop( Article_Localized::class );