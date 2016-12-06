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
 * Class GridRender
 * @package Mensam
 * @author Michal Tomczak (michal.tomczak@newclass.pl)
 */
class GridRender
{
    /**
     * @var Column[]
     */
    private $columns;
    /**
     * @var mixed[]
     */
    private $records;
    /**
     * @var int
     */
    private $totalCount;
    /**
     * @var int
     */
    private $limit;
    /**
     * @var int
     */
    private $page;
    /**
     * @var int[]
     */
    private $sortColumns;

    /**
     * GridRender constructor.
     * @param Column[] $columns
     * @param mixed[] $records
     * @param int $totalCount
     * @param int $limit
     * @param int $page
     * @param int[] $sortColumns
     */
    public function __construct($columns, $records, $totalCount, $limit, $page,$sortColumns)
    {
        $this->columns = $columns;
        $this->records = $records;
        $this->totalCount = $totalCount;
        $this->limit = $limit;
        $this->page = $page;
        $this->sortColumns = $sortColumns;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return \mixed[]
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @return int
     */
    public function getMaxPage()
    {
        return $this->getLimit() !== null ? ceil($this->getTotalCount() / $this->getLimit()) : 1;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int[]
     */
    public function getSortColumns()
    {
        return $this->sortColumns;
    }

    /**
     * @param int $indexColumn
     * @return string
     */
    public function getSortURL($indexColumn)
    {
        return $this->getURL($this->getPage(),$indexColumn);
    }

    /**
     * @param int $page
     * @return string
     */
    public function getPaginationURL($page)
    {
        return $this->getURL($page,null);
    }

    /**
     * @param int $page
     * @param int $indexColumn
     * @return string
     */
    private function getURL($page,$indexColumn)
    {
        $url = '?page=' . $page;
        $addColumn = $indexColumn !== null;
        foreach ($this->getSortColumns() as $sortColumn) {
            $column=$this->columns[$sortColumn];
            $sortOrder=$column->getSortOrder();
            if ($indexColumn == $sortColumn) {
                $addColumn = false;
                $sortOrder = $this->shiftSortOrder($sortOrder);
            }

            if ($sortOrder === null) {
                continue;
            }

            $url .= '&sort[]=' . urlencode($sortColumn . ';' . $sortOrder);
        }

        if ($addColumn) {
            $url .= '&sort[]=' . urlencode($indexColumn . ';asc');
        }

        return $url;

    }

    /**
     * @param string $sortDirection
     * @return null|string
     */
    private function shiftSortOrder($sortDirection)
    {
        switch ($sortDirection) {
            case 'asc':
                return 'desc';
            case 'desc':
                return null;
            case null:
                return 'asc';
            default:
                return null;//FIXME throw not supported exception?
        }
    }

}