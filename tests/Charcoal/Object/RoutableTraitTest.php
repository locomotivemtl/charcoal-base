<?php

namespace Charcoal\Tests\Object;

use \DateTime;

use \Charcoal\Object\PublishableTrait as PublishableTrait;

/**
 *
 */
class RoutableTraitTest extends \PHPUnit_Framework_TestCase
{
    public $obj;

    /**
     * Create mock object from trait.
     */
    public function setUp()
    {
        $this->obj = $this->getMockForTrait(
            '\Charcoal\Object\RoutableTrait',
            [],
            '',
            true,
            true,
            true,
            [ 'metadata' ]
        );

        $this->obj->expects($this->any())
            ->method('metadata')
            ->willReturn([]);
    }

    /**
     * @dataProvider providerSlugs
     */
    public function testSlugify($str, $slug)
    {
        $this->assertEquals($slug, $this->obj->slugify($str));
    }

    public function providerSlugs()
    {
        return [
            ['A B C', 'a-b-c'],
            ['_this_is_a_test_', 'this-is-a-test'],
            ['Allö Bébé!', 'allo-bebe'],
            ['"Hello-#-{$}-£™¡¢∞§¶•ªº-World"', 'hello-world'],
            ['&quot;', 'quot'],
            ['fr/14/Services Santé et Sécurité au Travail', 'fr/14/services-sante-et-securite-au-travail'],
            ['fr/ 14/Services S   anté et Sécurité au Travail', 'fr/14/services-s-ante-et-securite-au-travail']
        ];
    }
}
