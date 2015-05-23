<?php

/*
 * This file is part of Laravel Exceptions.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Exceptions\Displayers;

use Exception;
use GrahamCampbell\Exceptions\Displayers\HtmlDisplayer;
use GrahamCampbell\Exceptions\ExceptionInfo;
use GrahamCampbell\Tests\Exceptions\AbstractTestCase;

/**
 * This is the html displayer test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class HtmlDisplayerTest extends AbstractTestCase
{
    public function testServerError()
    {
        $displayer = new HtmlDisplayer(new ExceptionInfo(__DIR__.'/../../resources/errors.json'), __DIR__.'/../../resources/error.html');

        $response = $displayer->display(new Exception('Oh noes!'), 502, []);

        $expected = file_get_contents(__DIR__.'/stubs/502-html.txt');

        $this->assertSame($expected, $response->getContent());
        $this->assertSame(502, $response->getStatusCode());
        $this->assertSame('text/html', $response->headers->get('Content-Type'));
    }

    public function testClientError()
    {
        $displayer = new HtmlDisplayer(new ExceptionInfo(__DIR__.'/../../resources/errors.json'), __DIR__.'/../../resources/error.html');

        $response = $displayer->display(new Exception('Arghhhh!'), 404, []);

        $expected = file_get_contents(__DIR__.'/stubs/404-html.txt');

        $this->assertSame($expected, $response->getContent());
        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('text/html', $response->headers->get('Content-Type'));
    }

    public function testProperties()
    {
        $displayer = new HtmlDisplayer(new ExceptionInfo(__DIR__.'/../../resources/errors.json'), __DIR__.'/../../resources/error.html');

        $this->assertFalse($displayer->isVerbose());
        $this->assertTrue($displayer->canDisplay(new Exception()));
        $this->assertSame('text/html', $displayer->contentType());
    }
}
