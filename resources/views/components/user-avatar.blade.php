@props(['user', 'size' => 'w-12 h-12'])

@if ($user->image)
    <img src="{{ $user->UserimageURL() }}" alt="{{ $user->name }}" class="{{ $size }} rounded-full">
@else
    <img src="{{ asset('images/default-post-image.png') }}" alt="Default Post Image" class="{{ $size }} rounded-full">
@endif
