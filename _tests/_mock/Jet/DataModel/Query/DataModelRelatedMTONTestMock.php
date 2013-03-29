<?php
/**
 *
 * @copyright Copyright (c) 2012-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet\tests
 * @package DataModel
 */
namespace Jet;

class DataModel_Query_DataModelRelatedMTONTestMock extends DataModel_Related_MtoN {
	protected $__data_model_M_model_class_name = "Jet\\DataModel_Query_DataModelTestMock";
	protected $__data_model_N_model_class_name = "Jet\\DataModel_Query_DataModel2TestMock";

	protected static $__data_model_model_name = "data_model_test_mock_related_MtoN";

}