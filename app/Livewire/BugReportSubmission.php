<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\BugReportStatus;
use App\Enums\ComplaintSeverity;
use App\Livewire\Concerns\HasCurrentTeam;
use App\Livewire\Concerns\NotifiesAdmins;
use App\Models\BugReport;
use App\Notifications\NewBugReportNotification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

final class BugReportSubmission extends Component
{
    use HasCurrentTeam;
    use NotifiesAdmins;
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string|min:10')]
    public string $description = '';

    #[Validate('required|string|in:low,medium,high,critical')]
    public string $severity = 'medium';

    #[Validate('nullable|string|max:500')]
    public string $url = '';

    /** @var array<int, TemporaryUploadedFile> */
    #[Validate(['screenshots.*' => 'image|max:5120'])]
    public array $screenshots = [];

    public string $browserInfo = '';

    public bool $submitted = false;

    public function mount(): void
    {
        $this->browserInfo = '';
    }

    public function submit(): void
    {
        $this->validate();

        $screenshotPaths = [];
        foreach ($this->screenshots as $screenshot) {
            $screenshotPaths[] = $screenshot->store('bug-reports', 'public');
        }

        $bugReport = BugReport::query()->create([
            'team_id' => $this->team->id,
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'severity' => ComplaintSeverity::from($this->severity),
            'status' => BugReportStatus::Open,
            'source' => 'web_form',
            'screenshots' => $screenshotPaths,
            'browser_info' => $this->browserInfo ?: null,
            'url' => $this->url ?: null,
        ]);

        $this->notifyAdmins(new NewBugReportNotification($bugReport));

        $this->submitted = true;

        $this->reset(['title', 'description', 'severity', 'url', 'screenshots']);
    }

    public function render(): Factory|View
    {
        return view('livewire.bug-report-submission');
    }
}
