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
use Mensam\GridRender;

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
     * @param GridRender $render
     * @return string
     */
    public function render(GridRender $render)
    {
        $template = '';
        if ($this->responsive) {
            $template .= '<div class="table-responsive">';
        }
        $template .= '<table class="' . $this->getTableClass() . '">';
        $template .= $this->renderHead($render);
        $template .= $this->renderBody($render);
        $template .= $this->renderFoot($render);
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
     * @param GridRender $render
     * @return string
     */
    public function renderHead(GridRender $render)
    {
        $template = '<thead><tr>';
        foreach ($render->getColumns() as $kColumn => $column) {
            $template .= '<th>';
            if ($column->isSortable()) {
                $template .= '<a href="'.$render->getSortURL($kColumn) . '">';
            }
            $template .= $column->getLabel();

            if ($column->isSortable()) {
                $sortIcon=$this->getSortIcon($column->getSortOrder());
                $template .= '<span class="glyphicons '.$sortIcon.'"></span></a>';
            }

            $template .= '</th>';
        }
        $template .= '</tr></thead>';

        return $template;
    }

    /**
     * @param GridRender $render
     * @return string
     */
    public function renderBody(GridRender $render)
    {
        $template = '<tbody>';
        foreach ($render->getRecords() as $record) {
            $template .= '<tr>';
            foreach ($render->getColumns() as $column) {
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
     * @param GridRender $render
     * @return string
     */
    public function renderPagination(GridRender $render)
    {
        $page=$render->getPage();
        $template = '<nav><ul class="pagination">';
        if ($page === 1) {
            $template .= '<li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>';
        } else {
            $template .= '<li><a href="' . $render->getPaginationURL($page - 1) .
                '" aria-label="Previous"><span aria-hidden="true">«</span></a></li>';
        }

        $maxPage = $render->getMaxPage();
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
                $template .= '<li class="active"><a href="#">' . $i . '</a></li>';
                continue;
            }

            $template .= '<li><a href="' .$render->getPaginationURL($i) . '">' . $i . '</a></li>';

        }

        if ($page === $maxPage) {
            $template .= '<li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>';
        } else {
            $template .= '<li><a href="' . $render->getPaginationURL($page + 1) .
                '" aria-label="Next"><span aria-hidden="true">»</span></a></li>';
        }

        $template .= '</ul></nav>';

        return $template;
    }

    /**
     * @param GridRender $render
     * @return string
     */
    public function renderFoot(GridRender $render)
    {
        $template = '<tfoot>';
        $template .= '<tr><td colspan="' . count($render->getColumns()) . '" class="text-center">' .
            $this->renderPagination($render) . '</td>';
        $template .= '</tfoot>';
        return $template;
    }

    /**
     * @param string $sortOrder
     * @return string
     */
    private function getSortIcon($sortOrder)
    {
        if($sortOrder==='asc'){
            return 'glyphicons-sort-by-attributes';
        }
        else if($sortOrder==='desc'){
            return 'glyphicons-sort-by-attributes-alt';
        }

        return 'glyphicons-sorting';
    }

}