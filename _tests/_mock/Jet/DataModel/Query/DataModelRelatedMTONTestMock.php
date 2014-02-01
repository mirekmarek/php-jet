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
 * Class DataModel_Query_DataModelRelatedMTONTestMock
 *
 * @JetDataModel:name = 'data_model_test_mock_related_MtoN'
 * @JetDataModel:database_table_name = 'data_model_test_mock_related_MtoN'
 *
 * @JetDataModel:M_model_class_name = 'Jet\\DataModel_Query_DataModelTestMock'
 * @JetDataModel:N_model_class_name = 'Jet\\DataModel_Query_DataModel2TestMock'
 */
class DataModel_Query_DataModelRelatedMTONTestMock extends DataModel_Related_MtoN {
}