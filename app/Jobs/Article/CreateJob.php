<?php

namespace App\Jobs\Article;

use App\Mail\Article\CreateMail as CreateArticleMail;
use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User|Authenticatable $user, public Article $article)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $log = [
            'user' => [
                'id' => $this->user->id,
                'name' => "{$this->user->first_name} {$this->user->surname}",
                'email' => $this->user->email,
            ],
            'article' => [
                'id' => $this->article->id,
                'title' => $this->article->title,
                'status' => $this->article->status,
            ]
        ];
        try {
            Mail::to($this->user->email)->send(new CreateArticleMail());

            Log::channel('article_create')->info(
                'Сообщение о создании поста отправлено пользователю',
                $log
            );
        } catch (\Exception $e) {
            Log::channel('article_create')->error(
                'Ошибка при отправке сообщения о создании поста пользователю',
                array_merge($log, ['error' => $e->getMessage(), 'stack' => $e->getTraceAsString()])
            );
        }
    }
}
