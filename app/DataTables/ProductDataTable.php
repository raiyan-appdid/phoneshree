<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
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
            //     $edit_route = route('admin.products.edit', $value->id);
            //     $edit_callback = 'setValue';
            //     $modal = '#edit-product-modal';
            //     $delete_route = route('admin.products.destroy', $value->id);
            //     return view('content.table-component.action', compact('edit_route', 'delete_route', 'edit_callback', 'modal'));
            // })
            ->editColumn('created_at', function ($data) {
                return '<span class="badge badge-light-primary">' . date("M jS, Y h:i A", strtotime($data->created_at)) . '</span>';
            })
            ->editColumn('seller_id', function ($data) {
                return $data->seller;
            })
            //     ->addColumn('status', function ($data) {
            //     $route = route('admin.products.status');
            //     return view('content.table-component.switch', compact('data', 'route'));
            // })
            ->editColumn('status', function ($data) {
                if ($data->status == "livesell") {
                    return '<span class="badge badge-light-success">' . $data->status . '</span>';
                }
                if ($data->status == "sold") {
                    return '<span class="badge badge-light-danger">' . $data->status . '</span>';
                }
                if ($data->status == "inventory") {
                    return '<span class="badge badge-light-secondary">' . $data->status . '</span>';
                }
            })

            ->escapeColumns('created_at', 'action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        $model = $model->newQuery();
        $model->with(['seller']);
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
            ->setTableId('product-table')
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
            Column::make('seller_id')
                ->title('seller')
                ->data('seller.name'),
            Column::make('product_title'),
            Column::make('product_selling_price'),
            Column::make('status'),
            Column::make('created_at'),
            // Column::computed('action')
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
        return 'Product._' . date('YmdHis');
    }
}
