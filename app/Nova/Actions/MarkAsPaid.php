<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

/**
 * HOW NOVA ACTIONS WORK:
 *
 * 1. User selects records on the index page (checkboxes)
 * 2. User clicks "Actions" dropdown → selects "Mark as Paid"
 * 3. Nova shows a confirmation modal (with any fields from fields() method)
 * 4. User clicks "Run Action"
 * 5. Nova calls handle() with the selected models
 * 6. handle() does the work and returns a response (message, redirect, etc.)
 *
 * Key concepts:
 *   - $models = Collection of selected Eloquent models
 *   - $fields = Values from the action's form (if fields() returns any)
 *   - Return Action::message() to show a success toast
 *   - Return Action::danger() to show an error toast
 */
class MarkAsPaid extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The display name of the action.
     * This appears in the "Actions" dropdown menu.
     */
    public $name = 'Mark as Paid';

    /**
     * Perform the action on the given models.
     *
     * @param  ActionFields  $fields   Values from the action's form fields
     * @param  Collection    $models   The selected Order models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        // Loop through each selected order and update its status
        $count = 0;
        foreach ($models as $order) {
            // Only update orders that aren't already paid
            if ($order->status !== 'paid') {
                $order->update(['status' => 'paid']);
                $count++;
            }
        }

        // Return a success message — this shows as a green toast notification
        return Action::message("{$count} order(s) marked as paid!");
    }

    /**
     * Get the fields available on the action.
     *
     * These fields appear in the confirmation modal BEFORE the action runs.
     * For this simple action, we don't need any — just confirm and go.
     * But you COULD add fields like a "Reason" text field, a date picker, etc.
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
