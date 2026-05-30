<?php

namespace Acme\CmsDashboard\Console\Commands;

use Illuminate\Console\Command;
use Acme\CmsDashboard\Models\Post;
use Illuminate\Support\Carbon;

class PublishScheduledPosts extends Command
{
    protected $signature = 'lazy:publish-scheduled';
    protected $description = 'Publish posts whose scheduled publish time has arrived.';

    public function handle(): void
    {
        $posts = Post::where('status', 'scheduled')
            ->where('published_at', '<=', Carbon::now())
            ->get();

        if ($posts->isEmpty()) {
            return;
        }

        foreach ($posts as $post) {
            $post->update(['status' => 'published']);
            lazy_log_activity('updated', "Auto-published scheduled post: {$post->title}", $post);
        }

        $this->info("Published {$posts->count()} scheduled post(s).");
    }
}
