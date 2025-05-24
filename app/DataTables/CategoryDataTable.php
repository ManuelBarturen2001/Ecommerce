<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($query){
                $editBtn = "<a href='".route('admin.category.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='".route('admin.category.destroy', $query->id)."' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";

                return $editBtn.$deleteBtn;
            })
            ->addColumn('icon', function($query){
                return '<i style="font-size:40px" class="'.$query->icon.'"></i>';
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
            ->rawColumns(['icon', 'action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('category-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0)
                    ->selectStyleSingle()
                    //->dom('Bfrtip')
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
                            // Puedes poner la URL a tu archivo local de idioma español o usar CDN oficial
                            'url' => asset('backend/assets/json/es-MX.json'),
                            // 'url' => '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',
                        ],
                        'responsive' => true,
                        'autoWidth'   => false,
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID')->width(100),
            Column::make('icon')->title('Ícono')->width(300),
            Column::make('name')->title('Nombre'),
            Column::make('status')->title('Estado')->width(200),
            Column::computed('action')
                ->title('Acción')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];

    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Category_' . date('YmdHis');
    }
}
