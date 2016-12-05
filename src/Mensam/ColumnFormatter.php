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


namespace Mensam;

/**
 * Interface of column formatter (cell in grid).
 * @package Mensam
 * @author Michal Tomczak (michal.tomczak@newclass.pl)
 */
interface ColumnFormatter{

	/**
	 * Method generated html for column
	 *
	 * @param mixed $data - field from record
	 */
	public function render($data);

}