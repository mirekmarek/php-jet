<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license <%LICENSE%>
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetExampleApp;
use Jet\BaseObject;
use Jet\Locale;

class UI_flag extends BaseObject
{

    /**
     * @var Locale
     */
    protected $locale;


    /**
     * @param Locale $locale
     */
    public function __construct( Locale $locale ) {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function toString()
    {
    	$locale = $this->locale;

        $res = '';

	    $res .= '<div class="flag flag-'.strtolower($locale->getRegion()).'" title="'.$locale->getLanguageName().'" alt="'.$locale->getLanguageName().'" /></div>';

        return $res;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

}