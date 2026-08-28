<?php
namespace Wpint\WPAPI\Tests\PostType\Enum;

use PHPUnit\Framework\TestCase;
use Wpint\WPAPI\PostType\Enum\PostTypeSupportsEnum;

/**
 * @covers \Wpint\WPAPI\PostType\Enum\PostTypeSupportsEnum
 */
class PostTypeSupportsEnumTest extends TestCase
{

    public function test_values_match_real_wordpress_supports_strings() : void
    {
        $this->assertSame('title', PostTypeSupportsEnum::TITLE->value);
        $this->assertSame('editor', PostTypeSupportsEnum::EDITOR->value);
        $this->assertSame('comments', PostTypeSupportsEnum::COMMENTS->value);
        $this->assertSame('revisions', PostTypeSupportsEnum::REVISIONS->value);
        $this->assertSame('trackbacks', PostTypeSupportsEnum::TRACKBACKS->value);
        $this->assertSame('author', PostTypeSupportsEnum::AUTHOR->value);
        $this->assertSame('excerpt', PostTypeSupportsEnum::EXCERPT->value);
        $this->assertSame('page-attributes', PostTypeSupportsEnum::PAGE_ATTRIBUTES->value);
        $this->assertSame('thumbnail', PostTypeSupportsEnum::THUMBNAIL->value);
        $this->assertSame('custom-fields', PostTypeSupportsEnum::CUSTOM_FIELDS->value);
        $this->assertSame('post-formats', PostTypeSupportsEnum::POST_FORMATS->value);
    }

    public function test_no_two_cases_share_a_value() : void
    {
        $values = array_map(fn($case) => $case->value, PostTypeSupportsEnum::cases());

        $this->assertSame($values, array_unique($values));
    }

}
