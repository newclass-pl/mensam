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

class GridBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $grid = new GridBuilder();
        $request = $this->createMock(Request::class);
        $request->method('getQuery')->willReturn(['page' => 1]);
        $dataManager = $this->createMock(GridDataManager::class);
        $dataManager->method('getRecords')->willReturn([
            [
                'id' => 1,
                'name' => 'name1',
                'subName' => '-subName1',
            ],
            [
                'id' => 2,
                'name' => 'name2',
                'subName' => '-subName2',
            ]
        ]);
        $grid->setRequest($request);

        $grid->addColumn(new Column('id', 'Id'));
        $grid->addColumn(new Column([
            'name',
            'subName'
        ], 'Name'));

        $grid->setDataManager($dataManager);
        $grid->setFormatter(new Bootstrap3Formatter());

        $this->assertEquals('',$grid->render());
    }

}