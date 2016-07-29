<?php
namespace Math\Statistics\Regression;
class LinearThroughPointTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertInstanceOf('Math\Statistics\Regression\Regression', $regression);
        $this->assertInstanceOf('Math\Statistics\Regression\LinearThroughPoint', $regression);
    }
    public function testGetPoints()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals($points, $regression->getPoints());
    }
    public function testGetXs()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals([1,2,4,5,6], $regression->getXs());
    }
    public function testGetYs()
    {
        $points = [ [1,2], [2,3], [4,5], [5,7], [6,8] ];
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals([2,3,5,7,8], $regression->getYs());
    }
    /**
     * @dataProvider dataProviderForEquation
     * Equation matches pattern y = mx + b
     */
    public function testGetEquation(array $points)
    {
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertRegExp('/^y = \d+[.]\d+x [+] \d+[.]\d+$/', $regression->getEquation());
    }
    public function dataProviderForEquation()
    {
        return [
            [ [ [0,0], [1,1], [2,2], [3,3], [4,4] ] ],
            [ [ [1,2], [2,3], [4,5], [5,7], [6,8] ] ],
            [ [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ] ],
        ];
    }
    /**
     * @dataProvider dataProviderForParameters
     */
    public function testGetParameters(array $points, $m, $b)
    {
        $regression = new LinearThroughPoint($points, $force = [0,0];);
        $parameters = $regression->getParameters();
        $this->assertEquals($m, $parameters['m'], '', 0.0001);
        $this->assertEquals($b, $parameters['b'], '', 0.0001);
    }
    public function dataProviderForParameters()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ],
                1.35365853658537, 0
            ],
            [
                [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ],
                54.9003101462118, 0
            ],
        ];
    }
    /**
     * @dataProvider dataProviderForSampleSize
     */
    public function testGetSampleSize(array $points, $n)
    {
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals($n, $regression->getSampleSize());
    }
    public function dataProviderForSampleSize()
    {
        return [
            [
                [ [1,2], [2,3], [4,5], [5,7], [6,8] ], 5
            ],
            [
                [ [4,390], [9,580], [10,650], [14,730], [4,410], [7,530], [12,600], [22,790], [1,350], [3,400], [8,590], [11,640], [5,450], [6,520], [10,690], [11,690], [16,770], [13,700], [13,730], [10,640] ], 20
            ],
        ];
    }
    /**
     * @dataProvider dataProviderForEvaluate
     */
    public function testEvaluate(array $points, $x, $y)
    {
        $force = [0,0];
        $regression = new LinearThroughPoint($points, $force);
        $this->assertEquals($y, $regression->evaluate($x));   
    }
    public function dataProviderForEvaluate()
    {
        return [
            [
                [ [0,0], [1,1], [2,2], [3,3], [4,4] ], // y = x + 0
                5, 5,
            ],
            [
                [ [0,0], [1,1], [2,2], [3,3], [4,4] ], // y = x + 0
                18, 18,
            ],
            [
                [ [0,0], [1,2], [2,4], [3,6] ], // y = 2x + 0
                4, 8,
            ],
            [
                [ [0,1], [1,3.5], [2,6] ], // y = 2.5x + 1
                5, 15.5
            ],
            [
                [ [0,2], [1,1], [2,0], [3,-1] ], // y = -x + 2
                4, -0.571428571
            ],
        ];
    }
}
