<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;

class DeleteInactivePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-inactive-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete inactive posts that have not received a comment in 1 year';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $thresholdDate = Carbon::now()->subYear();
        
        // Fetch posts without comments older than one year and soft-delete them
        $postsToDelete = Post::whereDoesntHave('comments')
                            ->where('created_at', '<=', $thresholdDate)
                            ->get();

        foreach ($postsToDelete as $post) {
            $post->delete();
            $this->info("Post $post->id soft-deleted.");
        }

        $this->info('Inactive posts soft-deleted successfully.');
    }
}
