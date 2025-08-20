<x-app-layout>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Nav -->
                <div class="p-4 text-gray-900">
                    <x-category-tabs>
                        No categories
                    </x-category-tabs>

                </div>
            </div>

            <div class="mt-8">
                <div class="p-4 text-gray-900">
                    @forelse ($posts as $p)
                    <x-post-item :post="$p" />
                    @empty
                    <div class="p-4 text-gray-400 text-center py-16">
                        <p>No posts found.</p>
                    </div>
                    @endforelse
                </div>

                {{ $posts->links() }}

            </div>

        </div>
    </div>
</x-app-layout>