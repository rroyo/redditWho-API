<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Response;
use App\Redditwho\Transformers\SubredditsTransformer;

// Models
use App\Models\Subreddit;

class SubredditController extends APIController
{
    /**
     * Max number of top subreddits to be exposed via API
     * @var int
    **/
    protected $totalTopSubreddits = 1500;

    /**
     * @var Domy\Transformers\SubredditsTransformer
    **/
    protected $subredditsTransformer;

    function __construct(SubredditsTransformer $subredditsTransformer)
    {
        $this->subredditsTransformer = $subredditsTransformer;
    }

    /**
     * Displays subreddits, ordered by the order_by criteria&#46;
     * Possible values:
     *     display_name, created_utc, subscribers, submissions, over18&#46;
     * It defaults to "subscribers desc".
     *
     * @param Request $request
     * @param string $orderBy display_name, created_utc, subscribers, submissions, over18
     * @param string $order Order the results in an ascending or descending way
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $orderBy='subscribers', $order='desc')
    {
        $perPage = $this->getParam($request, 'per_page', 25, 100);

        // Queries the DB with specific order criteria and per page elements
        $subreddits = Subreddit::sortedTopSubreddits($orderBy, $order, $perPage);

        if ( ! $subreddits )
        {
            return $this->responseNotFound('Couldn\'t find any subreddit, sorry!');
        }

        // Makes the per page value persistent throught pages
        $persistentPerPage = $this->persistentPerPage($subreddits->currentPage(), $subreddits->lastPage(), $perPage);

        return $this->response([
            'data' => $this->subredditsTransformer->transformCollection($subreddits->all()),
            'previous_page' => $subreddits->previousPageUrl() . $persistentPerPage['prev'],
            'next_page' => $subreddits->nextPageUrl() . $persistentPerPage['next'],
            'current_page' => $subreddits->currentPage(),
            'has_more_pages' => $subreddits->hasMorePages(),
            'total_elements' => $subreddits->total(),
            'total_pages' => $subreddits->lastPage(),
            'elements_per_page' => $subreddits->perPage()
        ]);
    }

    /**
     * Display the specified subreddit.
     *
     * @param  int $idint Subreddit id in base10.
     * @return \Illuminate\Http\Response
     */
    public function getSubreddit($idint){
        return Subreddit::find($idint);
    }
}