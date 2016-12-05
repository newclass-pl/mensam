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
 * Generator grid. Support for mapping data, pagination and generate html code.
 * @package Mensam
 * @author Michal Tomczak (michal.tomczak@newclass.pl)
 */
class GridBuilder{

    /**
     * @var GridFormatter
     */
	private $formatter;

    /**
     * @var GridDataManager
     */
	private $dataManager;

    /**
     * @var int
     */
	private $limit=10;

    /**
     * @var int
     */
	private $page=1;

    /**
     * @var Column[]
     */
	private $columns=[];

	/**
	 * @var Request $request
	 */
	private $request;


	public function setRequest(Request $request){
	    $this->request=$request;
    }

	/**
	 * Set formatter with html rule pattern
	 *
	 * @param GridFormatter $formatter
	 */
	public function setFormatter(GridFormatter $formatter){
		$this->formatter=$formatter;
	}

	/**
	 * Set formatter with html rule pattern
	 *
	 * @param GridDataManager $dataManager
	 */
	public function setDataManager(GridDataManager $dataManager){
		$this->dataManager=$dataManager;
	}

	/**
	 * Get DataManager
	 *
	 * @return GridDataManager
	 */
	public function getDataManager(){
		return $this->dataManager;
	}

	/**
	 * Get columns data
	 *
	 * @return Column[]
	 */
	public function getColumns(){
		return $this->columns;
	}

	/**
	 * Get records
	 *
	 * @return mixed[]
	 */
	public function getRecords(){
		$query=$this->request->getQuery();
		$sort=null;
		if(isset($query['sort']) && isset($this->columns[$query['sort']])){
			$sort=$this->columns[$query['sort']]->getSortKeys();
		}
		if (isset($query['page'])) {
			$this->page=$query['page'];
		}


		return $this->dataManager->getRecords($this->limit,$this->page,$sort);
	}

	/**
	 * Get total count records
	 *
	 * @return int
	 */
	public function getTotalCount(){
		return $this->dataManager->getTotalCount();
	}

	/**
	 * Set limit records on single page
	 *
	 * @param int $limit - items on page
	 */
	public function setLimit($limit){
		$this->limit=$limit;
	}

	/**
	 * Set current page
	 *
	 * @param int $page - current page
	 */
	public function setPage($page){
		$this->page=$page;
	}

	/**
	 * Add column
	 *
	 * @param Column $column
	 */
	public function addColumn(Column $column){
		$this->columns[]=$column;
	}

	/**
	 * Generate html grid string
	 *
	 * @return string with html form
	 */
	public function render(){
		$sort=0;
		$query=$this->request->getQuery();
		if(isset($query['sort']) && isset($this->columns[$query['sort']])){
			$sort=$query['sort'];
		}

		return $this->formatter->render($this->columns
			,$this->getRecords()
			,$this->dataManager->getTotalCount(),$this->limit,$this->page,$sort);
	}


    /**
     * @return string
     */
	public function __toString(){
		return $this->render();
	}

}
