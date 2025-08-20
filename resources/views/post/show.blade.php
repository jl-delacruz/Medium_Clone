<x-app-layout>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div
                    x-data="{ hasClapped: {{ auth()->check() && auth()->user()->hasClapped($post) ? 'true' : 'false' }}, 
                count: {{ $post->claps()->count() }} },
                clap() {
                    axios.post('/clap/{{ $post->id }}')
                        .then(response => {
                            this.count = response.data.count;
                            this.hasClapped = response.data.hasClapped;
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }"
                    class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
                    <div class="mb-4 flex gap-4">
                        <!-- User Avatar -->
                        <x-user-avatar :user="$post->user" />
                        <div>
                            <!-- User Name -->
                            <x-follow-ctr :user="$post->user" class="flex gap-2">
                                <a href="{{ route('profile.show', $post->user) }}" class="hover:underline">{{ $post->user->name }}</a>

                                @auth
                                @if(auth()->id() !== $post->user->id)
                                &middot;
                                <button class="text-blue-500 hover:underline"
                                    x-text="isFollowing ? 'Unfollow' : 'Follow'"
                                    @click="followToggle()"
                                    :class="isFollowing ? 'text-red-500' : 'text-blue-500'">
                                </button>
                                @endif
                                @endauth

                                </button>
                            </x-follow-ctr>

                            <div class="flex gap-2 text-gray-500 text-sm">
                                {{ $post->readTime() }} min read
                                &middot;
                                {{ $post->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>

                    <!-- if current authenticated id === user ID of the post -->
                    @if (Auth::id() === $post->user_id)
                    <div class="flex gap-2 mt-2 py-2 border-b border-gray-200">
                        <x-primary-button href="{{ route('post.edit', $post->slug) }}">
                            Edit
                        </x-primary-button>

                        <form action="{{ route('post.destroy', $post) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>
                                Delete
                            </x-danger-button>
                        </form>
                    </div>
                    @endif
                    <!-- Clap Buttons1 -->
                    <x-clap-button :post="$post" />

                    <!-- Content Section -->
                    <div class="mt-8">
                        <img src="{{ $post->imageURL() }}" alt="{{ $post->title }}" class="w-full h-auto">
                        <div>
                            <p class="mt-4 text-gray-700">{{ $post->content }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <span class="px-4 py-2 bg-gray-200 rounded-xl text-sm">
                            {{ $post->category->name }}
                        </span>
                    </div>

                    <!-- Clap Buttons2 -->
                    <x-clap-button :post="$post" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>