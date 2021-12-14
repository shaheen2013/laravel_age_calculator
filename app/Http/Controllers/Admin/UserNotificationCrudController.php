<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserNotificationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Carbon\Carbon;

/**
 * Class UserNotificationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserNotificationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\UserNotification::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user-notification');
        CRUD::setEntityNameStrings('user notification', 'user notifications');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('client_name');
        CRUD::column('birthday');
        //CRUD::column('age');
        CRUD::column('marital_status');
        /*CRUD::column('created_at');
        CRUD::column('updated_at');*/

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserNotificationRequest::class);

        //CRUD::field('id');
        CRUD::field('client_name');
        CRUD::field('birthday')->type('date');
        //CRUD::field('age')->type('number');
        CRUD::addField([
            'name'        => 'marital_status',
            'type'        => 'select_from_array',
            'options'     => [
                'single'   => 'single',
                'married'  => 'married',
                'divorced' => 'divorced',
                'widowed'  => 'widowed',
            ],
            'allows_null' => false,
            'default'     => 'one',
        ]);
        /*CRUD::field('created_at');
        CRUD::field('updated_at');*/

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupShowOperation()
    {
        $this->setupCreateOperation();
        CRUD::addColumn([
            'name'     => 'output',
            'label'    => 'Output',
            'type'     => 'closure',
            'function' => function ($entry) {
                $age = Carbon::parse($entry->birthday)->diff(Carbon::now())->format('%y');
                return "Hello, my name is {$entry->client_name}. I am {$age} years old. I am {$entry->marital_status}.";
            },
        ]);
    }
}
