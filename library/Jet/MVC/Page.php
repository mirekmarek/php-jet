<?php
/**
 *
 * @copyright Copyright (c) Miroslav Marek <mirek.marek@web-jet.cz>
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
class MVC_Page extends BaseObject implements MVC_Page_Interface
{
	use MVC_Page_Trait_Main;
	use MVC_Page_Trait_Initialization;
	use MVC_Page_Trait_Tree;
	use MVC_Page_Trait_URL;
	use MVC_Page_Trait_Auth;
	use MVC_Page_Trait_Layout;
	use MVC_Page_Trait_MetaTags;
	use MVC_Page_Trait_HttpHeaders;
	use MVC_Page_Trait_Parameters;
	use MVC_Page_Trait_Content;
	use MVC_Page_Trait_Cache;
	use MVC_Page_Trait_Handlers;
	use MVC_Page_Trait_Save;

}