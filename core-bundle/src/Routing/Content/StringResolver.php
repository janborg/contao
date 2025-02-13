<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CoreBundle\Routing\Content;

use Contao\CoreBundle\InsertTag\InsertTagParser;
use Contao\PageModel;
use Symfony\Component\HttpFoundation\UrlHelper;

class StringResolver implements ContentUrlResolverInterface
{
    public function __construct(
        private readonly InsertTagParser $insertTagParser,
        private readonly UrlHelper $urlHelper,
    ) {
    }

    public function resolve(object $content): ContentUrlResult|null
    {
        if (!$content instanceof StringUrl) {
            return null;
        }

        $url = $this->insertTagParser->replaceInline($content->value);
        $url = $this->urlHelper->getAbsoluteUrl($url);

        return new ContentUrlResult($url);
    }

    public function getParametersForContent(object $content, PageModel $pageModel): array
    {
        return [];
    }
}
