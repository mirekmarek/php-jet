<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Pages
 */
namespace Jet;

/**
 * Class Mvc_Page_Content_Default
 *
 * @JetDataModel:database_table_name = 'Jet_Mvc_Pages_Contents'
 * @JetDataModel:parent_model_class_name = 'Jet\Mvc_Page_Default'
 */
class Mvc_Page_Content_Default extends Mvc_Page_Content_Abstract {
    const DEFAULT_CONTROLLER_ACTION = 'default';

	/**
	 * @JetDataModel:related_to = 'main.site_ID'
	 */
	protected $site_ID;

	/**
	 * @JetDataModel:related_to = 'main.ID'
	 */
	protected $page_ID;

	/**
	 * @JetDataModel:related_to = 'main.locale'
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ID
	 * @JetDataModel:is_ID = true
	 *
	 * @var string
	 */
	protected $ID = '';

    /**
     * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
     * @JetDataModel:default_value = false
     *
     * @var bool
     */
    protected $is_dynamic = false;

    /**
     * @JetDataModel:type = Jet\DataModel::TYPE_STRING
     * @JetDataModel:max_len = 255
     * @JetDataModel:default_value = ''
     *
     *
     * @var string
     */
    protected $custom_service_type = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 50
	 *
	 * @var string
	 */
	protected $module_name = '';

    /**
     * @JetDataModel:type = Jet\DataModel::TYPE_STRING
     * @JetDataModel:max_len = 50
     *
     * @var string
     */
    protected $parser_URL_method_name;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 50
	 *
	 * @var string
	 */
	protected $controller_action = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_ARRAY
	 * @JetDataModel:item_type = 'String'
	 *
	 * @var array
	 */
	protected $controller_action_parameters = array (
	);

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 50
	 *
	 * @var string
	 */
	protected $output_position = '';

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_BOOL
	 *
	 * @var bool
	 */
	protected $output_position_required = false;

	/**
	 *
	 * @JetDataModel:type = Jet\DataModel::TYPE_INT
	 *
	 * @var int
	 */
	protected $output_position_order = 0;

    /**
     * @var Mvc_Layout_OutputPart[]|null
     */
    protected $output_parts;

    /**
     * @var Mvc_Controller_Abstract
     */
    protected $_controller_instance;

	/**
	 * @param string $module_name (optional)
	 * @param string $controller_action (optional)
	 * @param array $controller_action_parameters (optional)
	 * @param string $output_position (optional)
	 * @param bool $output_position_required (optional)
	 * @param int $output_position_order (optional)
	 */
	public function __construct(
		$module_name='',
		$controller_action='',
		$controller_action_parameters=array(),
		$output_position='',
		$output_position_required=true,
		$output_position_order=0
	) {
		if($module_name) {

			$this->module_name = $module_name;
			$this->controller_action = $controller_action;
			$this->controller_action_parameters = $controller_action_parameters;

			$this->output_position = $output_position;
			$this->output_position_required = (bool)$output_position_required;
			$this->output_position_order = (int)$output_position_order;
		}

		parent::__construct();
	}

    /**
     * @param array $data
     * @return void
     */
    public function setData( array $data ) {
        if(!isset($data['controller_action_parameters'])) {
            $data['controller_action_parameters'] = array();
        }

        if(!is_array($data['controller_action_parameters']) && $data['controller_action_parameters']) {
            $data['controller_action_parameters'] = array( $data['controller_action_parameters'] );
        } else {
            $data['controller_action_parameters'] = array();
        }

        foreach( $data as $key=>$val ) {
            $this->{$key} = $val;
        }

    }

	/**
	 * @return mixed|null
	 */
	public function getArrayKeyValue() {
		return $this->ID;
	}

	/**
	 * @return string
	 */
	public function getSiteID() {
		return $this->site_ID;
	}

	/**
	 * @return Locale
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @return string
	 */
	public function getPageID() {
		return $this->page_ID;
	}

    /**
     * @param mixed $ID
     *
     */
    public function setID( $ID ) {
        $this->ID = $ID;
    }


    /**
     * @param bool $is_dynamic
     */
    public function setIsDynamic($is_dynamic)
    {
        $this->is_dynamic = $is_dynamic;
    }

    /**
     * @return boolean
     */
    public function getIsDynamic()
    {
        return $this->is_dynamic;
    }

    /**
     * @param string $custom_service_type
     */
    public function setCustomServiceType($custom_service_type)
    {
        $this->custom_service_type = $custom_service_type;
    }

    /**
     * @return string
     */
    public function getCustomServiceType()
    {
        return $this->custom_service_type;
    }


	/**
	 * @return string
	 */
	public function getModuleName() {
		return $this->module_name;
	}

