<?php
/**
 *
 *
 *
 * Default class describing Site (@see Mvc_Sites, @see Mvc_Site_Abstract)
 *
 * A class can be replaced by another class (@see Factory, @see Mvc_Factory), but they must expand Mvc_Site_Abstract
 *
 * @see Factory
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Sites
 */
namespace Jet;

/**
 * Class Mvc_Site_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Mvc_Sites'
 * @JetDataModel:ID_class_name = 'Mvc_Site_ID_Default'
 */
class Mvc_Site_Default extends Mvc_Site_Abstract {

    const SITE_DATA_FILE_NAME = 'site_data.php';
    const URL_MAP_FILE_NAME = 'urls_map.php';
    const PAGES_DIR = 'pages';
    const LAYOUTS_DIR = 'layouts';
    const PUBLIC_DIR = 'public';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 255
	 * @JetDataModel:form_field_label = 'Site name:'
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $is_default = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 * @JetDataModel:form_field_type = false
	 *
	 * @var bool
	 */
	protected $is_active = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_LOCALE
	 * @JetDataModel:is_required = true
	 * @JetDataModel:form_field_type = false
	 *
	 * @var Locale
	 */
	protected $default_locale;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_DATA_MODEL
	 * @JetDataModel:data_model_class = 'Mvc_Site_LocalizedData_Default'
	 *
	 * @var Mvc_Site_LocalizedData_Abstract[]
	 */
	protected $localized_data;

    /**
     * @var array|Mvc_Site_LocalizedData_URL_Abstract[]
     */
    protected static $URL_map;

    /**
     * @var array|Mvc_Site_Default[]
     */
    protected static $loaded_sites = [];


	/**
	 * @param string $ID
	 *
	 */
	public function setID( $ID ) {
		$this->ID = $ID;
	}

	/**
	 * Returns site name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Returns root directory path
	 *
	 * @return string
	 */
	public function getBasePath() {
		return JET_SITES_PATH . $this->ID.'/';
	}


    /**
     * @param Locale $locale (optional)
     * @return string
     */
    public function getPagesDataPath( Locale $locale=null ) {
        $path = $this->getBasePath().static::PAGES_DIR.'/';

        if(!$locale) {
            return $path;
        }

        return $path.$locale.'/';

    }

    /**
     * @return string
     */
    public function getBaseURI() {
        return JET_SITES_URI . $this->getID() . '/';
    }

	/**
	 * @return string
	 */
	public function getPublicURI() {
		return $this->getBaseURI() . static::PUBLIC_DIR.'/';
	}

	/**
	 * @return string
	 */
	public function getPublicPath() {
		return $this->getBasePath() . static::PUBLIC_DIR.'/';
	}

    /**
     * @return string
     */
    public function getLayoutsPath() {
        return $this->getBasePath().static::LAYOUTS_DIR.'/';
    }

	/**
	 * Returns default locale
	 *
	 * @return Locale
	 */
	public function getDefaultLocale() {
		return $this->default_locale;
	}

	/**
	 * @param Locale $locale
	 *
	 * @return bool
	 */
	public function getHasLocale( Locale $locale ) {
		return isset( $this->localized_data[$locale->toString()] );
	}



	/**
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_Abstract
	 */
	public function getLocalizedData( Locale $locale ) {
		return $this->localized_data[$locale->toString()];
	}

	/**
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Abstract[]
	 */
	public function getURLs( Locale $locale ) {
		return $this->localized_data[(string)$locale]->getURLs();
	}

	/**
	 * Add URL
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function addURL( Locale $locale, $URL ) {
		$this->localized_data[(string)$locale]->addURL( $URL );
	}

	/**
	 * Remove URL. If the URL was default, then set as the default first possible URL
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function removeURL( Locale $locale, $URL ) {
		$this->localized_data[(string)$locale]->addURL( $URL );
	}

	/**
	 * Set default URL. Add URL first if is not defined.
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function setDefaultURL( Locale $locale, $URL ) {
		$this->localized_data[(string)$locale]->setDefaultURL( $URL );
	}

	/**
	 * Returns default URL
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Abstract
	 */
	public function getDefaultURL( Locale $locale ) {

		return $this->localized_data[(string)$locale]->getDefaultURL();
	}



	/**
	 * Set default URL. Add URL first if is not defined.
	 *
	 * @param Locale $locale
	 * @param string $URL
	 */
	public function setDefaultSslURL( Locale $locale, $URL ) {
		$this->localized_data[(string)$locale]->setDefaultSslURL( $URL );
	}

	/**
	 * Returns default URL
	 *
	 * @param Locale $locale
	 *
	 * @return Mvc_Site_LocalizedData_URL_Abstract
	 */
	public function getDefaultSslURL( Locale $locale ) {
		return $this->localized_data[(string)$locale]->getDefaultSslURL();
	}


