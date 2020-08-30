<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Library\Models\Material;
use Library\Resources\MaterialResource;
use Spatie\QueryBuilder\QueryBuilder;

class MaterialsController extends Controller
{
    /**
     * Returns a collection of Material models
     */
    public function index(Request $request): JsonResource
    {
        $query = QueryBuilder::for(Material::class)
            ->allowedFilters([
                'title',
                'description',
                'parent_id',
                'author.fullname'
            ])
            ->allowedSorts([
                'title',
                'author.fullname',
                'rating'
            ])
            ->allowedIncludes([
                'uploader',
                'parent'
            ]);

        return MaterialResource::collection($query);
    }

    /**
     * Returns a specific of Material models
     */
    public function show(Material $material): JsonResource
    {
        return new MaterialResource($material);
    }

    /**
     * Creates a new Material model
     */
    public function store(Request $request): JsonResource
    {
        $this->validate($request, [
            'parent_id' => ['integer'],
            'title' => ['required', 'max:256'],
            'description' => ['required', 'max: 2048'],
            'date_published' => ['required', 'date'],
        ]);

        return new MaterialResource(
            Material::create($request->all())
        );
    }

    /**
     * Updates an existing Material model
     */
    public function update(Request $request, Material $material): JsonResource
    {
        $this->validate($request, [
            'parent_id' => ['integer'],
            'title' => ['required', 'max:256'],
            'description' => ['required', 'max: 2048'],
            'date_published' => ['required', 'date'],
        ]);

        return new MaterialResource(
            tap($material)->update($request->all())
        );
    }

    /**
     * Deletes an existing Material model
     */
    public function destroy(Material $material): Response
    {
        $material->delete();

        return response([], 204);
    }
}
