<?php

use Illuminate\Database\Seeder;

use App\Thread;
use App\Reply;

class ThreadsTableSeeder extends Seeder
{
    public function run()
    {
        $threads = factory(Thread::class, 50)->create();

        $threads->each(function ($thread) {
            factory(Reply::class, 10)->create(['thread_id' => $thread->id]);
        });
    }
}
