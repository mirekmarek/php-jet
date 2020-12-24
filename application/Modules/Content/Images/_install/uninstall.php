<?php
namespace JetApplicationModule\Content\Images;

use Jet\DataModel_Helper;

DataModel_Helper::drop( Gallery::class );
DataModel_Helper::drop( Gallery_Localized::class );
DataModel_Helper::drop( Gallery_Image::class );
