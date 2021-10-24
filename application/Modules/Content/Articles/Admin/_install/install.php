<?php
use Jet\DataModel_Helper;
use JetApplication\Content_Article;
use JetApplication\Content_Article_Localized;

DataModel_Helper::create( Content_Article::class );
DataModel_Helper::create( Content_Article_Localized::class );