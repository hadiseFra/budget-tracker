<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\User\Index as IndexRequest;
use App\Http\Requests\Category\User\Search as SearchRequest;
use App\Http\Requests\Category\User\Store as StoreRequest;
use App\Http\Requests\Category\User\Update as UpdateRequest;
use App\Http\Resources\User\Category\CategoryResource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Responses\Response as CustomResponse;
use Throwable;

class CategoryController extends Controller
{
    /**
     * This method returns the list of categories belongs to a user.
     *
     * @param User $user
     * @param IndexRequest $request
     * @return CustomResponse
     */
    public function index(User $user, IndexRequest $request)
    {
        $categories = $user->categories()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return CustomResponse::ok(CategoryResource::collection($categories));
    }

    /**
     * This method searches in a user's categories.
     *
     * @param User $user
     * @param SearchRequest $request
     * @return CustomResponse
     */
    public function search(User $user, SearchRequest $request)
    {
        $categories = $user->categories()
            ->where('title', 'LIKE', '%' . $request->title . '%')
            ->orderBy('created_at')
            ->paginate(20);

        return CustomResponse::ok(CategoryResource::collection($categories));
    }

    /**
     * This method returns a category belongs to a user.
     *
     * @param User $user
     * @param Category $category
     * @return CustomResponse
     */
    public function show(User $user, Category $category)
    {
        return CustomResponse::ok(CategoryResource::make($category));
    }

    /**
     * This method stores a category.
     *
     * @param User $user
     * @param StoreRequest $request
     * @return CustomResponse
     */
    public function store(User $user, StoreRequest $request)
    {
        $category = Category::query()
            ->create($request->validated());

        if (!$category) {
           CustomResponse::serverError();
        }

        return CustomResponse::created(CategoryResource::make($category));
    }

    /**
     * This method updates a category.
     * @param User $user
     * @param UpdateRequest $request
     * @return CustomResponse
     */
    public function update(User $user, Category $category, UpdateRequest $request)
    {
        if ($category->user_id != $user->id) {
            return CustomResponse::forbidden();
        }

        try {
            $category->update($request->validated());
        } catch (Throwable $exception) {
            CustomResponse::serverError($exception);
        }

        return CustomResponse::ok(CategoryResource::make($category));
    }

    /**
     * This method destroys a category.
     *
     * @param User $user
     * @param Category $category
     * @return CustomResponse
     */
    public function destroy(User $user, Category $category)
    {
        if ($category->user_id != $user->id) {
            return CustomResponse::forbidden();
        }

        $category->delete();

        return CustomResponse::noContent();
    }
}
