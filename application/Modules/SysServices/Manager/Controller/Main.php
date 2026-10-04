<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */
namespace JetApplicationModule\SysServices\Manager;


use Jet\AJAX;
use Jet\Form;
use Jet\Form_Field_DateTime;
use Jet\Form_Field_Hidden;
use Jet\Http_Request;
use Jet\MVC_Controller_Default;
use JetApplication\SysServices;


class Controller_Main extends MVC_Controller_Default
{

	/**
	 *
	 */
	public function default_Action() : void
	{
		$POST = Http_Request::POST();
		
		$service_action = new Form_Field_Hidden('service_action');
		$service_action->setDefaultValue('schedule_outage');
		$service = new Form_Field_Hidden('service');
		$from_date_time = new Form_Field_DateTime('from_date_time', 'From date and time:');
		$till_date_time = new Form_Field_DateTime('till_date_time', 'Till date and time:');
		
		$schedule_form = new Form('schedule_form', [
			$service_action,
			$service,
			$from_date_time,
			$till_date_time
		]);
		
		$this->view->setVar('schedule_form', $schedule_form);
		
		if(($action=$POST->getString('service_action'))) {
			
			$service = SysServices::getService( $POST->getString('service') );
			$manager = SysServices::getManager();
			
			if($service) {
				$this->view->setVar('service', $service);
				switch( $action ) {
					case 'shutdown':
						$manager->shutdownService( $service );
						break;
					case 'start':
						$manager->startService( $service );
						break;
					case 'schedule_outage':
						if($schedule_form->catch()) {
							$manager->planServiceOutage(
								$service,
								$schedule_form->field('from_date_time')->getValue(),
								$schedule_form->field('till_date_time')->getValue()
							);
						}
						break;
					case 'cancel_planned_outage':
						$manager->cancelPlannedOutage( $service, $POST->getInt('plan_id') );
						break;
				}
				
				AJAX::operationResponse(true, snippets: [
					'status_area_'.$service->getCode() => $this->view->render('admin/status')
				]);
			}
		}
		
		$this->output('admin');
	}
}