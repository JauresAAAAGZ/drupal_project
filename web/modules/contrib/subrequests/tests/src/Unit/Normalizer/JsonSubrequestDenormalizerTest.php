<?php

namespace Drupal\Tests\subrequests\Normalizer;

use Drupal\Component\Serialization\Json;
use Drupal\subrequests\Normalizer\JsonSubrequestDenormalizer;
use Drupal\subrequests\Subrequest;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @coversDefaultClass \Drupal\subrequests\Normalizer\JsonSubrequestDenormalizer
 * @group subrequests
 */
class JsonSubrequestDenormalizerTest extends UnitTestCase {

  /**
   * Json subrequest denormalizer.
   *
   * @var \Drupal\subrequests\Normalizer\JsonSubrequestDenormalizer
   */
  protected $sut;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->sut = new JsonSubrequestDenormalizer();
  }

  /**
   * Test for denormalize method.
   *
   * @covers ::denormalize
   */
  public function testDenormalize() {
    $class = Request::class;
    $data = new Subrequest([
      'requestId' => 'oof',
      'body' => ['bar' => 'foo'],
      'headers' => ['Authorization' => 'Basic ' . base64_encode('lorem:ipsum')],
      'waitFor' => ['lorem'],
      '_resolved' => FALSE,
      'uri' => 'oop',
      'action' => 'create',
    ]);
    $request = Request::create('');
    $request->setSession(new Session());
    $actual = $this->sut->denormalize($data, $class, NULL, ['master_request' => $request]);
    $this->assertSame('POST', $actual->getMethod());
    $this->assertEquals(['bar' => 'foo'], Json::decode($actual->getContent()));
    $this->assertSame('<oof>', $actual->headers->get('Content-ID'));
    $this->assertSame('lorem', $actual->headers->get('PHP_AUTH_USER'));
    $this->assertSame('ipsum', $actual->headers->get('PHP_AUTH_PW'));
  }

  /**
   * Test for supportsDenormalization method.
   *
   * @dataProvider dataProviderSupportsNormalization
   * @covers ::supportsDenormalization
   */
  public function testSupportsDenormalization($data, $type, $format, $is_supported) {
    $actual = $this->sut->supportsDenormalization($data, $type, $format);
    $this->assertSame($is_supported, $actual);
  }

  /**
   * Data provider for testSupportsDenormalization.
   */
  public static function dataProviderSupportsNormalization(): array {
    $subrequest = new Subrequest([
      'requestId' => 'oof',
      'body' => ['bar' => 'foo'],
      'headers' => [],
      'waitFor' => ['lorem'],
      '_resolved' => FALSE,
      'uri' => 'oop',
      'action' => 'create',
    ]);
    return [
      [$subrequest, Request::class, NULL, TRUE],
      ['fail', Request::class, NULL, FALSE],
      [$subrequest, 'fail', NULL, FALSE],
    ];
  }

}
