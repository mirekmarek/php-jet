<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\Exports\Manager;


use Jet\AJAX;
use Jet\Form;
use Jet\Form_Field_DateTime;
use Jet\Form_Field_Hidden;
use Jet\Http_Request;
use Jet\MVC_Controller_Default;
use JetApplication\Exports;


class Controller_Main extends MVC_Controller_Default
{

	/**
	 *
	 */
	public function default_Action() : void
	{
		$POST = Http_Request::POST();
		
		$export_action = new Form_Field_Hidden('export_action');
		$export_action->setDefaultValue('schedule_outage');
		$export = new Form_Field_Hidden('export');
		$from_date_time = new Form_Field_DateTime('from_date_time', 'From date and time:');
		$till_date_time = new Form_Field_DateTime('till_date_time', 'Till date and time:');
		
		$schedule_form = new Form('schedule_form', [
			$export_action,
			$export,
			$from_date_time,
			$till_date_time
		]);
		
		$this->view->setVar('schedule_form', $schedule_form);
		
		if(($action=$POST->getString('export_action'))) {
			
			$export = Exports::getExport( $POST->getString('export') );
			$manager = Exports::getManager();
			
			if($export) {
				$this->view->setVar('export', $export);
				switch( $action ) {
					case 'shutdown':
						$manager->shutdownExport( $export );
						break;
					case 'start':
						$manager->startExport( $export );
						break;
					case 'schedule_outage':
						if($schedule_form->catch()) {
							$manager->planExportOutage(
								$export,
								$schedule_form->field('from_date_time')->getValue(),
								$schedule_form->field('till_date_time')->getValue()
							);
						}
						break;
					case 'cancel_planned_outage':
						$manager->cancelPlannedOutage( $export, $POST->getInt('plan_id') );
						break;
				}
				
				AJAX::operationResponse(true, snippets: [
					'status_area_'.$export->getCode() => $this->view->render('admin/status')
				]);
			}
		}
		
		$this->output('admin');
	}
}