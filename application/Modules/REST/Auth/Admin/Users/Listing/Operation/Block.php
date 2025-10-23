<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\REST\Auth\Admin\Users;

use Jet\DataListing_Operation;
use Jet\Logger;
use Jet\Tr;

use JetApplicationModule\REST\Auth\Entity\APIUser;

class Listing_Operation_Block extends DataListing_Operation
{
	public const KEY = 'block';
	
	public function getKey(): string
	{
		return static::KEY;
	}
	
	public function getTitle(): string
	{
		return Tr::_('Block filtered users');
	}
	
	public function perform(): void
	{
		$ids = $this->listing->getAllIds();
		foreach($ids as $id) {
			$user = APIUser::get( $id );
			if(
				!$user ||
				$user->isBlocked()
			) {
				continue;
			}
			
			
			$user->block();
			$user->save();
			
			Logger::success(
				event: 'rest_client_blocked',
				event_message: 'REST client '.$user->getUsername().' ('.$user->getId().') has been blocked',
				context_object_id: $user->getId(),
				context_object_name: $user->getUsername()
			);
		}
	}
}