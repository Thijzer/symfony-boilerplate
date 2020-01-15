<?php

namespace App\Tests\Util;

use App\Utils\Slug;
use PHPUnit\Framework\TestCase;

class SlugifyTest extends TestCase
{
    public function testSlugify()
    {
        $slug = new Slug();
        $result = $slug->slugify('Symfony');

        $this->assertEquals('symfony',$result);
    }

    /**
     * @dataProvider provideSlug
     */
    public function testSlugAlot($title)
    {
        $slug = new Slug();
        $result = $slug->slugify($title);
        $this->assertNotEquals($title,$result);
    }

    public function provideSlug()
    {
        return [['SyMfony'],['AKANEO'],['weB'],['HtMl']];
    }
}