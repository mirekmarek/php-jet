<?php
/**
 *
 * @copyright Copyright (c) 2011-2017 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 */
namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_BreadcrumbNavigation {
	/**
	 *
	 * @var Mvc_NavigationData_Breadcrumb_Abstract[]
	 */
	protected $breadcrumb_navigation = [];

	/**
	 *
	 * @var string
	 */
	protected $breadcrumb_title = '';

	/**
	 * @return string
	 */
	public function getBreadcrumbTitle() {
		return $this->breadcrumb_title;
	}

	/**
	 * @param $breadcrumb_title
	 */
	public function setBreadcrumbTitle($breadcrumb_title) {
		$this->breadcrumb_title = $breadcrumb_title;
	}

	/**
	 * @return Mvc_NavigationData_Breadcrumb_Abstract[]
	 */
	public function getBreadcrumbNavigation() {
		/**
		 * @var Mvc_Page|Mvc_Page_Trait_BreadcrumbNavigation $this
		 */

		if( !$this->breadcrumb_navigation ) {
			$this->breadcrumb_navigation = [];

			$navigation_data = Mvc_Factory::getNavigationDataBreadcrumbInstance();
			$navigation_data->setPage( $this );

			$this->breadcrumb_navigation[] = $navigation_data;

			$parent = $this;
			while( ($parent = $parent->getParent()) ) {

				$navigation_data = Mvc_Factory::getNavigationDataBreadcrumbInstance();
				$navigation_data->setPage( $parent );

				array_unshift($this->breadcrumb_navigation, $navigation_data);

			}

		}

		$last = count( $this->breadcrumb_navigation )-1;
		foreach( $this->breadcrumb_navigation as $i=>$bd ) {
			$bd->setIsLast( $i==$last );
		}

		return $this->breadcrumb_navigation;
	}

	/**
	 * @param Mvc_NavigationData_Breadcrumb_Abstract $item
	 */
	public function addBreadcrumbNavigationItem( Mvc_NavigationData_Breadcrumb_Abstract $item  ) {

		$this->getBreadcrumbNavigation();

		$this->breadcrumb_navigation[] = $item;
		$last = count( $this->breadcrumb_navigation )-1;
		foreach( $this->breadcrumb_navigation as $i=>$bd ) {
			/**
			 * @var Mvc_NavigationData_Breadcrumb_Abstract $bd
			 */
			$bd->setIsLast( $i==$last );
		}

	}

	/**
	 * @param string $title
	 * @param string $URI (optional)
	 */
	public function addBreadcrumbNavigationData( $title, $URI='' ) {

		$bn = Mvc_Factory::getNavigationDataBreadcrumbInstance();

		$bn->setTitle( $title );
		$bn->setURI( $URI );

		$this->addBreadcrumbNavigationItem($bn);

	}

	/**
	 * @param Mvc_Page_Interface $page
	 */
	public function addBreadcrumbNavigationPage( Mvc_Page_Interface $page ) {

		$bn = Mvc_Factory::getNavigationDataBreadcrumbInstance();
		$bn->setPage( $page );

		$this->addBreadcrumbNavigationItem( $bn );
	}


	/**
	 * @param Mvc_NavigationData_Breadcrumb_Abstract[] $data
	 * @throws Exception
	 */
	public function setBreadcrumbNavigation( $data ) {

		$this->breadcrumb_navigation = [];

		foreach( $data as $dat ) {
			$this->addBreadcrumbNavigationItem( $dat );
		}
	}

	/**
	 *
	 * @param int $shift_count
	 */
	public function breadcrumbNavigationShift( $shift_count ) {

		$this->getBreadcrumbNavigation();
		if($shift_count<0) {
			$shift_count = count($this->breadcrumb_navigation)+$shift_count;
		}

		for($c=0;$c<$shift_count;$c++) {
			array_shift($this->breadcrumb_navigation);
		}
	}

}