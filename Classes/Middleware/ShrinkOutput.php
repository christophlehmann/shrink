<?php
declare(strict_types=1);
namespace Lemming\Shrink\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\StreamFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use voku\helper\HtmlMin;
use WyriHaximus\HtmlCompress\Factory;

class ShrinkOutput implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if (
            $this->isHtmlResponse($response) &&
            (bool)GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('shrink', 'enable') === true
        ) {
            $stream = $response->getBody();
            $stream->rewind();
            $content = $stream->getContents();
            $newBody = (new StreamFactory())->createStream($this->shrinkHtml($content));
            $response = $response->withBody($newBody);
        }

        return $response;
    }

    protected function shrinkHtml(string $html): string
    {
        $htmlMin = new HtmlMin();
        $htmlMin->setSpecialHtmlComments($this->getSpecialCommentStarts(), []);
        $parser = Factory::constructSmallest()->withHtmlMin($htmlMin);

        return $parser->compress($html);
    }

    protected function getSpecialCommentStarts(): array
    {
        $preserveCommentsStartingWith = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('shrink', 'preserveCommentsStartingWith');
        $commentStarts = GeneralUtility::trimExplode(',', $preserveCommentsStartingWith, true);
        $commentStarts[] = 'TYPO3SEARCH';

        return $commentStarts;
    }

    protected function isHtmlResponse(ResponseInterface $response): bool
    {
        return str_starts_with(
            $response->getHeader('content-type')[0] ?? '',
            'text/html;'
        );
    }
}