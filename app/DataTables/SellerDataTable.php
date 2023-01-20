<?php

namespace App\DataTables;

use App\Models\Seller;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SellerDataTable extends DataTable
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
            ->addColumn('action', function ($value) {
                $edit_route = route('admin.sellers.edit', $value->id);
                $edit_callback = 'setValue';
                $modal = '#edit-seller-modal';
                $delete_route = route('admin.sellers.destroy', $value->id);
                return view('content.table-component.action', compact('edit_route', 'delete_route', 'edit_callback', 'modal'));
            })

            ->editColumn('shop_image', function ($data) {
                if ($data->shop_image != "N/A") {
                    $no_image = 'images\placeholder.jpg';
                    $image = ($data->shop_image) ? $data->shop_image : $no_image;
                    return view('content.table-component.avatar', compact('image'));
                } else {
                    return $data->shop_image;
                }
            })

            ->editColumn('created_at', function ($data) {
                return '<span class="badge badge-light-info">' . date("M jS, Y h:i A", strtotime($data->created_at)) . '</span>';
            })
        //     ->addColumn('status', function ($data) {
        //     $route = route('admin.sellers.status');
        //     return view('content.table-component.switch', compact('data', 'route'));
        // })
            ->escapeColumns('created_at', 'action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Seller $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Seller $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('seller-table')
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
            Column::make('number'),
            Column::make('email'),
            Column::make('shop_name'),
            Column::make('short_description'),
            Column::make('shop_image'),
            Column::make('address'),
            Column::make('membership_expiry_date'),
            Column::make('created_at'),
            // Column::computed('status')
            //     ->exportable(false)
            //     ->printable(false)
            //     ->width(60)
            //     ->addClass('text-center'),
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
        return 'Seller._' . date('YmdHis');
    }
}
