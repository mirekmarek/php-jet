<?php
/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Fetch
 */
namespace Jet;

class DataModel_Fetch_Object_Assoc extends DataModel_Fetch_Object_Abstract implements Data_Paginator_DataSource_Interface,\ArrayAccess, \Iterator, \Countable  {

    /**
     * @var array|DataModel_Load_OnlyProperties
     */
    protected $load_only_properties;


    /**
     * @param array|DataModel_Load_OnlyProperties $load_only_properties
     */
    public function setLoadOnlyProperties($load_only_properties)
    {
    	if($load_only_properties) {

    		if( !($load_only_properties instanceof DataModel_Load_OnlyProperties) ) {
			    $load_only_properties = new DataModel_Load_OnlyProperties(
				    $this->data_model_definition,
				    $load_only_properties
			    );
		    }

		    $this->query->setSelect(
			    DataModel_Load_OnlyProperties::getSelectProperties( $this->data_model_definition, $load_only_properties )
		    );

	    }

        $this->load_only_properties = $load_only_properties;
    }

    /**
     * @return array|DataModel_Load_OnlyProperties
     */
    public function getLoadOnlyProperties()
    {
        return $this->load_only_properties;
    }




    /**
     * @param array $item
     * @return DataModel
     */
    protected function _get( $item ) {

    	if(isset($item['__instance'])) {
    		return $item['__instance'];
	    }
	    $class_name = $this->data_model_definition->getClassName();

	    if(isset($item['__data'])) {
		    /**
		     * @var DataModel $_i
		     */
	    	$_i = new $class_name();
		    $_i->setLoadOnlyProperties($this->load_only_properties);
		    $_i->setState($item['__data']);
		    if( ($related_data = $_i->loadRelatedData()) ) {
			    $_i->setRelatedState( $related_data );
		    }

		    $_i->afterLoad();
	    } else {
		    /**
		     * @var DataModel $class_name
		     */
		    $_i =$class_name::load( $item['__ID'], $this->load_only_properties );
	    }

	    $this->data[$_i->getIdObject()->toString()]['__instance'] = $item;


	    return $_i;
    }


	/**
	 *
	 */
	public function _fetch() {
		if($this->data!==null) {
			return;
		}

		$this->data = [];

		$backend = $this->data_model_definition->getBackendInstance();

		$pm = $backend->getDataPaginationMode();
		$backend->setDataPaginationMode( $this->pagination_enabled );

		$l = $backend->fetchAll( $this->query );

		$backend->setDataPaginationMode($pm);

		if($this->load_only_properties) {
			foreach( $l as $item ) {
				$l_ID = clone $this->empty_ID_instance;

				foreach($l_ID as $k=>$v) {
					$l_ID[$k] = $item[$k];
				}


				$this->data[(string)$l_ID] = [
					'__ID' => $l_ID,
					'__data' => $item
				];
			}
		} else {
			foreach( $l as $item ) {
				$l_ID = clone $this->empty_ID_instance;

				foreach($l_ID as $k=>$v) {
					$l_ID[$k] = $item[$k];
				}

				$this->data[(string)$l_ID] = [
					'__ID' => $l_ID,
				];
			}
		}
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$result = [];

		foreach($this as $key=>$val) {
			/**
			 * @var DataModel $val
			 */
			$result[$key] = $val->jsonSerialize();
		}

		return $result;
	}


}