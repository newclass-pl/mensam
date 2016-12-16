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
class GridBuilder
{

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
    private $limit = 10;

    /**
     * @var int
     */
    private $page = 1;

    /**
     * @var Column[]
     */
    private $columns = [];

    /**
     * @var Request $request
     */
    private $request;
    /**
     * @var GridRender
     */
    private $render;


    /**
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Set formatter with html rule pattern
     *
     * @param GridFormatter $formatter
     */
    public function setFormatter(GridFormatter $formatter = null)
    {
        $this->formatter = $formatter;
    }

    /**
     * Set formatter with html rule pattern
     *
     * @param GridDataManager $dataManager
     */
    public function setDataManager(GridDataManager $dataManager = null)
    {
        $this->dataManager = $dataManager;
    }

    /**
     * Get DataManager
     *
     * @return GridDataManager
     */
    public function getDataManager()
    {
        return $this->dataManager;
    }

    /**
     * Get columns data
     *
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get records
     *
     * @return mixed[]
     */
    public function getRecords()
    {
        if (!$this->isConfirmed()) {
            $this->submit();
        }
        return $this->render->getRecords();
    }

    /**
     * Get total count records
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->dataManager->getTotalCount();
    }

    /**
     * Set limit records on single page
     *
     * @param int $limit - items on page
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * Set current page
     *
     * @param int $page - current page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * Add column
     *
     * @param Column $column
     */
    public function addColumn(Column $column)
    {
        $this->columns[] = $column;
    }

    /**
     * Generate html grid string
     *
     * @return string with html form
     */
    public function render()
    {
        if (!$this->isConfirmed()) {
            $this->submit();
        }

        return $this->formatter->render($this->render);
    }

    public function isConfirmed()
    {
        return $this->render !== null;
    }

    /**
     * Submit data.
     */
    public function submit()
    {
        $sortColumns = [];
        $query = $this->request->getQuery();
        $sorts = [];
        if (isset($query['sort'])) {
            foreach ($query['sort'] as $sort) {
                list($columnIndex, $methodSort) = explode(';', $sort);
                $column = $this->columns[$columnIndex];
                $column->setSortOrder($methodSort);//TODO check value is valid
                $sortColumns[] = $columnIndex;
                foreach ($column->getSortKeys() as $sortKey) {
                    if (!isset($sorts[$sortKey])) {
                        $sorts[$sortKey] = $methodSort;
                    }
                }
            }
        }

        if (isset($query['page'])) {
            $this->page = $query['page'];
        }
        $records = $this->dataManager->getRecords($this->limit, $this->page, $sorts);
        $this->render =
            new GridRender($this->columns, $records, $this->dataManager->getTotalCount(), $this->limit, $this->page,
                $sortColumns);
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

}
