<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

/**
 *
 */
trait Mvc_Page_Trait_Save
{

	/**
	 * @return string
	 */
	public function getDataDirPath(): string
	{
		if( !$this->getParent() ) {
			return Mvc_Site::get( $this->site_id )->getPagesDataPath( $this->locale );
		} else {
			return $this->getParent()->getDataDirPath() . rawurldecode( $this->relative_path_fragment ) . '/';
		}
	}

	/**
	 * @return string
	 */
	public function getOriginalDataDirPath(): string
	{
		if( !$this->getParent() ) {
			return Mvc_Site::get( $this->site_id )->getPagesDataPath( $this->locale );
		} else {
			return $this->getParent()->getDataDirPath() . rawurldecode( $this->original_relative_path_fragment ) . '/';
		}
	}

	/**
	 *
	 */
	public function saveDataFile(): void
	{
		if(
			$this->original_relative_path_fragment &&
			$this->relative_path_fragment != $this->original_relative_path_fragment
		) {

			$page_dir = $this->getDataDirPath();
			$original_page_dir = $this->getOriginalDataDirPath();

			IO_Dir::rename( $original_page_dir, $page_dir );
		}

		$data = $this->toArray();

		$page_dir = $this->getDataDirPath();

		$data_file_path = $page_dir . static::getPageDataFileName();

		IO_File::write(
			$data_file_path,
			'<?php' . PHP_EOL . 'return ' . (new Data_Array( $data ))->export()
		);

		Mvc_Cache::reset();
	}

}