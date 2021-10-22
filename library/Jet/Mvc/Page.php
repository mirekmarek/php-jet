<?php
/**
 *
 * @copyright Copyright (c) 2011-2021 Miroslav Marek <mirek.marek@web-jet.cz>
 * @license http://www.php-jet.net/license/license.txt
 * @author Miroslav Marek <mirek.marek@web-jet.cz>
 */

namespace Jet;

require_once 'Page/Interface.php';
require_once 'Page/Trait/Initialization.php';
require_once 'Page/Trait/Tree.php';
require_once 'Page/Trait/URL.php';
require_once 'Page/Trait/Auth.php';
require_once 'Page/Trait/Handlers.php';
require_once 'Page/MetaTag.php';
require_once 'Page/Content.php';


/**
 *
 */
class Mvc_Page extends BaseObject implements Mvc_Page_Interface
{
	const HOMEPAGE_ID = '_homepage_';

	use Mvc_Page_Trait_Main;
	use Mvc_Page_Trait_Initialization;
	use Mvc_Page_Trait_Tree;
	use Mvc_Page_Trait_URL;
	use Mvc_Page_Trait_Auth;
	use Mvc_Page_Trait_Layout;
	use Mvc_Page_Trait_MetaTags;
	use Mvc_Page_Trait_HttpHeaders;
	use Mvc_Page_Trait_Parameters;
	use Mvc_Page_Trait_Content;
	use Mvc_Page_Trait_Cache;
	use Mvc_Page_Trait_Handlers;
	use Mvc_Page_Trait_Save;
	
}