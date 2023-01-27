<?php

namespace App\Http\Controllers;

use App\Models\LogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LogRequestController extends Controller
{

    private $http;
    private $result;
    private $endpoint;
    private $identifier;
    private $responseCode;

    /**
     * Display a listing of the resource.
     */
    public function __invoke(Request $request, $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->http = $request;
        $this->identifier = uniqid(rand(), true);
        $this->logRequest();
        $this->proccessRequest();
        $this->logResponse();
        return response($this->result, $this->responseCode);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function logRequest()
    {
        $fullpath = $this->http->path();
        $paths = explode('/', $fullpath);

        $log = new LogRequest();
        $log->agent = $this->http->header('user-agent');
        $log->ip = $this->http->ip();
        $log->target = $paths[2];
        $log->resource = $paths[4];
        $log->identifier = $this->identifier;
        $log->method = $this->http->method();
        $log->code = 0;
        $log->save();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function proccessRequest()
    {
        $method = strtolower($this->http->method());
        $url = 'http://localhost/' . $this->endpoint;
        if ($method === 'get') $this->result = Http::get($url);
        else $result = Http::post($url, $this->http);
        $this->responseCode = $result->status();
        $this->result = $result->json();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function logResponse()
    {
        LogRequest::where('identifier', $this->identifier)
            ->update(['code' => $this->responseCode]);
    }
}
