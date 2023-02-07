<?php

namespace App\DataTables;

use App\Models\ActiveBannerAds;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ActiveBannerAdsDataTable extends DataTable
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
            //     $edit_route = route('admin.activebanneradss.edit', $value->id);
            //     $edit_callback = 'setValue';
            //     $modal = '#edit-activebannerads-modal';
            //     $delete_route = route('admin.activebanneradss.destroy', $value->id);
            //     return view('content.table-component.action', compact('edit_route', 'delete_route', 'edit_callback', 'modal'));
            // })
            ->editColumn('created_at', function ($data) {
                return '<span class="badge badge-light-primary">' . date("M jS, Y h:i A", strtotime($data->created_at)) . '</span>';
            })
            ->editColumn('banner_ads_transaction_id', function ($data) {
                return $data->bannerAdsTransaction->seller->name;
            })
            ->editColumn('city_id', function ($data) {
                return $data->city->name;
            })
            ->editColumn('image', function ($data) {
                if ($data->image != "N/A") {
                    $no_image = 'images\placeholder.jpg';
                    $image = ($data->image) ? $data->image : $no_image;
                    return view('content.table-component.avatar', compact('image'));
                } else {
                    return $data->image;
                }
            })
                ->addColumn('status', function ($data) {
                $route = route('admin.banner-ads.status');
                return view('content.table-component.switch', compact('data', 'route'));
            })
            ->escapeColumns('created_at', 'action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ActiveBannerAds $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ActiveBannerAds $model)
    {
        $model = $model->newQuery();
        $model->with(['bannerAdsTransaction.seller']);
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
            ->setTableId('activebannerads-table')
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
            Column::make('banner_ads_transaction_id')
                ->title('seller'),
            Column::make('image'),
            Column::make('city_id')
            ->title('city'),
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
        return 'ActiveBannerAds._' . date('YmdHis');
    }
}
