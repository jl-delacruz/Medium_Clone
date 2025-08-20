<x-app-layout>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('post.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- @dump($errors) -->

                    @if ($post->imageUrl())
                    <div class="mb-8">
                        <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="w-full">
                    </div>
                    @endif
                    <div class="p-4 text-gray-900">
                        <!-- Image -->
                        <div>
                            <x-input-label for="image" :value="__('Image')" />
                            <input
                                id="image"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                type="file"
                                name="image"
                                accept="image/*" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />

                        </div>

                        <!-- Title -->
                        <div class="mt-4">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title', $post->title)" />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="" disabled selected>Select a category</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>

                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />

                        </div>

                        <!-- Content -->
                        <div class="mt-4">
                            <x-input-label for="content" :value="__('Content')" />
                            <x-textarea-input id="content" class="block mt-1 w-full h-48" name="content"
                                :value="old('content', $post->content)" />
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <x-primary-button class='mt-4'>
                            Submit
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>