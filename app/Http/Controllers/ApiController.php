<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ApiController extends Controller
{
    public function getResult(Request $request)
    {
        $query = $request->input('search_query');
        $page = $request->input('page');

        $response = Http::get('https://content.guardianapis.com/search', [
            // 'page' => $page,
            'q' => $query,
            'api-key' => 'test',
        ]);

        if ($response->getStatusCode() === 200) {
            // Decode the JSON content into an associative array
            $responseData = json_decode($response->getBody()->getContents(), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $results = $responseData['response']['results'];
        
                $resultsCollection = collect($results);
        
                // $sortedResults = $resultsCollection->sortBy('sectionId')->values()->all();
                $sortedResults = $responseData['response']['results'];
            } else {
            }
        }

        $result['sortedResults'] = $sortedResults;
        $result['currentPage'] = $responseData['response']['currentPage'];
        $result['orderBy'] = $responseData['response']['orderBy'];
        $result['pageSize'] = $responseData['response']['pageSize'];
        $result['pages'] = $responseData['response']['pages'];
        $result['startIndex'] = $responseData['response']['startIndex'];
        $result['status'] = $responseData['response']['status'];
        $result['total'] = $responseData['response']['total'];

        return $result;
    }

    public function saveBookmark(Request $request)
    {
        $id = $request->input('id');
        $link = $request->input('link');
        $title = $request->input('title');

        // Using Session
        $bookmarks = session('bookmarks', []);
        $bookmarks[] = [
            'id' => $id, 
            'link' => $link, 
            'title' => $title, 
        ];
        session(['bookmarks' => $bookmarks]);

        return ['message' => 'Successfully Bookmarked'];
    }

    public function removeBookmark(Request $request){
        
        // $sessionData = session('bookmarks', []);

        // foreach ($sessionData as $key => $value) {
        //     // Check if the value is an array or a string
        //     if (is_array($value)) {
        //         // If it's an array, use forget to remove the entire key
        //         session()->forget('bookmarks.' . $key);
        //     } else {
        //         // If it's a string, use forget to remove the specific index
        //         session()->forget('bookmarks.' . $key);
        //     }
        // }
    
        // return ['message' => 'Session flushed successfully'];

        // Session::flush();

        // return ['message' => 'Session cleared successfully'];
        $idToRemove = $request->input('id');

        $sessionKey = 'bookmarks'; 
        $sessionData = session($sessionKey, []);

        $indexToRemove = -1;
        foreach ($sessionData as $index => $item) {
            if (isset($item['id']) && $item['id'] === $idToRemove) {
                $indexToRemove = $index;
                break;
            }
        }

        if ($indexToRemove !== -1) {
            unset($sessionData[$indexToRemove]);
            session([$sessionKey => $sessionData]);

            return ['message' => 'Session item removed successfully'];
        } else {
            return ['message' => 'Item with the specified id not found in the session'];
        }
    }
}
