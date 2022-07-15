<?php

declare(strict_types=1);

namespace App\Domain\Components\Adapters;

use App\Models\Team;
use App\Models\UploadHistory;
use Illuminate\Support\Collection;
use App\Domain\Components\Helpers\StringHelper;

class TeamAdapter
{
    /** @var string */
    protected $cnpj;

    /** @var string */
    protected $registration;

    /** @var string */
    protected $registrationSalesManager;

    /** @var string */
    protected $registrationViewManager;

    /** @var string */
    protected $line;

    /** @var UploadHistory */
    protected $uploadHistory;

    public function __construct($team, UploadHistory $uploadHistory)
    {
        $team                           = $this->collectionAdapter($team);
        $this->cnpj                     = $team->get(0);
        $this->registration             = $team->get(1);
        $this->line                     = $team->get(2);
        $this->registrationSalesManager = $team->get(3);
        $this->registrationViewManager  = $team->get(4);
        $this->uploadHistory            = $uploadHistory;
    }

    private function collectionAdapter(array $team): Collection
    {
        return collect(StringHelper::explode(';', $team[0]));
    }

    private function cnpj(): string
    {
        return StringHelper::cnpjAdapter(
            (string) $this->cnpj
        );
    }

    private function registration(): string
    {
        return StringHelper::registrationAdapter(
            (string) $this->registration
        );
    }

    private function registrationSalesManager(): string
    {
        return StringHelper::registrationAdapter(
            (string) $this->registrationSalesManager
        );
    }

    private function registrationViewManager(): string
    {
        return StringHelper::registrationAdapter(
            (string) $this->registrationViewManager
        );
    }

    private function line(): string
    {
        return StringHelper::make()->removeAccentsAndToUppercase(
            (string) $this->line
        );
    }

    private function uploadHistory(): UploadHistory
    {
        return $this->uploadHistory;
    }

    /** @return string[] */
    public function team(): array
    {
        return [
            'registration'                  => $this->registration() ?? null,
            'registration_sales_manager'    => $this->registrationSalesManager() ?? null,
            'registration_view_manager'     => $this->registrationViewManager() ?? null,
            'cnpj'                          => $this->cnpj() ?? null,
            'line'                          => $this->line() ?? null,
            'uploadHistoryModel'            => $this->uploadHistory() ?? null,
            'errors'                        => Team::VALIDATION_ERRORS,
            'succefully'                    => Team::VALIDATION_SUCCESSFULLY
        ];
    }
}
