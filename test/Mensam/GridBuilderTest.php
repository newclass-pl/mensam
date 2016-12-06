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


namespace Test\Mensam;


use Mensam\Column;
use Mensam\Formatter\Bootstrap3Formatter;
use Mensam\GridBuilder;
use Mensam\GridDataManager;
use Mensam\Request;

/**
 * Class GridBuilderTest
 * @package Test\Mensam
 * @author Michal Tomczak (michal.tomczak@newclass.pl)
 */
class GridBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $grid = new GridBuilder();
        $request = $this->createMock(Request::class);
        $request->method('getQuery')->willReturn([
            'page' => 31,
            'sort' => [
                '0;asc',
                '1;desc',
            ]
        ]);
        $dataManager = $this->createMock(GridDataManager::class);
        $dataManager->method('getRecords')
            ->with($this->equalTo(10),$this->equalTo(31),$this->equalTo([
                'id'=>'asc',
                'name'=>'desc',
                'subName'=>'desc',
            ]))
            ->willReturn($this->getData());

        $dataManager->method('getTotalCount')->willReturn(1000);
        $grid->setRequest($request);
        $grid->addColumn(new Column('id', 'Id'));
        $grid->addColumn(new Column([
            'name',
            'subName'
        ], 'Name'));

        $grid->addColumn(new Column([
            'subName'
        ], 'Sub name'));

        $grid->addColumn(new Column([
            'id'
        ], 'No sort',null,[]));

        $grid->setDataManager($dataManager);
        $grid->setFormatter(new Bootstrap3Formatter());

        $this->assertEquals('<table class="table "><thead><tr><th><a href="?page=31&sort[]=0%3Bdesc&sort[]=1%3Bdesc">Id<span class="glyphicons glyphicons-sort-by-attributes"></span></a></th><th><a href="?page=31&sort[]=0%3Basc">Name<span class="glyphicons glyphicons-sort-by-attributes-alt"></span></a></th><th><a href="?page=31&sort[]=0%3Basc&sort[]=1%3Bdesc&sort[]=2%3Basc">Sub name<span class="glyphicons glyphicons-sorting"></span></a></th><th>No sort</th></tr></thead><tbody><tr><td>1</td><td>name1 -subName1</td><td>-subName1</td><td>1</td></tr><tr><td>2</td><td>name2 -subName2</td><td>-subName2</td><td>2</td></tr><tr><td>3</td><td>name3 -subName3</td><td>-subName3</td><td>3</td></tr><tr><td>4</td><td>name4 -subName4</td><td>-subName4</td><td>4</td></tr><tr><td>5</td><td>name5 -subName5</td><td>-subName5</td><td>5</td></tr><tr><td>6</td><td>name6 -subName6</td><td>-subName6</td><td>6</td></tr><tr><td>7</td><td>name7 -subName7</td><td>-subName7</td><td>7</td></tr><tr><td>8</td><td>name8 -subName8</td><td>-subName8</td><td>8</td></tr><tr><td>9</td><td>name9 -subName9</td><td>-subName9</td><td>9</td></tr></tbody><tfoot><tr><td colspan="4" class="text-center"><nav><ul class="pagination"><li><a href="?page=30&sort[]=0%3Basc&sort[]=1%3Bdesc" aria-label="Previous"><span aria-hidden="true">«</span></a></li><li><a href="?page=27&sort[]=0%3Basc&sort[]=1%3Bdesc">27</a></li><li><a href="?page=28&sort[]=0%3Basc&sort[]=1%3Bdesc">28</a></li><li><a href="?page=29&sort[]=0%3Basc&sort[]=1%3Bdesc">29</a></li><li><a href="?page=30&sort[]=0%3Basc&sort[]=1%3Bdesc">30</a></li><li class="active"><a href="#">31</a></li><li><a href="?page=32&sort[]=0%3Basc&sort[]=1%3Bdesc">32</a></li><li><a href="?page=33&sort[]=0%3Basc&sort[]=1%3Bdesc">33</a></li><li><a href="?page=34&sort[]=0%3Basc&sort[]=1%3Bdesc">34</a></li><li><a href="?page=35&sort[]=0%3Basc&sort[]=1%3Bdesc">35</a></li><li><a href="?page=32&sort[]=0%3Basc&sort[]=1%3Bdesc" aria-label="Next"><span aria-hidden="true">»</span></a></li></ul></nav></td></tfoot></table>',$grid->render());
    }

    public function testSetLimit()
    {
        $grid = new GridBuilder();
        $request = $this->createMock(Request::class);
        $request->method('getQuery')->willReturn(['page' => 31]);
        $dataManager = $this->createMock(GridDataManager::class);
        $dataManager->method('getRecords')->willReturn($this->getData());
        $dataManager->method('getTotalCount')->willReturn(1000);
        $grid->setRequest($request);
        $grid->setLimit(30);
        $grid->addColumn(new Column('id', 'Id'));
        $grid->addColumn(new Column([
            'name',
            'subName'
        ], 'Name'));

        $grid->setDataManager($dataManager);
        $grid->setFormatter(new Bootstrap3Formatter());

        $this->assertEquals('<table class="table "><thead><tr><th><a href="?page=31&sort[]=0%3Basc">Id<span class="glyphicons glyphicons-sorting"></span></a></th><th><a href="?page=31&sort[]=1%3Basc">Name<span class="glyphicons glyphicons-sorting"></span></a></th></tr></thead><tbody><tr><td>1</td><td>name1 -subName1</td></tr><tr><td>2</td><td>name2 -subName2</td></tr><tr><td>3</td><td>name3 -subName3</td></tr><tr><td>4</td><td>name4 -subName4</td></tr><tr><td>5</td><td>name5 -subName5</td></tr><tr><td>6</td><td>name6 -subName6</td></tr><tr><td>7</td><td>name7 -subName7</td></tr><tr><td>8</td><td>name8 -subName8</td></tr><tr><td>9</td><td>name9 -subName9</td></tr></tbody><tfoot><tr><td colspan="2" class="text-center"><nav><ul class="pagination"><li><a href="?page=30" aria-label="Previous"><span aria-hidden="true">«</span></a></li><li><a href="?page=27">27</a></li><li><a href="?page=28">28</a></li><li><a href="?page=29">29</a></li><li><a href="?page=30">30</a></li><li class="active"><a href="#">31</a></li><li><a href="?page=32">32</a></li><li><a href="?page=33">33</a></li><li><a href="?page=34">34</a></li><li><a href="?page=32" aria-label="Next"><span aria-hidden="true">»</span></a></li></ul></nav></td></tfoot></table>',$grid->render());
    }

    private function getData(){
        $result=[];
        for($i=1; $i<10; $i++){
            $result[]=[
                'id' => $i,
                'name' => 'name'.$i,
                'subName' => '-subName'.$i,
            ];
        }

        return $result;
    }
}