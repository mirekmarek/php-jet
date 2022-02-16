<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace JetApplicationModule\Test\REST;

use Jet\Data_DateTime;
use JetApplication\Content_Article;


/**
 *
 */
class Test_Article_Put extends Test_Abstract
{

	/**
	 * @return bool
	 */
	public function isEnabled(): bool
	{
		return count( $this->data['articles'] ) > 0;
	}

	/**
	 * @return string
	 */
	protected function _getTitle(): string
	{
		return 'Update (PUT) - valid';
	}

	/**
	 *
	 */
	public function test(): void
	{
		$id = $this->data['articles'][0]['id'];

		$data = [
			'date_time' => Data_DateTime::now()->toString(),
			'localized' =>
				[
				]
		];

		foreach( Content_Article::getLocales() as $locale_str => $locale ) {
			$data['localized'][$locale_str] = [
				'title'      => 'test title ' . time(),
				'annotation' => 'annotation annotation ' . time(),
				'text'       => 'text text text ' . time(),
			];
		}

		$this->client->put( 'article/' . $id, $data );

	}
}
