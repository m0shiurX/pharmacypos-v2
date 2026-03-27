<?php

namespace App\Providers;

use App\Events\TransactionPaymentAdded;
use App\Events\TransactionPaymentDeleted;
use App\Events\TransactionPaymentUpdated;
use App\Listeners\AddAccountTransaction;
use App\Listeners\DeleteAccountTransaction;
use App\Listeners\UpdateAccountTransaction;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 'App\Events\Event' => [
        //     'App\Listeners\EventListener',
        // ],
        TransactionPaymentAdded::class => [
            AddAccountTransaction::class,
        ],

        TransactionPaymentUpdated::class => [
            UpdateAccountTransaction::class,
        ],

        TransactionPaymentDeleted::class => [
            DeleteAccountTransaction::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
