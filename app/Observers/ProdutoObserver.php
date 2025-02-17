<?php

namespace App\Observers;

use App\Models\Produto;
use App\Http\Controllers\ProductNotificationController;

class ProdutoObserver
{
    /**
     * Handle the Produto "created" event.
     */
    public function created(Produto $produto): void
    {
        //
    }

    /**
     * Handle the Produto "updated" event.
     */
    public function updated(Produto $produto): void
    {
        if ($produto->getOriginal('quantidade') == 0 && $produto->quantidade > 0) {
            app(ProductNotificationController::class)->notifyUsers($produto->id);
        }
    }

    /**
     * Handle the Produto "deleted" event.
     */
    public function deleted(Produto $produto): void
    {
        //
    }

    /**
     * Handle the Produto "restored" event.
     */
    public function restored(Produto $produto): void
    {
        //
    }

    /**
     * Handle the Produto "force deleted" event.
     */
    public function forceDeleted(Produto $produto): void
    {
        //
    }
}
