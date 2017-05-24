<?php
const JET_DEVEL_MODE = false;
const JET_DEBUG_PROFILER_ENABLED = true;

const JET_LAYOUT_CSS_PACKAGER_ENABLED = true;
const JET_LAYOUT_JS_PACKAGER_ENABLED = true;

const JET_TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE = false;



const JET_CACHE_REFLECTION_LOAD = true;
const JET_CACHE_REFLECTION_SAVE = true;

const JET_CACHE_DATAMODEL_DEFINITION_LOAD = true;
const JET_CACHE_DATAMODEL_DEFINITION_SAVE = true;

const JET_CACHE_CONFIG_DEFINITION_LOAD = true;
const JET_CACHE_CONFIG_DEFINITION_SAVE = true;

const JET_CACHE_AUTOLOADER_LOAD = true;
const JET_CACHE_AUTOLOADER_SAVE = true;

const JET_CACHE_MVC_SITE_LOAD = true;
const JET_CACHE_MVC_SITE_SAVE = true;

const JET_CACHE_MVC_PAGE_LOAD = true;
const JET_CACHE_MVC_PAGE_SAVE = true;



/** @noinspection PhpIncludeInspection */
require realpath( __DIR__.'/../_common/jet.php' );