	/**
	 * @param string $module_name
	 */
	public function setModuleName( $module_name ) {
		$this->module_name = $module_name;
	}

	/**
	 * @return string
	 */
	public function getControllerAction() {
		return $this->controller_action ? $this->controller_action : static::DEFAULT_CONTROLLER_ACTION;
	}

	/**
	 * @param string $controller_action
	 */
	public function setControllerAction( $controller_action ) {
		$this->controller_action = $controller_action;
	}

	/**
	 * @return string
	 */
	public function getOutputPosition() {
		return $this->output_position;
	}

	/**
	 * @param string $output_position
	 */
	public function setOutputPosition( $output_position ) {
		$this->output_position = $output_position;
	}

	/**
	 * @return bool
	 */
	public function getOutputPositionRequired() {
		return $this->output_position_required;
	}

	/**
	 * @param bool $output_position_required
	 */
	public function setOutputPositionRequired( $output_position_required ) {
		$this->output_position_required = (bool)$output_position_required;
	}

	/**
	 * @return int
	 */
	public function getOutputPositionOrder() {
		return $this->output_position_order;
	}

	/**
	 * @param int $output_position_order
	 */
	public function setOutputPositionOrder( $output_position_order ) {
		$this->output_position_order = (int)$output_position_order;
	}

	/**
	 * @return array
	 */
	public function getControllerActionParameters() {
		return $this->controller_action_parameters;
	}

	/**
	 * @param array $controller_action_parameters
	 */
	public function setControllerActionParameters( array $controller_action_parameters ) {
		$this->controller_action_parameters = $controller_action_parameters;
	}

    /**
     * @param string $parser_URL_method_name
     */
    public function setParserURLMethodName($parser_URL_method_name)
    {
        $this->parser_URL_method_name = $parser_URL_method_name;
    }

    /**
     * @return string
     */
    public function getParserURLMethodName()
    {
        return $this->parser_URL_method_name;
    }


    /**
     * @param Mvc_Page_Abstract $page
     *
     * @return Mvc_Controller_Abstract
     */
    protected function getControllerInstance( Mvc_Page_Abstract $page ) {
        if($this->_controller_instance!==null) {
            return $this->_controller_instance;
        }


        $module_name = $this->getModuleName();
        $service_type = $this->getCustomServiceType() ? $this->getCustomServiceType() : $page->getServiceType();



        if(!Application_Modules::getModuleIsActivated($module_name)) {
            $this->_controller_instance = false;

            return false;
        }

        $module_instance = Application_Modules::getModuleInstance( $module_name );

        if(!$module_instance) {

            return false;
        }

        $this->_controller_instance = $module_instance->getControllerInstance( $service_type );

        return $this->_controller_instance;
    }

    /**
     * @param array|Mvc_Layout_OutputPart[] $output_parts
     */
    public function setOutputParts( array $output_parts)
    {
        $this->output_parts = $output_parts;
    }

    /**
     * @return Mvc_Layout_OutputPart[]|null
     */
    public function getOutputParts()
    {
        return $this->output_parts;
    }

    /**
     * @param Mvc_Page_Abstract $page
     */
    public function dispatch( Mvc_Page_Abstract $page ) {

        $module_name = $this->getModuleName();
        $controller_action = $this->getControllerAction();
        $service_type = $this->getCustomServiceType() ? $this->getCustomServiceType() : $page->getServiceType();

        $block_name =  $module_name.':'.$service_type.':'.$controller_action;

        Debug_Profiler::blockStart( 'Dispatch '.$block_name );


        $controller = $this->getControllerInstance( $page );

        if(!$controller) {

            Debug_Profiler::message('Module is not installed and/or activated - skipping');

        } else {
            Debug_Profiler::message('Content ID:'.$this->getID()->toString() );

            $layout = $page->getLayout();

            if(
                !$this->getIsDynamic() &&
                ($output_parts=$this->getOutputParts())
            ) {

                Debug_Profiler::message( 'CACHED - skipping' );

                foreach( $output_parts as $op ) {
                    $layout->setOutputPart($op);
                }


            } else {
                Translator::setCurrentNamespace( $module_name );

                $module_instance = Application_Modules::getModuleInstance( $module_name );
                $module_instance->callControllerAction(
                    $controller,
                    $controller_action,
                    $this->getControllerActionParameters()
                );



                if( $this->getIsDynamic() ) {
                    Debug_Profiler::message('Is dynamic');

                    $this->setIsDynamic(true);
                } else {
                    Debug_Profiler::message('Is static');

                    if( ($output_parts = $layout->getContentOutputParts( $this->getID()->toString() )) ) {
                        $this->setOutputParts( $output_parts );
                    }
                }

            }

        }

        Debug_Profiler::blockEnd( 'Dispatch '.$block_name );

    }


}