<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Throwable;

class ActivityReport extends Component
{
    public string $year = '';

    public function mount()
    {
        $this->year = now()->year;
    }

    public function rules()
    {
        return [
            'year' => ['required', 'date_format:Y', 'exists:meta_data,year'],
        ];
    }

    public function resetInput()
    {
        $this->year = '';
        $this->resetErrorBag();
    }

    public function download()
    {
        $this->validate();

        try {
            $year = $this->year;
            $this->resetInput();

            return redirect()->route('reports.activities.download', ['year' => $year]);
        } catch (Throwable $th) {
            logger()->error($th->getMessage(), ['ActivityReport' => 'download']);
            session()->flash('activity-failed', 'Failed generating report');
        }
    }

    public function render()
    {
        return view('livewire.report.activity');
    }
}
