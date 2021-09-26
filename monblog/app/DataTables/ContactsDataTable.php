<?php

namespace App\DataTables;

use App\Models\Contact;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContactsDataTable extends DataTable
{
    use DataTableTrait;

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', function ($contact) {
                return formatDate($contact->created_at) . __(' at ') . formatHour($contact->created_at);
            })
            ->editColumn('name', function ($contact) {
                return $contact->name . ($contact->user_id ? $this->badge('User', 'primary', 2) : '');
            })
            ->editColumn('email', function ($contact) {
                return '<a href = "mailto: ' . $contact->email . '">' . $contact->email . '</a>';
            })
            ->editColumn('action', function ($contact) {
                return $this->button('contacts.destroy', $contact->id, 'danger', __('Delete'), 'trash-alt', __('Really delete this contact?'));
            })
            ->rawColumns(['name', 'email', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Contact $contact
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Contact $contact)
    {
        $query = $contact->newQuery();
        
        if (\Route::currentRouteNamed('contacts.indexnew')) {
            $query->has('unreadNotifications');
        }

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('contacts-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Blfrtip')
            ->lengthMenu();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('name')->title(__('Name')),
            Column::make('email')->title(__('Email')),
            Column::make('message')->title(__('Message')),
            Column::make('created_at')->title(__('Date')),
            Column::computed('action')->title(__('Action'))->addClass('align-middle text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Contacts_' . date('YmdHis');
    }
}
