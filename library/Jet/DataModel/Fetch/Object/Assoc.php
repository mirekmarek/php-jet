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
     * @var array|DataModel_PropertyFilter
     */
    protected $load_filter;


    /**
     * @param array|DataModel_PropertyFilter $load_filter
     */
    public function setLoadFilter($load_filter)
    {
    	if($load_filter) {

    		if( !($load_filter instanceof DataModel_PropertyFilter) ) {
			    $load_filter = new DataModel_PropertyFilter(
				    $this->data_model_definition,
				    $load_filter
			    );
		    }

		    $this->query->setSelect(
			    DataModel_PropertyFilter::getQuerySelect( $this->data_model_definition, $load_filter )
		    );

	    }

        $this->load_filter = $load_filter;
    }

    /**
     * @return array|DataModel_PropertyFilter
     */
    public function getLoadFilter()
    {
        return $this->load_filter;
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
		    $_i->setLoadFilter($this->load_filter);
		    $_i->setState($item['__data'], $_i->loadMainRelatedData());

		    $_i->afterLoad();
	    } else {
		    /**
		     * @var DataModel $class_name
		     */
		    $_i =$class_name::load( $item['__id'], $this->load_filter );
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

		if($this->load_filter) {
			foreach( $l as $item ) {
				$l_id = clone $this->empty_id_instance;

				foreach($l_id as $k=>$v) {
					$l_id[$k] = $item[$k];
				}


				$this->data[(string)$l_id] = [
					'__id' => $l_id,
					'__data' => $item
				];
			}
		} else {
			foreach( $l as $item ) {
				$l_id = clone $this->empty_id_instance;

				foreach($l_id as $k=>$v) {
					$l_id[$k] = $item[$k];
				}

				$this->data[(string)$l_id] = [
					'__id' => $l_id,
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