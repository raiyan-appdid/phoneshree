<?php

namespace App\DataTables;

use App\Models\FeaturedProduct;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FeaturedProductDataTable extends DataTable
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
            //     $edit_route = route('admin.featuredproducts.edit', $value->id);
            //     $edit_callback = 'setValue';
            //     $modal = '#edit-featuredproduct-modal';
            //     $delete_route = route('admin.featuredproducts.destroy', $value->id);
            //     return view('content.table-component.action', compact('edit_route', 'delete_route', 'edit_callback', 'modal'));
            // })
            ->editColumn('created_at', function ($data) {
                return '<span class="badge badge-light-primary">' . date("M jS, Y h:i A", strtotime($data->created_at)) . '</span>';
            })
            ->editColumn('featured_product_transaction_id', function ($data) {
                return $data->featuredProductTransaction->seller->name ?? '';
            })
            ->editColumn('product_id', function ($data) {
                return $data->featuredProductTransaction->product->product_title ?? '';
            })
            ->editColumn('city_id', function ($data) {
                return $data->city->name;
            })
            ->addColumn('status', function ($data) {
                $route = route('admin.featured-product.status');
                return view('content.table-component.switch', compact('data', 'route'));
            })
            ->escapeColumns('created_at', 'action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\FeaturedProduct $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(FeaturedProduct $model)
    {
        $model = $model->newQuery();
        $model->with(['featuredProductTransaction' => fn($q) => $q->with(['seller', 'product'])]);
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
            ->setTableId('featuredproduct-table')
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
            Column::make('city_id')
                ->title('city'),
            Column::make('featured_product_transaction_id')
                ->title('seller'),
            Column::make('product_id')
                ->title('product title'),
            Column::make('expiry_date'),
            Column::make('created_at'),
            // Column::computed('action')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(60)
            //     ->addClass('text-center'),
            Column::computed('status')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'FeaturedProduct._' . date('YmdHis');
    }
}
