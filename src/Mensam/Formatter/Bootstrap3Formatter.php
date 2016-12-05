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

use Mensam\Column;
use Mensam\GridFormatter;

/**
 * Formatter for GridBuilder
 * @package Mensam
 * @author Michal Tomczak (michal.tomczak@newclass.pl)
 */
class Bootstrap3Formatter implements GridFormatter
{

    const BASIC_TYPE = '';
    const STRIPED_TYPE = 'table-striped';
    const BORDERED_TYPE = 'table-bordered';
    const HOVER_TYPE = 'table-hover';
    const CONDENSED_TYPE = 'table-condensed';

    /**
     * @var string
     */
    private $type;
    /**
     * @var bool
     */
    private $responsive;

    /**
     * Method generated html grid
     *
     * @param Column[] $columns
     * @param mixed[] $records
     * @param int $totalCount
     * @param int $limit
     * @param int $page
     * @param int $sort
     * @return string
     */
    public function render($columns, $records, $totalCount, $limit, $page, $sort)
    {
        $template = '';
        if ($this->responsive) {
            $template .= '<div class="table-responsive">';
        }
        $template .= '<table class="' . $this->getTableClass() . '">';
        $template .= $this->renderHead($columns, $records, $totalCount, $limit, $page, $sort);
        $template .= $this->renderBody($columns, $records, $totalCount, $limit, $page, $sort);
        $template .= $this->renderFoot($columns, $records, $totalCount, $limit, $page, $sort);
        $template .= '</table>';

        if ($this->responsive) {
            $template .= '</div>';
        }

        return $template;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param bool $responsive
     */
    public function setResponsive($responsive)
    {
        $this->responsive = $responsive;
    }

    private function getTableClass()
    {
        return implode(' ', [
            'table',
            $this->type
        ]);
    }

    /**
     * @param Column[] $columns
     * @param mixed[] $records
     * @param int $totalCount
     * @param int $limit
     * @param int $page
     * @param int $sort
     * @return string
     */
    public function renderHead($columns, $records, $totalCount, $limit, $page, $sort)
    {
        $template = '<thead><tr>';
        foreach ($columns as $column) {
            $template .= '<th>' . $column->getLabel() . '</th>';
        }
        $template .= '</tr></thead>';

        return $template;
    }

    /**
     * @param Column[] $columns
     * @param mixed[] $records
     * @param int $totalCount
     * @param int $limit
     * @param int $page
     * @param int $sort
     * @return string
     */
    public function renderBody($columns, $records, $totalCount, $limit, $page, $sort)
    {
        $template = '<tbody>';
        foreach ($records as $record) {
            $template .= '<tr>';
            foreach ($columns as $column) {
                $template .= $this->renderRecord($column, $record);
            }
            $template .= '</tr>';
        }

        $template .= '</tbody>';

        return $template;
    }

    /**
     * @param Column $column
     * @param mixed[] $record
     * @return string
     */
    private function renderRecord(Column $column, $record)
    {
        $template = '<td>';
        $template .= $column->getFormatter()->render($this->getData($column->getKeys(), $record));
        $template .= '</td>';

        return $template;
    }

    private function getData($keys, $record)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[] = $record[$key];//FIXME check key exists?
        }

        return $result;
    }

    /**
     * @param Column[] $columns
     * @param mixed[] $records
     * @param int $totalCount
     * @param int $limit
     * @param int $page
     * @param int $sort
     * @return string
     */
    public function renderPagination($columns, $records, $totalCount, $limit, $page, $sort)
    {
        $template = '<nav><ul class="pagination">';
        if ($page === 1) {
            $template .= '<li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>';
        } else {
            $template .= '<li><a href="?page=' . ($page - 1) .
                '" aria-label="Previous"><span aria-hidden="true">«</span></a></li>';
        }

        $maxPage = $limit !== null ? $totalCount / $limit : 1;
        $offset = 4;
        $startPage = $page - $offset;
        if ($startPage < 1) {
            $startPage = 1;
        }
        $endPage = $page + $offset;
        if ($endPage > $maxPage) {
            $endPage = $maxPage;
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i === $page) {
                $template .= '<li class="active"><a href="#">' . $page . '</a></li>';
                continue;
            }

            $template .= '<li><a href="?page=' . $page . '">' . $page . '</a></li>';

        }

        if ($page === $maxPage) {
            $template .= '<li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>';
        } else {
            $template .= '<li class="disabled"><a href="?page='.($page+1).'" aria-label="Next"><span aria-hidden="true">»</span></a></li>';
        }

        $template .= '</ul></nav>';

        return $template;
    }

    public function renderFoot($columns, $records, $totalCount, $limit, $page, $sort)
    {
        $template = '<tfoot>';
        $template .= '<tr><td colspan="' . count($columns) . '">' .
            $this->renderPagination($columns, $records, $totalCount, $limit, $page, $sort) . '</td>';
        $template .= '</tfoot>';
        return $template;
    }

}