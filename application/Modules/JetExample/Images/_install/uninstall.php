<?php
namespace JetApplicationModule\JetExample\Images;

use Jet\DataModel_Helper;

$gallery = new Gallery();
$gallery_image = new Gallery_Image();
$gallery_image_thumbnail = new Gallery_Image_Thumbnail();


DataModel_Helper::drop( get_class( $gallery ) );
DataModel_Helper::drop( get_class( $gallery_image ) );
DataModel_Helper::drop( get_class( $gallery_image_thumbnail ) );
