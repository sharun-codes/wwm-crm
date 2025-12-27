<?php

namespace App\Filament\Resources\Deals\Pages;

use App\Filament\Resources\Deals\DealResource;
use Filament\Resources\Pages\Page;

use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Models\LeadContact;
use App\Services\DealService;
use App\Exceptions\CrmException;
use Filament\Notifications\Notification;
use App\Exceptions\DealStageBlockedException;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class DealKanban extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = DealResource::class;

    protected string $view = 'filament.resources.deals.pages.kanban';

    public ?int $pipelineId = null;

    public bool $showAddContactModal = false;
    
    public ?int $pendingDealId = null;

    public ?array $formData = [];

    protected function getFormSchema(): array
    {
        return $this->getAddContactForm();
    }

    protected function getFormStatePath(): string
    {
        return 'formData';
    }


    public function mount(): void
    {
        abort_unless(
            auth()->user()->can('deals.view'),
            403
        );

        $this->pipelineId ??= Pipeline::where('slug', 'sales')->value('id');
    }

    public function getStagesProperty()
    {
        return PipelineStage::where('pipeline_id', $this->pipelineId)
            ->orderBy('sort_order')
            ->get();
    }

    public function getDealsByStageProperty()
    {
        return Deal::with('lead', 'client')
            ->where('pipeline_id', $this->pipelineId)
            ->get()
            ->groupBy('pipeline_stage_id');
    }

    public function moveDeal(int $dealId, int $stageId): void
    {
        abort_unless(
            auth()->user()->can('deals.move_stage'),
            403
        );
    
        try {
            $deal = Deal::findOrFail($dealId);
            $stage = PipelineStage::findOrFail($stageId);

            app(DealService::class)->moveToStage($deal, $stage);

            Notification::make()
                ->title('Deal moved')
                ->success()
                ->send();

        } catch (DealStageBlockedException $e) {
            
            Notification::make()
            ->title('Cannot move deal')
            ->body($e->reason)
            ->danger()
            ->send();

            match ($e->action) {
                'add_contact'   => $this->openAddContactModal($dealId),
                'edit_value'    => $this->highlightDealValue($dealId),
                'add_activity'  => $this->openAddActivityModal($dealId),
                default         => null,
            };
    
            return;
        }
    }

    protected function highlightDealValue(int $dealId): void
    {
        Notification::make()
            ->title('Update deal value')
            ->body('Please edit the deal and enter a valid value.')
            ->warning()
            ->send();

            $this->redirect(
                route('filament.admin.resources.deals.edit', $dealId)
            );
    }

    protected function openAddContactModal(int $dealId): void
    {
        $this->pendingDealId = $dealId;
        $this->dispatch('open-modal', id: 'add-contact-modal');
    }

    protected function getAddContactForm(): array
    {
        return [
            TextInput::make('name')
                ->required(),

            TextInput::make('designation'),

            TextInput::make('email')
                ->email(),

            TextInput::make('mobile'),

            Toggle::make('is_primary')
                ->label('Primary Contact')
                ->default(true)
                ->required(),
        ];
    }

    public function saveContact(): void
    {
        $data = $this->form->getState();

        $deal = Deal::findOrFail($this->pendingDealId);
        $lead = $deal->lead;

        // Ensure only one primary
        if ($data['is_primary']) {
            $lead->contacts()->update(['is_primary' => false]);
        }

        $lead->contacts()->create($data);

        // Close modal
        $this->showAddContactModal = false;

        // Retry stage move
        $wonStage = Stage::where('slug', 'won')->firstOrFail();
        app(DealService::class)->moveToStage($deal, $wonStage);

        // Cleanup
        $this->pendingDealId = null;
    }

}
