<?php
/**
 * User: Andy
 * Date: 09/07/2014
 * Time: 14:39
 */

namespace AVCMS\Core\Taxonomy\Tests;

use AVCMS\Core\Taxonomy\TaxonomyManager;

class TaxonomyManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TaxonomyManager
     */
    private $taxonomyManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\AVCMS\Core\Taxonomy\Taxonomy
     */
    private $mockTaxonomy;

    public function setUp()
    {
        $this->taxonomyManager = new TaxonomyManager();
        $this->mockTaxonomy = $this->getMock('AVCMS\Core\Taxonomy\Taxonomy');
    }

    public function testAddRetrieveTaxonomy()
    {
        $this->taxonomyManager->addTaxonomy('tags', $this->mockTaxonomy);

        $this->assertTrue($this->taxonomyManager->hasTaxonomy('tags'));

        $result = $this->taxonomyManager->getTaxonomy('tags');

        $this->assertEquals($this->mockTaxonomy, $result);

        $this->setExpectedException('\Exception');

        $this->taxonomyManager->getTaxonomy('non_existant');
    }

    public function testSetTaxonomyJoin()
    {
        $mockQueryBuilder = $this->getMockBuilder('AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler')
            ->disableOriginalConstructor()
            ->getMock();
        $mockModel = $this->getMockBuilder('AVCMS\Core\Model\Model')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockTaxonomy->expects($this->once())
            ->method('setTaxonomyJoin');

        $this->taxonomyManager->addTaxonomy('tags', $this->mockTaxonomy);

        $this->taxonomyManager->setTaxonomyJoin('tags', $mockModel, $mockQueryBuilder, ['example', 'example2']);
    }

    public function testUpdate()
    {
        $this->mockTaxonomy->expects($this->once())
            ->method('update');

        $this->taxonomyManager->addTaxonomy('tags', $this->mockTaxonomy);

        $this->taxonomyManager->update('tags', 1, 'content', [1, 2]);
    }

    public function testGet()
    {
        $this->mockTaxonomy->expects($this->once())
            ->method('get');

        $this->taxonomyManager->addTaxonomy('tags', $this->mockTaxonomy);

        $this->taxonomyManager->get('tags', 1, 'content');
    }
}
 