<?php
/**
 *
 *
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
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
 * Class Mvc_Page_Content
 *
 * @JetDataModel:name = 'page_content'
 * @JetDataModel:parent_model_class_name = 'Mvc_Page'
 * @JetDataModel:id_class_name = 'DataModel_Id_UniqueString'
 * @JetDataModel:database_table_name = 'Jet_Mvc_Pages_Contents'
 */
class Mvc_Page_Content extends BaseObject implements Mvc_Page_Content_Interface {
    const DEFAULT_CONTROLLER_ACTION = 'default';

	/**
	 * @JetDataModel:related_to = 'main.site_id'
	 */
	protected $site_id;

	/**
	 * @JetDataModel:related_to = 'main.id'
	 */
	protected $page_id;

	/**
	 * @var Mvc_Page
	 */
	protected $page;

	/**
	 * @JetDataModel:related_to = 'main.locale'
	 */
	protected $locale;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ID
	 * @JetDataModel:is_id = true
	 *
	 * @var string
	 */
	protected $content_id = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 50
	 *
	 * @var string
	 */
	protected $module_name = '';

    /**
     * @JetDataModel:type = DataModel::TYPE_STRING
     * @JetDataModel:max_len = 255
     * @JetDataModel:default_value = ''
     *
     *
     * @var string
     */
    protected $custom_controller = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 50
	 *
	 * @var string
	 */
	protected $controller_action = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_ARRAY
	 *
	 * @var array
	 */
	protected $controller_action_parameters = [
	];

	/**
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 999999
	 *
	 * @var string
	 */
	protected $output = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_STRING
	 * @JetDataModel:max_len = 50
	 *
	 * @var string
	 */
	protected $output_position = '';

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_BOOL
	 *
	 * @var bool
	 */
	protected $output_position_required = false;

	/**
	 *
	 * @JetDataModel:type = DataModel::TYPE_INT
	 *
	 * @var int
	 */
	protected $output_position_order = 0;


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
		$controller_action_parameters= [],
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

	}

    /**
     * @param array $data
     * @return void
     */
    public function setData( array $data ) {
        if(!isset($data['controller_action_parameters'])) {
            $data['controller_action_parameters'] = [];
        }

        if(!is_array($data['controller_action_parameters']) && $data['controller_action_parameters']) {
            $data['controller_action_parameters'] = [$data['controller_action_parameters']];
        } else {
            $data['controller_action_parameters'] = [];
        }

        foreach( $data as $key=>$val ) {
            $this->{$key} = $val;
        }
    }

	/**
	 * @return Mvc_Page_Interface
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function setPage(Mvc_Page_Interface $page)
	{
		$this->page = $page;
	}



	/**
	 * @return mixed|null
	 */
	public function getArrayKeyValue() {
		return $this->content_id;
	}

	/**
	 * @return string
	 */
	public function getSiteId() {
		return $this->site_id;
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
	public function getPageId() {
		return $this->page_id;
	}

	/**
	 * @return string
	 */
	public function getContentKey() {
		return $this->site_id.':'.$this->locale.':'.$this->page_id.':'.$this->content_id;
	}

    /**
     * @param mixed $id
     *
     */
    public function setContentId($id ) {
        $this->content_id = $id;
    }

	/**
	 * @return string
	 */
	public function getContentId() {
		return $this->content_id;
	}

    /**
     * @param string $custom_controller
     */
    public function setCustomController($custom_controller)
    {
        $this->custom_controller = $custom_controller;
    }

    /**
     * @return string
     */
    public function getCustomController()
    {
        return $this->custom_controller;
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
	public function getOutput()
	{
		return $this->output;
	}

	/**
	 * @param string $output
	 */
	public function setOutput($output)
	{
		$this->output = $output;
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
     *
     * @return Mvc_Controller_Abstract|bool
     */
    protected function getControllerInstance() {
        if($this->_controller_instance!==null) {
            return $this->_controller_instance;
        }


        $module_name = $this->getModuleName();

        if(!Application_Modules::getModuleIsActivated($module_name)) {
            $this->_controller_instance = false;

            return false;
        }

        $module_instance = Application_Modules::getModuleInstance( $module_name );

        if(!$module_instance) {

            return false;
        }

        $this->_controller_instance = $module_instance->getControllerInstance( $this );

        return $this->_controller_instance;
    }

    /**
     *
     */
    public function dispatch() {

        if($this->getOutput()) {

	        Mvc_Layout::getCurrentLayout()->addOutputPart(
                $this->getOutput(),
                $this->output_position,
                $this->output_position_required,
                $this->output_position_order,
                $this->getContentKey()
            );

            return;
        }

        $module_name = $this->getModuleName();
        $controller_action = $this->getControllerAction();

        $block_name =  $module_name.':'.$controller_action;

        Debug_Profiler::blockStart( 'Dispatch '.$block_name );


        $controller = $this->getControllerInstance();

        if(!$controller) {

            Debug_Profiler::message('Module is not installed and/or activated - skipping');

        } else {
            Debug_Profiler::message('Dispatch:'.$this->getContentKey() );

	        $translator_namespace = Translator::getCurrentNamespace();
	        Translator::setCurrentNamespace( $module_name );

	        $module_instance = Application_Modules::getModuleInstance( $module_name );
	        $module_instance->callControllerAction(
		        $controller,
		        $controller_action,
		        $this->getControllerActionParameters()
	        );

	        Translator::setCurrentNamespace( $translator_namespace );
        }

        Debug_Profiler::blockEnd( 'Dispatch '.$block_name );

    }


}