<?php

use JetApplication\Content_Gallery;
use JetApplication\Content_Gallery_Localized;
use JetApplication\Content_Gallery_Image;

use Jet\DataModel_Helper;

DataModel_Helper::create( Content_Gallery::class );
DataModel_Helper::create( Content_Gallery_Localized::class );
DataModel_Helper::create( Content_Gallery_Image::class );

