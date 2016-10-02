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
 * @abstract
 *
 * @category Jet
 * @package DataModel
 * @subpackage DataModel_Related
 */
namespace Jet;

trait DataModel_Related_Trait {
	/**
	 * @var DataModel
	 */
	private $_main_model_instance;

	/**
	 * @var DataModel
	 */
	private $_parent_model_instance;

    use DataModel_Trait;

    use DataModel_Related_Trait_Definition {
        DataModel_Related_Trait_Definition::_getDataModelDefinitionInstance insteadof DataModel_Trait;
    }
    use DataModel_Related_Trait_Backend {
        DataModel_Related_Trait_Backend::getBackendTransactionStarted insteadof DataModel_Trait;
        DataModel_Related_Trait_Backend::getBackendTransactionStartedByThisInstance insteadof DataModel_Trait;
    }
    use DataModel_Related_Trait_Load {
        DataModel_Related_Trait_Load::initRelatedProperties insteadof DataModel_Trait;
    }
    use DataModel_Related_Trait_Save {
        DataModel_Related_Trait_Save::_saveRelatedObjects insteadof DataModel_Trait;
    }
    use DataModel_Related_Trait_History {
        DataModel_Related_Trait_History::dataModelHistoryOperationStart insteadof DataModel_Trait;
        DataModel_Related_Trait_History::dataModelHistoryOperationDone insteadof DataModel_Trait;
    }
    use DataModel_Related_Trait_Cache {
        DataModel_Related_Trait_Cache::updateDataModelCache insteadof DataModel_Trait;
        DataModel_Related_Trait_Cache::deleteDataModelCache insteadof DataModel_Trait;
    }

}
