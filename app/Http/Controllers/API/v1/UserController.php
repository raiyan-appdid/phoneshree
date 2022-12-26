<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\FileUploader;
use App\Models\User;
use Illuminate\Support\Str;
use Laravolt\Avatar\Avatar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Job;
use DB;

class UserController extends Controller
{
    public function index(Request $request)
    {


        $user = $request->user();
        $user = User::find($user->id);

        return  $user->with([
            'addresses' => [
                'state',
                'district',
                'city',
            ],
            'educations' => [
                'eduction'
            ],
            'experiences' => [
                'experience'
            ],
            'skill_types' => [
                'skill_type'
            ],
            'salary_brackets' => [
                'salary_bracket'
            ],
        ])->first();
    }



    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'disability' => 'nullable|exists:disabilities,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'current_ctc' => 'nullable|numeric',
            'gender' => 'nullable|string|in:male,female,other',
            'education' => 'required|array',
            'education.*' => 'required|exists:education,id',
            'experience' => 'required|array',
            'experience.*' => 'required|exists:experiences,id',
            'skill_type' => 'required|array',
            'skill_type.*' => 'required|exists:skill_types,id',
            'industry' => 'required|array',
            'industry.*' => 'required|exists:industries,id',
            'salary_bracket' => 'required|array',
            'salary_bracket.*' => 'required|exists:salary_brackets,id',
            'address' => 'required|array',
            'address.*.city' => 'required|exists:cities,id',
            'address.*.district' => 'required|exists:districts,id',
            'address.*.state' => 'required|exists:states,id',
            'address.*.street' => 'nullable|string|max:250',
            'address.*.type' => 'nullable|string|in:corresponding,permanent,other',

        ]);
        $user = $request->user();
        if (!empty($request->file('image'))) {
            $image = FileUploader::uploadFile($request->file('image'), 'images/users/');
        } elseif (empty($user->image)) {
            $name = $request->first_name . ' ' . $request->last_name;
            $image = 'images/users/avatar-' . Str::uuid() . '.png';
            $avatar = new Avatar();
            $avatar->create($name)->save($image);
        } else {
            $image = $user->image;
        }



        foreach ($request->education as $key => $value) {
            $educations[] = [
                'user_id' => $user->id,
                'education_id' => $value,
            ];
        }

        foreach ($request->experience as $key => $value) {
            $experiences[] = [
                'user_id' => $user->id,
                'experience_id' => $value,
            ];
        }

        foreach ($request->skill_type as $key => $value) {
            $skill_types[] = [
                'user_id' => $user->id,
                'skill_type_id' => $value,
            ];
        }

        foreach ($request->industry as $key => $value) {
            $industries[] = [
                'user_id' => $user->id,
                'industry_id' => $value,
            ];
        }


        foreach ($request->salary_bracket as $key => $value) {
            $salary_brackets[] = [
                'user_id' => $user->id,
                'salary_bracket_id' => $value,
            ];
        }

        foreach ($request->address as $key => $value) {
            $addresses[] = [
                'user_id' => $user->id,
                'city_id' => $value['city'],
                'district_id' => $value['district'],
                'state_id' => $value['state'],
                'street' => $value['street'] ?? null,
                'address_type' => $value['type'] ?? null,
            ];
        }
        return   DB::transaction(function ()
        use (
            $user,
            $request,
            $educations,
            $experiences,
            $skill_types,
            $industries,
            $salary_brackets,
            $addresses,
            $image,
        ) {
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'image' => $image,
                'disability_id' => $request->disability,
            ]);
            $user->educations()->delete();
            $user->educations()->createMany($educations);
            $user->experiences()->delete();
            $user->experiences()->createMany($experiences);
            $user->skill_types()->delete();
            $user->skill_types()->createMany($skill_types);
            $user->industries()->delete();
            $user->industries()->createMany($industries);
            $user->salary_brackets()->delete();
            $user->salary_brackets()->createMany($salary_brackets);
            $user->addresses()->delete();
            $user->addresses()->createMany($addresses);
            return response()->json(['message' => 'User updated successfully.'], 200);
        });
    }
}