	/**
	 *
	 * @param Locale $locale
	 * @return Mvc_Site_LocalizedData_MetaTag_Abstract[]
	 */
	public function getDefaultMetaTags( Locale $locale ) {
		return $this->localized_data[(string)$locale]->getDefaultMetaTags();
	}

	/**
	 * @param Locale $locale
	 * @param Mvc_Site_LocalizedData_MetaTag_Abstract $meta_tag
	 */
	public function addDefaultMetaTag( Locale $locale, Mvc_Site_LocalizedData_MetaTag_Abstract $meta_tag) {
		$this->localized_data[(string)$locale]->addDefaultMetaTag($meta_tag);
	}

	/**
	 * @param Locale $locale
	 * @param int $index
	 */
	public function removeDefaultMetaTag( Locale $locale, $index ) {
		$this->localized_data[(string)$locale]->removeDefaultMetaTag($index);
	}

	/**
	 * @param Locale $locale
	 * @param Mvc_Site_LocalizedData_MetaTag_Abstract[] $meta_tags
	 */
	public function  setDefaultMetaTags( Locale $locale, $meta_tags ) {
		$this->localized_data[(string)$locale]->setDefaultMetaTags( $meta_tags );
	}


	/**
	 * Returns site locales
	 *
	 * @see Site
	 *
	 * @param bool $get_as_string (optional), default: false
	 *
	 * @return Locale[]
	 */
	public function getLocales( $get_as_string=false ) {

		$result = [];

		foreach( $this->localized_data as $ld ) {
			$locale = $ld->getLocale();

			$result[] = $get_as_string ? (string)$locale : $locale;
		}


		return $result;
	}

	/**
	 * Add locale
	 *
	 * @param Locale $locale
	 */
	public function addLocale( Locale $locale ) {
		if( isset($this->localized_data[(string)$locale]) ) {
			return;
		}

		$new_ld = Mvc_Factory::getLocalizedSiteInstance( $locale );

		$this->localized_data[(string)$locale] = $new_ld;

		if(!$this->default_locale || !$this->default_locale->toString()) {
			$this->setDefaultLocale( $locale );
		}
	}

	/**
	 * Remove locale. If the locale was default, then set as the default first possible locale
	 *
	 * @param Locale $locale
	 */
	public function removeLocale( Locale $locale ) {
		if( !isset($this->localized_data[(string)$locale]) ) {
			return;
		}

		foreach($this->localized_data as $ld) {
			$o_locale = $ld->getLocale();

			if((string)$o_locale==(string)$locale) {
				unset( $this->localized_data[(string)$locale] );
				continue;
			}

			if((string)$locale == (string)$this->default_locale) {
				$this->default_locale = $o_locale;
			}

		}

		if(!count($this->localized_data)) {
			$this->default_locale = null;
		}
	}

	/**
	 * Set default locale. Add locale first if is not defined.
	 *
	 * @param Locale $locale
	 */
	public function setDefaultLocale( Locale $locale ) {
		$this->addLocale( $locale );

		$this->default_locale = $locale;
	}

	/**
	 * @return bool
	 */
	public function getIsDefault() {
		return $this->is_default;
	}

	/**
	 * @param bool $is_default
	 */
	public function setIsDefault($is_default) {
		$this->is_default = (bool)$is_default;
	}

	/**
	 * @return bool
	 */
	public function getIsActive() {
		return $this->is_active;
	}

	/**
	 * @param bool $is_active
	 */
	public function setIsActive($is_active) {
		$this->is_active = (bool)$is_active;
	}

    /**
     * @param DataModel_ID_Abstract|string $ID
     * @return string
     */
    protected static function getSiteDataFilePath( $ID )
    {
        return JET_SITES_PATH.$ID.'/'.static::SITE_DATA_FILE_NAME;
    }

    /**
     *
     */
    public function setupErrorPagesDir() {

        $dir = $this->getBasePath() . 'error_pages/';
        if(IO_Dir::exists($dir)) {
            Debug_ErrorHandler::setErrorPagesDir( $dir );
        }

    }

    /**
     * Sends 401 HTTP header and shows the access denied page
     *
     */
    public function handleAccessDenied() {
        Http_Headers::authorizationRequired();
        if(!Debug_ErrorHandler::displayErrorPage( Http_Headers::CODE_401_UNAUTHORIZED )) {
            echo 'Unauthorized ...';
        }
        Application::end();
    }

    /**
     *
     */
    public function handleDeactivatedSite() {
        Http_Headers::response( Http_Headers::CODE_503_SERVICE_UNAVAILABLE );

        if(!Debug_ErrorHandler::displayErrorPage( Http_Headers::CODE_503_SERVICE_UNAVAILABLE )) {
            echo '503 - Service Unavailable';
        }
        Application::end();
    }

