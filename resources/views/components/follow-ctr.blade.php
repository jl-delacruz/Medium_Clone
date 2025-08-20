@props(['user'])

<div {{ $attributes }}
    x-data="{ isFollowing: {{ $user->isFollowedBy(auth()->user()) ? 'true' : 'false' }},
                            followToggle() {
                                
                                axios.post('/follow/{{ $user->id }}')
                                    .then(response => {
                                        this.isFollowing = !this.isFollowing;
                                        this.followerCount = response.data.count; // Update follower count
                                    })
                                    .catch(error => {
                                        console.error('Error following/unfollowing:', error);
                                        this.isFollowing = !this.isFollowing; // revert the change on error
                                    });
                            },
                            getFollowerCount() {
                                return {{ $user->followers()->count() }};
                            }

                        }"
    class="w-[320px] border-l px-8">
    {{ $slot }}
</div>