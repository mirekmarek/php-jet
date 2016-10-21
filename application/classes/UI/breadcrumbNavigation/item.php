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


class UI_breadcrumbNavigation_item extends BaseObject
{
    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $URL = '';

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var bool
     */
    protected $is_last = false;

    /**
     * @var bool
     */
    protected $is_active = false;

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getURL()
    {
        return $this->URL;
    }

    /**
     * @param string $URL
     */
    public function setURL($URL)
    {
        $this->URL = $URL;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param int $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return boolean
     */
    public function getIsLast()
    {
        return $this->is_last;
    }

    /**
     * @param boolean $is_last
     */
    public function setIsLast($is_last)
    {
        $this->is_last = $is_last;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active || $this->is_last;
    }

    /**
     * @param boolean $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }


}