    /**
     *
     */
    public function handleDeactivatedLocale() {
        $this->handle404();
        /*
        Http_Headers::response( Http_Headers::CODE_503_SERVICE_UNAVAILABLE );

        if(!Debug_ErrorHandler::displayErrorPage( Http_Headers::CODE_503_SERVICE_UNAVAILABLE )) {
            echo '503 - Service Unavailable';
        }
        Application::end();
        */
    }

    /**
     * Sends 404 HTTP header and shows the Page Not Found
     *
     */
    public function handle404() {
        Http_Headers::notFound();
        if(!Debug_ErrorHandler::displayErrorPage( Http_Headers::CODE_404_NOT_FOUND )) {
            echo '404 - Page Not Found';
        }
        Application::end();
    }



    /**
     * Returns a list of all sites
     *
     * @return Mvc_Site_Abstract[]
     */
    public function getList() {
        $dirs = IO_Dir::getSubdirectoriesList( JET_SITES_PATH );

        $sites = [];

        foreach( $dirs as $ID ) {
            $site = Mvc_Site::get( $ID );

            $sites[ $ID ] = $site;
        }

        uasort( $sites, function( Mvc_Site_Abstract $site_a, Mvc_Site_Abstract $site_b ) {
            return strcmp( $site_a->getName(), $site_b->getName() );
        } );

        return $sites;
    }

    /**
     * Returns default site data
     *
     * @return Mvc_Site_Abstract
     */
    public function getDefault() {
        $sites = $this->getList();

        foreach( $sites as $site ) {
            if($site->getIsDefault()) {
                return $site;
            }
        }

        return null;
    }



    /**
     * Loads DataModel.
     *
     * @param DataModel_ID_Abstract $ID
     *
     * @throws DataModel_Exception
     *
     * @return DataModel
     */
    public static function load( DataModel_ID_Abstract $ID ) {
        $ID_str = $ID->toString();

        if(isset(static::$loaded_sites[$ID_str])) {
            return static::$loaded_sites[$ID_str];
        }

        $data_file_path = static::getSiteDataFilePath($ID);

        if(!IO_File::exists($data_file_path)) {
            return null;
        }

        /** @noinspection PhpIncludeInspection */
        $data = require $data_file_path;

        $site = new self();

        $URL_map = $site->getUrlsMap();

        $site->ID = $ID;
        $site->name = $data['name'];
        $site->is_active = $data['is_active'];

        foreach( $data['localized_data'] as $locale_str=>$localized_data ) {
            $locale = new Locale($locale_str);

            $site->addLocale( $locale );

            $l_data = $site->localized_data[$locale_str];

            $l_data->setIsActive($localized_data['is_active']);
            $l_data->setDefaultHeadersSuffix($localized_data['default_headers_suffix']);
            $l_data->setDefaultBodyPrefix($localized_data['default_body_prefix']);
            $l_data->setDefaultBodySuffix($localized_data['default_body_suffix']);

            $meta_tags = [];

            foreach($localized_data['default_meta_tags'] as $m_data) {
                $meta_tags[] = Mvc_Factory::getLocalizedSiteMetaTagInstance( $m_data['content'], $m_data['attribute'], $m_data['attribute_value']);
            }

            $l_data->setDefaultMetaTags( $meta_tags );

            $URLs = [];

            foreach( $URL_map as $URL_data ) {
                if(
                    $URL_data->getSiteID()!=(string)$ID ||
                    $URL_data->getLocale()!=$locale_str
                ) {
                    continue;
                }

                $URLs[] = $URL_data;
            }


            $l_data->setURLs( $URLs );
        }

        static::$loaded_sites[$ID_str] = $site;

        return $site;
    }


    /**
     * @param Locale $locale
     *
     * @return Mvc_Page_Abstract
     */
    public function getHomepage( Locale $locale ) {
        return Mvc_Page::get( Mvc_Page::HOMEPAGE_ID, $locale, $this->getID() );
    }


    /**
     * @return string
     */
    protected static function getUrlMapFilePath() {
        return JET_SITES_PATH.static::URL_MAP_FILE_NAME;
    }

