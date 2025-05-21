<?php

namespace App\DataTables;

use App\Models\ShippingRateDistance;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ShippingRateDistanceDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('shipping_rule', function ($query) {
                // Suponiendo que deseas mostrar el nombre de la regla de envío
                return $query->shippingRule ? $query->shippingRule->name : 'Sin regla'; // Asegúrate de que 'name' sea el atributo que quieres mostrar
            })
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.shipping-rate-distance.edit', $query->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.shipping-rate-distance.destroy', $query->id) . "' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->addColumn('status', function ($query) {
                return '
                    <label class="custom-switch mt-2">
                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input change-status"
                        data-id="' . $query->id . '" ' . ($query->status ? 'checked' : '') . '>
                        <span class="custom-switch-indicator"></span>
                    </label>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }


    public function query(ShippingRateDistance $model): QueryBuilder
    {
        return $model->newQuery()->with('shippingRule');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('shipping-rate-distance-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->width(50),
            Column::make('shipping_rule')->title('Regla de Envío')->width(150), // Nueva columna para la regla de envío
            Column::make('min_km')->title('Mínima distancia (km)'),
            Column::make('max_km')->title('Máxima distancia (km)'),
            Column::make('price')->title('Precio'),
            Column::make('status')->width(100)->title('Estado'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }


    protected function filename(): string
    {
        return 'ShippingRateDistance_' . date('YmdHis');
    }
}
