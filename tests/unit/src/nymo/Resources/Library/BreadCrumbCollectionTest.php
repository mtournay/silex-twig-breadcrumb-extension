<?php
/**
 * This file is part of silex-twig-breadcrumb-extension
 *
 * (c) Gregor Panek <gp@gregorpanek.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace nymo\Resources\Library;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Testcases for the BreadCrumbCollection class
 * @author Gregor Panek <gp@gregorpanek.de>
 */
class BreadCrumbCollectionTest extends TestCase
{

    /**
     * @var BreadCrumbCollection
     */
    protected $breadCrumbColl;

    public function setUp()
    {
        $this->breadCrumbColl = new BreadCrumbCollection();
    }

    public function testAddItem()
    {

        $generator = $this->createMock(UrlGenerator::class);
        $generator->method('generate')->willReturn('www.yahoo.com');

        $this->breadCrumbColl->setUrlGenerator($generator);

        $this->breadCrumbColl->addItem('Google', 'www.google.de');
        $this->breadCrumbColl->addItem('No Target');
        $this->breadCrumbColl->addItem('Yahoo', ['route' => 'foo']);
        $this->breadCrumbColl->addItem('Yahoo', ['params' => 'bar', 'route' => 'foo']);


        $breadCrumbs = $this->breadCrumbColl->getItems();

        $this->assertCount(4, $breadCrumbs);
        $this->assertEquals('Google', $breadCrumbs[0]['linkName']);
        $this->assertEquals('Google', $breadCrumbs[0]['linkName']);
        $this->assertEquals('No Target', $breadCrumbs[1]['linkName']);
        $this->assertNull($breadCrumbs[1]['target']);
        $this->assertEquals('www.yahoo.com', $breadCrumbs[2]['target']);
        $this->assertEquals('www.yahoo.com', $breadCrumbs[3]['target']);
    }
}
