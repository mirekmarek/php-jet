<?php
namespace JetApplicationModule\Content\Images;

use Jet\DataModel_Helper;

$gallery = new Gallery();
$gallery_localized = new Gallery_Localized();
$gallery_image = new Gallery_Image();


DataModel_Helper::drop( get_class( $gallery ) );
DataModel_Helper::drop( get_class( $gallery_localized ) );
DataModel_Helper::drop( get_class( $gallery_image ) );
