<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

/**
 * Class DataModel_Definition_M2NDataModelTestMock
 *
 * @JetDataModel:name = 'm2n_data_model_test_mock'
 * @JetDataModel:database_table_name = 'm2n_data_model_test_mock'
 *
 * @JetDataModel:M_model_class_name = 'Jet\\DataModel_Definition_DataModelTestMock'
 * @JetDataModel:N_model_class_name = 'Jet\\DataModel_Definition_NRelatedDataModelTestMock'
 */
class DataModel_Definition_M2NDataModelTestMock extends DataModel_Related_MtoN {
}