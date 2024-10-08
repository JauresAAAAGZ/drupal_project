<?php

namespace Drupal\Tests\subrequests\Unit;

use Drupal\subrequests\Subrequest;
use Drupal\subrequests\SubrequestsTree;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\subrequests\SubrequestsTree
 * @group subrequests
 */
class SubrequestsTreeTest extends UnitTestCase {

  /**
   * Test for stack method.
   *
   * @dataProvider dataProviderStack
   * @covers ::stack
   * @covers ::getLowestLevel
   * @covers ::getNumLevels
   */
  public function testStack($input, $expected_count) {
    $sut = new SubrequestsTree();
    $sut->stack($input);
    $this->assertSame(1, $sut->getNumLevels());
    $this->assertCount($expected_count, $sut->getLowestLevel());
  }

  /**
   * Data provider for testSupportsNormalization.
   */
  public static function dataProviderStack(): array {
    $defaults = [
      'requestId' => 1,
      'body' => '',
      'headers' => [],
      'waitFor' => [1],
      '_resolved' => FALSE,
      'uri' => '',
      'action' => '',
    ];
    return [
      [[new Subrequest($defaults), 12, new Subrequest($defaults)], 2],
      [[12], 0],
    ];
  }

}
