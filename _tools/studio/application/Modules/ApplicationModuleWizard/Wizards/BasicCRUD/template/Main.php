<?php
/**
 *
 * @copyright %<COPYRIGHT>%
 * @license  %<LICENSE>%
 * @author  %<AUTHOR>%
 */
namespace %<NAMESPACE>%;

use Jet\Application_Module;

/**
 *
 */
class Main extends Application_Module
{
	public const ADMIN_MAIN_PAGE = '%<PAGE_ID>%';
	
	public const ACTION_GET = 'get_%<ACL_ENTITY_NAME>%';
	public const ACTION_ADD = 'add_%<ACL_ENTITY_NAME>%';
	public const ACTION_UPDATE = 'update_%<ACL_ENTITY_NAME>%';
	public const ACTION_DELETE = 'delete_%<ACL_ENTITY_NAME>%';



}