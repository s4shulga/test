<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Profile;
use App\ProfileImage;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Transformers\ProfilesTransformer;
use Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\API\Profile\ProfileUpdateRequest;
use App\Http\Requests\API\Profile\ProfileStoreRequest;

/**
 * Class ProfileController
 *
 * @package App\Http\Controllers\API\V1
 */
class ProfileController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $profiles = Profile::with('profile_images')->orderBy('created_at', 'desc')->get();

      return response()->json(['error' => false, 'data' => $profiles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileStoreRequest $request)
    {
        $name = $request->name;
        $images = $request->images;
        try {
            $profile = Profile::create(
                [ 'name' => $name ]
            );
            if(!empty($images)) {
                foreach ($images as $image) {
                    $imagePath = Storage::disk('uploads')->put('/profile/' .$profile->id, $image);
                    ProfileImage::create([
                        'profile_image_path' => '/uploads/'. $imagePath,
                        'profile_id' => $profile->id
                    ]);
                }
            }

            return response()->json(['error' => false, 'data' => $profile]);

        } catch (\Exception $exception) {
            return response()->json(['error' => true, 'exception' => $exception]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profile = Profile::with('profile_images')->findOrFail($id);

        return response()->json(['error' => false, 'data' => $profile]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request, $id)
    {
        $profile = Profile::with('profile_images')->findOrFail($id);
        try {
            $profile->name = $request->name;
            $images = $request->images;

            if(!empty($images)) {
                foreach ($images as $image) {
                    $imagePath = Storage::disk('uploads')->put('/profile/' .$profile->id, $image);
                   ProfileImage::create([
                        'profile_image_path' => '/uploads/'. $imagePath,
                        'profile_id' => $profile->id
                    ]);
                }
            }
            $profile->load('profile_images');
            $profile->save();

            return response()->json(['error' => false, 'data' => $profile]);

        } catch (\Exception $exception) {
            return response()->json(['error' => true, 'exception' => $exception]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);
        try {
            $profile->profile_images()->delete();
            $profile->delete();
            $this->deleteFolder($id);

            return response()->json(['error' => false, 'data' => $profile]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception, 'code' => 500]);
        }
    }

    /**
     * Delete folder by prodile id
     * @param $id
     */
    public function deleteFolder($id) {
        return Storage::disk('uploads')->deleteDirectory("profile/$id");
    }
}
