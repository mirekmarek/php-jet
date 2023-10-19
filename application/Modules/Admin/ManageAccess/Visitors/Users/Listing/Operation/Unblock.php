<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Admin\ManageAccess\Visitors\Users;

use Jet\DataListing_Operation;
use Jet\Logger;
use Jet\Tr;

use JetApplication\Auth_Visitor_User as User;

class Listing_Operation_Unblock extends DataListing_Operation
{
	public const KEY = 'unblock';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Unblock filtered users');
	}
	
	public function perform(): void
	{
		$ids = $this->listing->getAllIds();
		
		foreach($ids as $id) {
			$user = User::get( $id );
			if(
				!$user ||
				!$user->isBlocked()
			) {
				continue;
			}
			
			
			$user->unBlock();
			$user->save();
			
			Logger::success(
				event: 'visitor_unblocked',
				event_message: 'Visitor '.$user->getUsername().' ('.$user->getId().') has been unblocked',
				context_object_id: $user->getId(),
				context_object_name: $user->getUsername()
			);
		}
	}
}