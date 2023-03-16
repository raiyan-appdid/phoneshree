<?php

namespace App\DataTables;

use App\Models\SellPhoneByUser;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SellPhoneByUserDataTable extends DataTable
{
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
            // ->addColumn('action', function ($value) {
            //     $edit_route = route('admin.sellphonebyusers.edit', $value->id);
            //     $edit_callback = 'setValue';
            //     $modal = '#edit-sellphonebyuser-modal';
            //     $delete_route = route('admin.sellphonebyusers.destroy', $value->id);
            //     return view('content.table-component.action', compact('edit_route', 'delete_route', 'edit_callback', 'modal'));
            // })
            ->editColumn('created_at', function ($data) {
                return '<span class="badge badge-light-primary">' . date("M jS, Y h:i A", strtotime($data->created_at)) . '</span>';
            })

            //     ->addColumn('status', function ($data) {
            //     $route = route('admin.sellphonebyusers.status');
            //     return view('content.table-component.switch', compact('data', 'route'));
            // })
            ->escapeColumns('created_at', 'action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SellPhoneByUser $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SellPhoneByUser $model)
    {
        $model = $model->newQuery();
        $model->with(['brand', 'city', 'state']);
        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('sellphonebyuser-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0)
            ->searchDelay(1000)
            ->parameters([
                'scrollX' => true, 'paging' => true,
                'searchDelay' => 350,
                'lengthMenu' => [
                    [10, 25, 50, -1],
                    ['10 rows', '25 rows', '50 rows', 'Show all'],
                ],
            ])
            ->buttons(
                Button::make('csv'),
                Button::make('excel'),
                Button::make('print'),
                Button::make('pageLength'),
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('mobile'),
            Column::make('state_id')
                ->title('state')
                ->data('state.name'),
            Column::make('city_id')
                ->title('city')
                ->data('city.name'),
            Column::make('brand_id')
                ->title('Brand')
                ->data('brand.title'),
            Column::make('mobile_name'),
            Column::make('description'),
            Column::make('price'),
            Column::make('expiry_date'),
            Column::make('created_at'),
            Column::make('updated_at'),
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(60)
            //     ->addClass('text-center'),
            // Column::computed('status')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(60)
            //     ->addClass('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'SellPhoneByUser._' . date('YmdHis');
    }
}
