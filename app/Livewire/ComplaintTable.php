<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Complaint;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Livewire\Attributes\Lazy;
use Illuminate\Database\Eloquent\Builder;

#[Lazy]
class ComplaintTable extends LivewireTableComponent
{
    public $showButtonOnHeader = false;

    public $showFilterOnHeader = true;

    public $paginationIsEnabled = true;

    public $buttonComponent = 'complaints.add-button';

    public $FilterComponent = ['complaints.filter-button', Complaint::STATUS_ARR];

    protected $model = Complaint::class;

    public $statusFilter = '';

    protected $listeners = ['refresh' => '$refresh',
        'resetPage',
        'changeStatusFilter'];

    public function changeStatusFilter($value)
    {
        $this->statusFilter = $value;
        $this->resetPage();
    }
    public function placeholder()
    {
        return view('livewire.skeleton_files.common_skeleton');
    }

    public function configure(): void
{
    $this->setPrimaryKey('id')
        ->setDefaultSort('created_at', 'desc')
        ->setQueryStringStatus(false);

    if (auth()->user()->hasRole('Patient')) {
        $this->showButtonOnHeader = true;
    }

    $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {

        if (auth()->user()->hasRole('Patient') && $columnIndex == '1') {
            return [
                'style' => 'width:70%; ',
            ];
        }

        if (!auth()->user()->hasRole('Patient') && $columnIndex == '2') {
            
            return [
                'style' => 'width:70%; ',
            ];
        }

        if ($columnIndex == '2') {
            return [
                'class' => 'text-left',
                'width' => '8%',
            ];
        }

        if (auth()->user()->hasRole('Patient') && $columnIndex == '3') {
            return [
                'class' => 'text-left',
                'width' => '8%',
            ];
        }

        return [
            'class' => 'text-left',
        ];
    });
}

    public function columns(): array
    {
        return  [
            Column::make(__('messages.complaints.title'), 'title')
                ->view('complaints.columns.title')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.user.user'), 'patient_id')
                ->view('complaints.columns.patient')
                ->hideIf(auth()->user()->hasRole('Patient')),
            Column::make(__('messages.complaints.description'), 'description')
                ->view('complaints.columns.description')
                ->sortable()
                ->searchable(), 
            Column::make(__('messages.complaints.status'), 'status')
                ->view('complaints.columns.status'),
            Column::make(__('messages.common.action'), 'id')    
                ->view('complaints.action')
                // ->hideIf(!auth()->user()->hasRole('Patient'))

        ];
    }
    public function builder(): Builder
    {
        $query = Complaint::query()->with('patient');
    
        $user = auth()->user();

        if ($user->hasRole('Patient')) {
            $query->where('patient_id', $user->id);
        }

        if ($this->statusFilter !== '' && $this->statusFilter !== null) {
            $query->where('status', $this->statusFilter);
        }
    
        return $query;
    }

}
