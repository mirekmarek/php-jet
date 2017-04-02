<?php
/**
 *
 * @copyright Copyright (c) 2016 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 */
namespace JetUI;
use Jet\BaseObject;


class dialog extends BaseObject
{

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var int
	 */
	protected $width = 0;

	/**
	 *
	 * @param string $id
	 * @param string $title
	 * @param int $width
	 */
	public function __construct($id, $title, $width)
	{
		$this->id = $id;
		$this->title = $title;
		$this->width = $width;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 *
	 */
	public function start() {
		?>
		<div class="modal fade" id="<?=$this->id?>" role="dialog">
			<div class="modal-dialog" style="width: <?=$this->width?>px;">

			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?=$this->title?></h4>
				</div>
				<div class="modal-body">
		<?php
	}

	/**
	 *
	 */
	public function footer() {
		?>
				</div>
				<div class="modal-footer">
		<?php
	}

	/**
	 *
	 */
	public function end() {
		?>
				</div>
			</div>
			</div>
		</div>
		<?php
	}

}