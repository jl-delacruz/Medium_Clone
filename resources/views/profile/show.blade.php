<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex">
                    <div class="flex-1 pr-8">
                        <h1 class="text-5xl font-bold">{{ $user->name }}</h1>


                        <div class="p-4 text-gray-900 mt-8">
                            @forelse ($posts as $p)
                            <x-post-item :post="$p" />
                            @empty
                            <div class="p-4 text-gray-400 text-center py-16">
                                <p>No posts found.</p>
                            </div>
                            @endforelse
                        </div>


                    </div>
                    <x-follow-ctr :user="$user">
                        <x-user-avatar :user="$user" size="w-16 h-16" />
                        <h3>{{ $user->name }}</h3>
                        <p class="text-gray-500"><span x-text="getFollowerCount()"></span> followers</p>
                        <p>{{ $user->bio }}</p>

                        <!-- show only if you are not the user and current user is auth -->
                        @if (auth()->user() && auth()->user()->id !== $user->id)
                        <div class="mt-8">
                            <button class="bg-blue-500 rounded-full px-4 py-2 text-white"
                                x-text="isFollowing ? 'Unfollow' : 'Follow'"
                                @click="followToggle()"
                                :class="isFollowing ? 'bg-red-500' : 'bg-blue-500'">
                            </button>
                        </div>
                        @endif
                    </x-follow-ctr>
                
                </div>
            </div>
        </div>
    </div>
</x-app-layout>