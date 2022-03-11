<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Mtownsend\XmlToArray\XmlToArray;

class Spelling extends Controller
{
    public function spellCheck(Request $request)
    {
        $errorMessages = XmlToArray::convert($request->getContent());
        foreach($errorMessages as $errorMessage) {
            $response = Http::post('https://languagetool.org/api/v2/check', [
                'apiKey' => 'xxxxxxxxxxxx',
                'text' => $errorMessage['message']['@content'],
                'language' => $errorMessage['message']['@attributes']['language']
            ]);
            dd($response);
        }
    }

    public function getDifferences(Request $request)
    {
        $errorMessages = $request->get('error_messages');
        $comparison = [];
        foreach ($errorMessages as $errorMessage) {
            $distance = levenshtein($errorMessage['original_message'], $errorMessage['message']);
            $comparison[] = [
                'message' => $errorMessage['message'],
                'original_message' => $errorMessage['original_message'],
                'distance' => $distance,
                'similarity' => $this->similarity($errorMessage['original_message'], $errorMessage['message'], $distance)
            ];
        }

        return response()->json(['comparison' => $comparison]);
    }


    private function similarity($s1,$s2, $distance) {
        return round((1 - ($distance/max(strlen($s1), strlen($s2))) ) * 100);
    }
}
