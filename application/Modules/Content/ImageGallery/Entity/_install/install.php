<?php
namespace JetApplicationModule\Content\ImageGallery\Entity;

use Jet\DataModel_Helper;

DataModel_Helper::create( Gallery::class );
DataModel_Helper::create( Gallery_Localized::class );
DataModel_Helper::create( Gallery_Image::class );

