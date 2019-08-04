<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Controller\Front;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SearchAnalysisController extends FrontBaseController
{

    public function __construct() {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function collectQueryAction(Request $request)
    {
        d($request->get('query'));

        return new JsonResponse();
    }
}
