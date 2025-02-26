<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

abstract class DataModel_ImportExport_ExportPreprocessor
{
	abstract public function preprocess( DataModel_ImportExport_MetaInfo $meta_info, DataModel_LoadedData $data ) : bool;
}