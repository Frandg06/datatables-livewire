<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ArticleTable extends DataTableComponent
{

    public $uc;
    
    public function configure(): void
    {
        // dd($this->uc);
        $this->setPrimaryKey('id')
        ->setTableRowUrl(
            function($row){
                return route('dashboard');
            }
        )
        ->setTableRowUrlTarget(
            function($row){
                return '_blank';
        });

        $this->setDefaultSort('created_at', 'asc');

        $this->setBulkActions([
            'deleteSelected' => 'Eliminar',
        ]);
    }

    public function columns(): array
    {
        return [
            // Column::make("Id", "id")
            //     ->sortable(),
            Column::make("orden", "sort")
                ->sortable()
                ->collapseOnTablet(),

            Column::make("Autor", "user.name")
                ->sortable()
                ->collapseOnTablet(),

            Column::make("Update", "updated_at")
                ->sortable(),

            Column::make("Titulo", "title")
                ->sortable()
                ->searchable(),

            BooleanColumn::make("Activo", "sw_active")
                // ->setSuccessValue('false')
                ->sortable()
                ->collapseOnTablet(),

            ImageColumn::make('Imagen')
                ->location(fn()=> 'https://www.reasonwhy.es/media/cache/destacada/nuevo_icono_youtube2_-_reasonwhy_1.png')
                ->collapseOnTablet(),

            Column::make("Created at", "created_at")
                ->format(
                    fn($value, $row, Column $column) => date('d/m/Y', strtotime($row->created_at))
                )
                ->sortable(),

            Column::make('AccionesHtml', 'id')
                ->format(fn($value)=> view('articles.tables.action', 
                [
                    'id' =>$value,
                ]
                ))
                ->collapseOnTablet()
                ->unclickable(),

            ];
    }
    public function builder(): Builder
    {
        return Article::query()
        // ->where('articles.id', $this->uc)
        ->with('user');
    }

    public function deleteSelected() {
        if($this->getSelected()){
            $articles = Article::whereIn('id', $this->getSelected())->delete();
            $this->clearSelected();

        }else{
            $this->emit('error', 'No hay nada seleccionado');
        }
    }








    public function filters(): array
    {
        return [
            SelectFilter::make('Publicado')
                ->options([
                    ''=> 'Todos',
                    '1'=> 'Si',
                    '0'=> 'No',
                ])
                ->filter(function($query,$value){
                    if($value != ''){
                        // dd('holas');
                        $query->where('sw_active', $value);
                    }
                }),
            DateFilter::make('Desde')
                ->filter(function($query , $value){
                    $query->whereDate('articles.created_at', '>=', $value);
                }),


        ];
    }
}
