<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Repositories\FaqRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    private FaqRepo $faqRepo;

    public function __construct(FaqRepo $faqRepo)
    {
        $this->faqRepo = $faqRepo;
    }

    public function getFaqs(Request $request): JsonResponse
    {
        $per_page = $request->integer('per_page');

        return response()->json(['success' => true, 'faqs' => FaqResource::collection($this->faqRepo->list($per_page))]);
    }
}
