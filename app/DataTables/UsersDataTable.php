<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
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
            ->editColumn('valid', function ($user) {
                return $user->valid ? '<i class="fas fa-check"></i>' : '';
            })
            ->editColumn('created_at', function ($user) {
                return formatDate($user->created_at);
            })
            ->editColumn('updated_at', function ($user) {
                return formatDate($user->updated_at);
            })
            ->addColumn('action', function ($user) {
                $btn = $this->button('users.edit', $user->id, 'warning', __('Edit'), 'edit');
                $btn .= $this->button('users.destroy', $user->id, 'danger', __('Delete'), 'trash-alt', __('Really delete this user?'));
                return $btn;
            })
            ->rawColumns(['valid', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $user)
    {
        if (\Route::currentRouteNamed('users.indexnew')) {
            return $user->has('unreadNotifications');
        }

        return $user->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
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
            Column::make('role')->title(__('Role')),
            Column::make('created_at')->title('Creation'),
            Column::make('updated_at')->title('Modification'),
            Column::make('valid')->title(__('Valid'))->addClass('align-middle text-center'),
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
        return 'Users_' . date('YmdHis');
    }
}
