<?php
const JET_DEVEL_MODE = false;
const JET_DEBUG_PROFILER_ENABLED = true;

const JET_LAYOUT_CSS_PACKAGER_ENABLED = true;
const JET_LAYOUT_JS_PACKAGER_ENABLED = true;

const JET_TRANSLATOR_AUTO_APPEND_UNKNOWN_PHRASE = false;



const JET_REFLECTION_CACHE_LOAD = true;
const JET_REFLECTION_CACHE_SAVE = true;

const JET_DATAMODEL_DEFINITION_CACHE_LOAD = true;
const JET_DATAMODEL_DEFINITION_CACHE_SAVE = true;

const JET_CONFIG_DEFINITION_CACHE_LOAD = true;
const JET_CONFIG_DEFINITION_CACHE_SAVE = true;

const JET_AUTOLOADER_CACHE_LOAD = true;
const JET_AUTOLOADER_CACHE_SAVE = true;

const JET_MVC_SITE_CACHE_LOAD = true;
const JET_MVC_SITE_CACHE_SAVE = true;

const JET_MVC_PAGE_CACHE_LOAD = true;
const JET_MVC_PAGE_CACHE_SAVE = true;



/** @noinspection PhpIncludeInspection */
require realpath( __DIR__.'/../_common/jet.php' );
