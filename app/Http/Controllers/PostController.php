<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Redditwho\Transformers\PostsTransformer;
use App\Models\Post;

class PostController extends APIController
{
    /**
     * @var Domy\Transformers\PostsTransformer
    **/
    protected $postsTransformer;

    function __construct(PostsTransformer $postsTransformer)
    {
        $this->postsTransformer = $postsTransformer;
    }

    /**
     * Displays all the publications of a subreddit, ordered by the order_by criteria
     * Possible parameters:
     *     created_utc, author, subreddit, score, over18, domain&#46;
     * It defaults to "created_utc asc".
     *
     * @param int $idint The id in base10 of the subreddit
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $idint, $orderBy="created_utc", $desc="asc")
    {
        //Publicacions per pàgina, capturat de la URL si s'ha inicialitzat
        //Per Page subreddits, captured from the URL if set
        $perPage = $this->getParam($request, 'per_page', 25, 100);

        $posts = Post::orderedPaginatedPosts($idint, $orderBy, $desc, $perPage);

        if ( ! $posts )
        {
            return $this->responseNotFound('Couldn\'t find any post, sorry!');
        }

        $persistentPerPage = $this->persistentPerPage($posts->currentPage(), $posts->lastPage(), $perPage);

        return $this->response([
            'data' => $this->postsTransformer->transformCollection($posts->all()),
            'subreddit' => $posts['0']->subreddit,
            'previous_page' => $posts->previousPageUrl() . $persistentPerPage['prev'],
            'next_page' => $posts->nextPageUrl() . $persistentPerPage['next'],
            'current_page' => $posts->currentPage(),
            'has_more_pages' => $posts->hasMorePages(),
            'total_elements' => $posts->total(),
            'total_pages' => $posts->lastPage(),
            'elements_per_page' => $posts->perPage()
        ]);
    }

    /**
     * Displays the specified posts.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
}