    /**
     * @return array|Mvc_Site_LocalizedData_URL_Abstract[]
     *
     * @throws Mvc_Router_Exception
     */
    public function getUrlsMap() {

        if(static::$URL_map) {
            return static::$URL_map;
        }

        $sites_URL_map_file_path = static::getUrlMapFilePath();


        /** @noinspection PhpIncludeInspection */
        $site_URL_map_data = require $sites_URL_map_file_path;


        $URL_map = [];

        foreach( $site_URL_map_data as $key=>$URLs ) {
            list( $site_ID, $locale_str ) = explode('/', $key);

            $site_default_SSL_URL = null;
            $site_default_non_SSL_URL = null;

            foreach( $URLs as $URL ) {
                $URL_i = Mvc_Factory::getLocalizedSiteURLInstance( $URL );
                $URL_i->setSiteID($site_ID);
                $URL_i->setLocale( new Locale($locale_str) );

                if($URL_i->getIsSSL()) {
                    if(!$site_default_SSL_URL) {
                        $site_default_SSL_URL = $URL;
                        $URL_i->setIsDefault(true);
                    }
                } else {
                    if(!$site_default_non_SSL_URL) {
                        $site_default_non_SSL_URL = $URL;
                        $URL_i->setIsDefault(true);
                    }
                }

                $URL = $URL_i->toString();

                if(isset($URL_map[$URL])) {
                    throw new Mvc_Router_Exception('Duplicated site URL: \''.$URL.'\' ');
                }

                $URL_map[$URL] = $URL_i;
            }
        }

        static::$URL_map = $URL_map;

        return $URL_map;
    }


    /**
     * @param array &$data
     *
     */
    public function readCachedData(&$data)
    {
        static::$URL_map = $data['URL_map'];
        static::$loaded_sites = $data['loaded_sites'];

        $data['site'] = static::$loaded_sites[$data['site']];

    }

    /**
     * @param &$data
     */
    public function writeCachedData(&$data)
    {
        /**
         * @var Mvc_Site_Default $site
         */
        $site = $data['site'];

        $data['loaded_sites'] = static::$loaded_sites;
        $data['URL_map'] = static::$URL_map;
        $data['site'] = (string)$site->getID();

    }

    /**
     * @param string $template
     */
    public function create( $template )
    {

        IO_Dir::copy( JET_TEMPLATES_SITES_PATH . $template, $this->getBasePath());

        $pages_root_path = $this->getPagesDataPath();

        $default_pages_path = $pages_root_path.'_default/';

        $locales = [];
        foreach( $this->getLocales() as $locale ) {
            $pages_path = $this->getPagesDataPath($locale);

            if(!IO_Dir::exists($pages_path)) {
                IO_Dir::copy( $default_pages_path, $pages_path);
            }

            $locales[(string)$locale] = true;
        }

        $sub_dirs = IO_Dir::getSubdirectoriesList($pages_root_path);

        foreach( $sub_dirs as $path=>$dir ) {
            if(!isset($locales[$dir])) {
                IO_Dir::remove($path);
            }
        }

        //- DATA FILE
        $data = $this->jsonSerialize();

        foreach( $data['localized_data'] as $locale=>$ld ) {
            unset($data['localized_data'][$locale]['site_ID']);
            unset($data['localized_data'][$locale]['locale']);
            unset($data['localized_data'][$locale]['URLs']);
        }

        $ar = new Data_Array($data);

        $data = '<?php'.JET_EOL.'return '.$ar->export();

        $data_file_path = static::getSiteDataFilePath($this->getID());


        IO_File::write($data_file_path, $data);

        //- MAP
        $URLs_map_data = [];
        if(static::$URL_map) {
            foreach(static::$URL_map as $key=>$URLs) {
                $URLs_map_data[$key] = [];

                foreach( $URLs as $URL ) {
                    /**
                     * @var Mvc_Site_LocalizedData_URL_Abstract $URL
                     */
                    if($URL->getIsSSL()) {
                        $URL = 'SSL:'.$URL;
                    }

                    $URLs_map_data[$key][] = (string)$URL;
                }
            }
        }

        foreach( $this->localized_data as $ld ) {

            $key = $this->getID().'/'.$ld->getLocale();

            $URLs_map_data[$key] = [];

            foreach( $ld->getURLs() as $URL ) {
                if(!$URL->getIsDefault()) {
                    continue;
                }

                if($URL->getIsSSL()) {
                    continue;
                }

                $URLs_map_data[$key][] = $URL->toString();
            }

            foreach( $ld->getURLs() as $URL ) {
                if(!$URL->getIsDefault()) {
                    continue;
                }

                if(!$URL->getIsSSL()) {
                    continue;
                }

                $URLs_map_data[$key][] = 'SSL:'.$URL->toString();
            }

            foreach( $ld->getURLs() as $URL ) {
                if($URL->getIsDefault()) {
                    continue;
                }

                if($URL->getIsSSL()) {
                    $URLs_map_data[$key][] = 'SSL:'.$URL->toString();
                } else {
                    $URLs_map_data[$key][] = $URL->toString();
                }
            }
        }

        $ar = new Data_Array($URLs_map_data);

        IO_File::write(
            static::getUrlMapFilePath(),
            '<?php'.JET_EOL.'return '.$ar->export()
        );

        Mvc::truncateRouterCache();
    }

}