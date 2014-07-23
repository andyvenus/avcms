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
    private $taxonomy_manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\AVCMS\Core\Taxonomy\Taxonomy
     */
    private $mock_taxonomy;

    public function setUp()
    {
        $this->taxonomy_manager = new TaxonomyManager();
        $this->mock_taxonomy = $this->getMock('AVCMS\Core\Taxonomy\Taxonomy');
    }

    public function testAddRetrieveTaxonomy()
    {
        $this->taxonomy_manager->addTaxonomy('tags', $this->mock_taxonomy);

        $this->assertTrue($this->taxonomy_manager->hasTaxonomy('tags'));

        $result = $this->taxonomy_manager->getTaxonomy('tags');

        $this->assertEquals($this->mock_taxonomy, $result);

        $this->setExpectedException('\Exception');

        $this->taxonomy_manager->getTaxonomy('non_existant');
    }

    public function testSetTaxonomyJoin()
    {
        $mock_query_builder = $this->getMockBuilder('AVCMS\Core\Database\QueryBuilder\QueryBuilderHandler')
            ->disableOriginalConstructor()
            ->getMock();
        $mock_model = $this->getMockBuilder('AVCMS\Core\Model\Model')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mock_taxonomy->expects($this->once())
            ->method('setTaxonomyJoin');

        $this->taxonomy_manager->addTaxonomy('tags', $this->mock_taxonomy);

        $this->taxonomy_manager->setTaxonomyJoin('tags', $mock_model, $mock_query_builder, ['example', 'example2']);
    }

    public function testUpdate()
    {
        $this->mock_taxonomy->expects($this->once())
            ->method('update');

        $this->taxonomy_manager->addTaxonomy('tags', $this->mock_taxonomy);

        $this->taxonomy_manager->update('tags', 1, 'content', [1, 2]);
    }

    public function testGet()
    {
        $this->mock_taxonomy->expects($this->once())
            ->method('get');

        $this->taxonomy_manager->addTaxonomy('tags', $this->mock_taxonomy);

        $this->taxonomy_manager->get('tags', 1, 'content');
    }
}
 