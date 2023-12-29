<?php

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    public function creating(Article $article){
        $article->sort = Article::max('sort')+1;
    }
}
