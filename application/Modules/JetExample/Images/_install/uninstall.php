<?php
namespace JetApplicationModule\JetExample\Images;

use Jet\DataModel_Helper;

$gallery = new Gallery();
$gallery_image = new Gallery_Image();


DataModel_Helper::drop( get_class( $gallery ) );
DataModel_Helper::drop( get_class( $gallery_image ) );
