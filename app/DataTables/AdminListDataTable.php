<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AdminListDataTable extends DataTable
{
    /**
     * Construye el DataTable.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            // ───── ACCIÓN (borrar) ───────────────────────────────────────────────
            ->addColumn('action', function ($query) {
                if ($query->id !== 1) {
                    return "<a href='" . route('admin.admin-list.destory', $query->id) . "' 
                               class='btn btn-danger ml-2 delete-item'>
                               <i class='far fa-trash-alt'></i>
                            </a>";
                }
            })
            // ───── STATUS (switch) ──────────────────────────────────────────────
            ->addColumn('status', function ($query) {
                if ($query->id !== 1) {
                    $checked = $query->status === 'active' ? 'checked' : '';
                    return '<label class="custom-switch mt-2">
                                <input type="checkbox" ' . $checked . '
                                       name="custom-switch-checkbox"
                                       data-id="' . $query->id . '"
                                       class="custom-switch-input change-status">
                                <span class="custom-switch-indicator"></span>
                            </label>';
                }
            })
            // ───── ROL (traducido) ──────────────────────────────────────────────
            ->editColumn('role', function ($query) {                       // 👈 Nuevo
                return $query->role === 'admin' ? 'Administrador' : $query->role;
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Fuente de la consulta.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->where('role', 'admin')->newQuery();
    }

    /**
     * Configuración del Builder de DataTables.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('adminlist-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->selectStyleSingle()
            //->dom('Bfrtip')  si quieres mostrar botones y filtros ordenadamente
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
                                   // 👈 Nuevo
                'language' => [
                    // CDN oficial de DataTables (es-ES):
                    'url' => asset('backend/assets/json/es-MX.json'),
                ],
                // Otras opciones habituales (opcionales):
                'responsive' => true,
                'autoWidth'   => false,
            ]);
    }

    /**
     * Columnas.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('name')->title('Nombre'),
            Column::make('email')->title('Correo'),
            Column::make('role')->title('Rol'),          // título ya en ES 👈
            Column::make('status')->title('Estado'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->title('Acción'),                       // 👈 Nuevo
        ];
    }

    /**
     * Nombre del archivo de exportación.
     */
    protected function filename(): string
    {
        return 'AdminList_' . date('YmdHis');
    }
}