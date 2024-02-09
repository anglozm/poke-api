<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pokemon\StorePokemonRequest;
use App\Http\Requests\Pokemon\UpdatePokemonRequest;
use App\Http\Resources\PokemonResource;
use App\Models\Pokemon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return PokemonResource::collection(Pokemon::orderBy('id', 'ASC')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePokemonRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $pokemon = Pokemon::create($request->validated());

            return Redirect::route('pokemon');
        } catch (Exception $exception) {
            return response()->json(
                [
                    'message' => 'Failed to store pokemon',
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pokemon $pokemon): PokemonResource
    {
        return new PokemonResource($pokemon);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePokemonRequest $request, Pokemon $pokemon): RedirectResponse|JsonResponse
    {
        try {
            $pokemon->update($request->validated());

            return Redirect::route('pokemon');
        } catch (Exception $exception) {
            return response()->json(
                [
                    'message' => 'Failed to delete pokemon. Possibly route [] not defined.',
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pokemon $pokemon): RedirectResponse|JsonResponse
    {
        try {
            $pokemon->delete();

            return Redirect::route('pokemon');
        } catch (Exception $exception) {
            return response()->json(
                [
                    'message' => 'Failed to delete pokemon',
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
