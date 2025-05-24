<?php

namespace App\DataTables;

use App\Models\AdminReview;
use App\Models\ProductReview;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AdminReviewDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('product', function($query){
            return "<a href='".route('product-detail', $query->product->slug)."' > ".$query->product->name."</a>";
        })
        ->addColumn('user', function($query){
            return $query->user->name;
        })
        ->addColumn('status', function($query){
            if($query->status == 1){
                $button = '<label class="custom-switch mt-2">
                    <input type="checkbox" checked name="custom-switch-checkbox" data-id="'.$query->id.'" class="custom-switch-input change-status" >
                    <span class="custom-switch-indicator"></span>
                </label>';
            }else {
                $button = '<label class="custom-switch mt-2">
                    <input type="checkbox" name="custom-switch-checkbox" data-id="'.$query->id.'" class="custom-switch-input change-status">
                    <span class="custom-switch-indicator"></span>
                </label>';
            }
            return $button;
        })
        ->rawColumns(['product', 'status'])
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductReview $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('adminreview-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        // Botones traducidos manualmente 👇
                        Button::make('excel')->text('Excel'),
                        Button::make('csv')->text('CSV'),
                        Button::make('pdf')->text('PDF'),
                        Button::make('print')->text('Imprimir'),
                        Button::make('pageLength')->text('Mostrar'), // ✅ Botón válido extra
                        Button::raw([
                                        'text' => 'Recargar',
                                        'action' => 'function ( e, dt, node, config ) {
                                            dt.ajax.reload();
                                        }'
                                    ])
                    ])
                    ->parameters([
                        'language' => [
                            'url' => asset('backend/assets/json/es-MX.json'), // Ruta a tu archivo de traducción español
                        ],
                        'responsive' => true,
                        'autoWidth' => false,
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('product')->title('Producto'),
            Column::make('user')->title('Usuario'),
            Column::make('rating')->title('Calificación'),
            Column::make('review')->title('Reseña'),
            Column::make('status')->title('Estado'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'AdminReview_' . date('YmdHis');
    }
}
