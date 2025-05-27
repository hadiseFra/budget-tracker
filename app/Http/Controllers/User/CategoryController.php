<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\User\Index as IndexRequest;
use App\Http\Requests\Category\User\Search as SearchRequest;
use App\Http\Requests\Category\User\Store as StoreRequest;
use App\Http\Requests\Category\User\Update as UpdateRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;
use Throwable;

class CategoryController extends Controller
{
    /**
     * This method returns the list of categories belongs to a user.
     *
     * @param User $user
     * @param IndexRequest $request
     * @return Response
     */
    public function index(User $user, IndexRequest $request)
    {
        $categories = Category::query()
            ->where('user_id', '=', $user->id)
            ->orderBy('created_at')
            ->paginate(20);

        if ($categories->isEmpty()) {
            return response()->json([
                'data' => [],
                'message' => 'no data'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'data' => $categories,
            'message' => 'data is retrieved successfully.'
        ], Response::HTTP_OK);

    }

    /**
     * This method searches in a user's categories.
     *
     * @param User $user
     * @param SearchRequest $request
     * @return mixed
     */
    public function search(User $user, SearchRequest $request)
    {
        $categories = Category::query()
            ->where('user_id', '=', $user->id)
            ->where('title', 'LIKE', '%' . $request->title . '%')
            ->orderBy('created_at')
            ->paginate(20);

        if ($categories->isEmpty()) {
            return response()->json([
                'data' => [],
                'message' => 'no data'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'data' => $categories,
            'message' => 'data is retrieved successfully.'
        ], Response::HTTP_OK);
    }

    /**
     * This method returns a category belongs to a user.
     *
     * @param User $user
     * @param Category $category
     * @return mixed
     */
    public function show(User $user, Category $category)
    {
        return response()->json([
            'data' => $category,
            'message' => 'data is retrieved successfully.'
        ], Response::HTTP_OK);
    }

    /**
     * This method stores a category.
     *
     * @param User $user
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(User $user, StoreRequest $request)
    {
        $category = Category::query()
            ->create($request->validated());

        if (!$category) {
            return response()->json([
                'data' => [],
                'message' => 'An error occurred during process.'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'data' => $category,
            'message' => 'Category created successfully.'
        ], Response::HTTP_CREATED);
    }

    /**
     * This method updates a category.
     * @param User $user
     * @param UpdateRequest $request
     * @return mixed
     */
    public function update(User $user, Category $category, UpdateRequest $request)
    {
        if ($category->user_id != $user->id) {
            return response()->json([
                'data' => [],
                'message' => 'No access to this data.'
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            $category = Category::query()
                ->update($request->validated());
        } catch (Throwable $exception) {
            return response()->json([
                'data' => [],
                'message' => $exception->getMessage()
            ], Response::HTTP_OK);
        }

        return response()->json([
            'data' => $category,
            'message' => 'Category updated successfully.'
        ], Response::HTTP_OK);
    }

    /**
     * This method destroys a category.
     *
     * @param User $user
     * @param Category $category
     * @return mixed
     */
    public function destroy(User $user, Category $category)
    {
        if ($category->user_id != $user->id) {
            return response()->json([
                'data' => [],
                'message' => 'No access to this data.'
            ], Response::HTTP_FORBIDDEN);
        }

        $category->delete();

        return response()->json([
            'data' => $category,
            'message' => 'Category deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }
}
