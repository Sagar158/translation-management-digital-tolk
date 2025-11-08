<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;


class TranslationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/translations",
     *     summary="Create a new translation",
     *     description="Creates a new translation in the database.",
     *     tags={"Translations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"locale", "key", "content"},
     *             @OA\Property(property="locale", type="string"),
     *             @OA\Property(property="key", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="tag", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Translation created successfully"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'locale' => 'required|string',
            'key' => 'required|string',
            'content' => 'required|string',
            'tag' => 'nullable|string',
        ]);

        $translation = Translation::create($request->all());

        return response()->json($translation, 201);
    }

     /**
     * @OA\Put(
     *     path="/api/translations/{id}",
     *     summary="Update translation content",
     *     description="Updates the content of a translation by its ID.",
     *     tags={"Translations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the translation to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="New translation content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="locale", type="string"),
     *             @OA\Property(property="key", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="tag", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=404, description="Translation not found"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function update($id, Request $request)
    {
        $translation = Translation::findOrFail($id);

        $request->validate([
            'content' => 'required|string',
        ]);

        $translation->update($request->only('content'));

        return response()->json($translation);
    }
     /**
     * @OA\Get(
     *     path="/api/translations/{id}",
     *     summary="Get a translation by ID",
     *     description="Fetch a translation by its ID.",
     *     tags={"Translations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the translation to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation found",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="locale", type="string"),
     *             @OA\Property(property="key", type="string"),
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="tag", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Translation not found"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */

    public function show($id)
    {
        $translation = Translation::findOrFail($id);
        return response()->json($translation);
    }

        /**
     * @OA\Get(
     *     path="/api/translations",
     *     summary="Get all translations",
     *     description="Fetches all translations from the database.",
     *     tags={"Translations"},
     *     @OA\Response(
     *         response=200,
     *         description="List of translations",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="locale", type="string"),
     *                 @OA\Property(property="key", type="string"),
     *                 @OA\Property(property="content", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function index(Request $request)
    {
        $cacheKey = 'translations_index_' . md5(json_encode($request->all()));

        $translations = cache()->remember($cacheKey, 60, function () use ($request) {
            $translations = Translation::query();

            if ($request->filled('tag')) {
                $translations->where('tag', $request->tag);
            }

            if ($request->filled('key')) {
                $translations->where('key', 'like', '%' . $request->key . '%');
            }

            if ($request->filled('content')) {
                $translations->where('content', 'like', '%' . $request->content . '%');
            }

            return $translations->paginate(100);
        });

        return response()->json($translations);
    }
    /**
     * @OA\Get(
     *     path="/api/translations/export",
     *     summary="Export all translations",
     *     description="Exports all translations in JSON format.",
     *     tags={"Translations"},
     *     @OA\Response(
     *         response=200,
     *         description="Export successful, returns all translations",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="locale", type="string", example="en"),
     *             @OA\Property(property="key", type="string", example="welcome_message"),
     *             @OA\Property(property="content", type="string", example="Welcome to the site")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function export()
    {
        $cacheKey = 'translations_export';

        $translations = cache()->remember($cacheKey, 60, function () {
            $translations = [];
            Translation::select('locale', 'key', 'content')
                ->chunk(10000, function ($chunk) use (&$translations) {
                    foreach ($chunk as $translation) {
                        $translations[$translation->locale][$translation->key] = $translation->content;
                    }
                });
            return $translations;
        });

        return response()->json($translations);
    }

}
