<?php
/**
 * Mensam: Grid manager
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */


namespace Mensam\Formatter;

use Mensam\ColumnFormatter;

/**
 * Formatter for GridBuilder
 * @package Mensam
 * @author Michal Tomczak (michal.tomczak@newclass.pl)
 */
class BasicColumnFormatter implements ColumnFormatter{

	/**
	 * {@inheritdoc}
	 */
	public function render($data){
		return htmlspecialchars(trim(implode(' ',$data)));
	}

}