<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Get validated data without profile_picture
        $validated = $request->validated();
        unset($validated['profile_picture']); // Remove profile_picture from validated data

        // Handle profile picture upload separately
        if ($request->hasFile('profile_picture')) {
            try {
                // Define storge path (outside storage directory)
                $storgePath = base_path('storge');

                Log::info('Profile picture upload started', [
                    'user_id' => $user->id,
                    'storge_path' => $storgePath,
                    'file_exists' => $request->file('profile_picture')->isValid()
                ]);

                // Delete old profile picture if exists
                if ($user->profile_picture && file_exists($storgePath . '/' . $user->profile_picture)) {
                    unlink($storgePath . '/' . $user->profile_picture);
                    Log::info('Old profile picture deleted: ' . $user->profile_picture);
                }

                // Generate unique filename with timestamp
                $file = $request->file('profile_picture');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Move file to storge directory
                $file->move($storgePath, $filename);

                Log::info('New profile picture saved: ' . $filename);

                // Store only filename in database
                $user->profile_picture = $filename;

            } catch (\Exception $e) {
                Log::error('Profile picture upload failed: ' . $e->getMessage());
                return Redirect::route('profile.edit')->with('error', 'Failed to upload profile picture: ' . $e->getMessage());
            }
        }

        // Fill user with validated data (without profile_picture)
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
