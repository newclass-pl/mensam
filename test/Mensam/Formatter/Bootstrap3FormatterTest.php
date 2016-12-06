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


namespace Test\Mensam\Formatter;


use Mensam\Formatter\Bootstrap3Formatter;
use Mensam\GridRender;

/**
 * Class Bootstrap3FormatterTest
 * @package Test\Mensam\Formatter
 * @author Michal Tomczak (michal.tomczak@newclass.pl)
 */
class Bootstrap3FormatterTest extends \PHPUnit_Framework_TestCase
{

    public function testSetType(){
        $formatter=new Bootstrap3Formatter();

        $gridRender=$this->getMockBuilder(GridRender::class)->disableOriginalConstructor()->getMock();
        $gridRender->method('getColumns')->willReturn([]);
        $gridRender->method('getRecords')->willReturn([]);

        $formatter->setType(Bootstrap3Formatter::BASIC_TYPE);
        $this->assertEquals('<table class="table "><thead><tr></tr></thead><tbody></tbody><tfoot><tr><td colspan="0" class="text-center"><nav><ul class="pagination"><li><a href="" aria-label="Previous"><span aria-hidden="true">«</span></a></li><li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li></ul></nav></td></tfoot></table>',$formatter->render($gridRender));

        $formatter->setType(Bootstrap3Formatter::BORDERED_TYPE);
        $this->assertEquals('<table class="table table-bordered"><thead><tr></tr></thead><tbody></tbody><tfoot><tr><td colspan="0" class="text-center"><nav><ul class="pagination"><li><a href="" aria-label="Previous"><span aria-hidden="true">«</span></a></li><li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li></ul></nav></td></tfoot></table>',$formatter->render($gridRender));

        $formatter->setType(Bootstrap3Formatter::CONDENSED_TYPE);
        $this->assertEquals('<table class="table table-condensed"><thead><tr></tr></thead><tbody></tbody><tfoot><tr><td colspan="0" class="text-center"><nav><ul class="pagination"><li><a href="" aria-label="Previous"><span aria-hidden="true">«</span></a></li><li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li></ul></nav></td></tfoot></table>',$formatter->render($gridRender));

        $formatter->setType(Bootstrap3Formatter::HOVER_TYPE);
        $this->assertEquals('<table class="table table-hover"><thead><tr></tr></thead><tbody></tbody><tfoot><tr><td colspan="0" class="text-center"><nav><ul class="pagination"><li><a href="" aria-label="Previous"><span aria-hidden="true">«</span></a></li><li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li></ul></nav></td></tfoot></table>',$formatter->render($gridRender));

        $formatter->setType(Bootstrap3Formatter::STRIPED_TYPE);
        $this->assertEquals('<table class="table table-striped"><thead><tr></tr></thead><tbody></tbody><tfoot><tr><td colspan="0" class="text-center"><nav><ul class="pagination"><li><a href="" aria-label="Previous"><span aria-hidden="true">«</span></a></li><li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li></ul></nav></td></tfoot></table>',$formatter->render($gridRender));

    }

    public function testSetResponsive(){
        $formatter=new Bootstrap3Formatter();

        $gridRender=$this->getMockBuilder(GridRender::class)->disableOriginalConstructor()->getMock();
        $gridRender->method('getColumns')->willReturn([]);
        $gridRender->method('getRecords')->willReturn([]);

        $formatter->setResponsive(false);
        $this->assertEquals('<table class="table "><thead><tr></tr></thead><tbody></tbody><tfoot><tr><td colspan="0" class="text-center"><nav><ul class="pagination"><li><a href="" aria-label="Previous"><span aria-hidden="true">«</span></a></li><li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li></ul></nav></td></tfoot></table>',$formatter->render($gridRender));

        $formatter->setResponsive(true);
        $this->assertEquals('<div class="table-responsive"><table class="table "><thead><tr></tr></thead><tbody></tbody><tfoot><tr><td colspan="0" class="text-center"><nav><ul class="pagination"><li><a href="" aria-label="Previous"><span aria-hidden="true">«</span></a></li><li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li></ul></nav></td></tfoot></table></div>',$formatter->render($gridRender));

    }

}